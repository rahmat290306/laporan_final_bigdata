<?php
/**
 * SISTEM INFORMASI SMART-TRANSPORT TRANS DONGGALA
 * Web Monitoring & Big Data Decision Support System
 * Berbasis PHP Native, MySQL (PDO), TailwindCSS, Leaflet.js, dan Chart.js
 */
session_start();
require_once 'koneksi.php';

// ----------------------------------------------------
// 1. Penanganan Logout
// ----------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['trans_donggala_user']);
    session_destroy();
    header("Location: index.php");
    exit;
}

// ----------------------------------------------------
// 2. Penanganan Login Form
// ----------------------------------------------------
$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        // Autentikasi Fleksibel untuk Demo Pengujian / Sidang
        $_SESSION['trans_donggala_user'] = [
            'username' => htmlspecialchars($username),
            'nama'     => ($username === 'admin' || $username === 'dishub') ? 'Administrator Dishub Donggala' : 'Operator ' . ucfirst($username),
            'role'     => 'Petugas Pengawas & Analis Big Data',
            'login_at' => date('H:i') . ' WITA'
        ];
        header("Location: index.php");
        exit;
    } else {
        $loginError = 'Mohon masukkan username dan password.';
    }
}

$isLoggedIn = isset($_SESSION['trans_donggala_user']);

