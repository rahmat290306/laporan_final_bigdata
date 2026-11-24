-- ========================================================
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
('2026-10-21', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 541, 86.33, 0),
('2026-10-21', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 554, 88.7, 0),
('2026-10-21', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 561, 83.52, 0),
('2026-10-21', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 488, 85.42, 0),
('2026-10-21', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 552, 80.29, 0),
('2026-10-22', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 534, 81.22, 0),
('2026-10-22', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 538, 78.33, 0),
('2026-10-22', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 103, 1, 54, 43.07, 1500000),
('2026-10-22', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 492, 76.2, 0),
('2026-10-22', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 565, 91.67, 0),
('2026-10-23', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 507, 86.18, 0),
('2026-10-23', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 546, 77.99, 0),
('2026-10-23', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 510, 90.04, 0),
('2026-10-23', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 503, 78.05, 0),
('2026-10-23', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 488, 83.16, 0),
('2026-10-24', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 380, 88.48, 0),
('2026-10-24', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 381, 91.74, 0),
('2026-10-24', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 98, 1, 43, 42.54, 1200000),
('2026-10-24', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 392, 85.97, 0),
('2026-10-24', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 377, 79.84, 0),
('2026-10-25', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 421, 84.04, 0),
('2026-10-25', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 409, 77.87, 0),
('2026-10-25', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 412, 78.85, 0),
('2026-10-25', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 383, 84.91, 0),
('2026-10-25', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 420, 78.68, 0),
('2026-10-26', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 526, 84.38, 0),
('2026-10-26', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 538, 91.55, 0),
('2026-10-26', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 103, 1, 47, 53.84, 1500000),
('2026-10-26', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 540, 87.88, 0),
('2026-10-26', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 539, 86.44, 0),
('2026-10-27', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 507, 90.71, 0),
('2026-10-27', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 495, 80.87, 0),
('2026-10-27', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 514, 81.14, 0),
('2026-10-27', 4, 'TD 04', 'Rusak', 'Gangguan Sistem Pengereman (Air Brake)', 73, 3, 155, 48.6, 850000),
('2026-10-27', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 554, 76.69, 0),
('2026-10-28', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 492, 84.72, 0),
('2026-10-28', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 566, 80.11, 0),
('2026-10-28', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 557, 86.99, 0),
('2026-10-28', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 529, 90.01, 0),
('2026-10-28', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 500, 87.9, 0),
('2026-10-29', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 561, 76.62, 0),
('2026-10-29', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 511, 86.9, 0),
('2026-10-29', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 98, 2, 100, 46.21, 1200000),
('2026-10-29', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 525, 86.95, 0),
('2026-10-29', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 488, 90.9, 0),
('2026-10-30', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 523, 86.58, 0),
('2026-10-30', 2, 'TD 02', 'Rusak', 'Gangguan Sistem Pengereman (Air Brake Leak)', 78, 1, 52, 48.5, 850000),
('2026-10-30', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 487, 84.2, 0),
('2026-10-30', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 509, 76.59, 0),
('2026-10-30', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 531, 80.05, 0),
('2026-10-31', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 393, 76.21, 0),
('2026-10-31', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 436, 87.44, 0),
('2026-10-31', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 378, 91.33, 0),
('2026-10-31', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 425, 82.09, 0),
('2026-10-31', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 419, 77.84, 0),
('2026-11-01', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 413, 82.8, 0),
('2026-11-01', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 411, 79.22, 0),
('2026-11-01', 3, 'TD 03', 'Rusak', 'Kopling Aus & Transmisi Macet', 123, 1, 40, 53.06, 2400000),
('2026-11-01', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 386, 80.3, 0),
('2026-11-01', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 412, 91.88, 0),
('2026-11-02', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 548, 85.57, 0),
('2026-11-02', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 566, 78.54, 0),
('2026-11-02', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 511, 89.36, 0),
('2026-11-02', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 530, 90.73, 0),
('2026-11-02', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 505, 91.7, 0),
('2026-11-03', 1, 'TD 01', 'Rusak', 'Kebocoran Selang Solar / Filter Bahan Bakar', 58, 1, 52, 48.06, 450000),
('2026-11-03', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 560, 79.27, 0),
('2026-11-03', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 492, 87.86, 0),
('2026-11-03', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 501, 86.02, 0),
('2026-11-03', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 534, 86.79, 0),
('2026-11-04', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 539, 89.92, 0),
('2026-11-04', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 499, 87.76, 0),
('2026-11-04', 3, 'TD 03', 'Rusak', 'Kopling Aus & Transmisi Macet', 123, 1, 53, 52.02, 2400000),
('2026-11-04', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 496, 87.65, 0),
('2026-11-04', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 503, 76.75, 0),
('2026-11-05', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 552, 80.53, 0),
('2026-11-05', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 490, 80.95, 0),
('2026-11-05', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 495, 84.12, 0),
('2026-11-05', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 490, 89.79, 0),
('2026-11-05', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 561, 84.77, 0),
('2026-11-06', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 549, 83.26, 0),
('2026-11-06', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 524, 84.38, 0),
('2026-11-06', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 103, 1, 55, 43.68, 1500000),
('2026-11-06', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 510, 81.63, 0),
('2026-11-06', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 490, 83.0, 0),
('2026-11-07', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 377, 77.25, 0),
('2026-11-07', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 422, 88.85, 0),
('2026-11-07', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 433, 87.3, 0),
('2026-11-07', 4, 'TD 04', 'Rusak', 'Korsleting Jalur Kelistrikan & Alternator', 88, 2, 79, 52.47, 950000),
('2026-11-07', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 429, 91.94, 0),
('2026-11-08', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 397, 89.95, 0),
('2026-11-08', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 390, 88.64, 0),
('2026-11-08', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 382, 87.97, 0),
('2026-11-08', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 399, 80.0, 0),
('2026-11-08', 5, 'TD 05', 'Rusak', 'Kerusakan Suspensi & Patah Per Daun', 133, 1, 40, 43.21, 1800000),
('2026-11-09', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 541, 80.68, 0),
('2026-11-09', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 507, 88.72, 0),
('2026-11-09', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 98, 2, 98, 43.45, 1200000),
('2026-11-09', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 506, 86.43, 0),
('2026-11-09', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 516, 78.18, 0),
('2026-11-10', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 562, 87.76, 0),
('2026-11-10', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 520, 81.9, 0),
('2026-11-10', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 535, 89.92, 0),
('2026-11-10', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 529, 91.92, 0),
('2026-11-10', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 510, 82.46, 0),
('2026-11-11', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 532, 91.09, 0),
('2026-11-11', 2, 'TD 02', 'Rusak', 'Gangguan Sistem Pengereman (Air Brake Leak)', 77, 3, 148, 42.95, 850000),
('2026-11-11', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 556, 80.96, 0),
('2026-11-11', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 547, 82.75, 0),
('2026-11-11', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 508, 78.05, 0),
('2026-11-12', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 496, 85.07, 0),
('2026-11-12', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 547, 80.59, 0),
('2026-11-12', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 97, 1, 56, 42.88, 1200000),
('2026-11-12', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 505, 86.45, 0),
('2026-11-12', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 504, 85.6, 0),
('2026-11-13', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 493, 76.87, 0),
('2026-11-13', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 494, 76.49, 0),
('2026-11-13', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 508, 77.11, 0),
('2026-11-13', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 558, 82.02, 0),
('2026-11-13', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 495, 83.21, 0),
('2026-11-14', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 407, 83.14, 0),
('2026-11-14', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 436, 79.88, 0),
('2026-11-14', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 421, 85.16, 0),
('2026-11-14', 4, 'TD 04', 'Rusak', 'Korsleting Jalur Kelistrikan & Alternator', 87, 2, 77, 57.22, 950000),
('2026-11-14', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 420, 86.34, 0),
('2026-11-15', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 403, 91.94, 0),
('2026-11-15', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 404, 82.47, 0),
('2026-11-15', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 102, 2, 79, 51.99, 1500000),
('2026-11-15', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 405, 86.02, 0),
('2026-11-15', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 435, 87.2, 0),
('2026-11-16', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 501, 77.62, 0),
('2026-11-16', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 557, 90.85, 0),
('2026-11-16', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 528, 78.51, 0),
('2026-11-16', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 2, 8, 513, 81.83, 0),
('2026-11-16', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 535, 91.09, 0),
('2026-11-17', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 1, 8, 565, 85.11, 0),
('2026-11-17', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 489, 76.38, 0),
('2026-11-17', 3, 'TD 03', 'Rusak', 'Kopling Aus & Transmisi Macet', 122, 2, 108, 49.89, 2400000),
('2026-11-17', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 514, 76.45, 0),
('2026-11-17', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 516, 88.61, 0),
('2026-11-18', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 505, 80.86, 0),
('2026-11-18', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 529, 85.79, 0),
('2026-11-18', 3, 'TD 03', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 507, 90.21, 0),
('2026-11-18', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 538, 82.71, 0),
('2026-11-18', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 6, 8, 507, 89.07, 0),
('2026-11-19', 1, 'TD 01', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 518, 78.11, 0),
('2026-11-19', 2, 'TD 02', 'Beroperasi', 'Normal / Tidak Ada Kendala', 3, 8, 556, 84.53, 0),
('2026-11-19', 3, 'TD 03', 'Rusak', 'Overheating Mesin & Radiator Bocor', 97, 2, 111, 42.04, 1200000),
('2026-11-19', 4, 'TD 04', 'Beroperasi', 'Normal / Tidak Ada Kendala', 4, 8, 506, 83.35, 0),
('2026-11-19', 5, 'TD 05', 'Beroperasi', 'Normal / Tidak Ada Kendala', 5, 8, 536, 80.97, 0);

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
