<?php
/**
 * SISTEM INFORMASI SMART-TRANSPORT TRANS DONGGALA
 * Modul Koneksi Database PDO (MySQL XAMPP) & Data Integrity Guard
 */

$host     = "localhost";
$username = "root";
$password = "";
$database = "db_trans_donggala";
$charset  = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$database;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Auto-sync ringan untuk menjamin integritas data baseline riil
    ensure_baseline_integrity($pdo);

} catch (PDOException $e) {
    $errorMessage = "Koneksi ke Database gagal: " . $e->getMessage();
    if ((isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
        (isset($_SERVER['SCRIPT_NAME']) && (strpos($_SERVER['SCRIPT_NAME'], 'api_') !== false || strpos($_SERVER['SCRIPT_NAME'], 'update_') !== false))) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $errorMessage]);
        exit;
    }
    die("<div style='font-family: sans-serif; padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin: 20px;'>
        <h3 style='margin-top:0;'>⚠️ Gagal Menghubungkan ke Database</h3>
        <p>$errorMessage</p>
        <p><strong>Panduan:</strong> Pastikan modul <em>Apache</em> dan <em>MySQL</em> pada XAMPP Control Panel sudah dalam keadaan <strong>Running</strong> dan database <code>db_trans_donggala</code> sudah di-import melalui phpMyAdmin.</p>
    </div>");
}

/**
 * Menjamin integritas data: 5 trayek lengkap, nama halte resmi, dan plat nomor bus
 */
function ensure_baseline_integrity($pdo) {
    try {
        // 1. Pastikan plat nomor bus resmi
        $pdo->exec("
            UPDATE tb_bus SET plat_nomor = 'DN 7301 BU' WHERE kode_bus = 'TD 01';
            UPDATE tb_bus SET plat_nomor = 'DN 7303 BU' WHERE kode_bus = 'TD 02';
            UPDATE tb_bus SET plat_nomor = 'DN 7394 BU' WHERE kode_bus = 'TD 03';
            UPDATE tb_bus SET plat_nomor = 'DN 7302 BU' WHERE kode_bus = 'TD 04';
            UPDATE tb_bus SET plat_nomor = 'DN 7305 BU' WHERE kode_bus = 'TD 05';
        ");

        // 2. Koreksi nama halte resmi simetris (Loli Saluran 2, Wisma Donggala, dsb)
        $pdo->exec("
            UPDATE tb_halte SET nama_halte = 'Stop bus Loli Saluran 2' WHERE nama_halte LIKE '%Loli Saluran 1%';
            UPDATE tb_halte SET nama_halte = 'Wisma Donggala (Pusat Kota Palu)' WHERE nama_halte LIKE '%Pool Pemda%' OR nama_halte LIKE '%Perbatasan Palu%';
            UPDATE tb_halte SET nama_halte = 'Terminal Banawa (Pusat Donggala)' WHERE nama_halte = 'Terminal Donggala' OR nama_halte = 'Terminal Banawa';
        ");

        // 3. Pastikan 5 trayek lengkap terdaftar
        $countTrayek = (int)$pdo->query("SELECT COUNT(*) FROM tb_trayek")->fetchColumn();
        if ($countTrayek < 5) {
            $feeders = [
                ['FD01', 'Feeder Banawa Tengah', '06:30 - 17:30 WITA', 14.20, 5000, 'Terminal Banawa ↔ Limboro ↔ Towale'],
                ['FD02', 'Feeder Tanjung Karang', '07:00 - 17:00 WITA', 6.50, 5000, 'Terminal Banawa ↔ Pantai Wisata Tanjung Karang'],
                ['FD03', 'Feeder Sindue Agropolitan', '06:30 - 16:30 WITA', 22.00, 8000, 'Pasar Toaya ↔ Masaingi ↔ Batusuya'],
                ['FD04', 'Feeder Dampelas Pesisir', '06:00 - 16:00 WITA', 31.50, 12000, 'Sabang ↔ Sabodi ↔ Lembah Mukti']
            ];
            $stmtInsT = $pdo->prepare("INSERT INTO tb_trayek (kode_trayek, nama_trayek, jam_operasional, jarak_km, tarif, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($feeders as $f) {
                $check = $pdo->prepare("SELECT id_trayek FROM tb_trayek WHERE kode_trayek = ?");
                $check->execute([$f[0]]);
                if (!$check->fetch()) {
                    $stmtInsT->execute($f);
                }
            }
        }
    } catch (Exception $e) {
        // Abaikan error minor jika tabel belum dibuat saat import pertama
    }
}

/**
 * Fungsi pembantu untuk menghitung Estimasi Waktu Tiba (ETA)
 * @param float $jarak_km Jarak dalam kilometer
 * @param int $kecepatan_kmh Kecepatan rata-rata (default: 65 km/jam)
 * @return array Berisi menit dan string format waktu
 */
function hitung_eta($jarak_km, $kecepatan_kmh = 65) {
    if ($kecepatan_kmh <= 0) {
        return ['menit' => 0, 'format' => 'Armada Berhenti / Rusak'];
    }
    $waktu_jam = $jarak_km / $kecepatan_kmh;
    $total_menit = round($waktu_jam * 60);
    
    if ($total_menit < 1) {
        return ['menit' => 1, 'format' => '< 1 mnt • Tiba Segera'];
    } elseif ($total_menit < 60) {
        return ['menit' => $total_menit, 'format' => "{$total_menit} Menit"];
    } else {
        $jam = floor($total_menit / 60);
        $sisa_menit = $total_menit % 60;
        return ['menit' => $total_menit, 'format' => "{$jam} Jam {$sisa_menit} Menit"];
    }
}
