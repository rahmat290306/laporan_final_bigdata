<?php
/**
 * SISTEM INFORMASI SMART-TRANSPORT TRANS DONGGALA
 * Endpoint Pengubah Status Bus & Reset Baseline Operasional
 * 
 * Fitur:
 * 1. Update status bus tunggal (Beroperasi / Rusak)
 * 2. Reset baseline operasional (4 Aktif | 1 Rusak - TD 03)
 * 3. 100% Aman & Ringan (Tanpa ALTER TABLE yang bisa mematikan MySQL)
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once 'koneksi.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Hanya menerima request POST']);
    exit;
}

// Ambil payload JSON atau form-data
$rawInput = file_get_contents('php://input');
$data     = json_decode($rawInput, true) ?? [];

$action  = isset($_POST['action'])  ? trim($_POST['action'])        : trim($data['action']  ?? '');
$id_bus  = isset($_POST['id_bus'])  ? (int)$_POST['id_bus']        : (int)($data['id_bus']  ?? 0);
$status  = isset($_POST['status'])  ? trim($_POST['status'])        : trim($data['status']   ?? '');
$kendala = isset($_POST['kendala']) ? trim($_POST['kendala'])       : trim($data['kendala']  ?? '');

try {
    // ----------------------------------------------------------------
    // AKSI 1: RESET KE KONDISI BASELINE RIIL (4 AKTIF | 1 RUSAK TD 03)
    // ----------------------------------------------------------------
    if ($action === 'reset_baseline') {
        $baselineData = [
            1 => ['kode' => 'TD 01', 'plat' => 'DN 7301 BU', 'speed' => 65, 'status' => 'Beroperasi', 'kendala' => 'Normal / Siap Operasi (Melaju 65 km/jam)', 'posisi' => 'Terminal Banawa (Pusat Donggala)'],
            2 => ['kode' => 'TD 02', 'plat' => 'DN 7303 BU', 'speed' => 62, 'status' => 'Beroperasi', 'kendala' => 'Normal / Siap Operasi (Melaju 62 km/jam)', 'posisi' => 'Stop bus Loli Dondo 2'],
            3 => ['kode' => 'TD 03', 'plat' => 'DN 7394 BU', 'speed' => 0,  'status' => 'Rusak',      'kendala' => 'Overheating Mesin & Radiator Bocor (0 km/jam)', 'posisi' => 'Wisma Donggala (Pusat Kota Palu)'],
            4 => ['kode' => 'TD 04', 'plat' => 'DN 7302 BU', 'speed' => 68, 'status' => 'Beroperasi', 'kendala' => 'Normal / Siap Operasi (Melaju 68 km/jam)', 'posisi' => 'Stop bus Loli Saluran 2'],
            5 => ['kode' => 'TD 05', 'plat' => 'DN 7305 BU', 'speed' => 64, 'status' => 'Beroperasi', 'kendala' => 'Normal / Siap Operasi (Melaju 64 km/jam)', 'posisi' => 'Stop bus Kabongga Besar 3']
        ];

        $stmtReset = $pdo->prepare("
            UPDATE tb_bus 
            SET status = :status, kecepatan = :kecepatan, kendala_terakhir = :kendala, posisi_terakhir = :posisi
            WHERE id_bus = :id_bus
        ");

        foreach ($baselineData as $bId => $bInfo) {
            $stmtReset->execute([
                ':status'    => $bInfo['status'],
                ':kecepatan' => $bInfo['speed'],
                ':kendala'   => $bInfo['kendala'],
                ':posisi'    => $bInfo['posisi'],
                ':id_bus'    => $bId
            ]);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Status seluruh armada berhasil dikembalikan ke kondisi Baseline Riil (4 Beroperasi, 1 Rusak - TD 03).'
        ]);
        exit;
    }

    // ----------------------------------------------------------------
    // AKSI 2: UPDATE STATUS ARMADA TUNGGAL
    // ----------------------------------------------------------------
    if ($id_bus <= 0 || !in_array($status, ['Beroperasi', 'Rusak'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Parameter tidak valid. id_bus dan status (Beroperasi/Rusak) wajib diisi.'
        ]);
        exit;
    }

    // Ambil data bus saat ini
    $stmtCek = $pdo->prepare("SELECT * FROM tb_bus WHERE id_bus = ?");
    $stmtCek->execute([$id_bus]);
    $bus = $stmtCek->fetch();

    if (!$bus) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Armada bus tidak ditemukan di database']);
        exit;
    }

    // Tentukan kecepatan dan atribut log
    if ($status === 'Rusak') {
        $kecepatan = 0;
        $kendala   = !empty($kendala) ? $kendala : 'Overheating Mesin & Radiator Bocor (0 km/jam)';
        $delay     = 85;
        $trip      = 2;
        $penumpang = 92;
        $loadFactor= 42.5;
        $biaya     = 1500000;
    } else {
        $kecepatan = 65; // Kecepatan rata-rata standar koridor
        $kendala   = 'Normal / Siap Operasi (Melaju 65 km/jam)';
        $delay     = 5;
        $trip      = 8;
        $penumpang = 480;
        $loadFactor= 88.0;
        $biaya     = 0;
    }

    // Update tb_bus
    $stmtUpdate = $pdo->prepare("
        UPDATE tb_bus
        SET status = :status,
            kecepatan = :kecepatan,
            kendala_terakhir = :kendala
        WHERE id_bus = :id_bus
    ");
    $stmtUpdate->execute([
        ':status'    => $status,
        ':kecepatan' => $kecepatan,
        ':kendala'   => $kendala,
        ':id_bus'    => $id_bus,
    ]);

    // Catat / perbarui riwayat ke tb_log_historis tanpa ALTER TABLE
    $today = date('Y-m-d');
    $stmtCheckLog = $pdo->prepare("SELECT id_log FROM tb_log_historis WHERE tanggal = ? AND id_bus = ? ORDER BY id_log DESC LIMIT 1");
    $stmtCheckLog->execute([$today, $id_bus]);
    $existingLog = $stmtCheckLog->fetch();

    if ($existingLog) {
        $stmtUpLog = $pdo->prepare("
            UPDATE tb_log_historis 
            SET status_harian = ?, kendala_teknis = ?, durasi_keterlambatan_menit = ?, 
                total_trip_harian = ?, jumlah_penumpang = ?, load_factor_persen = ?, biaya_perbaikan = ?
            WHERE id_log = ?
        ");
        $stmtUpLog->execute([$status, $kendala, $delay, $trip, $penumpang, $loadFactor, $biaya, $existingLog['id_log']]);
    } else {
        $stmtInsLog = $pdo->prepare("
            INSERT INTO tb_log_historis 
                (tanggal, id_bus, kode_bus, status_harian, kendala_teknis, durasi_keterlambatan_menit, total_trip_harian, jumlah_penumpang, load_factor_persen, biaya_perbaikan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInsLog->execute([$today, $id_bus, $bus['kode_bus'], $status, $kendala, $delay, $trip, $penumpang, $loadFactor, $biaya]);
    }

    echo json_encode([
        'success' => true,
        'message' => "Status {$bus['kode_bus']} berhasil diubah menjadi '{$status}' ({$kecepatan} km/jam).",
        'data' => [
            'id_bus' => $id_bus,
            'kode_bus' => $bus['kode_bus'],
            'status' => $status,
            'kecepatan' => $kecepatan,
            'kendala' => $kendala
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Kesalahan database: ' . $e->getMessage()
    ]);
}
