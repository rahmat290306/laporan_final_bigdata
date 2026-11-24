# SISTEM INFORMASI SMART-TRANSPORT TRANS DONGGALA
### Berbasis Web Monitoring & Big Data Decision Support System (DSS)
**Tugas Akhir Mata Kuliah:** Big Data Analysis (Semester 5)  
**Studi Kasus:** Pengelolaan & Pemantauan Operasional Armada Bus Trans Donggala (Trayek Palu – Donggala)  
**Referensi Desain Antarmuka:** CekTrans Trans Donggala ([cektrans.banuacoder.com/donggala](https://cektrans.banuacoder.com/donggala))

---

## 📌 Ringkasan Proyek & Pemenuhan Syarat Dosen

| Poin Ketentuan Tugas Akhir | Implementasi Nyata pada Proyek Ini |
| :--- | :--- |
| **1. Analisis Big Data untuk Keputusan Bisnis (Actionable)** | Sistem mengagregasi ratusan log telemetri dan kerusakan historis untuk menghasilkan rekomendasi dinamis: *Early Warning* perbaikan armada berisiko tinggi (TD 03), penyesuaian jadwal (*dynamic dispatching*) saat ada bus mogok, dan usulan pembangunan halte permanen. |
| **2. Memanfaatkan Data Historis untuk Prediksi Masa Depan** | Menghitung prediksi waktu tunggu (*Headway*) dan Estimasi Waktu Tiba (*Estimated Time of Arrival / ETA*) per halte berdasarkan kecepatan rata-rata 60–70 km/jam serta riwayat durasi delay. |
| **3. Menyampaikan Insight Menjadi Aksi Nyata** | Dashboard memvisualisasikan korelasi antara kerusakan armada dengan lonjakan delay dan penurunan jumlah penumpang komuter, serta menyajikan kalkulator simulator kapasitas operasional. |

---

## 🛠️ Spesifikasi Tech Stack
* **Server Lokal:** XAMPP (Apache Web Server & MySQL / MariaDB Database).
* **Backend:** PHP Native dengan **PDO (PHP Data Objects)** untuk koneksi database yang aman (*prepared statements*).
* **Database:** MySQL (`db_trans_donggala`).
* **Frontend:** HTML5, Tailwind CSS (via CDN), JavaScript ES6.
* **Komponen Spasial & Grafik:**
  * **Leaflet.js:** Visualisasi peta interaktif jalur pesisir Palu–Donggala, polyline rute, marker halte, dan pulsing marker armada bus realtime.
  * **Chart.js:** Grafik analitik Big Data (Bar chart kerusakan bus, Line chart tren penumpang vs delay, dan Doughnut chart jenis kerusakan mesin).

---

## 📂 Struktur Berkas Proyek

```
trans-donggala/
│
├── db_trans_donggala.sql       # Skrip SQL lengkap (Skema & Dataset Simulasi 30 Hari)
├── koneksi.php                 # File konfigurasi koneksi PDO MySQL & fungsi pembantu ETA
├── index.php                   # Antarmuka Web Utama (Monitoring CekTrans + Big Data Analytics + DSS)
├── api_monitoring.php          # REST API JSON: Posisi bus, status, rute, dan halte ETA
├── api_analytics.php           # REST API JSON: Agregasi SQL Big Data, KPI, dan insight DSS
├── update_status.php           # Endpoint AJAX: Mengubah status armada (Beroperasi / Rusak)
└── README.md                   # Dokumentasi instalasi dan panduan presentasi
```

---

## 🚀 Panduan Instalasi & Menjalankan di XAMPP

Ikuti langkah-langkah mudah berikut untuk menjalankan proyek di komputer lokal:

### 1. Salin Folder Proyek ke `htdocs`
Pastikan folder proyek ini berada di dalam direktori `htdocs` XAMPP Anda:
```
C:\xampp\htdocs\trans-donggala\
```
*(atau jika disimpan di folder lain, buat symlink atau jalankan Apache yang mengarah ke folder ini).*

### 2. Nyalakan XAMPP
1. Buka aplikasi **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache**.
3. Klik tombol **Start** pada modul **MySQL**.

### 3. Impor Database ke phpMyAdmin
1. Buka browser dan akses: `http://localhost/phpmyadmin/`
2. Klik menu **Databases**, beri nama database baru: `db_trans_donggala`, lalu klik **Create**.
3. Pilih database `db_trans_donggala` di panel kiri.
4. Klik tab **Import** di bagian atas.
5. Klik **Choose File**, pilih berkas `db_trans_donggala.sql` dari folder proyek.
6. Gulir ke bawah dan klik tombol **Import** (atau **Go**).
7. Tunggu hingga muncul notifikasi hijau bahwa seluruh tabel dan data berhasil diimpor.

### 4. Buka Aplikasi di Browser
Buka peramban (Chrome / Edge / Firefox) dan buka URL:
```
http://localhost/trans-donggala/
```

---

## 📊 Fitur Utama Proyek

1. **Portal Login Petugas & Analis Trans Donggala:**
   - Halaman autentikasi awal yang menyambut pengguna sebelum mengakses dashboard.
   - Dilengkapi fitur *1-Klik Akses Cepat (Instant Demo Login)* untuk kemudahan demonstrasi saat sidang/presentasi di hadapan dosen penguji.
   - Sesi terenkripsi dan tombol *Logout* di bilah navigasi atas.

2. **Dashboard 25 Visualisasi Data (Chart.js):**
   - Disusun rapi ke dalam 5 Klaster Tematik dengan tombol filter interaktif:
     - **Klaster 1: Operasional Armada (Chart 1 - 5):** Frekuensi kerusakan, rata-rata kecepatan, trip selesai, rasio kesiapan (*availability*), dan efisiensi konsumsi BBM solar.
     - **Klaster 2: Delay & Waktu Tempuh (Chart 6 - 10):** Rata-rata delay per bus, tren delay harian 30 hari, delay jam sibuk pagi/sore, perbandingan waktu tempuh riil vs SPM 26 menit, dan variansi headway.
     - **Klaster 3: Volume Penumpang (Chart 11 - 15):** Tren komuter harian, akumulasi penumpang per armada, pola jam sibuk (*twin peaks*), komparasi weekday vs weekend, dan tingkat okupansi (*load factor*).
     - **Klaster 4: Perawatan Mesin & Biaya (Chart 16 - 20):** Distribusi kendala teknis, biaya servis per bus, komposisi biaya suku cadang, *Mean Time Between Failures* (MTBF), dan radar degradasi 6 subsistem mesin.
     - **Klaster 5: Halte & Trayek (Chart 21 - 25):** Kepadatan naik-turun 14 halte K1, rata-rata waktu tunggu per halte, pendapatan tiket harian per trayek, tren kepatuhan jadwal (*OTP*), dan proyeksi kebutuhan armada 6 bulan ke depan.
   - Setiap kartu chart dilengkapi kotak **Insight Analis** berwarna di bagian bawah agar data mudah dipahami seketika.

3. **Live Monitoring Spasial GIS (Leaflet.js):**
   - Pemantauan posisi 5 armada bus (TD 01 - TD 05) realtime dengan penanda warna (*merah kedip untuk TD 03 yang mogok, hijau untuk yang aktif*).
   - 1.269 titik koordinat OSRM jalan pesisir Palu - Donggala (28,5 km).
   - Estimasi waktu tiba (*ETA*) dinamis pada 14 halte simetris.

4. **Decision Support System (DSS) & Simulator Dynamic Headway:**
   - Rekomendasi aksi preventif Dishub (overhaul TD 03, dynamic dispatching kecepatan 65–70 km/jam, dan pembangunan shelter permanen).
   - Simulator dinamis untuk menguji skenario jumlah bus aktif dan kecepatan terhadap waktu tempuh (26 menit), headway (6,5 menit), dan kapasitas angkut (257 penumpang/jam).

---

### 1. Rincian 5 Unit Armada Bus Trans Donggala
| Kode Bus | Plat Nomor | Posisi Awal | Status Awal | Keterangan |
| :---: | :---: | :--- | :---: | :--- |
| **TD 01** | DN 7301 BU | Terminal Banawa | Beroperasi | Kecepatan 65 km/jam, kondisi prima |
| **TD 02** | DN 7303 BU | Loli Dondo 2 | Beroperasi | Kecepatan 62 km/jam, kondisi stabil |
| **TD 03** | DN 7394 BU | Wisma Donggala | **Rusak** | Mogok (Kerusakan radiator/overheating) |
| **TD 04** | DN 7302 BU | Stop bus Loli Saluran 1 | Beroperasi | Kecepatan 68 km/jam, rute balik |
| **TD 05** | DN 7305 BU | Stop bus Kabongga Besar 2 | Beroperasi | Kecepatan 64 km/jam, rute balik |

### 2. Titik Halte Rute Bolak-Balik (28.5 km Jalur Pesisir)
* **Arah Terminal Donggala &rarr; Wisma Donggala (16 Titik):**
  Terminal Banawa &rarr; Stop bus Kabongga Kecil 2 &rarr; Stop bus Kabongga Kecil 4 &rarr; Stop bus Kabongga Besar 3 &rarr; Stop bus Kabongga Besar 4 &rarr; Stop bus Kabongga Besar 1 &rarr; Stop bus Kabongga Besar 5 &rarr; Stop bus Loli Dondo 2 &rarr; Desa Loli Tasiburi 2 &rarr; Stop bus Loli Tasiburi 3 &rarr; Desa Loli Pesua &rarr; Stop bus Loli Saluran 3 &rarr; Stop bus Loli Saluran 2 &rarr; Depan Kantor Desa Loli &rarr; Stop bus Loli Oge 2 &rarr; Wisma Donggala.
* **Arah Wisma Donggala &rarr; Terminal Banawa (13 Titik):**
  Wisma Donggala &rarr; Stop bus Loli Oge 1 &rarr; Stop bus Loli Saluran 1 &rarr; Stop bus Loli Pesua 1 &rarr; Stop bus Loli Tasiburi 1 &rarr; Stop bus Loli Tasiburi 2 &rarr; Stop bus Loli Dondo 1 &rarr; Rumah Tujuh &rarr; Stop bus Kabongga Besar 2 &rarr; Stop bus Kabongga Besar 3 &rarr; Stop bus Kabongga Kecil 1 &rarr; Stop bus Gunung Bale 1 &rarr; Terminal Banawa.

---

## 🎯 Panduan Presentasi di Depan Dosen

Saat mempresentasikan tugas akhir ini:
1. **Buka Tab "Live Monitoring":**
   * Tunjukkan kemiripannya dengan CekTrans (Banua Coder).
   * Tunjukkan pergerakan marker bus (warna hijau untuk beroperasi, merah berkedip untuk rusak).
   * Tunjukkan estimasi waktu tiba (ETA) di setiap halte yang dihitung otomatis berdasarkan jarak dan kecepatan operasional 60–70 km/jam.
2. **Uji Fitur Interaktif Operator (Ubah Status):**
   * Klik tombol **"Kelola Armada"** di pojok kanan atas.
   * Pilih misalnya **TD 01** dan ubah statusnya menjadi **"Rusak"**.
   * Klik Simpan. Tunjukkan bahwa tanpa me-reload halaman, marker pada peta langsung berubah merah, jumlah armada aktif berkurang, dan log historis otomatis bertambah di database MySQL.
3. **Buka Tab "Big Data Analytics":**
   * Jelaskan grafik **Frekuensi Kerusakan per Armada**: Soroti bahwa armada **TD 03** secara statistik Big Data paling sering rusak (mencapai 38% rasio mogok).
   * Jelaskan grafik **Tren Penumpang vs Delay**: Tunjukkan bahwa keterlambatan tinggi langsung menekan volume penumpang komuter.
4. **Buka Tab "Decision Support (DSS)":**
   * Jelaskan kartu *Early Warning System* untuk armada kritis.
   * Demonstrasikan **Simulator Headway Dinamis**: Geser slider armada aktif dari 5 menjadi 3 untuk memperlihatkan bagaimana waktu tunggu komuter membengkak dari 11 menit menjadi 20+ menit.