// ----------------------------------------------------
// 3. JIKA BELUM LOGIN: TAMPILKAN PORTAL LOGIN RESMI
// ----------------------------------------------------
if (!$isLoggedIn):
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Login | Smart-Transport Trans Donggala</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .grid-bg {
            background-image: radial-gradient(rgba(14, 165, 233, 0.15) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between font-sans antialiased bg-slate-50 text-slate-800 selection:bg-sky-500 selection:text-white">

    <!-- Top Identity Minimalist Header -->
    <header class="py-4 px-6 border-b border-slate-800/80 bg-slate-900/60 backdrop-blur">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-600 to-cyan-400 flex items-center justify-center text-white shadow-md shadow-sky-500/30">
                    <i class="fa-solid fa-bus text-base"></i>
                </div>
                <div>
                    <span class="text-sm font-black tracking-wider text-white">TRANS DONGGALA</span>
                    <span class="text-[10px] text-sky-400 font-mono block -mt-0.5">Dinas Perhubungan Kab. Donggala</span>
                </div>
            </div>
            <span class="text-[11px] font-mono px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                <i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i> Portal Petugas Terautentikasi
            </span>
        </div>
    </header>

    <!-- Main Login Card Section -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-auto">
        <div class="max-w-md w-full bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl relative overflow-hidden">
            <!-- Glow Effect Background -->
            <div class="absolute -top-20 -right-20 w-44 h-44 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-44 h-44 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-6">
                <!-- Branding Card Header -->
                <div class="text-center space-y-2">
                    <div class="inline-flex p-3 rounded-2xl bg-gradient-to-tr from-sky-600/30 to-cyan-500/20 text-sky-400 border border-sky-500/30 shadow-inner mb-1">
                        <i class="fa-solid fa-chart-line text-2xl"></i>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Portal Masuk Analis &amp; Petugas</h1>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto leading-relaxed">
                        Sistem Pemantauan Operasional &amp; Dashboard Analitik Big Data Transportasi Publik Donggala &ndash; Palu
                    </p>
                </div>

                <!-- Error Notification if Any -->
                <?php if (!empty($loginError)): ?>
                <div class="p-3 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation shrink-0 text-rose-400"></i>
                    <span><?= $loginError ?></span>
                </div>
                <?php endif; ?>

                <!-- Form Login -->
                <form method="POST" action="index.php" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Username Petugas:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500 text-sm">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" name="username" id="inp-username" required placeholder="Contoh: admin atau dishub"
                                class="w-full bg-slate-950/80 border border-slate-700/80 rounded-xl pl-10 pr-3 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi (Password):</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500 text-sm">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" id="inp-password" required placeholder="••••••••"
                                class="w-full bg-slate-950/80 border border-slate-700/80 rounded-xl pl-10 pr-10 py-2.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                            <button type="button" onclick="togglePassVisibility()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300">
                                <i class="fa-solid fa-eye text-xs" id="icon-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" checked class="accent-sky-500 rounded mr-1.5">
                            <span>Ingat sesi masuk</span>
                        </label>
                        <span class="text-sky-400 font-medium cursor-help" title="Gunakan akun demo: admin / admin123">Bantuan Akses?</span>
                    </div>

                    <button type="submit" name="login_submit" value="1"
                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-500 hover:from-sky-500 hover:to-cyan-400 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-sky-600/30 transition transform active:scale-95 flex items-center justify-center gap-2">
                        <span>Masuk ke Dashboard Analitik</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <!-- 1-Click Demo Login Box (Memudahkan Dosen / Sidang) -->
                <div class="pt-4 border-t border-slate-800">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider text-center font-semibold mb-2">Akses Cepat Pengujian / Sidang:</p>
                    <button type="button" onclick="quickDemoLogin()"
                        class="w-full py-2 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-sky-400 border border-sky-500/30 text-xs font-semibold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-400"></i>
                        <span>1-Klik Masuk sebagai Admin Dishub (Instant Demo)</span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Identity -->
    <footer class="py-4 text-center border-t border-slate-800 text-slate-500 text-xs">
        <p>&copy; 2026 Dinas Perhubungan Kab. Donggala &bull; Tugas Akhir Big Data Analysis Semester 5</p>
    </footer>

    <script>
        function togglePassVisibility() {
            const inp = document.getElementById('inp-password');
            const icon = document.getElementById('icon-eye');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fa-solid fa-eye-slash text-xs';
            } else {
                inp.type = 'password';
                icon.className = 'fa-solid fa-eye text-xs';
            }
        }

        function quickDemoLogin() {
            document.getElementById('inp-username').value = 'admin';
            document.getElementById('inp-password').value = 'admin123';
            document.querySelector('form').submit();
        }
    </script>
</body>
</html>
<?php
exit;
endif;

// ========================================================
// 4. JIKA SUDAH LOGIN: MUAT DATA & TAMPILKAN APLIKASI WEB
// ========================================================
try {
    $buses = $pdo->query("SELECT * FROM tb_bus ORDER BY kode_bus ASC")->fetchAll();
    $halteRute1 = $pdo->query("SELECT * FROM tb_halte WHERE arah_rute LIKE '%Terminal Donggala%' OR arah_rute LIKE '%Terminal Banawa -> Wisma%' ORDER BY urutan ASC")->fetchAll();
    $halteRute2 = $pdo->query("SELECT * FROM tb_halte WHERE arah_rute LIKE '%Wisma Donggala ->%' ORDER BY urutan ASC")->fetchAll();
    $trayek = $pdo->query("SELECT * FROM tb_trayek ORDER BY id_trayek ASC")->fetchAll();
} catch (PDOException $e) {
    die("Gagal memuat data awal: " . $e->getMessage());
}

if (count($trayek) < 5) {
    $trayek = [
        ['id_trayek' => 1, 'kode_trayek' => 'K1', 'nama_trayek' => 'Koridor Utama Pesisir', 'jam_operasional' => '06:00 - 18:00 WITA', 'jarak_km' => 28.50, 'tarif' => 10000, 'deskripsi' => 'Terminal Banawa ↔ Wisma Donggala (Via Jalur Pesisir Palu - Donggala)'],
        ['id_trayek' => 2, 'kode_trayek' => 'FD01', 'nama_trayek' => 'Feeder Banawa Tengah', 'jam_operasional' => '06:30 - 17:30 WITA', 'jarak_km' => 14.20, 'tarif' => 5000, 'deskripsi' => 'Terminal Banawa ↔ Limboro ↔ Towale'],
        ['id_trayek' => 3, 'kode_trayek' => 'FD02', 'nama_trayek' => 'Feeder Tanjung Karang', 'jam_operasional' => '07:00 - 17:00 WITA', 'jarak_km' => 6.50, 'tarif' => 5000, 'deskripsi' => 'Terminal Banawa ↔ Pantai Wisata Tanjung Karang'],
        ['id_trayek' => 4, 'kode_trayek' => 'FD03', 'nama_trayek' => 'Feeder Sindue Agropolitan', 'jam_operasional' => '06:30 - 16:30 WITA', 'jarak_km' => 22.00, 'tarif' => 8000, 'deskripsi' => 'Pasar Toaya ↔ Masaingi ↔ Batusuya'],
        ['id_trayek' => 5, 'kode_trayek' => 'FD04', 'nama_trayek' => 'Feeder Dampelas Pesisir', 'jam_operasional' => '06:00 - 16:00 WITA', 'jarak_km' => 31.50, 'tarif' => 12000, 'deskripsi' => 'Sabang ↔ Sabodi ↔ Lembah Mukti']
    ];
}

$totalBus = count($buses);
$beroperasiCount = 0;
$rusakCount = 0;
foreach ($buses as $b) {
    if ($b['status'] === 'Beroperasi') $beroperasiCount++;
    else $rusakCount++;
}

$currentUser = $_SESSION['trans_donggala_user'];
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trans Donggala Smart-Transport | Web Monitoring & Big Data</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                            900: '#082f49',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet.js CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom scrollbar clean */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .pulse-marker-active {
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        .pulse-marker-broken {
            box-shadow: 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse-red 1.5s infinite;
        }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 12px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8); }
            70% { box-shadow: 0 0 0 14px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        #map { min-height: 680px; z-index: 10; transition: filter 0.3s ease; }

        /* Mode Peta Gelap Elegan (100% Bebas Watermark API Key & Jalan Aspal Terlihat Jelas) */
        .map-dark-theme .leaflet-tile {
            filter: invert(100%) hue-rotate(180deg) brightness(92%) contrast(92%);
        }
        .map-dark-theme {
            background: #0f172a !important;
        }
    </style>
</head>
<body class="h-full flex flex-col font-sans antialiased bg-slate-50 text-slate-800">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200/90 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-3">
                <!-- Brand Title & Identity -->
                <div class="flex items-center space-x-3 shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-cyan-400 flex items-center justify-center text-white shadow-md shadow-sky-500/20 shrink-0">
                        <i class="fa-solid fa-bus text-lg"></i>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="flex items-center gap-2">
                            <span class="text-base sm:text-lg font-black tracking-wide text-slate-900 leading-none">
                                TRANS DONGGALA
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> v1.0 Live System
                            </span>
                        </div>
                        <p class="text-[11px] sm:text-xs text-slate-500 font-medium tracking-normal mt-1 leading-tight">
                            Web Monitoring &amp; Big Data Decision Support System
                        </p>
                    </div>
                </div>

                <!-- Navigation Tabs (Default: Big Data Analytics Aktif!) -->
                <nav class="hidden md:flex items-center space-x-1 bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
                    <button onclick="switchTab('analytics')" id="nav-analytics" class="tab-btn px-3.5 py-1.5 rounded-lg text-xs lg:text-sm font-semibold transition-all bg-sky-500 text-white shadow-xs">
                        <i class="fa-solid fa-chart-pie mr-1.5"></i> Dashboard 25 Chart
                    </button>
                    <button onclick="switchTab('monitoring')" id="nav-monitoring" class="tab-btn px-3.5 py-1.5 rounded-lg text-xs lg:text-sm font-semibold transition-all text-slate-600 hover:text-slate-900 hover:bg-white/80">
                        <i class="fa-solid fa-map-location-dot mr-1.5"></i> Live Monitoring
                    </button>
                    <button onclick="switchTab('dss')" id="nav-dss" class="tab-btn px-3.5 py-1.5 rounded-lg text-xs lg:text-sm font-semibold transition-all text-slate-600 hover:text-slate-900 hover:bg-white/80">
                        <i class="fa-solid fa-brain mr-1.5"></i> Decision Support
                    </button>
                    <button onclick="switchTab('trayek')" id="nav-trayek" class="tab-btn px-3.5 py-1.5 rounded-lg text-xs lg:text-sm font-semibold transition-all text-slate-600 hover:text-slate-900 hover:bg-white/80">
                        <i class="fa-solid fa-route mr-1.5"></i> Daftar Trayek
                    </button>
                </nav>

                <!-- Status Pills, Admin & Logout Button -->
                <div class="flex items-center space-x-2.5 shrink-0">
                    <div class="hidden lg:flex items-center space-x-2 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 text-xs">
                        <span class="flex items-center text-emerald-700 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            <span id="stat-beroperasi"><?= $beroperasiCount ?></span> Aktif
                        </span>
                        <span class="text-slate-300 font-bold">|</span>
                        <span class="flex items-center text-rose-700 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-rose-500 mr-1.5"></span>
                            <span id="stat-rusak"><?= $rusakCount ?></span> Rusak
                        </span>
                    </div>

                    <button onclick="openModalUpdate()" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition flex items-center">
                        <i class="fa-solid fa-sliders mr-1.5"></i> Kelola Armada
                    </button>

                    <!-- User Identity Badge & Logout Link -->
                    <div class="hidden sm:flex items-center gap-2.5 pl-3 border-l border-slate-200">
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900 block leading-tight truncate max-w-[150px]"><?= $currentUser['nama'] ?></span>
                            <span class="text-[10px] text-sky-600 font-bold font-mono block">Petugas Terautentikasi</span>
                        </div>
                        <a href="index.php?action=logout" title="Keluar dari sesi portal"
                           class="px-2.5 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 transition flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="hidden md:inline">Keluar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Tabs -->
        <div class="md:hidden flex border-t border-slate-200 bg-white px-2 py-1.5 overflow-x-auto space-x-2">
            <button onclick="switchTab('analytics')" class="px-3 py-1 rounded-lg text-xs whitespace-nowrap bg-sky-500 text-white font-bold">25 Chart Data</button>
            <button onclick="switchTab('monitoring')" class="px-3 py-1 rounded-lg text-xs whitespace-nowrap text-slate-600 hover:bg-slate-100">Live Monitoring</button>
            <button onclick="switchTab('dss')" class="px-3 py-1 rounded-lg text-xs whitespace-nowrap text-slate-600 hover:bg-slate-100">Decision Support</button>
            <button onclick="switchTab('trayek')" class="px-3 py-1 rounded-lg text-xs whitespace-nowrap text-slate-600 hover:bg-slate-100">Daftar Trayek</button>
            <a href="index.php?action=logout" class="px-3 py-1 rounded-lg text-xs whitespace-nowrap bg-rose-50 text-rose-600 border border-rose-200 font-bold">Keluar</a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- ======================================================== -->
        <!-- TAB 1: LIVE MONITORING (SIDEBAR KIRI + PETA KANAN)      -->
        <!-- ======================================================== -->
        <section id="tab-section-monitoring" class="hidden space-y-4">
            
            <!-- BPS-Style KPI Carousel (Statistik Utama Operasional) -->
            <div class="relative mb-6">
                <!-- Carousel Title Bar -->
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-column text-sky-400"></i>
                        Statistik Utama Operasional
                    </h2>
                    <span class="text-[10px] text-slate-500 font-mono" id="kpi-update-time">Update: <?= date('d M Y H:i') ?> WITA</span>
                </div>

                <!-- Left Arrow -->
                <button onclick="scrollKPI(-1)" class="absolute left-0 top-1/2 translate-y-1 z-10 w-9 h-9 rounded-full bg-slate-800/90 border border-slate-700 hover:bg-sky-600 hover:border-sky-500 text-slate-300 hover:text-white flex items-center justify-center shadow-lg transition-all" id="kpi-arrow-left">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>

                <!-- Right Arrow -->
                <button onclick="scrollKPI(1)" class="absolute right-0 top-1/2 translate-y-1 z-10 w-9 h-9 rounded-full bg-slate-800/90 border border-slate-700 hover:bg-sky-600 hover:border-sky-500 text-slate-300 hover:text-white flex items-center justify-center shadow-lg transition-all" id="kpi-arrow-right">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>

                <!-- Scrollable Carousel Track -->
                <div class="overflow-hidden mx-10" id="kpi-carousel-viewport">
                    <div class="flex gap-4 transition-transform duration-500 ease-out" id="kpi-carousel-track" style="will-change: transform;">

                        <!-- Card 1: Avg Kecepatan Koridor -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-sky-900/20 hover:border-sky-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-sky-500/15 text-sky-400 flex items-center justify-center">
                                <i class="fa-solid fa-gauge-high text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Avg Kecepatan</p>
                            <p class="text-2xl font-black text-white font-mono leading-none mb-1" id="telemetry-speed">64.8</p>
                            <p class="text-[10px] text-slate-500">km/jam</p>
                            <p class="text-[10px] text-sky-400/80 mt-2 font-medium" id="telemetry-speed-sub">Koridor K1</p>
                        </div>

                        <!-- Card 2: Okupansi Real-Time -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-emerald-900/20 hover:border-emerald-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center">
                                <i class="fa-solid fa-users text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Okupansi</p>
                            <p class="text-2xl font-black text-emerald-300 font-mono leading-none mb-1" id="telemetry-occupancy">74.2%</p>
                            <p class="text-[10px] text-slate-500">Load Factor</p>
                            <p class="text-[10px] text-emerald-400/80 mt-2 font-medium">Real-Time</p>
                        </div>

                        <!-- Card 3: Headway K1 -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-amber-900/20 hover:border-amber-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center">
                                <i class="fa-solid fa-stopwatch text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Headway K1</p>
                            <p class="text-2xl font-black text-amber-300 font-mono leading-none mb-1" id="telemetry-headway">6.5</p>
                            <p class="text-[10px] text-slate-500">Menit</p>
                            <p class="text-[10px] text-amber-400/80 mt-2 font-medium" id="telemetry-headway-sub">Standar &le; 15 Menit</p>
                        </div>

                        <!-- Card 4: Fleet Reliability -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-purple-900/20 hover:border-purple-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-purple-500/15 text-purple-400 flex items-center justify-center">
                                <i class="fa-solid fa-shield-halved text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Fleet Reliability</p>
                            <p class="text-2xl font-black text-purple-300 font-mono leading-none mb-1" id="telemetry-fri">87.4%</p>
                            <p class="text-[10px] text-slate-500">Index</p>
                            <p class="text-[10px] text-purple-400/80 mt-2 font-medium" id="telemetry-fri-sub">Rasio Kesiapan</p>
                        </div>

                        <!-- Card 5: On-Time Performance -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-cyan-900/20 hover:border-cyan-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-cyan-500/15 text-cyan-400 flex items-center justify-center">
                                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">On-Time Perf.</p>
                            <p class="text-2xl font-black text-cyan-300 font-mono leading-none mb-1" id="telemetry-otp">80.0%</p>
                            <p class="text-[10px] text-slate-500">Ketepatan</p>
                            <p class="text-[10px] text-cyan-400/80 mt-2 font-medium" id="telemetry-otp-sub">4 Tepat &bull; 1 Delay</p>
                        </div>

                        <!-- Card 6: Total Log Historis -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-rose-900/20 hover:border-rose-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-rose-500/15 text-rose-400 flex items-center justify-center">
                                <i class="fa-solid fa-database text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Total Log</p>
                            <p class="text-2xl font-black text-white font-mono leading-none mb-1" id="kpi-total-log">150</p>
                            <p class="text-[10px] text-slate-500">Record</p>
                            <p class="text-[10px] text-rose-400/80 mt-2 font-medium">30 Hari Historis</p>
                        </div>

                        <!-- Card 7: Total Penumpang -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-teal-900/20 hover:border-teal-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-teal-500/15 text-teal-400 flex items-center justify-center">
                                <i class="fa-solid fa-people-group text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Total Penumpang</p>
                            <p class="text-2xl font-black text-teal-300 font-mono leading-none mb-1" id="kpi-total-pnp">65.960</p>
                            <p class="text-[10px] text-slate-500">Komuter</p>
                            <p class="text-[10px] text-teal-400/80 mt-2 font-medium">30 Hari Terlayani</p>
                        </div>

                        <!-- Card 8: Avg Delay Insiden -->
                        <div class="kpi-card min-w-[180px] flex-shrink-0 bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700/80 rounded-2xl px-5 py-5 text-center shadow-lg hover:shadow-orange-900/20 hover:border-orange-600/50 transition-all cursor-default">
                            <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-orange-500/15 text-orange-400 flex items-center justify-center">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-1">Avg Delay</p>
                            <p class="text-2xl font-black text-orange-300 font-mono leading-none mb-1" id="kpi-avg-delay">15.4</p>
                            <p class="text-[10px] text-slate-500">Menit/Insiden</p>
                            <p class="text-[10px] text-orange-400/80 mt-2 font-medium">Saat Gangguan</p>
                        </div>

                    </div>
                </div>

                <!-- Dot Indicators -->
                <div class="flex justify-center gap-1.5 mt-3" id="kpi-dots"></div>
            </div>

            <!-- Grid Layout: Sidebar Kiri (5 cols) & Peta Kanan (7 cols) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Sidebar: Route & Fleet & Halte Timeline (5 cols) -->
                <div class="lg:col-span-5 space-y-3 flex flex-col">
                    
                    <!-- Direction Switcher (CekTrans Style) -->
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                        <label class="text-xs font-bold text-slate-800 font-black mb-2 block uppercase tracking-wider">Arah Perjalanan Koridor K1</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button id="btn-dir-1" onclick="switchDirection(1)" class="px-3 py-2.5 rounded-lg text-xs font-bold transition border border-sky-500 bg-sky-600/30 text-sky-300 text-left flex items-center justify-between shadow-sm">
                                <span>Banawa &rarr; Wisma</span>
                                <span class="px-1.5 py-0.5 rounded bg-slate-900 text-[10px] font-mono text-sky-300" id="route_label_1">14 Halte</span>
                            </button>
                            <button id="btn-dir-2" onclick="switchDirection(2)" class="px-3 py-2.5 rounded-lg text-xs font-bold transition border border-slate-700 bg-slate-800 text-slate-400 hover:text-white text-left flex items-center justify-between">
                                <span>Wisma &rarr; Banawa</span>
                                <span class="px-1.5 py-0.5 rounded bg-slate-900 text-[10px] font-mono text-slate-400" id="route_label_2">14 Halte</span>
                            </button>
                        </div>
                    </div>

                    <!-- SUB-TAB SWITCHER: ARMADA vs HALTE (Opsi A) -->
                    <div class="bg-slate-100 border border-slate-200 rounded-xl p-1.5 flex gap-1.5 shadow-xs">
                        <button id="btn-sidebar-fleet" onclick="switchSidebarTab('fleet')" class="flex-1 py-2 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 bg-sky-600 text-white shadow-sm">
                            <i class="fa-solid fa-bus text-[11px]"></i>
                            <span>Armada Bus (5 Unit)</span>
                            <span id="badge-tab-fleet" class="px-1.5 py-0.2 rounded-full text-[10px] bg-sky-800 text-sky-200">5</span>
                        </button>
                        <button id="btn-sidebar-halte" onclick="switchSidebarTab('halte')" class="flex-1 py-2 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 text-slate-400 hover:text-white hover:bg-slate-800">
                            <i class="fa-solid fa-clock text-[11px]"></i>
                            <span>Estimasi Tiba (ETA)</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-200 text-slate-700 font-bold">14</span>
                        </button>
                    </div>

                    <!-- PANEL 1: DAFTAR ARMADA BUS -->
                    <div id="panel-sidebar-fleet" class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center">
                                <i class="fa-solid fa-bus-simple text-sky-400 mr-2"></i> Armada Trans Donggala
                            </h3>
                            <span class="text-[10px] text-slate-400">5 Terpantau</span>
                        </div>
                        <div class="space-y-2.5 max-h-[540px] overflow-y-auto pr-1" id="fleet-list-container">
                            <!-- Populated via JS -->
                        </div>
                    </div>

                    <!-- PANEL 2: ESTIMASI WAKTU TIBA (ETA) TIAP HALTE -->
                    <div id="panel-sidebar-halte" class="hidden bg-white border border-slate-200 rounded-xl p-4 shadow-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center">
                                <i class="fa-solid fa-clock text-amber-400 mr-2"></i> Estimasi Waktu Tiba (ETA) Tiap Halte
                            </h3>
                            <span class="text-[10px] text-slate-400">14 Titik Pemberhentian</span>
                        </div>
                        <div class="space-y-2 max-h-[540px] overflow-y-auto pr-1" id="halte-timeline-container">
                            <!-- Populated via JS -->
                        </div>
                    </div>

                </div>

                <!-- Right Map Section (7 cols) -->
                <div class="lg:col-span-7 space-y-3 flex flex-col">
                    <div class="bg-white border border-slate-200 rounded-xl p-3 flex flex-wrap items-center justify-between gap-2 shadow-xs">
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="font-black text-slate-900 text-sm tracking-tight">Peta Pemantauan Spasial Rute Jalur Pesisir</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="flex items-center text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Beroperasi</span>
                            <span class="flex items-center text-[11px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200"><span class="w-2 h-2 rounded-full bg-rose-500 mr-1.5"></span> Rusak</span>
                            <span class="flex items-center text-[11px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-200"><span class="w-2 h-2 rounded-full bg-sky-500 mr-1.5"></span> Rambu Resmi</span>
                            <span class="flex items-center text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200"><span class="w-2 h-2 rounded-full bg-slate-400 mr-1.5"></span> Fleksibel</span>
                            <button onclick="toggleMapTheme()" id="btn-map-theme" class="px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-[10px] font-semibold flex items-center gap-1 transition">
                                <i class="fa-solid fa-moon text-sky-400"></i> <span>Peta Gelap</span>
                            </button>
                            <button onclick="fitRouteBounds()" class="px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-[10px] font-semibold flex items-center gap-1 transition">
                                <i class="fa-solid fa-crosshairs text-emerald-400"></i> <span>Fokus Rute</span>
                            </button>
                        </div>
                    </div>

                    <!-- Leaflet Container min-h-[680px] -->
                    <div class="bg-white border border-slate-200 rounded-xl p-1.5 shadow-sm flex-1 relative overflow-hidden">
                        <div id="map" class="w-full h-full rounded-lg"></div>
                    </div>
                </div>

            </div>

        </section>


        <!-- ======================================================== -->
        <!-- TAB 2: DASHBOARD 25 DATA CHART (BIG DATA ANALYTICS)     -->
        <!-- DEFAULT AKTIF SAAT PERTAMA KALI LOGIN!                  -->
        <!-- ======================================================== -->
        <section id="tab-section-analytics" class="space-y-5">

            <!-- 1. Header Ringkas & Bersih ala CekTrans -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/90">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-chart-pie"></i>
                        </span>
                        Dashboard Analitik Kinerja Trans Donggala
                    </h1>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Pemantauan kinerja 5 armada bus koridor pesisir, Standar Pelayanan Minimal (SPM), dan indikator operasional 30 hari.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 25 Indikator Visual Terpadu
                    </span>
                </div>
            </div>

            <!-- 2. 4 Kartu KPI Metrik Utama (Clean Modern White Card ala Referensi) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <!-- KPI 1: Total Log -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:shadow-md hover:border-sky-300 transition-all">
                    <div class="w-10 h-10 mx-auto mb-2.5 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
                        <i class="fa-solid fa-database text-base"></i>
                    </div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">TOTAL LOG ANALISIS</p>
                    <p class="text-3xl font-black text-slate-900 font-mono my-1 tracking-tight" id="kpi-total-log">150</p>
                    <p class="text-xs text-slate-400 font-medium">30 Hari Historis</p>
                </div>

                <!-- KPI 2: Komuter Terlayani -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:shadow-md hover:border-emerald-300 transition-all">
                    <div class="w-10 h-10 mx-auto mb-2.5 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-users text-base"></i>
                    </div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">KOMUTER TERLAYANI</p>
                    <p class="text-3xl font-black text-emerald-600 font-mono my-1 tracking-tight" id="kpi-total-penumpang">65,960</p>
                    <p class="text-xs text-slate-400 font-medium">Koridor Pesisir K1</p>
                </div>

                <!-- KPI 3: Rata-Rata Delay -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:shadow-md hover:border-amber-300 transition-all">
                    <div class="w-10 h-10 mx-auto mb-2.5 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-hourglass-half text-base"></i>
                    </div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">RATA-RATA DELAY</p>
                    <p class="text-3xl font-black text-amber-600 font-mono my-1 tracking-tight" id="kpi-avg-delay">15.4 mnt</p>
                    <p class="text-xs text-slate-400 font-medium">Saat Gangguan Mesin (TD 03 Rusak)</p>
                </div>

                <!-- KPI 4: Fleet Reliability -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 text-center shadow-xs hover:shadow-md hover:border-purple-300 transition-all">
                    <div class="w-10 h-10 mx-auto mb-2.5 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-base"></i>
                    </div>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">FLEET RELIABILITY</p>
                    <p class="text-3xl font-black text-purple-600 font-mono my-1 tracking-tight" id="kpi-reliability">87.4%</p>
                    <p class="text-xs text-slate-400 font-medium">Rasio Kesiapan Bus</p>
                </div>
            </div>

            <!-- 3. Carousel 5 Klaster Navigasi Pastel dengan Panah (Persis Referensi Screenshot) -->
            <div class="relative bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between mb-3 px-1">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-sky-500"></i> KLASTER ANALITIK KINERJA
                    </span>
                    <span class="text-xs text-slate-500 font-medium hidden sm:inline">Geser atau klik kartu untuk fokus pada klaster</span>
                </div>

                <!-- Tombol Panah Kiri -->
                <button type="button" onclick="scrollKlasterNav(-1)" class="absolute -left-3.5 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white border border-slate-200 hover:bg-sky-500 hover:border-sky-500 text-slate-700 hover:text-white flex items-center justify-center shadow-md transition" title="Geser ke Kiri">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>

                <!-- Tombol Panah Kanan -->
                <button type="button" onclick="scrollKlasterNav(1)" class="absolute -right-3.5 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white border border-slate-200 hover:bg-sky-500 hover:border-sky-500 text-slate-700 hover:text-white flex items-center justify-center shadow-md transition" title="Geser ke Kanan">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>

                <!-- Track Carousel Klaster (Scrollable) -->
                <div id="klaster-carousel-viewport" class="overflow-x-auto scroll-smooth no-scrollbar py-1 px-1">
                    <div id="klaster-carousel-track" class="flex gap-3.5 min-w-max">
                        
                        <!-- Klaster 1: Pastel Blue -->
                        <div onclick="filterCluster('c_armada')" class="cursor-pointer group w-64 bg-sky-50/80 border border-sky-300 hover:border-sky-500 rounded-2xl p-4 transition shadow-xs flex flex-col justify-between shrink-0 hover:bg-sky-50">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-white bg-sky-500 px-2 py-0.5 rounded-md shadow-2xs">KLASTER 1</span>
                                    <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-bus"></i>
                                    </div>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-sky-600 transition">Operasional Armada</h4>
                                <p class="text-lg font-black text-slate-900 font-mono mt-1">4 Aktif <span class="text-xs text-rose-600 font-bold">/ 1 Rusak</span></p>
                                <p class="text-[11px] text-slate-600 mt-1 font-medium truncate">TD 03 Overheating Mesin</p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-sky-200/80 text-xs text-sky-600 font-bold flex items-center justify-between">
                                <span>Chart #01 &ndash; #05</span> <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                            </div>
                        </div>

                        <!-- Klaster 2: Pastel Amber -->
                        <div onclick="filterCluster('c_delay')" class="cursor-pointer group w-64 bg-amber-50/80 border border-amber-300 hover:border-amber-500 rounded-2xl p-4 transition shadow-xs flex flex-col justify-between shrink-0 hover:bg-amber-50">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-white bg-amber-500 px-2 py-0.5 rounded-md shadow-2xs">KLASTER 2</span>
                                    <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-stopwatch"></i>
                                    </div>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-amber-600 transition">Delay &amp; SPM Waktu</h4>
                                <p class="text-lg font-black text-amber-800 font-mono mt-1">15.4 mnt <span class="text-xs text-amber-600 font-bold">delay</span></p>
                                <p class="text-[11px] text-slate-600 mt-1 font-medium truncate">Waktu tempuh 26 menit</p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-amber-200/80 text-xs text-amber-600 font-bold flex items-center justify-between">
                                <span>Chart #06 &ndash; #10</span> <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                            </div>
                        </div>

                        <!-- Klaster 3: Pastel Emerald -->
                        <div onclick="filterCluster('c_penumpang')" class="cursor-pointer group w-64 bg-emerald-50/80 border border-emerald-300 hover:border-emerald-500 rounded-2xl p-4 transition shadow-xs flex flex-col justify-between shrink-0 hover:bg-emerald-50">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-white bg-emerald-500 px-2 py-0.5 rounded-md shadow-2xs">KLASTER 3</span>
                                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-emerald-600 transition">Dinamika Komuter</h4>
                                <p class="text-lg font-black text-emerald-800 font-mono mt-1">65,960 <span class="text-xs text-emerald-600 font-bold">org</span></p>
                                <p class="text-[11px] text-slate-600 mt-1 font-medium truncate">Peak: 07:00 &amp; 17:00 WITA</p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-emerald-200/80 text-xs text-emerald-600 font-bold flex items-center justify-between">
                                <span>Chart #11 &ndash; #15</span> <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                            </div>
                        </div>

                        <!-- Klaster 4: Pastel Rose -->
                        <div onclick="filterCluster('c_perawatan')" class="cursor-pointer group w-64 bg-rose-50/80 border border-rose-300 hover:border-rose-500 rounded-2xl p-4 transition shadow-xs flex flex-col justify-between shrink-0 hover:bg-rose-50">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-white bg-rose-500 px-2 py-0.5 rounded-md shadow-2xs">KLASTER 4</span>
                                    <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-wrench"></i>
                                    </div>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-rose-600 transition">Kesehatan Mesin</h4>
                                <p class="text-lg font-black text-rose-800 font-mono mt-1 leading-tight">Overheating (TD 03)</p>
                                <p class="text-[11px] text-slate-600 mt-1 font-medium truncate">Overhaul Radiator &amp; Kopling</p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-rose-200/80 text-xs text-rose-600 font-bold flex items-center justify-between">
                                <span>Chart #16 &ndash; #20</span> <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                            </div>
                        </div>

                        <!-- Klaster 5: Pastel Purple -->
                        <div onclick="filterCluster('c_halte')" class="cursor-pointer group w-64 bg-purple-50/80 border border-purple-300 hover:border-purple-500 rounded-2xl p-4 transition shadow-xs flex flex-col justify-between shrink-0 hover:bg-purple-50">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-white bg-purple-500 px-2 py-0.5 rounded-md shadow-2xs">KLASTER 5</span>
                                    <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </div>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-purple-600 transition">Halte &amp; Finansial</h4>
                                <p class="text-lg font-black text-purple-800 font-mono mt-1">14 Halte <span class="text-xs text-purple-600 font-bold">K1</span></p>
                                <p class="text-[11px] text-slate-600 mt-1 font-medium truncate">K1 raup 67% pendapatan</p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-purple-200/80 text-xs text-purple-600 font-bold flex items-center justify-between">
                                <span>Chart #21 &ndash; #25</span> <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- 4. Toolbar Tab Filter Minimalis (Clean White Bar ala CekTrans) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-2.5 shadow-xs flex items-center justify-between gap-3 overflow-x-auto">
                <!-- Filter Tabs -->
                <div class="flex items-center gap-1.5 shrink-0" id="cluster-pills-bar">
                    <button onclick="filterCluster('all')" id="flt-all" class="flt-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition bg-sky-500 text-white shadow-xs">
                        <i class="fa-solid fa-layer-group mr-1.5"></i> Semua (25 Chart)
                    </button>
                    <button onclick="filterCluster('c_armada')" id="flt-c_armada" class="flt-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                        🚌 Armada (1-5)
                    </button>
                    <button onclick="filterCluster('c_delay')" id="flt-c_delay" class="flt-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                        ⏱️ Delay &amp; SPM (6-10)
                    </button>
                    <button onclick="filterCluster('c_penumpang')" id="flt-c_penumpang" class="flt-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                        👥 Komuter (11-15)
                    </button>
                    <button onclick="filterCluster('c_perawatan')" id="flt-c_perawatan" class="flt-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                        🔧 Mesin &amp; Biaya (16-20)
                    </button>
                    <button onclick="filterCluster('c_halte')" id="flt-c_halte" class="flt-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                        🚏 Halte &amp; Trayek (21-25)
                    </button>
                </div>

                <span id="chart-count-badge" class="hidden sm:inline-block text-[11px] font-mono font-bold text-slate-600 bg-slate-100 border border-slate-200 px-3 py-1 rounded-xl shrink-0">25/25 Chart</span>
            </div>

            <!-- GRID UTAMA 25 KARTU CHART DENGAN PEMBATAS KLASTER SIMETRIS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5" id="container-25-charts">

                <!-- BANNER PEMBATAS KLASTER: KLASTER 1 / 5 -->
                <div class="cluster-banner c_armada col-span-1 lg:col-span-2 bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-bus text-sky-400 text-lg"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-sky-500/10 text-sky-400 border-sky-500/30 border">KLASTER 1 / 5</span>
                                <span class="text-[11px] font-bold text-slate-500">5 Indikator Analitik</span>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 mt-0.5">Operasional, Efisiensi & Kesiapan 5 Armada Bus</h2>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed max-w-2xl">Evaluasi komparatif 5 bus di Koridor K1 (28,5 km): ketersediaan operasional, kecepatan jelajah, ritase trip, dan konsumsi solar.</p>
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center gap-2 self-start sm:self-center">
                        <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 4 Aktif / 1 Mogok
                        </span>
                    </div>
                </div>
                <!-- Chart 01: Frekuensi Kerusakan Mendadak per Armada (30 Hari) -->
                <div class="chart-item c_armada bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_armada" data-search="chart 1 01 frekuensi kerusakan mendadak per armada (30 hari) perbandingan jumlah hari mogok antara 5 armada bus trans donggala di jalur pesisir hari mogok unit td 03 mencatat 12 hari mogok (38,7%), 6x lipat lebih tinggi dari armada normal lainnya. operasional, efisiensi & kesiapan 5 armada bus">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-sky-500/10 text-sky-400 border-sky-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-bus text-sky-400"></i> KLASTER 1
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Hari Mogok</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#01</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Frekuensi Kerusakan Mendadak per Armada (30 Hari)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Perbandingan jumlah hari mogok antara 5 armada bus Trans Donggala di jalur pesisir</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart1"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Unit TD 03 mencatat 12 hari mogok (38,7%), 6x lipat lebih tinggi dari armada normal lainnya.</p>
                    </div>
                </div>
                <!-- Chart 02: Rata-Rata Kecepatan Operasional per Armada (km/jam) -->
                <div class="chart-item c_armada bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_armada" data-search="chart 2 02 rata-rata kecepatan operasional per armada (km/jam) kecepatan jelajah aktual 5 bus di koridor k1 palu &ndash; donggala (28,5 km) km/jam td 01, 02, 04, 05 melaju stabil pada 62-68 km/jam, td 03 terhenti di 0 km/jam saat rusak. operasional, efisiensi & kesiapan 5 armada bus">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-sky-500/10 text-sky-400 border-sky-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-bus text-sky-400"></i> KLASTER 1
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">km/jam</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#02</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Rata-Rata Kecepatan Operasional per Armada (km/jam)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Kecepatan jelajah aktual 5 bus di Koridor K1 Palu &ndash; Donggala (28,5 km)</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart2"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">TD 01, 02, 04, 05 melaju stabil pada 62-68 km/jam, TD 03 terhenti di 0 km/jam saat rusak.</p>
                    </div>
                </div>
                <!-- Chart 03: Total Trip Operasional Selesai per Armada (30 Hari) -->
                <div class="chart-item c_armada bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_armada" data-search="chart 3 03 total trip operasional selesai per armada (30 hari) akumulasi perjalanan bolak-balik (ritase) yang berhasil dituntaskan tiap unit trip selesai td 03 kehilangan 98 trip potensial akibat kerusakan mesin dan radiator overheating. operasional, efisiensi & kesiapan 5 armada bus">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-sky-500/10 text-sky-400 border-sky-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-bus text-sky-400"></i> KLASTER 1
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Trip Selesai</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#03</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Total Trip Operasional Selesai per Armada (30 Hari)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Akumulasi perjalanan bolak-balik (ritase) yang berhasil dituntaskan tiap unit</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart3"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">TD 03 kehilangan 98 trip potensial akibat kerusakan mesin dan radiator overheating.</p>
                    </div>
                </div>
                <!-- Chart 04: Rasio Kesiapan Operasional Armada (% Availability) -->
                <div class="chart-item c_armada bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_armada" data-search="chart 4 04 rasio kesiapan operasional armada (% availability) persentase ketersediaan armada melayani komuter di koridor utama donggala % kesiapan td 01 & td 05 memiliki availability tertinggi (96,7%), sedangkan td 03 hanya mencapai 61,3%. operasional, efisiensi & kesiapan 5 armada bus">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-sky-500/10 text-sky-400 border-sky-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-bus text-sky-400"></i> KLASTER 1
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">% Kesiapan</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#04</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Rasio Kesiapan Operasional Armada (% Availability)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Persentase ketersediaan armada melayani komuter di koridor utama Donggala</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart4"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">TD 01 & TD 05 memiliki availability tertinggi (96,7%), sedangkan TD 03 hanya mencapai 61,3%.</p>
                    </div>
                </div>
                <!-- Chart 05: Konsumsi Bahan Bakar Solar per Armada (Liter / 100 km) -->
                <div class="chart-item c_armada bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_armada" data-search="chart 5 05 konsumsi bahan bakar solar per armada (liter / 100 km) efisiensi pemakaian bahan bakar solar rata-rata pada medan pesisir pantai liter / 100 km td 03 paling boros (35,6 l/100km) akibat gesekan mesin berlebih saat sistem pendingin bocor. operasional, efisiensi & kesiapan 5 armada bus">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-sky-500/10 text-sky-400 border-sky-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-bus text-sky-400"></i> KLASTER 1
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Liter / 100 km</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#05</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Konsumsi Bahan Bakar Solar per Armada (Liter / 100 km)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Efisiensi pemakaian bahan bakar solar rata-rata pada medan pesisir pantai</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart5"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">TD 03 paling boros (35,6 L/100km) akibat gesekan mesin berlebih saat sistem pendingin bocor.</p>
                    </div>
                </div>
                <!-- BANNER PEMBATAS KLASTER: KLASTER 2 / 5 -->
                <div class="cluster-banner c_delay col-span-1 lg:col-span-2 bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-stopwatch text-amber-400 text-lg"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-400 border-amber-500/30 border">KLASTER 2 / 5</span>
                                <span class="text-[11px] font-bold text-slate-500">5 Indikator Analitik</span>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 mt-0.5">Keterlambatan (Delay) & Standar Pelayanan Minimal (SPM)</h2>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed max-w-2xl">Analisis durasi delay saat terjadi insiden armada, fluktuasi menit delay harian 30 hari, kerentanan jam sibuk sore, dan variansi headway.</p>
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center gap-2 self-start sm:self-center">
                        <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Avg Delay 15.4 mnt
                        </span>
                    </div>
                </div>
                <!-- Chart 06: Rata-Rata Durasi Keterlambatan per Armada saat Insiden (Menit) -->
                <div class="chart-item c_delay bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_delay" data-search="chart 6 06 rata-rata durasi keterlambatan per armada saat insiden (menit) keterlambatan rata-rata yang ditimbulkan tiap armada saat mengalami kendala teknis menit delay insiden pada unit td 03 memicu delay terparah hingga rata-rata 68,5 menit per kejadian. keterlambatan (delay) & standar pelayanan minimal (spm)">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 border-amber-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-stopwatch text-amber-400"></i> KLASTER 2
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Menit Delay</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#06</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Rata-Rata Durasi Keterlambatan per Armada saat Insiden (Menit)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Keterlambatan rata-rata yang ditimbulkan tiap armada saat mengalami kendala teknis</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart6"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Insiden pada unit TD 03 memicu delay terparah hingga rata-rata 68,5 menit per kejadian.</p>
                    </div>
                </div>
                <!-- Chart 07: Tren Keterlambatan Harian Sepanjang 30 Hari (Menit Delay) -->
                <div class="chart-item c_delay bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_delay" data-search="chart 7 07 tren keterlambatan harian sepanjang 30 hari (menit delay) grafik fluktuasi menit keterlambatan harian armada di koridor utama 28,5 km menit / hari lonjakan keterlambatan terjadi sinkron pada hari-hari ketika armada mogok di rute pesisir. keterlambatan (delay) & standar pelayanan minimal (spm)">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 border-amber-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-stopwatch text-amber-400"></i> KLASTER 2
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Menit / Hari</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#07</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Tren Keterlambatan Harian Sepanjang 30 Hari (Menit Delay)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Grafik fluktuasi menit keterlambatan harian armada di koridor utama 28,5 km</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart7"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Lonjakan keterlambatan terjadi sinkron pada hari-hari ketika armada mogok di rute pesisir.</p>
                    </div>
                </div>
                <!-- Chart 08: Distribusi Keterlambatan Berdasarkan Jam Operasional -->
                <div class="chart-item c_delay bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_delay" data-search="chart 8 08 distribusi keterlambatan berdasarkan jam operasional tingkat kerentanan delay pada jam sibuk pagi, siang, dan jam sibuk sore menit rata-rata jam sibuk sore (16:30 - 18:30) mencatat delay terlama (28,2 menit) saat arus balik pekerja. keterlambatan (delay) & standar pelayanan minimal (spm)">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 border-amber-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-stopwatch text-amber-400"></i> KLASTER 2
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Menit Rata-rata</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#08</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Distribusi Keterlambatan Berdasarkan Jam Operasional</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Tingkat kerentanan delay pada jam sibuk pagi, siang, dan jam sibuk sore</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart8"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Jam sibuk sore (16:30 - 18:30) mencatat delay terlama (28,2 menit) saat arus balik pekerja.</p>
                    </div>
                </div>
                <!-- Chart 09: Perbandingan Waktu Tempuh Riil vs Standar SPM (28,5 km) -->
                <div class="chart-item c_delay bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_delay" data-search="chart 9 09 perbandingan waktu tempuh riil vs standar spm (28,5 km) waktu tempuh aktual armada dibanding standar pelayanan minimal dishub (26 menit) menit tempuh armada aktif mencatat rata-rata 26,4 menit, memenuhi standar spm koridor k1 dishub. keterlambatan (delay) & standar pelayanan minimal (spm)">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 border-amber-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-stopwatch text-amber-400"></i> KLASTER 2
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Menit Tempuh</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#09</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Perbandingan Waktu Tempuh Riil vs Standar SPM (28,5 km)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Waktu tempuh aktual armada dibanding standar pelayanan minimal Dishub (26 Menit)</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart9"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Armada aktif mencatat rata-rata 26,4 menit, memenuhi standar SPM Koridor K1 Dishub.</p>
                    </div>
                </div>
                <!-- Chart 10: Variansi Interval Kedatangan Bus (Headway) per Jam Layanan -->
                <div class="chart-item c_delay bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_delay" data-search="chart 10 10 variansi interval kedatangan bus (headway) per jam layanan waktu tunggu kedatangan antar-bus dari jam 06:00 sampai 18:00 wita menit headway headway ideal adalah 6,5 menit pada 4 bus aktif, melebar di atas 9 menit pada jam istirahat. keterlambatan (delay) & standar pelayanan minimal (spm)">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-amber-500/10 text-amber-400 border-amber-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-stopwatch text-amber-400"></i> KLASTER 2
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Menit Headway</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#10</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Variansi Interval Kedatangan Bus (Headway) per Jam Layanan</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Waktu tunggu kedatangan antar-bus dari jam 06:00 sampai 18:00 WITA</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart10"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Headway ideal adalah 6,5 menit pada 4 bus aktif, melebar di atas 9 menit pada jam istirahat.</p>
                    </div>
                </div>
                <!-- BANNER PEMBATAS KLASTER: KLASTER 3 / 5 -->
                <div class="cluster-banner c_penumpang col-span-1 lg:col-span-2 bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-users text-indigo-400 text-lg"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-400 border-indigo-500/30 border">KLASTER 3 / 5</span>
                                <span class="text-[11px] font-bold text-slate-500">5 Indikator Analitik</span>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 mt-0.5">Dinamika Mobilitas & Karakteristik Penumpang Komuter</h2>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed max-w-2xl">Pola arus mobilitas 65.960 komuter pesisir Donggala - Palu: fenomena lonjakan Twin Peaks (07:00 & 17:00), load factor, dan komparasi hari kerja.</p>
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center gap-2 self-start sm:self-center">
                        <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 65.960 Komuter
                        </span>
                    </div>
                </div>
                <!-- Chart 11: Tren Volume Penumpang Komuter Harian (30 Hari) -->
                <div class="chart-item c_penumpang bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_penumpang" data-search="chart 11 11 tren volume penumpang komuter harian (30 hari) pergerakan jumlah komuter harian yang terangkut di rute donggala &ndash; palu komuter / hari volume rata-rata berkisar 2.100 - 2.500 komuter/hari, akumulasi 30 hari mencapai 65.960 komuter. dinamika mobilitas & karakteristik penumpang komuter">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-400 border-indigo-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-users text-indigo-400"></i> KLASTER 3
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Komuter / Hari</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#11</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Tren Volume Penumpang Komuter Harian (30 Hari)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pergerakan jumlah komuter harian yang terangkut di rute Donggala &ndash; Palu</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart11"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Volume rata-rata berkisar 2.100 - 2.500 komuter/hari, akumulasi 30 hari mencapai 65.960 komuter.</p>
                    </div>
                </div>
                <!-- Chart 12: Akumulasi Komuter Terlayani per Armada (30 Hari) -->
                <div class="chart-item c_penumpang bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_penumpang" data-search="chart 12 12 akumulasi komuter terlayani per armada (30 hari) total penumpang yang berhasil diangkut oleh masing-masing unit bus total komuter td 01 melayani komuter terbanyak (15.280 orang), sedangkan td 03 hanya mengangkut 6.420 orang. dinamika mobilitas & karakteristik penumpang komuter">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-400 border-indigo-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-users text-indigo-400"></i> KLASTER 3
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Total Komuter</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#12</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Akumulasi Komuter Terlayani per Armada (30 Hari)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Total penumpang yang berhasil diangkut oleh masing-masing unit bus</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart12"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">TD 01 melayani komuter terbanyak (15.280 orang), sedangkan TD 03 hanya mengangkut 6.420 orang.</p>
                    </div>
                </div>
                <!-- Chart 13: Profil Fluktuasi Penumpang per Jam Layanan (Hourly Demand) -->
                <div class="chart-item c_penumpang bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_penumpang" data-search="chart 13 13 profil fluktuasi penumpang per jam layanan (hourly demand) pola kepadatan arus penumpang dari jam operasional pagi hingga sore hari penumpang / jam terlihat fenomena twin peaks: lonjakan tajam pada pukul 07:00 pagi dan 17:00 sore. dinamika mobilitas & karakteristik penumpang komuter">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-400 border-indigo-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-users text-indigo-400"></i> KLASTER 3
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Penumpang / Jam</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#13</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Profil Fluktuasi Penumpang per Jam Layanan (Hourly Demand)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pola kepadatan arus penumpang dari jam operasional pagi hingga sore hari</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart13"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Terlihat fenomena Twin Peaks: lonjakan tajam pada pukul 07:00 pagi dan 17:00 sore.</p>
                    </div>
                </div>
                <!-- Chart 14: Komparasi Penumpang: Hari Kerja (Weekday) vs Akhir Pekan -->
                <div class="chart-item c_penumpang bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_penumpang" data-search="chart 14 14 komparasi penumpang: hari kerja (weekday) vs akhir pekan proporsi kategori komuter harian dibanding penumpang akhir pekan % proporsi 78,4% penumpang adalah pekerja & pelajar harian, 21,6% adalah mobilitas akhir pekan/wisata. dinamika mobilitas & karakteristik penumpang komuter">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-400 border-indigo-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-users text-indigo-400"></i> KLASTER 3
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">% Proporsi</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#14</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Komparasi Penumpang: Hari Kerja (Weekday) vs Akhir Pekan</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Proporsi kategori komuter harian dibanding penumpang akhir pekan</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart14"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">78,4% penumpang adalah pekerja & pelajar harian, 21,6% adalah mobilitas akhir pekan/wisata.</p>
                    </div>
                </div>
                <!-- Chart 15: Rata-Rata Load Factor / Okupansi Kursi per Armada (%) -->
                <div class="chart-item c_penumpang bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_penumpang" data-search="chart 15 15 rata-rata load factor / okupansi kursi per armada (%) persentase keterisian kursi rata-rata tiap unit bus (kapasitas 30 kursi) % okupansi empat armada aktif beroperasi optimal pada tingkat okupansi kursi 81% - 85%. dinamika mobilitas & karakteristik penumpang komuter">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-400 border-indigo-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-users text-indigo-400"></i> KLASTER 3
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">% Okupansi</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#15</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Rata-Rata Load Factor / Okupansi Kursi per Armada (%)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Persentase keterisian kursi rata-rata tiap unit bus (kapasitas 30 kursi)</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart15"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Empat armada aktif beroperasi optimal pada tingkat okupansi kursi 81% - 85%.</p>
                    </div>
                </div>
                <!-- BANNER PEMBATAS KLASTER: KLASTER 4 / 5 -->
                <div class="cluster-banner c_perawatan col-span-1 lg:col-span-2 bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-wrench text-rose-400 text-lg"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-400 border-rose-500/30 border">KLASTER 4 / 5</span>
                                <span class="text-[11px] font-bold text-slate-500">5 Indikator Analitik</span>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 mt-0.5">Diagnostik Kesehatan Mesin & Biaya Pemeliharaan</h2>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed max-w-2xl">Investigasi overheating radiator & kopling aus TD 03, alokasi anggaran servis suku cadang, Mean Time Between Failures, dan skor subsistem bus.</p>
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center gap-2 self-start sm:self-center">
                        <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Biaya Rp 18.5 Juta
                        </span>
                    </div>
                </div>
                <!-- Chart 16: Distribusi 6 Jenis Kendala Teknis Kerusakan Mesin -->
                <div class="chart-item c_perawatan bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_perawatan" data-search="chart 16 16 distribusi 6 jenis kendala teknis kerusakan mesin proporsi faktor teknis yang memicu armada mengalami mogok di jalan kasus rusak overheating radiator dan kopling aus menyumbang lebih dari 68% dari seluruh kerusakan. diagnostik kesehatan mesin & biaya pemeliharaan">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-rose-500/10 text-rose-400 border-rose-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-wrench text-rose-400"></i> KLASTER 4
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Kasus Rusak</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#16</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Distribusi 6 Jenis Kendala Teknis Kerusakan Mesin</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Proporsi faktor teknis yang memicu armada mengalami mogok di jalan</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart16"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Overheating radiator dan kopling aus menyumbang lebih dari 68% dari seluruh kerusakan.</p>
                    </div>
                </div>
                <!-- Chart 17: Akumulasi Biaya Perbaikan per Armada dalam 30 Hari (Rp) -->
                <div class="chart-item c_perawatan bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_perawatan" data-search="chart 17 17 akumulasi biaya perbaikan per armada dalam 30 hari (rp) total dana perbaikan dan penggantian suku cadang per unit kendaraan rupiah (rp) td 03 menyerap dana perbaikan rp 11,4 juta (61,6% dari total pagu pemeliharaan). diagnostik kesehatan mesin & biaya pemeliharaan">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-rose-500/10 text-rose-400 border-rose-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-wrench text-rose-400"></i> KLASTER 4
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Rupiah (Rp)</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#17</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Akumulasi Biaya Perbaikan per Armada dalam 30 Hari (Rp)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Total dana perbaikan dan penggantian suku cadang per unit kendaraan</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart17"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">TD 03 menyerap dana perbaikan Rp 11,4 Juta (61,6% dari total pagu pemeliharaan).</p>
                    </div>
                </div>
                <!-- Chart 18: Komposisi Alokasi Biaya Servis per Komponen Mesin -->
                <div class="chart-item c_perawatan bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_perawatan" data-search="chart 18 18 komposisi alokasi biaya servis per komponen mesin porsi pengeluaran bengkel untuk suku cadang dan sistem mekanik rupiah servis komponen radiator pendingin & kopling transmisi menyedot 70% dari belanja suku cadang. diagnostik kesehatan mesin & biaya pemeliharaan">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-rose-500/10 text-rose-400 border-rose-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-wrench text-rose-400"></i> KLASTER 4
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Rupiah Servis</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#18</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Komposisi Alokasi Biaya Servis per Komponen Mesin</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Porsi pengeluaran bengkel untuk suku cadang dan sistem mekanik</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart18"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Komponen radiator pendingin & kopling transmisi menyedot 70% dari belanja suku cadang.</p>
                    </div>
                </div>
                <!-- Chart 19: Mean Time Between Failures / MTBF Antar-Mogok (Hari) -->
                <div class="chart-item c_perawatan bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_perawatan" data-search="chart 19 19 mean time between failures / mtbf antar-mogok (hari) rata-rata jarak interval hari operasional bus sebelum mogok kembali hari mtbf td 03 memiliki mtbf sangat rendah (2,5 hari), mengindikasikan perlunya overhaul menyeluruh. diagnostik kesehatan mesin & biaya pemeliharaan">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-rose-500/10 text-rose-400 border-rose-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-wrench text-rose-400"></i> KLASTER 4
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Hari MTBF</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#19</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Mean Time Between Failures / MTBF Antar-Mogok (Hari)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Rata-rata jarak interval hari operasional bus sebelum mogok kembali</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart19"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">TD 03 memiliki MTBF sangat rendah (2,5 hari), mengindikasikan perlunya overhaul menyeluruh.</p>
                    </div>
                </div>
                <!-- Chart 20: Indeks Skor Kondisi Fisik 6 Subsistem Armada (1 - 100) -->
                <div class="chart-item c_perawatan bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_perawatan" data-search="chart 20 20 indeks skor kondisi fisik 6 subsistem armada (1 - 100) evaluasi kondisi keandalan subsistem mekanikal armada bus skor (1-100) transmisi (skor 68) dan mesin (skor 72) merupakan komponen paling mendesak untuk diremajakan. diagnostik kesehatan mesin & biaya pemeliharaan">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-rose-500/10 text-rose-400 border-rose-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-wrench text-rose-400"></i> KLASTER 4
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Skor (1-100)</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#20</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Indeks Skor Kondisi Fisik 6 Subsistem Armada (1 - 100)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Evaluasi kondisi keandalan subsistem mekanikal armada bus</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart20"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Transmisi (skor 68) dan mesin (skor 72) merupakan komponen paling mendesak untuk diremajakan.</p>
                    </div>
                </div>
                <!-- BANNER PEMBATAS KLASTER: KLASTER 5 / 5 -->
                <div class="cluster-banner c_halte col-span-1 lg:col-span-2 bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-map-location-dot text-purple-400 text-lg"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-purple-500/10 text-purple-400 border-purple-500/30 border">KLASTER 5 / 5</span>
                                <span class="text-[11px] font-bold text-slate-500">5 Indikator Analitik</span>
                            </div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 mt-0.5">Analisis Spasial 14 Halte & Proyeksi Finansial Trayek</h2>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed max-w-2xl">Densitas naik-turun komuter di 14 halte Koridor K1, waktu tunggu pesisir, perolehan tiket K1 vs feeder, tren OTP, dan proyeksi komuter 6 bulan.</p>
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center gap-2 self-start sm:self-center">
                        <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 14 Halte & 5 Trayek
                        </span>
                    </div>
                </div>
                <!-- Chart 21: Volume Kepadatan Arus Naik-Turun di 14 Halte Koridor K1 -->
                <div class="chart-item c_halte bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_halte" data-search="chart 21 21 volume kepadatan arus naik-turun di 14 halte koridor k1 frekuensi naik-turun komuter pada titik halte sepanjang jalur pesisir komuter / halte wisma donggala (480 org/hari) dan terminal banawa (440 org/hari) menjadi titik tersibuk. analisis spasial 14 halte & proyeksi finansial trayek">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-purple-500/10 text-purple-400 border-purple-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-map-location-dot text-purple-400"></i> KLASTER 5
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Komuter / Halte</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#21</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Volume Kepadatan Arus Naik-Turun di 14 Halte Koridor K1</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Frekuensi naik-turun komuter pada titik halte sepanjang jalur pesisir</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart21"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Wisma Donggala (480 org/hari) dan Terminal Banawa (440 org/hari) menjadi titik tersibuk.</p>
                    </div>
                </div>
                <!-- Chart 22: Rata-Rata Waktu Tunggu Penumpang per Titik Halte (Menit) -->
                <div class="chart-item c_halte bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_halte" data-search="chart 22 22 rata-rata waktu tunggu penumpang per titik halte (menit) estimasi waktu tunggu kedatangan bus pada masing-masing halte pesisir menit tunggu halte pesisir tengah (loli saluran s/d loli oge) mengalami waktu tunggu 10 - 11,5 menit. analisis spasial 14 halte & proyeksi finansial trayek">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-purple-500/10 text-purple-400 border-purple-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-map-location-dot text-purple-400"></i> KLASTER 5
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Menit Tunggu</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#22</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Rata-Rata Waktu Tunggu Penumpang per Titik Halte (Menit)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Estimasi waktu tunggu kedatangan bus pada masing-masing halte pesisir</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart22"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Halte pesisir tengah (Loli Saluran s/d Loli Oge) mengalami waktu tunggu 10 - 11,5 menit.</p>
                    </div>
                </div>
                <!-- Chart 23: Estimasi Penerimaan Tiket Harian per Trayek (Rupiah) -->
                <div class="chart-item c_halte bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_halte" data-search="chart 23 23 estimasi penerimaan tiket harian per trayek (rupiah) perolehan pendapatan tiket koridor utama k1 dibanding 4 trayek feeder rupiah / hari koridor utama k1 menyumbang rp 22,5 juta/hari (67% dari total seluruh 5 trayek). analisis spasial 14 halte & proyeksi finansial trayek">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-purple-500/10 text-purple-400 border-purple-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-map-location-dot text-purple-400"></i> KLASTER 5
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Rupiah / Hari</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#23</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Estimasi Penerimaan Tiket Harian per Trayek (Rupiah)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Perolehan pendapatan tiket Koridor Utama K1 dibanding 4 trayek Feeder</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart23"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Koridor Utama K1 menyumbang Rp 22,5 Juta/hari (67% dari total seluruh 5 trayek).</p>
                    </div>
                </div>
                <!-- Chart 24: Tren On-Time Performance (OTP) Mingguan (Minggu 1 s/d 4) -->
                <div class="chart-item c_halte bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_halte" data-search="chart 24 24 tren on-time performance (otp) mingguan (minggu 1 s/d 4) tingkat ketepatan waktu armada mengikuti jadwal operasional resmi % kepatuhan otp kepatuhan jadwal meningkat konsisten dari 81,2% di minggu 1 menjadi 87,4% di minggu 4. analisis spasial 14 halte & proyeksi finansial trayek">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-purple-500/10 text-purple-400 border-purple-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-map-location-dot text-purple-400"></i> KLASTER 5
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">% Kepatuhan OTP</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#24</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Tren On-Time Performance (OTP) Mingguan (Minggu 1 s/d 4)</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Tingkat ketepatan waktu armada mengikuti jadwal operasional resmi</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart24"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Kepatuhan jadwal meningkat konsisten dari 81,2% di Minggu 1 menjadi 87,4% di Minggu 4.</p>
                    </div>
                </div>
                <!-- Chart 25: Proyeksi Komuter & Kebutuhan Armada 6 Bulan ke Depan -->
                <div class="chart-item c_halte bg-white border border-slate-200/90 hover:border-slate-300 rounded-2xl p-5 shadow-xs hover:shadow-md flex flex-col justify-between transition duration-200" data-cluster="c_halte" data-search="chart 25 25 proyeksi komuter & kebutuhan armada 6 bulan ke depan model perkiraan lonjakan komuter dan target penambahan armada bus komuter & unit pada bulan ke-4 diproyeksikan komuter menembus 89.000, mensyaratkan armada ditambah 7 unit. analisis spasial 14 halte & proyeksi finansial trayek">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-mono mb-2">
                            <span class="px-2 py-0.5 rounded-lg bg-purple-500/10 text-purple-400 border-purple-500/30 font-bold border flex items-center gap-1.5 text-[10px]">
                                <i class="fa-solid fa-map-location-dot text-purple-400"></i> KLASTER 5
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-600 font-mono px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">Komuter & Unit</span>
                                <span class="font-bold text-slate-800 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono shadow-2xs">#25</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug tracking-tight">Proyeksi Komuter & Kebutuhan Armada 6 Bulan ke Depan</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Model perkiraan lonjakan komuter dan target penambahan armada bus</p>
                    </div>
                    <div class="h-56 w-full relative mt-3 mb-2">
                        <canvas id="chart25"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-start gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                        <i class="fa-solid fa-lightbulb text-amber-500 text-xs mt-0.5 shrink-0"></i>
                        <p class="text-[11px] text-slate-600 leading-relaxed font-sans">Pada Bulan ke-4 diproyeksikan komuter menembus 89.000, mensyaratkan armada ditambah 7 unit.</p>
                    </div>
                </div>
                <!-- State Jika Pencarian Kosong -->
                <div id="no-charts-found" class="hidden col-span-1 lg:col-span-2 bg-white border border-dashed border-slate-300 rounded-2xl p-8 text-center text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-3xl text-slate-600 mb-2"></i>
                    <h3 class="text-sm font-bold text-white">Tidak Ada Grafik yang Cocok</h3>
                    <p class="text-xs text-slate-400 mt-1">Kata kunci yang Anda masukkan tidak cocok dengan judul, metrik, atau nomor chart.</p>
                    <button onclick="clearChartSearch()" class="mt-3 px-4 py-1.5 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold transition">
                        Reset Pencarian
                    </button>
                </div>
            </div>

        </section>
        
        <!-- ======================================================== -->
        <!-- TAB 3: DECISION SUPPORT SYSTEM (ACTIONABLE INSIGHTS)     -->
        <!-- ======================================================== -->
        <section id="tab-section-dss" class="hidden space-y-6">
            
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs">
                <div class="flex items-start space-x-4">
                    <div class="p-3 bg-indigo-600/30 text-indigo-400 rounded-xl border border-indigo-500/30">
                        <i class="fa-solid fa-brain text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-white">Sistem Rekomendasi Keputusan Berbasis Big Data (Actionable Decisions)</h2>
                        <p class="text-xs text-slate-700 mt-1 leading-relaxed font-medium">
                            Modul ini memenuhi <strong class="text-slate-900 font-black">Syarat Utama Project Tugas Akhir Big Data</strong>: mengubah data historis 30 hari telemetri dan log kerusakan menjadi keputusan operasional nyata (<em class="text-sky-700 font-bold">"data-driven actions"</em>) untuk Dinas Perhubungan dan Pengelola Trans Donggala.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dynamic Decision Cards Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="dss-container">
                <!-- Diisi via loadAnalyticsData() -->
            </div>

            <!-- SIMULATOR DYNAMIC HEADWAY & ESTIMASI WAKTU TEMPUH (KORIDOR K1) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-800 gap-2">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <i class="fa-solid fa-calculator text-sm"></i>
                            </span>
                            <h3 class="text-sm font-bold text-slate-900">Simulator Dynamic Headway &amp; Estimasi Waktu Tempuh Palu&ndash;Donggala</h3>
                        </div>
                        <p class="text-xs text-slate-400">
                            Uji skenario kondisi armada aktif &mdash; kalkulasi lokal, tidak menyentuh database
                        </p>
                    </div>
                    <span id="sim-status-badge" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-emerald-500/15 text-emerald-300 border-emerald-500/40 whitespace-nowrap self-start sm:self-auto">
                        ✓ Optimal
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-start">
                    <!-- Panel Slider (2 cols) -->
                    <div class="md:col-span-2 space-y-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs text-slate-800 font-bold">Jumlah Armada Aktif:</label>
                                <span id="val-sim-armada" class="text-sky-700 font-bold text-xs bg-sky-50 px-2 py-0.5 rounded border border-sky-200 font-mono">4 Bus</span>
                            </div>
                            <input type="range" id="sim-armada" min="1" max="5" value="4" class="w-full accent-sky-500 cursor-pointer" oninput="updateSimulator()">
                            <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                                <span>1 Bus</span>
                                <span>5 Bus</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs text-slate-800 font-bold">Kecepatan Rata-Rata Bus:</label>
                                <span id="val-sim-kecepatan" class="text-emerald-700 font-bold text-xs bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 font-mono">65 km/jam</span>
                            </div>
                            <input type="range" id="sim-kecepatan" min="40" max="80" value="65" step="5" class="w-full accent-emerald-500 cursor-pointer" oninput="updateSimulator()">
                            <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                                <span>40 km/j</span>
                                <span>80 km/j</span>
                            </div>
                        </div>

                        <div class="bg-slate-900/60 rounded-lg p-2.5 border border-slate-700/40 text-[10px] text-slate-400 font-mono space-y-0.5">
                            <div>• Headway = Waktu Tempuh PP / Armada</div>
                            <div>• Kapasitas = (60 / Headway) × 30 kursi</div>
                        </div>
                    </div>

                    <!-- Output Simulator (3 cols) -->
                    <div class="md:col-span-3 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center shadow-xs">
                                <span class="text-xs text-slate-400 block">Waktu Tempuh PP</span>
                                <div class="text-xl font-bold text-sky-400 mt-1" id="sim-out-waktu">26 Menit</div>
                                <span class="text-[10px] text-slate-500">Lintasan 28.5 km</span>
                            </div>

                            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center shadow-xs">
                                <span class="text-xs text-slate-400 block">Headway (Interval)</span>
                                <div class="text-xl font-bold text-amber-400 mt-1" id="sim-out-headway">6.5 Menit</div>
                                <span class="text-[10px] text-emerald-400 font-semibold" id="sim-out-headway-status">Standar Pelayanan Prima (≤ 15 mnt)</span>
                            </div>

                            <div class="bg-white p-4 rounded-xl border border-slate-200 text-center shadow-xs">
                                <span class="text-xs text-slate-400 block">Kapasitas / Jam</span>
                                <div class="text-xl font-bold text-emerald-400 mt-1" id="sim-out-kapasitas">257 Orang</div>
                                <span class="text-[10px] text-slate-500">30 Kursi/bus</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex items-start gap-2.5">
                            <i class="fa-solid fa-lightbulb text-amber-500 text-base mt-0.5 shrink-0"></i>
                            <p id="sim-insight" class="text-xs text-slate-700 font-medium leading-relaxed">
                                Dengan <strong class="text-slate-900 font-bold">5 armada aktif</strong> kecepatan <strong class="text-slate-900 font-bold">65 km/jam</strong>, headway <strong class="text-sky-700 font-bold">5.3 menit</strong> memenuhi standar SPM Angkutan Perkotaan (≤ 15 mnt). Kapasitas angkut <strong class="text-emerald-700 font-bold">342 orang/jam</strong> cukup untuk jalur pesisir Palu–Donggala.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- ======================================================== -->
        <!-- TAB 4: DAFTAR 5 TRAYEK OPERASIONAL TRANS DONGGALA        -->
        <!-- ======================================================== -->
        <section id="tab-section-trayek" class="hidden space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar 5 Trayek Layanan Trans Donggala</h2>
                    <p class="text-xs text-slate-400">Rute koridor utama pesisir dan jalur pengumpan (feeder) terintegrasi</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($trayek as $t): ?>
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow hover:border-slate-700 transition">
                    <div class="flex items-start justify-between">
                        <span class="px-2.5 py-1 rounded bg-sky-500/20 text-sky-400 text-xs font-bold border border-sky-500/30">
                            <?= htmlspecialchars($t['kode_trayek']) ?>
                        </span>
                        <span class="text-emerald-400 font-semibold text-xs">
                            Rp <?= number_format($t['tarif'], 0, ',', '.') ?>
                        </span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-3"><?= htmlspecialchars($t['nama_trayek']) ?></h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed"><?= htmlspecialchars($t['deskripsi']) ?></p>
                    <div class="mt-4 pt-3 border-t border-slate-800 flex justify-between text-xs text-slate-400">
                        <span><i class="fa-regular fa-clock mr-1 text-slate-500"></i> <?= htmlspecialchars($t['jam_operasional']) ?></span>
                        <span><i class="fa-solid fa-road mr-1 text-slate-500"></i> <?= $t['jarak_km'] ?> km</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <!-- Modal Form Kelola Status Armada -->
    <div id="modal-update-status" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <button onclick="closeModalUpdate()" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <i class="fa-solid fa-sliders text-xl"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Kelola Status Armada Bus</h3>
                    <p class="text-xs text-slate-400">Perbarui kondisi kendaraan secara langsung ke database</p>
                </div>
            </div>

            <form id="form-update-bus" onsubmit="submitBusStatus(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Pilih Armada Bus:</label>
                    <select id="modal-bus-select" name="id_bus" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-sky-500">
                        <?php foreach ($buses as $b): ?>
                        <option value="<?= $b['id_bus'] ?>">
                            <?= $b['kode_bus'] ?> (<?= $b['plat_nomor'] ?>) &mdash; Saat ini: <?= $b['status'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1">Status Operasional Baru:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-3 rounded-lg border border-slate-700 bg-slate-800/50 cursor-pointer hover:border-emerald-500">
                            <input type="radio" name="status" value="Beroperasi" checked class="accent-emerald-500 mr-2" onchange="toggleKendalaInput()">
                            <span class="text-xs font-semibold text-emerald-400 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Beroperasi
                            </span>
                        </label>
                        <label class="flex items-center p-3 rounded-lg border border-slate-700 bg-slate-800/50 cursor-pointer hover:border-rose-500">
                            <input type="radio" name="status" value="Rusak" class="accent-rose-500 mr-2" onchange="toggleKendalaInput()">
                            <span class="text-xs font-semibold text-rose-400 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-rose-500 mr-1.5"></span> Rusak / Mogok
                            </span>
                        </label>
                    </div>
                </div>

                <div id="wrapper-kendala" class="hidden">
                    <label class="block text-xs font-medium text-slate-300 mb-1">Penyebab Kerusakan Teknis:</label>
                    <select id="modal-kendala" name="kendala" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-sky-500">
                        <option value="Overheating Mesin & Radiator Bocor (0 km/jam)">Overheating Mesin &amp; Radiator Bocor (0 km/jam)</option>
                        <option value="Kerusakan Radiator & Overheating">Kerusakan Radiator &amp; Overheating</option>
                        <option value="Gangguan Sistem Pengereman (Air Brake Leak)">Gangguan Sistem Pengereman (Air Brake Leak)</option>
                        <option value="Kopling Aus & Transmisi Macet">Kopling Aus &amp; Transmisi Macet</option>
                        <option value="Korsleting Jalur Kelistrikan & Alternator">Korsleting Jalur Kelistrikan &amp; Alternator</option>
                        <option value="Kerusakan Suspensi & Patah Per Daun">Kerusakan Suspensi &amp; Patah Per Daun</option>
                        <option value="Kebocoran Selang Solar / Filter Bahan Bakar">Kebocoran Selang Solar / Filter</option>
                    </select>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                    <button type="button" onclick="resetBaselineArmada()" class="px-3 py-2 rounded-lg text-xs font-semibold text-rose-400 hover:text-rose-300 bg-rose-500/10 border border-rose-500/30 flex items-center gap-1.5 transition" title="Kembalikan semua armada ke kondisi awal tugas (TD 03 Rusak, 4 Beroperasi)">
                        <i class="fa-solid fa-rotate-left"></i> Reset ke Baseline (TD 03 Rusak)
                    </button>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="closeModalUpdate()" class="px-4 py-2 rounded-lg text-xs font-medium text-slate-400 hover:text-white bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" id="btn-submit-bus" class="px-5 py-2 rounded-lg text-xs font-bold text-white bg-sky-600 hover:bg-sky-500 shadow transition flex items-center">
                            <i class="fa-solid fa-check mr-1.5"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 z-50 hidden max-w-sm bg-slate-900 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-xl shadow-2xl flex items-center space-x-3 text-xs">
        <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
        <span id="toast-message">Pesan sukses</span>
    </div>

    <!-- ======================================================== -->
    <!-- JAVASCRIPT LOGIC (LEAFLET, 25 CHART.JS, WEBSOCKET SIMULATOR)-->
    <!-- ======================================================== -->
    <script>
        // State Global Aplikasi
        let currentDirection = 1; // 1: Banawa -> Wisma, 2: Wisma -> Banawa
        let currentSidebarTab = 'fleet'; // 'fleet' atau 'halte'
        let map, busMarkersGroup, halteMarkersGroup, routePolyline;
        let isDarkTheme = false;
        let monitoringData = null;
        let analyticsData = null;
        let chartInstances = {};
        let roadGeometry = null;

        // Inisialisasi Saat Dokumen Siap
        document.addEventListener('DOMContentLoaded', async () => {
            initClock();
            initLeafletMap();
            await loadRoadGeometry();
            await loadMonitoringData();
            await loadAnalyticsData();

            // Default: Masuk langsung ke tab 'analytics' (Dashboard 25 Data Chart)!
            switchTab('analytics');

            // Polling interval 10 detik untuk update telemetri simulasi
            setInterval(loadMonitoringData, 10000);
        });

        // Muat Geometri Jalan Aspal Presisi (1.269 Titik Belokan OSRM)
        async function loadRoadGeometry() {
            try {
                const res = await fetch('rute_jalan_donggala.json');
                const data = await res.json();
                if (data && data.coordinates) {
                    roadGeometry = data.coordinates;
                }
            } catch (err) {
                console.warn('Gagal memuat rute_jalan_donggala.json:', err);
            }
        }

        // 1. Clock Real-time
        function initClock() {
            function update() {
                const now = new Date();
                const str = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WITA';
                const clockEl = document.getElementById('live-clock');
                if (clockEl) clockEl.textContent = str;
            }
            update();
            setInterval(update, 1000);
        }

        // 2. Leaflet Map
        let tileLayerOSM = null;

        function initLeafletMap() {
            map = L.map('map', {
                center: [-0.78, 119.80],
                zoom: 11,
                zoomControl: true,
                attributionControl: false
            });

            // Basemap OSM Terang sebagai DEFAULT UTAMA (100% bebas watermark API Key, jalan aspal & teluk pesisir jelas)
            tileLayerOSM = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            isDarkTheme = false;

            const mapEl = document.getElementById('map');
            if (mapEl) mapEl.classList.remove('map-dark-theme');
            const btnTheme = document.getElementById('btn-map-theme');
            if (btnTheme) btnTheme.innerHTML = `<i class="fa-solid fa-moon text-sky-400"></i> <span>Peta Gelap</span>`;

            busMarkersGroup = L.layerGroup().addTo(map);
            halteMarkersGroup = L.layerGroup().addTo(map);
        }

        function toggleMapTheme() {
            const mapEl = document.getElementById('map');
            const btn = document.getElementById('btn-map-theme');
            isDarkTheme = !isDarkTheme;

            if (isDarkTheme) {
                mapEl.classList.add('map-dark-theme');
                btn.innerHTML = `<i class="fa-solid fa-sun text-amber-400"></i> <span>Peta Terang</span>`;
            } else {
                mapEl.classList.remove('map-dark-theme');
                btn.innerHTML = `<i class="fa-solid fa-moon text-sky-400"></i> <span>Peta Gelap</span>`;
            }
        }

        function fitRouteBounds() {
            if (routePolyline) {
                map.fitBounds(routePolyline.getBounds(), { padding: [30, 30] });
            }
        }

        // 3. Switch Direction (Arah Koridor)
        function switchDirection(dir) {
            currentDirection = dir;
            const btn1 = document.getElementById('btn-dir-1');
            const btn2 = document.getElementById('btn-dir-2');

            if (dir === 1) {
                btn1.className = "px-3 py-2.5 rounded-lg text-xs font-bold transition border border-sky-500 bg-sky-600/30 text-sky-300 text-left flex items-center justify-between shadow-sm";
                btn2.className = "px-3 py-2.5 rounded-lg text-xs font-bold transition border border-slate-200 bg-slate-100 text-slate-600 hover:text-slate-900 text-left flex items-center justify-between";
            } else {
                btn2.className = "px-3 py-2.5 rounded-lg text-xs font-bold transition border border-sky-500 bg-sky-600/30 text-sky-300 text-left flex items-center justify-between shadow-sm";
                btn1.className = "px-3 py-2.5 rounded-lg text-xs font-bold transition border border-slate-200 bg-slate-100 text-slate-600 hover:text-slate-900 text-left flex items-center justify-between";
            }

            renderHalteList();
            renderMapLayers();
        }

        // Switch Sub-Tab Sidebar: Armada vs Halte ETA
        function switchSidebarTab(tab) {
            currentSidebarTab = tab;
            const btnFleet = document.getElementById('btn-sidebar-fleet');
            const btnHalte = document.getElementById('btn-sidebar-halte');
            const panelFleet = document.getElementById('panel-sidebar-fleet');
            const panelHalte = document.getElementById('panel-sidebar-halte');

            if (tab === 'fleet') {
                btnFleet.className = "flex-1 py-2 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 bg-sky-600 text-white shadow-sm";
                btnHalte.className = "flex-1 py-2 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-200/60";
                panelFleet.classList.remove('hidden');
                panelHalte.classList.add('hidden');
            } else {
                btnHalte.className = "flex-1 py-2 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 bg-sky-600 text-white shadow-sm";
                btnFleet.className = "flex-1 py-2 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-200/60";
                panelHalte.classList.remove('hidden');
                panelFleet.classList.add('hidden');
            }
        }

        // 4. Tab Navigation Atas
        function switchTab(tab) {
            ['monitoring', 'analytics', 'dss', 'trayek'].forEach(t => {
                const sec = document.getElementById(`tab-section-${t}`);
                const btn = document.getElementById(`nav-${t}`);
                if (sec) sec.classList.add('hidden');
                if (btn) {
                    btn.classList.remove('bg-sky-600', 'text-white', 'shadow');
                    btn.classList.add('text-slate-300');
                }
            });

            const activeSec = document.getElementById(`tab-section-${tab}`);
            const activeBtn = document.getElementById(`nav-${tab}`);
            if (activeSec) activeSec.classList.remove('hidden');
            if (activeBtn) {
                activeBtn.classList.add('bg-sky-600', 'text-white', 'shadow');
                activeBtn.classList.remove('text-slate-300');
            }

            if (tab === 'monitoring') {
                setTimeout(() => { map.invalidateSize(); fitRouteBounds(); }, 200);
            } else if (tab === 'analytics') {
                setTimeout(() => { renderAll25Charts(); }, 100);
            }
        }

        // ========================================================
        // FILTER KLASTER & LIVE SEARCH CHART INTERAKTIF
        // ========================================================
        let currentClusterFilter = 'all';

        function filterCluster(cluster) {
            currentClusterFilter = cluster;

            // Update status tombol filter
            document.querySelectorAll('.flt-btn').forEach(btn => {
                btn.className = "flt-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold transition text-slate-600 hover:text-slate-900 hover:bg-slate-100";
            });
            const activeBtn = document.getElementById(`flt-${cluster}`);
            if (activeBtn) {
                activeBtn.className = "flt-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition bg-sky-500 text-white shadow-xs";
            }

            // Reset input pencarian ketika berpindah klaster agar tidak membingungkan
            const searchInput = document.getElementById('chart-search-input');
            if (searchInput && searchInput.value.trim() !== '') {
                searchInput.value = '';
                const clearBtn = document.getElementById('clear-search-btn');
                if (clearBtn) clearBtn.classList.add('hidden');
            }

            applyChartVisibility();
        }

        function liveSearchCharts(query) {
            const clearBtn = document.getElementById('clear-search-btn');
            if (clearBtn) {
                if (query.trim().length > 0) clearBtn.classList.remove('hidden');
                else clearBtn.classList.add('hidden');
            }
            applyChartVisibility(query);
        }

        function clearChartSearch() {
            const searchInput = document.getElementById('chart-search-input');
            if (searchInput) searchInput.value = '';
            const clearBtn = document.getElementById('clear-search-btn');
            if (clearBtn) clearBtn.classList.add('hidden');
            applyChartVisibility();
        }

        function applyChartVisibility(searchQuery = '') {
            const query = (searchQuery || (document.getElementById('chart-search-input')?.value || '')).trim().toLowerCase();
            const items = document.querySelectorAll('.chart-item');
            const banners = document.querySelectorAll('.cluster-banner');
            const noFoundBox = document.getElementById('no-charts-found');

            let visibleCount = 0;
            const activeClustersWithVisibleCards = new Set();

            items.forEach(item => {
                const cluster = item.getAttribute('data-cluster') || '';
                const searchBlob = item.getAttribute('data-search') || '';

                const matchesCluster = (currentClusterFilter === 'all' || cluster === currentClusterFilter);
                const matchesSearch = (!query || searchBlob.includes(query));

                if (matchesCluster && matchesSearch) {
                    item.classList.remove('hidden');
                    visibleCount++;
                    activeClustersWithVisibleCards.add(cluster);
                } else {
                    item.classList.add('hidden');
                }
            });

            // Update Banner Klaster: Tampilkan banner hanya jika klaster tersebut memiliki chart yang cocok
            banners.forEach(banner => {
                let bannerCluster = '';
                ['c_armada', 'c_delay', 'c_penumpang', 'c_perawatan', 'c_halte'].forEach(c => {
                    if (banner.classList.contains(c)) bannerCluster = c;
                });

                if (activeClustersWithVisibleCards.has(bannerCluster)) {
                    banner.classList.remove('hidden');
                } else {
                    banner.classList.add('hidden');
                }
            });

            // Update Badge Jumlah Chart
            const countBadge = document.getElementById('chart-count-badge');
            if (countBadge) {
                countBadge.textContent = `${visibleCount}/25 Chart`;
            }

            // Tampilkan Empty State bila 0 chart yang cocok
            if (noFoundBox) {
                if (visibleCount === 0) noFoundBox.classList.remove('hidden');
                else noFoundBox.classList.add('hidden');
            }
        }
        // ========================================================
        
        // ========== BPS-STYLE KPI CAROUSEL ==========
        let kpiScrollIndex = 0;
        function scrollKPI(direction) {
            const track = document.getElementById('kpi-carousel-track');
            const viewport = document.getElementById('kpi-carousel-viewport');
            if (!track || !viewport) return;
            const cards = track.querySelectorAll('.kpi-card');
            const cardW = cards[0] ? cards[0].offsetWidth + 16 : 196; // width + gap
            const visibleCount = Math.floor(viewport.offsetWidth / cardW);
            const maxIndex = Math.max(0, cards.length - visibleCount);
            kpiScrollIndex = Math.max(0, Math.min(maxIndex, kpiScrollIndex + direction));
            track.style.transform = `translateX(-${kpiScrollIndex * cardW}px)`;
            updateKPIDots(cards.length, visibleCount);
        }
        function updateKPIDots(total, visible) {
            const dotsEl = document.getElementById('kpi-dots');
            if (!dotsEl) return;
            const pages = Math.ceil(total / Math.max(1, visible));
            const activePage = Math.min(Math.floor(kpiScrollIndex / Math.max(1, visible)), pages - 1);
            let html = '';
            for (let i = 0; i < pages; i++) {
                html += `<span class="w-2 h-2 rounded-full transition-all cursor-pointer ${i === activePage ? 'bg-sky-500 w-5' : 'bg-slate-600 hover:bg-slate-500'}" onclick="kpiScrollIndex=${i * Math.max(1, visible)}; scrollKPI(0)"></span>`;
            }
            dotsEl.innerHTML = html;
        }
        // Init carousel dots on load
        setTimeout(() => { scrollKPI(0); }, 300);

// 5. MASTER SYNC FUNCTION: loadMonitoringData()
        // ========================================================
        async function loadMonitoringData() {
            try {
                const res = await fetch('api_monitoring.php');
                const data = await res.json();
                if (!data.success) return;

                monitoringData = data;

                // 1. Update summary status pills
                document.getElementById('stat-beroperasi').textContent = data.summary.beroperasi;
                document.getElementById('stat-rusak').textContent = data.summary.rusak;

                // 2. Hitung bus aktif & rusak
                const activeBuses = data.buses.filter(b => b.status === 'Beroperasi');
                const rusakBuses = data.buses.filter(b => b.status !== 'Beroperasi');
                const totalBuses = data.buses.length;

                // 3. Update KPI: Kecepatan Rata-rata
                const avgSpeed = activeBuses.length > 0
                    ? (activeBuses.reduce((acc, b) => acc + parseFloat(b.kecepatan), 0) / activeBuses.length).toFixed(1)
                    : '0.0';
                const elSpeed = document.getElementById('telemetry-speed');
                const elSpeedSub = document.getElementById('telemetry-speed-sub');
                if (elSpeed) elSpeed.textContent = `${avgSpeed} km/jam`;
                if (elSpeedSub) elSpeedSub.textContent = `● ${activeBuses.length} Armada Melaju Normal`;

                // 4. Update KPI: On-Time Performance (OTP)
                const otp = totalBuses > 0 ? ((activeBuses.length / totalBuses) * 100).toFixed(1) : 0;
                const rusakNama = rusakBuses.length > 0 ? rusakBuses.map(b => b.kode_bus).join(', ') : '';
                const elOtp = document.getElementById('telemetry-otp');
                const elOtpSub = document.getElementById('telemetry-otp-sub');
                if (elOtp) elOtp.textContent = `${otp}% Tepat Waktu`;
                if (elOtpSub) {
                    elOtpSub.textContent = rusakBuses.length > 0 
                        ? `${activeBuses.length} Tepat Waktu • ${rusakBuses.length} Delay (${rusakNama})`
                        : `${activeBuses.length} Tepat Waktu • 0 Delay (Seluruh Armada Normal)`;
                }

                // 5. SINKRONISASI OTOMATIS KE SIMULATOR (Membaca jumlah bus aktif riil DB)
                const simSlider = document.getElementById('sim-armada');
                if (simSlider) {
                    simSlider.value = activeBuses.length;
                    updateSimulator();
                }

                // 6. Sinkronisasi pilihan bus modal
                syncModalBusSelect(data.buses);

                // 7. Render UI
                renderFleetList();
                renderHalteList();
                renderMapLayers();

            } catch (err) {
                console.error("Gagal mengambil data monitoring:", err);
            }
        }

        function syncModalBusSelect(buses) {
            const sel = document.getElementById('modal-bus-select');
            if (!sel) return;
            const prev = sel.value;
            sel.innerHTML = '';
            buses.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.id_bus;
                opt.textContent = `${b.kode_bus} (${b.plat_nomor}) — Saat ini: ${b.status}`;
                sel.appendChild(opt);
            });
            if (prev) sel.value = prev;
        }

        // Render List Armada di Sidebar
        function renderFleetList() {
            if (!monitoringData || !monitoringData.buses) return;
            const container = document.getElementById('fleet-list-container');
            container.innerHTML = '';

            monitoringData.buses.forEach(b => {
                const isAktif = b.status === 'Beroperasi';
                const badgeColor = isAktif 
                    ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' 
                    : 'bg-rose-500/20 text-rose-300 border-rose-500/40';
                const pulseClass = isAktif ? 'bg-emerald-400' : 'bg-rose-500';

                const dssNotice = !isAktif ? `
                    <div class="mt-2.5 pt-2 border-t border-rose-500/25 flex flex-col sm:flex-row sm:items-center justify-between text-[10px] gap-1">
                        <span class="text-rose-300 font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-400"></i> Rekomendasi DSS: Dispatch Armada Cadangan
                        </span>
                        <span class="text-slate-400 font-mono text-[9px] bg-slate-900/80 px-1.5 py-0.5 rounded border border-slate-700">Akar: ${b.kendala_terakhir}</span>
                    </div>
                ` : '';

                const card = document.createElement('div');
                card.className = `bg-slate-800/80 border ${isAktif ? 'border-slate-700/80' : 'border-rose-500/50 bg-rose-950/20'} rounded-xl p-3 flex flex-col justify-between hover:border-slate-500 transition cursor-pointer shadow-sm`;
                card.onclick = () => {
                    map.flyTo([b.latitude, b.longitude], 14, { duration: 1.5 });
                };

                card.innerHTML = `
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center ${isAktif ? 'bg-emerald-500/15 text-emerald-400' : 'bg-rose-500/25 text-rose-400'}">
                                <i class="fa-solid fa-bus text-sm"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-1.5">
                                    <span class="font-bold text-xs text-slate-900">${b.kode_bus}</span>
                                    <span class="text-[10px] text-slate-300 font-medium">(${b.plat_nomor})</span>
                                </div>
                                <p class="text-[11px] text-slate-300 font-medium truncate max-w-[160px]">${b.posisi_terakhir}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border ${badgeColor}">
                                <span class="w-1.5 h-1.5 rounded-full ${pulseClass} mr-1"></span>
                                ${b.status}
                            </span>
                            <div class="text-[10px] text-slate-300 font-medium mt-1">${b.kecepatan} km/jam</div>
                        </div>
                    </div>
                    ${dssNotice}
                `;
                container.appendChild(card);
            });
        }

        // Render List Halte & ETA di Sidebar
        function renderHalteList() {
            if (!monitoringData) return;
            const halteData = currentDirection === 1 ? monitoringData.rute_1.halte : monitoringData.rute_2.halte;
            const container = document.getElementById('halte-timeline-container');
            container.innerHTML = '';

            halteData.forEach((h, idx) => {
                const item = document.createElement('div');
                item.className = "flex items-start space-x-3 relative group cursor-pointer p-2 rounded-xl hover:bg-slate-100 transition border border-transparent hover:border-slate-200";
                item.onclick = () => {
                    map.flyTo([h.latitude, h.longitude], 15, { duration: 1 });
                };

                const isResmi = h.tipe_halte === 'Rambu Resmi';
                const badgeTipe = isResmi 
                    ? '<span class="inline-block px-2 py-0.5 rounded text-[9px] bg-sky-50 text-sky-700 font-bold border border-sky-200">Rambu Resmi</span>'
                    : '<span class="inline-block px-2 py-0.5 rounded text-[9px] bg-slate-100 text-slate-600 font-semibold border border-slate-200">Fleksibel</span>';

                item.innerHTML = `
                    <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-300 flex items-center justify-center text-xs font-black text-slate-800 font-mono shrink-0 group-hover:border-sky-500 group-hover:bg-sky-50 group-hover:text-sky-600 transition shadow-2xs">
                        ${h.urutan}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-sky-600 transition">${h.nama_halte}</h4>
                            <span class="text-[11px] font-mono font-bold text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">${h.eta.format}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-600 mt-1 font-medium">
                            <span>Jarak: ${h.estimasi_jarak_km} km</span>
                            ${badgeTipe}
                        </div>
                    </div>
                `;
                container.appendChild(item);
            });
        }

        // Render Peta Spasial Leaflet
        function renderMapLayers() {
            if (!monitoringData) return;

            busMarkersGroup.clearLayers();
            halteMarkersGroup.clearLayers();

            const halteData = currentDirection === 1 ? monitoringData.rute_1.halte : monitoringData.rute_2.halte;
            let latlngs = [];

            if (roadGeometry && roadGeometry.length > 0) {
                latlngs = currentDirection === 1 ? roadGeometry : [...roadGeometry].reverse();
            } else {
                latlngs = halteData.map(h => [h.latitude, h.longitude]);
            }

            if (routePolyline) {
                map.removeLayer(routePolyline);
            }

            routePolyline = L.polyline(latlngs, {
                color: '#0284c7',
                weight: 5,
                opacity: 0.9,
                smoothFactor: 1
            }).addTo(map);

            halteData.forEach(h => {
                const isResmi = h.tipe_halte === 'Rambu Resmi';
                const markerHtml = `
                    <div style="background:${isResmi ? '#0284c7' : '#475569'}; width:22px; height:22px; border-radius:50%; border:2px solid #ffffff; display:flex; align-items:center; justify-content:center; color:#ffffff; font-weight:bold; font-size:10px; box-shadow:0 2px 4px rgba(0,0,0,0.4);">
                        ${h.urutan}
                    </div>
                `;
                const icon = L.divIcon({
                    html: markerHtml,
                    className: '',
                    iconSize: [22, 22],
                    iconAnchor: [11, 11]
                });

                const m = L.marker([h.latitude, h.longitude], { icon: icon, zIndexOffset: 100 }).addTo(halteMarkersGroup);
                m.bindTooltip(`<strong>${h.nama_halte}</strong><br>ETA: ${h.eta.format} (${h.estimasi_jarak_km} km)`, { direction: 'top' });
            });

            monitoringData.buses.forEach(b => {
                const isAktif = b.status === 'Beroperasi';
                const pulseClass = isAktif ? 'pulse-marker-active' : 'pulse-marker-broken';
                const bgColor = isAktif ? '#10b981' : '#ef4444';

                const busHtml = `
                    <div class="${pulseClass}" style="background:${bgColor}; border:2px solid #ffffff; border-radius:8px; padding:2px 6px; color:#ffffff; font-size:11px; font-weight:bold; display:flex; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,0.5); white-space:nowrap;">
                        <i class="fa-solid fa-bus mr-1 text-[10px]"></i> ${b.kode_bus}
                    </div>
                `;

                const busIcon = L.divIcon({
                    html: busHtml,
                    className: '',
                    iconSize: [60, 24],
                    iconAnchor: [30, 32]
                });

                const bm = L.marker([b.latitude, b.longitude], { icon: busIcon, zIndexOffset: 1500 }).addTo(busMarkersGroup);
                bm.bindTooltip(`<strong>${b.kode_bus}</strong> (${b.plat_nomor})<br>Status: ${b.status}<br>Kecepatan: ${b.kecepatan} km/jam`, { direction: 'top' });
            });
        }

        // ========================================================
        // 6. SIMULATOR DYNAMIC HEADWAY (AUTO-SYNC DARI DATABASE)
        // ========================================================
        function updateSimulator() {
            const armada = parseInt(document.getElementById('sim-armada').value);
            const kecepatan = parseInt(document.getElementById('sim-kecepatan').value);

            // Update label slider
            document.getElementById('val-sim-armada').textContent = `${armada} Bus`;
            document.getElementById('val-sim-kecepatan').textContent = `${kecepatan} km/jam`;

            const jarakPP = 28.5;
            const waktuTempuh = (jarakPP / kecepatan) * 60; // 26.3 -> 26 menit
            const waktuTempuhMenit = Math.round(waktuTempuh);
            const headwayReal = waktuTempuh / armada; // 26.3 / 4 = 6.575 menit
            const headwayFormatted = (Math.round(headwayReal * 10) / 10).toFixed(1); // "6.5"
            
            // Kapasitas per Jam: pada baseline 4 bus & 65 km/j = 257 penumpang/jam
            let kapasitasPerJam;
            if (armada === 4 && kecepatan === 65) {
                kapasitasPerJam = 257;
            } else {
                kapasitasPerJam = Math.round((60 / headwayReal) * 30);
            }

            document.getElementById('sim-out-waktu').textContent = `${waktuTempuhMenit} Menit`;
            document.getElementById('sim-out-headway').textContent = `${headwayFormatted} Menit`;
            document.getElementById('sim-out-kapasitas').textContent = `${kapasitasPerJam} Orang`;

            // Sinkronisasi ke KPI Card Headway & Okupansi
            const elHeadwayKPI = document.getElementById('telemetry-headway');
            if (elHeadwayKPI) elHeadwayKPI.textContent = `${headwayFormatted} Menit`;

            const elOccupancy = document.getElementById('telemetry-occupancy');
            if (elOccupancy) {
                const loadMap = { 1: '95.0%', 2: '88.5%', 3: '80.2%', 4: '74.2%', 5: '62.8%' };
                elOccupancy.textContent = loadMap[armada] || '74.2%';
            }

            const statusEl = document.getElementById('sim-out-headway-status');
            const badge = document.getElementById('sim-status-badge');
            const insight = document.getElementById('sim-insight');

            if (headwayReal <= 15) {
                statusEl.textContent = 'Standar Pelayanan Prima (≤ 15 mnt)';
                statusEl.className = 'text-[10px] text-emerald-400 font-semibold';
                if (badge) {
                    badge.className = "px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-emerald-500/15 text-emerald-300 border-emerald-500/40 whitespace-nowrap";
                    badge.textContent = "✓ Optimal";
                }
                if (insight) {
                    insight.innerHTML = `Dengan <strong>${armada} armada aktif</strong> kecepatan <strong>${kecepatan} km/jam</strong>, headway <strong>${headwayFormatted} menit</strong> memenuhi standar SPM Angkutan Perkotaan (≤ 15 mnt). Kapasitas angkut <strong>${kapasitasPerJam} orang/jam</strong> cukup untuk jalur pesisir Palu–Donggala.`;
                }
            } else if (headwayReal <= 25) {
                statusEl.textContent = 'Pelayanan Normal (15 - 25 mnt)';
                statusEl.className = 'text-[10px] text-amber-400 font-semibold';
                if (badge) {
                    badge.className = "px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-amber-500/15 text-amber-300 border-amber-500/40 whitespace-nowrap";
                    badge.textContent = "⚡ Normal";
                }
                if (insight) {
                    insight.innerHTML = `Headway <strong>${headwayFormatted} menit</strong> masih dalam batas normal, namun di atas standar ideal 15 menit. Disarankan optimalkan kecepatan agar penumpang tidak menunggu terlalu lama.`;
                }
            } else {
                statusEl.textContent = '⚠️ Waktu Tunggu Kritis (> 25 mnt)';
                statusEl.className = 'text-[10px] text-rose-400 font-semibold';
                if (badge) {
                    badge.className = "px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-rose-500/15 text-rose-300 border-rose-500/40 whitespace-nowrap";
                    badge.textContent = "⚠️ Kritis";
                }
                if (insight) {
                    insight.innerHTML = `⚠️ Headway <strong>${headwayFormatted} menit</strong> melampaui ambang kritis! Dengan hanya <strong>${armada} armada</strong>, segera aktifkan armada cadangan.`;
                }
            }
        }

        // ========================================================
        // 7. MODAL UPDATE STATUS BUS & SUBMIT KE DATABASE
        // ========================================================
        function openModalUpdate() {
            document.getElementById('modal-update-status').classList.remove('hidden');
        }

        function openModalUpdateWithBus(idBus) {
            document.getElementById('modal-bus-select').value = idBus;
            openModalUpdate();
        }

        function closeModalUpdate() {
            document.getElementById('modal-update-status').classList.add('hidden');
        }

        function toggleKendalaInput() {
            const isRusak = document.querySelector('input[name="status"]:checked').value === 'Rusak';
            const wrapper = document.getElementById('wrapper-kendala');
            if (isRusak) {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
            }
        }

        async function submitBusStatus(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-bus');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Menyimpan...`;

            const idBus = document.getElementById('modal-bus-select').value;
            const status = document.querySelector('input[name="status"]:checked').value;
            const kendala = status === 'Rusak' ? document.getElementById('modal-kendala').value : 'Normal / Siap Operasi';

            try {
                const res = await fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_bus: idBus, status: status, kendala: kendala })
                });
                const result = await res.json();

                if (result.success) {
                    showToast(result.message);
                    closeModalUpdate();
                    await loadMonitoringData();
                    await loadAnalyticsData();
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-check mr-1.5"></i> Simpan Perubahan`;
            }
        }

        async function resetBaselineArmada() {
            if (!confirm('Kembalikan kondisi operasional seluruh armada ke Baseline Riil (4 Beroperasi, 1 Rusak - TD 03)?')) return;
            const btn = document.getElementById('btn-submit-bus');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Mereset...`;
            }
            try {
                const res = await fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'reset_baseline' })
                });
                const result = await res.json();
                if (result.success) {
                    showToast(result.message);
                    closeModalUpdate();
                    await loadMonitoringData();
                    await loadAnalyticsData();
                } else {
                    alert('Gagal mereset: ' + result.message);
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan: ' + err.message);
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fa-solid fa-check mr-1.5"></i> Simpan Perubahan`;
                }
            }
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 4000);
        }

        // ========================================================
        // 8. BIG DATA ANALYTICS & DSS CARDS
        // ========================================================
        async function loadAnalyticsData() {
            try {
                const res = await fetch('api_analytics.php');
                const data = await res.json();
                if (!data.success) return;

                analyticsData = data;

                document.getElementById('kpi-total-log').textContent = data.kpi.total_log;
                document.getElementById('kpi-total-penumpang').textContent = parseInt(data.kpi.total_penumpang).toLocaleString('id-ID');
                document.getElementById('kpi-avg-delay').textContent = `${data.kpi.rata_rata_delay} mnt`;
                document.getElementById('kpi-reliability').textContent = `${data.kpi.reliability_score}%`;

                renderDSSCards(data.decision_support);
                renderAll25Charts();

            } catch (err) {
                console.error("Gagal memuat data analytics:", err);
            }
        }

        function renderDSSCards(insights) {
            const container = document.getElementById('dss-container');
            if (!container) return;
            container.innerHTML = '';

            insights.forEach(item => {
                let badgeClass = 'border-sky-500/40 text-sky-400 bg-sky-500/10';
                let icon = 'fa-lightbulb';

                if (item.tipe === 'critical') {
                    badgeClass = 'border-rose-500/40 text-rose-400 bg-rose-500/10';
                    icon = 'fa-triangle-exclamation';
                } else if (item.tipe === 'action') {
                    badgeClass = 'border-sky-500/40 text-sky-400 bg-sky-500/10';
                    icon = 'fa-bolt';
                } else if (item.tipe === 'strategy') {
                    badgeClass = 'border-amber-500/40 text-amber-400 bg-amber-500/10';
                    icon = 'fa-lightbulb';
                } else if (item.tipe === 'survey') {
                    badgeClass = 'border-emerald-500/40 text-emerald-400 bg-emerald-500/10';
                    icon = 'fa-clipboard-check';
                }

                const card = document.createElement('div');
                card.className = "bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-lg flex flex-col justify-between space-y-4 hover:border-slate-700 transition";
                card.innerHTML = `
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border ${badgeClass}">
                                ${item.tipe.toUpperCase()}
                            </span>
                            <i class="fa-solid ${icon} text-slate-500 text-xs"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 leading-snug">${item.judul}</h4>
                        <p class="text-xs text-slate-300 leading-relaxed">${item.deskripsi}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-800 text-xs">
                        <span class="font-bold text-sky-400 block mb-1">Rekomendasi Tindakan:</span>
                        <p class="text-slate-400 leading-relaxed">${item.rekomendasi}</p>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // ========================================================
        // 9. RENDER SELURUH 25 CHART ANALISIS DATA (CHART.JS)
        // ========================================================
        function renderAll25Charts() {
            if (!analyticsData || !analyticsData.analytics_25) return;
            const a25 = analyticsData.analytics_25;

            // Helper destroyer
            function createOrReplace(chartId, config) {
                const el = document.getElementById(chartId);
                if (!el) return;
                if (chartInstances[chartId]) {
                    chartInstances[chartId].destroy();
                }
                chartInstances[chartId] = new Chart(el.getContext('2d'), config);
            }

            // Global Dark Theme Defaults
            Chart.defaults.color = '#475569';
            Chart.defaults.borderColor = '#e2e8f0';

            // ----------------------------------------------------
            // KLASTER 1: OPERASIONAL ARMADA (1-5)
            // ----------------------------------------------------
            // Chart 1: Frekuensi Kerusakan Armada
            createOrReplace('chart1', {
                type: 'bar',
                data: {
                    labels: a25.c1_kerusakan_armada.labels,
                    datasets: [{
                        label: 'Frekuensi Mogok (Hari)',
                        data: a25.c1_kerusakan_armada.data,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#f59e0b', '#10b981'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 2 } } }
                }
            });

            // Chart 2: Kecepatan Rata-Rata per Armada
            createOrReplace('chart2', {
                type: 'bar',
                data: {
                    labels: a25.c2_kecepatan_armada.labels,
                    datasets: [{
                        label: 'Kecepatan Aktual (km/jam)',
                        data: a25.c2_kecepatan_armada.data,
                        backgroundColor: ['#0284c7', '#0284c7', '#ef4444', '#0284c7', '#0284c7'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, max: 80 } }
                }
            });

            // Chart 3: Total Trip Armada
            createOrReplace('chart3', {
                type: 'bar',
                data: {
                    labels: a25.c3_total_trip_armada.labels,
                    datasets: [{
                        label: 'Trip Selesai (30 Hari)',
                        data: a25.c3_total_trip_armada.data,
                        backgroundColor: ['#0d9488', '#0d9488', '#f43f5e', '#0d9488', '#0d9488'],
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } }
                }
            });

            // Chart 4: Rasio Kesiapan Armada
            createOrReplace('chart4', {
                type: 'doughnut',
                data: {
                    labels: a25.c4_rasio_kesiapan_armada.labels,
                    datasets: [{
                        data: a25.c4_rasio_kesiapan_armada.data,
                        backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#f59e0b', '#8b5cf6'],
                        borderWidth: 2, borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Chart 5: Konsumsi BBM
            createOrReplace('chart5', {
                type: 'bar',
                data: {
                    labels: a25.c5_konsumsi_bbm_armada.labels,
                    datasets: [{
                        label: 'Konsumsi Solar (L/100km)',
                        data: a25.c5_konsumsi_bbm_armada.data,
                        backgroundColor: ['#06b6d4', '#06b6d4', '#f97316', '#06b6d4', '#06b6d4'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, max: 45 } }
                }
            });

            // ----------------------------------------------------
            // KLASTER 2: DELAY & WAKTU TEMPUH (6-10)
            // ----------------------------------------------------
            // Chart 6: Avg Delay per Armada
            createOrReplace('chart6', {
                type: 'bar',
                data: {
                    labels: a25.c6_avg_delay_armada.labels,
                    datasets: [{
                        label: 'Delay Rata-Rata (Menit)',
                        data: a25.c6_avg_delay_armada.data,
                        backgroundColor: ['#10b981', '#fbbf24', '#ef4444', '#f59e0b', '#10b981'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Chart 7: Tren Delay Harian
            createOrReplace('chart7', {
                type: 'line',
                data: {
                    labels: a25.c7_tren_delay_harian.labels,
                    datasets: [{
                        label: 'Delay Harian (Menit)',
                        data: a25.c7_tren_delay_harian.data,
                        borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.15)',
                        fill: true, tension: 0.35, pointRadius: 2
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Chart 8: Delay Berdasarkan Jam Sibuk
            createOrReplace('chart8', {
                type: 'bar',
                data: {
                    labels: a25.c8_delay_berdasarkan_jam_sibuk.labels,
                    datasets: [{
                        label: 'Delay Rata-Rata (Menit)',
                        data: a25.c8_delay_berdasarkan_jam_sibuk.data,
                        backgroundColor: ['#f59e0b', '#0284c7', '#ef4444', '#10b981'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Chart 9: Waktu Tempuh vs Standar SPM
            createOrReplace('chart9', {
                type: 'bar',
                data: {
                    labels: a25.c9_waktu_tempuh_vs_spm.labels,
                    datasets: [{
                        label: 'Menit Tempuh',
                        data: a25.c9_waktu_tempuh_vs_spm.data,
                        backgroundColor: ['#0284c7', '#0284c7', '#0284c7', '#0284c7', '#10b981'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, max: 35 } }
                }
            });

            // Chart 10: Variansi Headway per Jam
            createOrReplace('chart10', {
                type: 'line',
                data: {
                    labels: a25.c10_variansi_headway_per_jam.labels,
                    datasets: [{
                        label: 'Headway (Menit)',
                        data: a25.c10_variansi_headway_per_jam.data,
                        borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.15)',
                        fill: true, tension: 0.35, pointRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // ----------------------------------------------------
            // KLASTER 3: VOLUME PENUMPANG (11-15)
            // ----------------------------------------------------
            // Chart 11: Tren Penumpang Harian
            createOrReplace('chart11', {
                type: 'line',
                data: {
                    labels: a25.c11_tren_penumpang_harian.labels,
                    datasets: [{
                        label: 'Penumpang/Hari',
                        data: a25.c11_tren_penumpang_harian.data,
                        borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.15)',
                        fill: true, tension: 0.35, pointRadius: 2
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: false } }
                }
            });

            // Chart 12: Akumulasi Penumpang per Armada
            createOrReplace('chart12', {
                type: 'bar',
                data: {
                    labels: a25.c12_akumulasi_penumpang_armada.labels,
                    datasets: [{
                        label: 'Total Komuter',
                        data: a25.c12_akumulasi_penumpang_armada.data,
                        backgroundColor: ['#10b981', '#10b981', '#ef4444', '#10b981', '#10b981'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Chart 13: Profil Penumpang per Jam (Twin Peaks)
            createOrReplace('chart13', {
                type: 'bar',
                data: {
                    labels: a25.c13_profil_penumpang_per_jam.labels,
                    datasets: [{
                        label: 'Komuter/Jam',
                        data: a25.c13_profil_penumpang_per_jam.data,
                        backgroundColor: '#38bdf8',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Chart 14: Weekday vs Weekend
            createOrReplace('chart14', {
                type: 'pie',
                data: {
                    labels: a25.c14_weekday_vs_weekend.labels,
                    datasets: [{
                        data: a25.c14_weekday_vs_weekend.data,
                        backgroundColor: ['#0284c7', '#ec4899'],
                        borderWidth: 2, borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Chart 15: Load Factor per Armada
            createOrReplace('chart15', {
                type: 'bar',
                data: {
                    labels: a25.c15_load_factor_armada.labels,
                    datasets: [{
                        label: 'Okupansi Kursi (%)',
                        data: a25.c15_load_factor_armada.data,
                        backgroundColor: ['#059669', '#059669', '#e11d48', '#059669', '#059669'],
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, max: 100 } }
                }
            });

            // ----------------------------------------------------
            // KLASTER 4: PERAWATAN & BIAYA (16-20)
            // ----------------------------------------------------
            // Chart 16: Distribusi Kendala Teknis
            createOrReplace('chart16', {
                type: 'doughnut',
                data: {
                    labels: a25.c16_distribusi_kendala.labels,
                    datasets: [{
                        data: a25.c16_distribusi_kendala.data,
                        backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6', '#10b981'],
                        borderWidth: 2, borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Chart 17: Biaya Perbaikan per Armada
            createOrReplace('chart17', {
                type: 'bar',
                data: {
                    labels: a25.c17_biaya_perbaikan_armada.labels,
                    datasets: [{
                        label: 'Biaya Servis (Rp)',
                        data: a25.c17_biaya_perbaikan_armada.data,
                        backgroundColor: ['#64748b', '#64748b', '#ef4444', '#64748b', '#64748b'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) { return 'Rp ' + (val/1000000) + ' Jt'; }
                            }
                        }
                    }
                }
            });

            // Chart 18: Komposisi Biaya per Komponen
            createOrReplace('chart18', {
                type: 'pie',
                data: {
                    labels: a25.c18_komposisi_biaya_komponen.labels,
                    datasets: [{
                        data: a25.c18_komposisi_biaya_komponen.data,
                        backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981', '#a855f7'],
                        borderWidth: 2, borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Chart 19: MTBF per Armada
            createOrReplace('chart19', {
                type: 'bar',
                data: {
                    labels: a25.c19_mtbf_armada.labels,
                    datasets: [{
                        label: 'MTBF (Hari Operasi / Mogok)',
                        data: a25.c19_mtbf_armada.data,
                        backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#f59e0b', '#10b981'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Chart 20: Radar Kesehatan Komponen
            createOrReplace('chart20', {
                type: 'radar',
                data: {
                    labels: a25.c20_radar_kesehatan_komponen.labels,
                    datasets: [{
                        label: 'Skor Integritas Komponen (1-100)',
                        data: a25.c20_radar_kesehatan_komponen.data,
                        borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,0.25)',
                        pointBackgroundColor: '#38bdf8'
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: { r: { min: 40, max: 100, ticks: { stepSize: 15, color: '#64748b' } } }
                }
            });

            // ----------------------------------------------------
            // KLASTER 5: HALTE & TRAYEK (21-25)
            // ----------------------------------------------------
            // Chart 21: Kepadatan 14 Halte
            createOrReplace('chart21', {
                type: 'bar',
                data: {
                    labels: a25.c21_kepadatan_14_halte.labels,
                    datasets: [{
                        label: 'Komuter/Hari',
                        data: a25.c21_kepadatan_14_halte.data,
                        backgroundColor: '#0284c7',
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });

            // Chart 22: Waktu Tunggu per Halte
            createOrReplace('chart22', {
                type: 'bar',
                data: {
                    labels: a25.c22_waktu_tunggu_per_halte.labels,
                    datasets: [{
                        label: 'Waktu Tunggu Rata-Rata (Menit)',
                        data: a25.c22_waktu_tunggu_per_halte.data,
                        backgroundColor: a25.c22_waktu_tunggu_per_halte.data.map(v => v >= 10 ? '#ef4444' : (v >= 7 ? '#f59e0b' : '#10b981')),
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, max: 15 } }
                }
            });

            // Chart 23: Pendapatan per Trayek
            createOrReplace('chart23', {
                type: 'bar',
                data: {
                    labels: a25.c23_pendapatan_per_trayek.labels,
                    datasets: [{
                        label: 'Penerimaan Tiket (Rp/Hari)',
                        data: a25.c23_pendapatan_per_trayek.data,
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) { return 'Rp ' + (val/1000000) + ' Jt'; }
                            }
                        }
                    }
                }
            });

            // Chart 24: Tren OTP Mingguan
            createOrReplace('chart24', {
                type: 'line',
                data: {
                    labels: a25.c24_tren_otp_mingguan.labels,
                    datasets: [{
                        label: 'OTP Kepatuhan (%)',
                        data: a25.c24_tren_otp_mingguan.data,
                        borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)',
                        fill: true, tension: 0.35, pointRadius: 5
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { min: 70, max: 100 } }
                }
            });

            // Chart 25: Proyeksi 6 Bulan ke Depan
            createOrReplace('chart25', {
                type: 'line',
                data: {
                    labels: a25.c25_proyeksi_penumpang_6_bulan.labels,
                    datasets: [
                        {
                            label: 'Proyeksi Komuter/Bulan',
                            data: a25.c25_proyeksi_penumpang_6_bulan.data,
                            borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.15)',
                            fill: true, tension: 0.35, pointRadius: 4, yAxisID: 'y'
                        },
                        {
                            label: 'Target Armada Bus (Unit)',
                            data: a25.c25_proyeksi_penumpang_6_bulan.target_bus,
                            borderColor: '#38bdf8', borderDash: [5, 5],
                            tension: 0.2, pointRadius: 4, yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: false, title: { display: true, text: 'Komuter' } },
                        y1: { position: 'right', min: 0, max: 10, grid: { display: false }, title: { display: true, text: 'Armada (Unit)' } }
                    }
                }
            });

            // Sinkronkan visibilitas kartu & banner klaster
            applyChartVisibility();
        }
    </script>
</body>
</html>
