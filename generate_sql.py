import random
from datetime import datetime, timedelta

def generate():
    sql = """-- ========================================================
-- SISTEM INFORMASI SMART-TRANSPORT TRANS DONGGALA
-- BIG DATA ANALYSIS & WEB MONITORING
-- Database: db_trans_donggala
-- Platform: XAMPP (Apache + PHP PDO + MySQL)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `db_trans_donggala` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_trans_donggala`;

-- --------------------------------------------------------
-- 1. Struktur Tabel `tb_bus`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tb_bus`;
CREATE TABLE `tb_bus` (
  `id_bus` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_bus` VARCHAR(10) NOT NULL,
  `plat_nomor` VARCHAR(20) NOT NULL,
  `kecepatan` INT(11) NOT NULL DEFAULT 65,
  `posisi_terakhir` VARCHAR(100) NOT NULL,
  `latitude` DECIMAL(10, 6) NOT NULL,
  `longitude` DECIMAL(10, 6) NOT NULL,
  `status` ENUM('Beroperasi','Rusak') NOT NULL DEFAULT 'Beroperasi',
  `kendala_terakhir` VARCHAR(150) DEFAULT 'Normal / Siap Operasi',
  `arah_tujuan` VARCHAR(100) DEFAULT 'Wisma Donggala',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal Armada Bus (4 Beroperasi Normal, 1 Rusak - TD 03)
INSERT INTO `tb_bus` (`id_bus`, `kode_bus`, `plat_nomor`, `kecepatan`, `posisi_terakhir`, `latitude`, `longitude`, `status`, `kendala_terakhir`, `arah_tujuan`) VALUES
(1, 'TD 01', 'DN 7301 BU', 65, 'Terminal Banawa (Pusat Donggala)', -0.671007, 119.749562, 'Beroperasi', 'Normal / Siap Operasi (Melaju 65 km/jam)', 'Wisma Donggala (Pusat Kota Palu)'),
(2, 'TD 02', 'DN 7303 BU', 62, 'Stop bus Loli Dondo 2', -0.752653, 119.783939, 'Beroperasi', 'Normal / Siap Operasi (Melaju 62 km/jam)', 'Wisma Donggala (Pusat Kota Palu)'),
(3, 'TD 03', 'DN 7394 BU', 0, 'Wisma Donggala (Pusat Kota Palu)', -0.889473, 119.851257, 'Rusak', 'Overheating Mesin & Radiator Bocor (0 km/jam)', 'Terminal Banawa (Pusat Donggala)'),
(4, 'TD 04', 'DN 7302 BU', 68, 'Stop bus Loli Saluran 2', -0.781496, 119.793813, 'Beroperasi', 'Normal / Siap Operasi (Melaju 68 km/jam)', 'Terminal Banawa (Pusat Donggala)'),
(5, 'TD 05', 'DN 7305 BU', 64, 'Stop bus Kabongga Besar 3', -0.701156, 119.754981, 'Beroperasi', 'Normal / Siap Operasi (Melaju 64 km/jam)', 'Terminal Banawa (Pusat Donggala)');

-- --------------------------------------------------------
-- 2. Struktur Tabel `tb_trayek` (5 Trayek Lengkap)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tb_trayek`;
CREATE TABLE `tb_trayek` (
  `id_trayek` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_trayek` VARCHAR(10) NOT NULL,
  `nama_trayek` VARCHAR(100) NOT NULL,
  `jam_operasional` VARCHAR(50) NOT NULL,
  `jarak_km` DECIMAL(5,2) NOT NULL,
  `tarif` INT(11) NOT NULL DEFAULT 5000,
  `deskripsi` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_trayek`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_trayek` (`id_trayek`, `kode_trayek`, `nama_trayek`, `jam_operasional`, `jarak_km`, `tarif`, `deskripsi`) VALUES
(1, 'K1', 'Koridor Utama Pesisir', '06:00 - 18:00 WITA', 28.50, 10000, 'Terminal Banawa ↔ Wisma Donggala (Via Jalur Pesisir Palu - Donggala)'),
(2, 'FD01', 'Feeder Banawa Tengah', '06:30 - 17:30 WITA', 14.20, 5000, 'Terminal Banawa ↔ Limboro ↔ Towale'),
(3, 'FD02', 'Feeder Tanjung Karang', '07:00 - 17:00 WITA', 6.50, 5000, 'Terminal Banawa ↔ Pantai Wisata Tanjung Karang'),
(4, 'FD03', 'Feeder Sindue Agropolitan', '06:30 - 16:30 WITA', 22.00, 8000, 'Pasar Toaya ↔ Masaingi ↔ Batusuya'),
(5, 'FD04', 'Feeder Dampelas Pesisir', '06:00 - 16:00 WITA', 31.50, 12000, 'Sabang ↔ Sabodi ↔ Lembah Mukti');

-- --------------------------------------------------------
-- 3. Struktur Tabel `tb_halte` (14 Halte Simetris K1 Terverifikasi Lapangan)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tb_halte`;
CREATE TABLE `tb_halte` (
  `id_halte` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_halte` VARCHAR(100) NOT NULL,
  `arah_rute` VARCHAR(100) NOT NULL,
  `urutan` INT(11) NOT NULL,
  `latitude` DECIMAL(10, 6) NOT NULL,
  `longitude` DECIMAL(10, 6) NOT NULL,
  `estimasi_jarak_km` DECIMAL(5, 2) NOT NULL,
  `tipe_halte` ENUM('Resmi','Fleksibel') NOT NULL DEFAULT 'Fleksibel',
  PRIMARY KEY (`id_halte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Halte Rute 1: Terminal Donggala -> Wisma Donggala (14 Halte Simetris)
INSERT INTO `tb_halte` (`nama_halte`, `arah_rute`, `urutan`, `latitude`, `longitude`, `estimasi_jarak_km`, `tipe_halte`) VALUES
('Terminal Banawa (Pusat Donggala)', 'Terminal Donggala -> Wisma Donggala', 1, -0.671007, 119.749562, 0.00, 'Resmi'),
('Stop bus Gunung Bale 1', 'Terminal Donggala -> Wisma Donggala', 2, -0.675015, 119.748047, 1.20, 'Fleksibel'),
('Stop bus Kabongga Kecil 3', 'Terminal Donggala -> Wisma Donggala', 3, -0.691371, 119.758077, 2.80, 'Resmi'),
('Stop bus Kabongga Besar 3', 'Terminal Donggala -> Wisma Donggala', 4, -0.701156, 119.754981, 5.80, 'Resmi'),
('Stop bus Kabongga Besar 5', 'Terminal Donggala -> Wisma Donggala', 5, -0.718242, 119.774047, 8.10, 'Resmi'),
('Stop bus Rumah Tujuh', 'Terminal Donggala -> Wisma Donggala', 6, -0.731775, 119.776399, 10.40, 'Fleksibel'),
('Stop bus Loli Dondo 2', 'Terminal Donggala -> Wisma Donggala', 7, -0.752653, 119.783939, 13.20, 'Resmi'),
('Stop bus Loli Tasiburi 2', 'Terminal Donggala -> Wisma Donggala', 8, -0.756854, 119.785548, 14.90, 'Fleksibel'),
('Stop bus Loli Tasiburi 3', 'Terminal Donggala -> Wisma Donggala', 9, -0.762839, 119.789690, 16.50, 'Resmi'),
('Stop bus Loli Pesua', 'Terminal Donggala -> Wisma Donggala', 10, -0.767563, 119.787619, 18.00, 'Fleksibel'),
('Stop bus Loli Saluran 2', 'Terminal Donggala -> Wisma Donggala', 11, -0.781496, 119.793813, 20.10, 'Fleksibel'),
('Stop bus Depan Kantor Desa Loli', 'Terminal Donggala -> Wisma Donggala', 12, -0.786366, 119.796216, 21.60, 'Fleksibel'),
('Stop bus Loli Oge 2', 'Terminal Donggala -> Wisma Donggala', 13, -0.793916, 119.800039, 23.40, 'Resmi'),
('Wisma Donggala (Pusat Kota Palu)', 'Terminal Donggala -> Wisma Donggala', 14, -0.889473, 119.851257, 28.50, 'Resmi');

-- Halte Rute 2: Wisma Donggala -> Terminal Banawa (14 Halte Simetris)
INSERT INTO `tb_halte` (`nama_halte`, `arah_rute`, `urutan`, `latitude`, `longitude`, `estimasi_jarak_km`, `tipe_halte`) VALUES
('Wisma Donggala (Pusat Kota Palu)', 'Wisma Donggala -> Terminal Banawa', 1, -0.889473, 119.851257, 0.00, 'Resmi'),
('Stop bus Loli Oge 2', 'Wisma Donggala -> Terminal Banawa', 2, -0.793916, 119.800039, 5.10, 'Resmi'),
('Stop bus Depan Kantor Desa Loli', 'Wisma Donggala -> Terminal Banawa', 3, -0.786366, 119.796216, 6.90, 'Fleksibel'),
('Stop bus Loli Saluran 2', 'Wisma Donggala -> Terminal Banawa', 4, -0.781496, 119.793813, 8.40, 'Fleksibel'),
('Stop bus Loli Pesua', 'Wisma Donggala -> Terminal Banawa', 5, -0.767563, 119.787619, 10.50, 'Fleksibel'),
('Stop bus Loli Tasiburi 3', 'Wisma Donggala -> Terminal Banawa', 6, -0.762839, 119.789690, 12.00, 'Resmi'),
('Stop bus Loli Tasiburi 2', 'Wisma Donggala -> Terminal Banawa', 7, -0.756854, 119.785548, 13.60, 'Fleksibel'),
('Stop bus Loli Dondo 2', 'Wisma Donggala -> Terminal Banawa', 8, -0.752653, 119.783939, 15.30, 'Resmi'),
('Stop bus Rumah Tujuh', 'Wisma Donggala -> Terminal Banawa', 9, -0.731775, 119.776399, 18.10, 'Fleksibel'),
('Stop bus Kabongga Besar 5', 'Wisma Donggala -> Terminal Banawa', 10, -0.718242, 119.774047, 20.40, 'Resmi'),
('Stop bus Kabongga Besar 3', 'Wisma Donggala -> Terminal Banawa', 11, -0.701156, 119.754981, 22.70, 'Resmi'),
('Stop bus Kabongga Kecil 3', 'Wisma Donggala -> Terminal Banawa', 12, -0.691371, 119.758077, 25.70, 'Resmi'),
('Stop bus Gunung Bale 1', 'Wisma Donggala -> Terminal Banawa', 13, -0.675015, 119.748047, 27.30, 'Fleksibel'),
('Terminal Banawa (Pusat Donggala)', 'Wisma Donggala -> Terminal Banawa', 14, -0.671007, 119.749562, 28.50, 'Resmi');

-- --------------------------------------------------------
-- 4. Struktur Tabel `tb_log_historis` (Simulasi Big Data 30 Hari: 150 Log)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tb_log_historis`;
CREATE TABLE `tb_log_historis` (
  `id_log` INT(11) NOT NULL AUTO_INCREMENT,
  `tanggal` DATE NOT NULL,
  `id_bus` INT(11) NOT NULL,
  `kode_bus` VARCHAR(10) NOT NULL,
  `status_harian` ENUM('Beroperasi','Rusak') NOT NULL,
  `kendala_teknis` VARCHAR(100) NOT NULL,
  `durasi_keterlambatan_menit` INT(11) NOT NULL DEFAULT 0,
  `total_trip_harian` INT(11) NOT NULL DEFAULT 8,
  `jumlah_penumpang` INT(11) NOT NULL DEFAULT 0,
  `load_factor_persen` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `biaya_perbaikan` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_kode_bus` (`kode_bus`),
  KEY `idx_status` (`status_harian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_log_historis` (`tanggal`, `id_bus`, `kode_bus`, `status_harian`, `kendala_teknis`, `durasi_keterlambatan_menit`, `total_trip_harian`, `jumlah_penumpang`, `load_factor_persen`, `biaya_perbaikan`) VALUES
"""

    # Generate 150 calibrated rows
    # Target: 150 rows, total passengers = 65,960, avg delay = 15.4 minutes, 19 broken (87.4% reliability)
    # Unit breakdown: TD 03 has 12 breakdowns (~38.7% breakdown rate), TD 04 has 3, TD 02 has 2, TD 01 has 1, TD 05 has 1.
    
    start_date = datetime.now() - timedelta(days=30)
    
    # We will construct 30 days x 5 buses = 150 rows
    # Specified breakdown days for buses
    broken_schedule = {
        3: [1, 3, 5, 8, 11, 14, 16, 19, 22, 25, 27, 29], # 12 days broken for TD 03 (38.7% breakdown)
        4: [6, 17, 24], # 3 days broken for TD 04
        2: [9, 21],     # 2 days broken for TD 02
        1: [13],        # 1 day broken for TD 01
        5: [18]         # 1 day broken for TD 05
    }

    kendala_map = {
        3: [
            ("Overheating Mesin & Radiator Bocor", 85, 1200000),
            ("Kopling Aus & Transmisi Macet", 110, 2400000),
            ("Overheating Mesin & Radiator Bocor", 90, 1500000)
        ],
        4: [
            ("Korsleting Jalur Kelistrikan & Alternator", 75, 950000),
            ("Gangguan Sistem Pengereman (Air Brake)", 60, 850000)
        ],
        2: [
            ("Gangguan Sistem Pengereman (Air Brake Leak)", 65, 850000)
        ],
        1: [
            ("Kebocoran Selang Solar / Filter Bahan Bakar", 45, 450000)
        ],
        5: [
            ("Kerusakan Suspensi & Patah Per Daun", 120, 1800000)
        ]
    }

    rows = []
    total_passengers = 0
    total_delay = 0

    # Base allocations
    all_rows_data = []

    for d in range(30):
        c_date = (start_date + timedelta(days=d)).strftime('%Y-%m-%d')
        is_weekend = (start_date + timedelta(days=d)).weekday() >= 5
        base_pax = 390 if is_weekend else 510

        for b_id, b_code in [(1, 'TD 01'), (2, 'TD 02'), (3, 'TD 03'), (4, 'TD 04'), (5, 'TD 05')]:
            is_broken = d in broken_schedule.get(b_id, [])
            if is_broken:
                kendala, d_time, biaya = random.choice(kendala_map[b_id])
                trip = random.randint(1, 3)
                pax = int(base_pax * (trip / 8.0) * random.uniform(0.75, 0.90))
                load = round(random.uniform(42.0, 58.0), 2)
                all_rows_data.append({
                    'tanggal': c_date, 'id': b_id, 'kode': b_code, 'status': 'Rusak',
                    'kendala': kendala, 'delay': d_time, 'trip': trip,
                    'pax': pax, 'load': load, 'biaya': biaya
                })
            else:
                d_time = random.randint(1, 6)
                trip = 8
                pax = int(base_pax * random.uniform(0.92, 1.08))
                load = round(random.uniform(76.0, 92.0), 2)
                all_rows_data.append({
                    'tanggal': c_date, 'id': b_id, 'kode': b_code, 'status': 'Beroperasi',
                    'kendala': 'Normal / Tidak Ada Kendala', 'delay': d_time, 'trip': trip,
                    'pax': pax, 'load': load, 'biaya': 0
                })

    # Fine-tune total passengers to exactly 65,960
    current_pax_sum = sum(r['pax'] for r in all_rows_data)
    diff_pax = 65960 - current_pax_sum
    # Distribute diff_pax among normal rows
    normal_indices = [i for i, r in enumerate(all_rows_data) if r['status'] == 'Beroperasi']
    chunk = diff_pax // len(normal_indices)
    rem = diff_pax % len(normal_indices)
    for idx in normal_indices:
        all_rows_data[idx]['pax'] += chunk
    for i in range(abs(rem)):
        if rem > 0:
            all_rows_data[normal_indices[i]]['pax'] += 1
        elif rem < 0:
            all_rows_data[normal_indices[i]]['pax'] -= 1

    # Fine-tune total delay so average is exactly 15.4 minutes (total = 15.4 * 150 = 2310)
    target_total_delay = 2310
    current_delay_sum = sum(r['delay'] for r in all_rows_data)
    diff_delay = target_total_delay - current_delay_sum
    
    broken_indices = [i for i, r in enumerate(all_rows_data) if r['status'] == 'Rusak']
    b_chunk = diff_delay // len(broken_indices)
    b_rem = diff_delay % len(broken_indices)
    for idx in broken_indices:
        all_rows_data[idx]['delay'] += b_chunk
    for i in range(abs(b_rem)):
        if b_rem > 0:
            all_rows_data[broken_indices[i]]['delay'] += 1
        elif b_rem < 0:
            all_rows_data[broken_indices[i]]['delay'] -= 1

    for r in all_rows_data:
        rows.append(f"('{r['tanggal']}', {r['id']}, '{r['kode']}', '{r['status']}', '{r['kendala']}', {r['delay']}, {r['trip']}, {r['pax']}, {r['load']}, {r['biaya']})")

    sql += ",\n".join(rows) + ";\n"

    sql += """
-- --------------------------------------------------------
-- 5. View Agregasi Analitik untuk Decision Support
-- --------------------------------------------------------
CREATE OR REPLACE VIEW `vw_rekap_performa_bus` AS
SELECT 
    b.kode_bus,
    b.plat_nomor,
    COUNT(l.id_log) AS total_hari_pantau,
    SUM(CASE WHEN l.status_harian = 'Rusak' THEN 1 ELSE 0 END) AS frekuensi_kerusakan,
    ROUND((SUM(CASE WHEN l.status_harian = 'Rusak' THEN 1 ELSE 0 END) / COUNT(l.id_log)) * 100, 2) AS rasio_kerusakan_persen,
    ROUND(AVG(l.durasi_keterlambatan_menit), 1) AS rata_rata_delay_menit,
    SUM(l.jumlah_penumpang) AS total_penumpang_diangkut,
    SUM(l.biaya_perbaikan) AS total_biaya_perbaikan
FROM tb_bus b
LEFT JOIN tb_log_historis l ON b.id_bus = l.id_bus
GROUP BY b.id_bus, b.kode_bus, b.plat_nomor;
"""
    return sql

with open("db_trans_donggala.sql", "w", encoding="utf-8") as f:
    f.write(generate())

print("Successfully regenerated calibrated db_trans_donggala.sql")
