<?php
/**
 * SISTEM INFORMASI SMART-TRANSPORT TRANS DONGGALA
 * API Monitoring Real-Time Armada & Halte (Single Source of Truth)
 * Format: JSON
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once 'koneksi.php';

try {
    // 1. Ambil data armada bus
    $stmtBus = $pdo->query("SELECT * FROM tb_bus ORDER BY kode_bus ASC");
    $buses = $stmtBus->fetchAll();

    // 2. Ambil data trayek (5 Trayek Lengkap)
    $stmtTrayek = $pdo->query("SELECT * FROM tb_trayek ORDER BY id_trayek ASC");
    $trayek = $stmtTrayek->fetchAll();

    // Fallback 5 trayek jika database belum terisi lengkap
    if (count($trayek) < 5) {
        $trayek = [
            ['id_trayek' => 1, 'kode_trayek' => 'K1', 'nama_trayek' => 'Koridor Utama Pesisir', 'jam_operasional' => '06:00 - 18:00 WITA', 'jarak_km' => 28.50, 'tarif' => 10000, 'deskripsi' => 'Terminal Banawa ↔ Wisma Donggala (Via Jalur Pesisir Palu - Donggala)'],
            ['id_trayek' => 2, 'kode_trayek' => 'FD01', 'nama_trayek' => 'Feeder Banawa Tengah', 'jam_operasional' => '06:30 - 17:30 WITA', 'jarak_km' => 14.20, 'tarif' => 5000, 'deskripsi' => 'Terminal Banawa ↔ Limboro ↔ Towale'],
            ['id_trayek' => 3, 'kode_trayek' => 'FD02', 'nama_trayek' => 'Feeder Tanjung Karang', 'jam_operasional' => '07:00 - 17:00 WITA', 'jarak_km' => 6.50, 'tarif' => 5000, 'deskripsi' => 'Terminal Banawa ↔ Pantai Wisata Tanjung Karang'],
            ['id_trayek' => 4, 'kode_trayek' => 'FD03', 'nama_trayek' => 'Feeder Sindue Agropolitan', 'jam_operasional' => '06:30 - 16:30 WITA', 'jarak_km' => 22.00, 'tarif' => 8000, 'deskripsi' => 'Pasar Toaya ↔ Masaingi ↔ Batusuya'],
            ['id_trayek' => 5, 'kode_trayek' => 'FD04', 'nama_trayek' => 'Feeder Dampelas Pesisir', 'jam_operasional' => '06:00 - 16:00 WITA', 'jarak_km' => 31.50, 'tarif' => 12000, 'deskripsi' => 'Sabang ↔ Sabodi ↔ Lembah Mukti']
        ];
    }

    // 3. Ambil data 14 Halte Simetris
    $stmtHalte1 = $pdo->prepare("SELECT * FROM tb_halte WHERE arah_rute LIKE '%Terminal Donggala%' OR arah_rute LIKE '%Terminal Banawa -> Wisma%' ORDER BY urutan ASC");
    $stmtHalte1->execute();
    $halteRute1 = $stmtHalte1->fetchAll();

    $stmtHalte2 = $pdo->prepare("SELECT * FROM tb_halte WHERE arah_rute LIKE '%Wisma Donggala ->%' ORDER BY urutan ASC");
    $stmtHalte2->execute();
    $halteRute2 = $stmtHalte2->fetchAll();

    // Hitung ringkasan armada
    $totalArmada = count($buses);
    $beroperasi = 0;
    $rusak = 0;
    $totalKecepatanAktif = 0;

    foreach ($buses as &$b) {
        $b['kecepatan'] = (int)$b['kecepatan'];
        $b['latitude'] = (float)$b['latitude'];
        $b['longitude'] = (float)$b['longitude'];

        if ($b['status'] === 'Beroperasi') {
            $beroperasi++;
            $totalKecepatanAktif += $b['kecepatan'];
        } else {
            $rusak++;
        }
    }

    $avgSpeedAktif = $beroperasi > 0 ? round($totalKecepatanAktif / $beroperasi, 1) : 0;

    // Kalkulasi Headway & Kapasitas Sesuai Rumus Koridor K1:
    // Jarak = 28.5 km, Kecepatan default = 65 km/j (atau avg aktif)
    $jarakK1 = 28.50;
    $speedRef = $beroperasi > 0 ? ($avgSpeedAktif > 0 ? $avgSpeedAktif : 65.0) : 65.0;
    $waktuTempuhMenit = round(($jarakK1 / $speedRef) * 60); // 26 menit
    $headwayMenit = $beroperasi > 0 ? round(($waktuTempuhMenit / $beroperasi), 1) : 0;
    $kapasitasPerJam = $headwayMenit > 0 ? round((60 / $headwayMenit) * 30) : 0;

    // Tambahkan kalkulasi ETA pada setiap halte dengan kecepatan 65 km/jam
    foreach ($halteRute1 as &$h) {
        $h['latitude'] = (float)$h['latitude'];
        $h['longitude'] = (float)$h['longitude'];
        $h['estimasi_jarak_km'] = (float)$h['estimasi_jarak_km'];
        $h['tipe_halte'] = !empty($h['tipe_halte']) ? $h['tipe_halte'] : 'Fleksibel';
        $h['eta'] = hitung_eta($h['estimasi_jarak_km'], 65);
    }

    foreach ($halteRute2 as &$h) {
        $h['latitude'] = (float)$h['latitude'];
        $h['longitude'] = (float)$h['longitude'];
        $h['estimasi_jarak_km'] = (float)$h['estimasi_jarak_km'];
        $h['tipe_halte'] = !empty($h['tipe_halte']) ? $h['tipe_halte'] : 'Fleksibel';
        $h['eta'] = hitung_eta($h['estimasi_jarak_km'], 65);
    }

    echo json_encode([
        'success' => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'summary' => [
            'total_armada' => $totalArmada,
            'beroperasi' => $beroperasi,
            'rusak' => $rusak,
            'persentase_aktif' => $totalArmada > 0 ? round(($beroperasi / $totalArmada) * 100, 1) : 0,
            'kecepatan_rata_rata' => $avgSpeedAktif > 0 ? "{$avgSpeedAktif} km/jam" : "65.0 km/jam",
            'waktu_tempuh_menit' => $waktuTempuhMenit,
            'headway_menit' => $headwayMenit,
            'kapasitas_per_jam' => $kapasitasPerJam
        ],
        'buses' => $buses,
        'trayek' => $trayek,
        'rute_1' => [
            'nama_arah' => 'Terminal Banawa (Pusat Donggala) -> Wisma Donggala (Pusat Kota Palu)',
            'total_halte' => count($halteRute1),
            'halte' => $halteRute1
        ],
        'rute_2' => [
            'nama_arah' => 'Wisma Donggala (Pusat Kota Palu) -> Terminal Banawa (Pusat Donggala)',
            'total_halte' => count($halteRute2),
            'halte' => $halteRute2
        ]
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
