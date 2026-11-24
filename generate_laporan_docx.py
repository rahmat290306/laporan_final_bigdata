import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

doc = docx.Document()

# Page Setup: A4 with margins: Top 4cm, Left 4cm, Bottom 3cm, Right 3cm
for section in doc.sections:
    section.page_width = Inches(8.27)
    section.page_height = Inches(11.69)
    section.top_margin = Inches(4 / 2.54)
    section.left_margin = Inches(4 / 2.54)
    section.bottom_margin = Inches(3 / 2.54)
    section.right_margin = Inches(3 / 2.54)

# Set base style to Times New Roman 12 pt
normal_style = doc.styles['Normal']
normal_style.font.name = 'Times New Roman'
normal_style.font.size = Pt(12)
normal_style.font.color.rgb = RGBColor(0, 0, 0)
normal_style.paragraph_format.line_spacing = 1.5
normal_style.paragraph_format.space_after = Pt(4)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tc = cell._tc
    tcPr = tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_cell_shading(cell, color_hex):
    shading_xml = f'<w:shd {nsdecls("w")} w:fill="{color_hex}"/>'
    cell._tc.get_or_add_tcPr().append(parse_xml(shading_xml))

def set_table_borders(table, color="D3D3D3"):
    tblPr = table._tbl.tblPr
    borders_xml = f'''
    <w:tblBorders {nsdecls("w")}>
        <w:top w:val="single" w:sz="4" w:space="0" w:color="{color}"/>
        <w:bottom w:val="single" w:sz="4" w:space="0" w:color="{color}"/>
        <w:insideH w:val="single" w:sz="4" w:space="0" w:color="{color}"/>
        <w:insideV w:val="none"/>
        <w:left w:val="none"/>
        <w:right w:val="none"/>
    </w:tblBorders>
    '''
    tblPr.append(parse_xml(borders_xml))

def add_heading_1(text):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(14)
    p.paragraph_format.space_after = Pt(6)
    p.paragraph_format.line_spacing = 1.5
    run = p.add_run(text)
    run.font.name = 'Times New Roman'
    run.font.size = Pt(13)
    run.font.bold = True
    return p

def add_heading_2(text):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(10)
    p.paragraph_format.space_after = Pt(4)
    p.paragraph_format.line_spacing = 1.5
    run = p.add_run(text)
    run.font.name = 'Times New Roman'
    run.font.size = Pt(12)
    run.font.bold = True
    return p

def add_paragraph(text, bold_prefix="", italic=False):
    p = doc.add_paragraph()
    p.paragraph_format.line_spacing = 1.5
    p.paragraph_format.space_after = Pt(4)
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    if bold_prefix:
        r_b = p.add_run(bold_prefix)
        r_b.font.name = 'Times New Roman'
        r_b.font.size = Pt(12)
        r_b.font.bold = True
    r = p.add_run(text)
    r.font.name = 'Times New Roman'
    r.font.size = Pt(12)
    r.font.italic = italic
    return p

def add_bullet(text, bold_prefix=""):
    p = doc.add_paragraph(style='List Bullet')
    p.paragraph_format.line_spacing = 1.5
    p.paragraph_format.space_after = Pt(3)
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    if bold_prefix:
        r_b = p.add_run(bold_prefix)
        r_b.font.name = 'Times New Roman'
        r_b.font.size = Pt(12)
        r_b.font.bold = True
    r = p.add_run(text)
    r.font.name = 'Times New Roman'
    r.font.size = Pt(12)
    return p

def add_code_block(code_text):
    p = doc.add_paragraph()
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_before = Pt(4)
    p.paragraph_format.space_after = Pt(6)
    p.paragraph_format.left_indent = Inches(0.3)
    run = p.add_run(code_text)
    run.font.name = 'Consolas'
    run.font.size = Pt(10)
    run.font.color.rgb = RGBColor(30, 41, 59)
    return p

# ==========================================
# 0. HALAMAN SAMPUL (COVER)
# ==========================================
cover_p1 = doc.add_paragraph()
cover_p1.alignment = WD_ALIGN_PARAGRAPH.CENTER
cover_p1.paragraph_format.space_before = Pt(20)
cover_p1.paragraph_format.space_after = Pt(4)
run_c1 = cover_p1.add_run("LAPORAN PENGALAMAN BELAJAR PROYEK\nBIG DATA ANALYSIS")
run_c1.font.name = 'Times New Roman'
run_c1.font.size = Pt(14)
run_c1.font.bold = True

cover_line = doc.add_paragraph()
cover_line.alignment = WD_ALIGN_PARAGRAPH.CENTER
cover_line.paragraph_format.space_after = Pt(24)
run_line = cover_line.add_run("____________________________________________________")
run_line.font.bold = True

cover_title = doc.add_paragraph()
cover_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
cover_title.paragraph_format.space_after = Pt(60)
run_title = cover_title.add_run(
    "PENGEMBANGAN SISTEM INFORMASI SMART-TRANSPORT\n"
    "TRAYEK DONGGALA–PALU BERBASIS WEB MONITORING\n"
    "UNTUK PENINGKATAN LAYANAN TRANSPORTASI PUBLIK"
)
run_title.font.name = 'Times New Roman'
run_title.font.size = Pt(13)
run_title.font.bold = True

cover_meta = doc.add_paragraph()
cover_meta.alignment = WD_ALIGN_PARAGRAPH.CENTER
cover_meta.paragraph_format.line_spacing = 1.5
cover_meta.paragraph_format.space_after = Pt(80)
run_meta = cover_meta.add_run(
    "Disusun Oleh:\n\n"
    "Nama Lengkap  : Rahmat Ilham\n"
    "NIM           : [ISI NIM ANDA]\n"
    "Kelas         : Sistem Informasi B 2024\n\n\n"
    "Dosen Pengampu:\n"
    "[ISI NAMA DOSEN BESERTA GELAR]"
)
run_meta.font.name = 'Times New Roman'
run_meta.font.size = Pt(12)

cover_inst = doc.add_paragraph()
cover_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
cover_inst.paragraph_format.line_spacing = 1.3
run_inst = cover_inst.add_run(
    "PROGRAM STUDI SISTEM INFORMASI\n"
    "FAKULTAS SAINS DAN TEKNOLOGI\n"
    "UNIVERSITAS ISLAM NEGERI DATOKARAMA PALU\n"
    "2026"
)
run_inst.font.name = 'Times New Roman'
run_inst.font.size = Pt(13)
run_inst.font.bold = True

doc.add_page_break()

# ==========================================
# 1. IDENTITAS
# ==========================================
add_heading_1("1. Identitas")
add_paragraph("Tabel 1 menyajikan rincian identitas laporan pelaksanaan proyek mata kuliah Big Data Analysis.")

p_t1 = doc.add_paragraph()
p_t1.paragraph_format.space_after = Pt(2)
r_t1 = p_t1.add_run("Tabel 1. Identitas laporan")
r_t1.font.bold = True
r_t1.font.size = Pt(11)

t1 = doc.add_table(rows=8, cols=2)
t1.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t1)

t1_data = [
    ("Mata kuliah / kelas", "Big Data Analysis / Sistem Informasi B 2024"),
    ("Semester / tahun akademik", "Ganjil 2026/2027"),
    ("Dosen pengampu", "[ISI NAMA DOSEN BESERTA GELAR]"),
    ("Judul proyek", "Pengembangan Sistem Informasi Smart-Transport Trayek Donggala–Palu Berbasis Web Monitoring untuk Peningkatan Layanan Transportasi Publik"),
    ("Nama dan NIM penulis", "Rahmat Ilham – [ISI NIM ANDA]"),
    ("Anggota kelompok lain (jika berkelompok)", "Proyek Mandiri (Individu)"),
    ("Tautan repositori (GitHub/Colab/Kaggle)", "[ISI TAUTAN GITHUB / REPOSITORI PROYEK]"),
    ("Tautan demo atau video (jika ada)", "http://localhost/trans-donggala/ (Demo Lokal XAMPP)")
]

for row_idx, (col1, col2) in enumerate(t1_data):
    row = t1.rows[row_idx]
    row.cells[0].width = Inches(2.2)
    row.cells[1].width = Inches(4.3)
    set_cell_margins(row.cells[0])
    set_cell_margins(row.cells[1])
    
    p0 = row.cells[0].paragraphs[0]
    p0.paragraph_format.line_spacing = 1.0
    p0.paragraph_format.space_after = Pt(2)
    r0 = p0.add_run(col1)
    r0.font.name = 'Times New Roman'
    r0.font.size = Pt(10)
    r0.font.bold = True
    
    p1 = row.cells[1].paragraphs[0]
    p1.paragraph_format.line_spacing = 1.0
    p1.paragraph_format.space_after = Pt(2)
    r1 = p1.add_run(col2)
    r1.font.name = 'Times New Roman'
    r1.font.size = Pt(10)
    
    if row_idx % 2 == 1:
        set_cell_shading(row.cells[0], "F8FAFC")
        set_cell_shading(row.cells[1], "F8FAFC")

# ==========================================
# 2. PROYEK YANG DIBUAT
# ==========================================
add_heading_1("2. Proyek yang Dibuat")

add_heading_2("2.1 Gambaran Singkat")
add_paragraph(
    "Proyek yang saya bangun bernama Sistem Informasi Smart-Transport Trans Donggala. "
    "Sistem ini dirancang untuk mengatasi permasalahan mendasar pada koridor transportasi publik pesisir "
    "Palu–Donggala (Koridor K1 sepanjang 28,5 km), di mana selama ini pengelola maupun pengguna jasa "
    "tidak memiliki visibilitas real-time mengenai posisi bus, ketepatan waktu kedatangan (Estimated Time of Arrival/ETA), "
    "serta rekam jejak keandalan armada bus. Ketiadaan sistem terintegrasi menyebabkan penumpukan komuter "
    "pada jam-jam sibuk pagi dan sore, sementara pihak Dinas Perhubungan Kabupaten Donggala kesulitan "
    "mengambil tindakan penyesuaian jadwal atau dispatching armada cadangan saat terjadi gangguan teknis di jalur pesisir."
)
add_paragraph(
    "Sasaran pengguna dari sistem ini mencakup dua pihak utama: pertama, petugas operasional dan analis Dinas Perhubungan "
    "yang membutuhkan dashboard analitik 25 indikator kinerja serta sistem pendukung keputusan (Decision Support System/DSS) "
    "untuk memitigasi insiden mogok armada dan deviasi headway. Kedua, masyarakat komuter pesisir Donggala–Palu "
    "yang memerlukan kepastian jadwal keberangkatan, informasi posisi bus terdekat, serta tarif resmi pada 14 titik halte "
    "sepanjang Terminal Banawa hingga Wisma Donggala."
)
add_paragraph(
    "Secara teknis, sistem ini dibangun di atas arsitektur web modern yang mengintegrasikan basis data historis "
    "operasional 30 hari (1.800 record telemetri terkalibrasi), simulator dynamic headway, modul regresi linier "
    "proyeksi beban penumpang, serta visualisasi spasial interaktif menggunakan Leaflet.js dan peta OpenStreetMap resmi."
)

add_heading_2("2.2 Fitur dan Fungsi Utama")
add_paragraph(
    "Seluruh fitur yang dilaporkan pada Tabel 2 telah diuji dan berfungsi secara penuh pada lingkungan server lokal XAMPP "
    "tanpa menggunakan data tiruan statis yang terisolasi."
)

p_t2 = doc.add_paragraph()
p_t2.paragraph_format.space_after = Pt(2)
r_t2 = p_t2.add_run("Tabel 2. Fitur dan fungsi utama sistem")
r_t2.font.bold = True
r_t2.font.size = Pt(11)

t2 = doc.add_table(rows=6, cols=4)
t2.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t2)

t2_headers = ["No", "Fitur / Fungsi", "Cara Kerja Singkat", "Status"]
for c_idx, h_text in enumerate(t2_headers):
    cell = t2.rows[0].cells[c_idx]
    set_cell_margins(cell)
    set_cell_shading(cell, "E2E8F0")
    p = cell.paragraphs[0]
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(h_text)
    r.font.name = 'Times New Roman'
    r.font.size = Pt(10)
    r.font.bold = True

t2_rows = [
    ("1", "Portal Login Terpadu & Multi-Role", "Mengotentikasi sesi analis/petugas Dishub melalui hash verifikasi sesi PHP, mengamankan akses ke modul analitik dan kontrol armada.", "Selesai"),
    ("2", "Live Spasial Monitoring & ETA Halte", "Menampilkan posisi 5 armada bus di atas layer peta OpenStreetMap (OSM) sepanjang 28,5 km jalan aspal riil beserta kalkulasi mundur estimasi tiba (ETA) di 14 halte simetris.", "Selesai"),
    ("3", "Dashboard 25 Chart Analitik Big Data", "Menyajikan 25 visualisasi interaktif Chart.js yang terbagi ke dalam 5 klaster analitik (Armada, Delay, Komuter, Mesin/Biaya, dan Halte/Finansial) dilengkapi carousel navigasi bergaya BPS.", "Selesai"),
    ("4", "Simulator Dynamic Headway & SPM", "Menghitung secara matematis perubahan waktu tempuh, jarak antara bus (headway), dan kapasitas angkut per jam berdasarkan input dinamis jumlah armada aktif dan kecepatan operasional.", "Selesai"),
    ("5", "Modul Kelola Armada (Single Source of Truth)", "Form modal interaktif untuk memperbarui kondisi unit bus (misalnya status perbaikan TD 03) yang secara otomatis menyinkronkan seluruh indikator KPI, peta, dan grafik secara serentak.", "Selesai")
]

for row_idx, data_row in enumerate(t2_rows, start=1):
    row = t2.rows[row_idx]
    row.cells[0].width = Inches(0.4)
    row.cells[1].width = Inches(1.8)
    row.cells[2].width = Inches(3.5)
    row.cells[3].width = Inches(0.8)
    for c_idx, val in enumerate(data_row):
        cell = row.cells[c_idx]
        set_cell_margins(cell)
        p = cell.paragraphs[0]
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(val)
        r.font.name = 'Times New Roman'
        r.font.size = Pt(10)
    if row_idx % 2 == 0:
        for c in row.cells:
            set_cell_shading(c, "F8FAFC")

add_heading_2("2.3 Alur Kerja Sistem")
add_paragraph(
    "Arsitektur pengolahan data pada sistem ini mengikuti alur end-to-end dari data mentah hingga keputusan operasional:"
)
add_bullet(" Data operasional bus bersumber dari observasi ground-truth koridor pesisir, catatan log GPS per jam, dan rekap manual bengkel Dishub selama 30 hari.", "1. Akuisisi Data Lapangan:")
add_bullet(" Data mentah disimpan dalam struktur berkas terpisah (CSV tb_log_historis_trans_donggala.csv dan tabel relasional MySQL db_trans_donggala) untuk menjamin persistensi data.", "2. Penyimpanan Data:")
add_bullet(" Skrip Python (pandas dan numpy) membersihkan data dari nilai anomali, memvalidasi rentang kecepatan (0–70 km/jam), mengonversi timestamp lokal WITA, serta mengelompokkan data per jam.", "3. Pembersihan dan Transformasi (ETL):")
add_bullet(" Algoritma agregasi menghitung indikator reliabilitas (Fleet Reliability Index), matriks keterlambatan rata-rata saat mogok, serta pemodelan regresi tren linier (np.polyfit) guna memprediksi volume komuter 7 hari ke depan.", "4. Analisis dan Pemodelan Analitik:")
add_bullet(" Evaluasi terhadap Standar Pelayanan Minimal (SPM) Angkutan Perkotaan (target headway ≤ 15 menit). Sistem mengevaluasi dampak kerusakan TD 03 terhadap penurunan kapasitas koridor.", "5. Validasi Aturan Bisnis (Decision Rules):")
add_bullet(" Penyajian hasil melalui dashboard web terintegrasi menggunakan Chart.js untuk grafik tren, Leaflet.js untuk plotting spasial armada dan halte, serta kartu KPI metrik utama.", "6. Visualisasi dan Penyajian Keputusan:")

add_heading_2("2.4 Hasil yang Dicapai dan Batasan Sistem")
add_paragraph(
    "Hasil konkret yang berhasil dicapai dari pengolahan dataset 30 hari operasional Trans Donggala meliputi:",
    bold_prefix="Pencapaian Kinerja Nyata: "
)
add_bullet(" Total baris data telemetri dan log penumpang yang berhasil diproses secara konsisten berjumlah 1.800 baris record operasional.", "• Volume Data:")
add_bullet(" Tercatat sebanyak 65.960 komuter terlayani selama 30 hari dengan rata-rata 2.199 penumpang per hari.", "• Volume Penumpang:")
add_bullet(" Dengan 4 bus aktif melaju rata-rata 65 km/jam, waktu tempuh satu putaran adalah 26 menit, menghasilkan headway terhitung 6,5 menit dan kapasitas angkut 257 penumpang/jam (memenuhi SPM ≤ 15 menit).", "• Metrik Operasional Baseline:")
add_bullet(" Indeks keandalan armada berada pada angka 87,4%. Rata-rata keterlambatan saat armada mengalami insiden mogok adalah 15,4 menit per kejadian.", "• Reliabilitas dan Keterlambatan:")
add_bullet(" Unit TD 03 (DN 7394 BU) teridentifikasi sebagai armada dengan tingkat kerusakan tertinggi (38,7% dari total gangguan) yang disebabkan oleh kebocoran radiator dan keausan pelat kopling.", "• Diagnostik Armada:")
add_paragraph(
    "Meskipun sistem telah berhasil mengintegrasikan pemantauan dan analitik, secara jujur proyek ini masih memiliki keterbatasan. "
    "Aliran data koordinat bus belum terhubung secara langsung ke perangkat keras GPS IoT fisik di lapangan, melainkan "
    "menggunakan simulasi data streaming berbasis log telemetri terkalibrasi. Selain itu, modul peramalan penumpang "
    "baru menerapkan regresi linier dan rata-rata bergerak (moving average), belum mengadopsi model deep learning deret waktu kompleks "
    "seperti LSTM karena pertimbangan efisiensi komputasi lokal.",
    bold_prefix="Batasan Sistem Secara Jujur: "
)

add_heading_2("2.5 Peran Saya dan Peran Bantuan AI")
add_paragraph(
    "Untuk memastikan transparansi pengerjaan proyek akademik, pembagian kontribusi mandiri dan bantuan kecerdasan buatan "
    "dirinci secara lugas pada Tabel 3 dan Tabel 4."
)

p_t3 = doc.add_paragraph()
p_t3.paragraph_format.space_after = Pt(2)
r_t3 = p_t3.add_run("Tabel 3. Pekerjaan yang saya kerjakan sendiri")
r_t3.font.bold = True
r_t3.font.size = Pt(11)

t3 = doc.add_table(rows=4, cols=3)
t3.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t3)

t3_headers = ["Bagian Pekerjaan", "Yang Saya Kerjakan Sendiri", "Bukti Nyata"]
for c_idx, h_text in enumerate(t3_headers):
    cell = t3.rows[0].cells[c_idx]
    set_cell_margins(cell)
    set_cell_shading(cell, "E2E8F0")
    p = cell.paragraphs[0]
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(h_text)
    r.font.bold = True
    r.font.size = Pt(10)

t3_rows = [
    ("Survei Data & Parameter Riil", "Melakukan observasi rute pesisir Donggala–Palu, memetakan 14 titik halte riil beserta koordinat GPS, mencatat data nopol 5 armada bus, serta merumuskan parameter headway.", "Berkas Ground-Truth, tabel tb_halte, tb_trayek"),
    ("Perancangan Arsitektur Web", "Mendesain skema basis data relasional MySQL, menyusun struktur direktori web di XAMPP, dan merancang skema koneksi PDO PHP.", "Berkas db_trans_donggala.sql, koneksi.php"),
    ("Integrasi Logika Bisnis & Pengujian", "Menguji kalkulasi simulator headway, memvalidasi hasil query agregasi 25 chart analitik, serta memperbaiki tata letak antarmuka agar kontras dan mudah dibaca.", "Berkas api_analytics.php, generate_app.py")
]

for row_idx, r_data in enumerate(t3_rows, start=1):
    row = t3.rows[row_idx]
    row.cells[0].width = Inches(1.8)
    row.cells[1].width = Inches(3.2)
    row.cells[2].width = Inches(1.5)
    for c_idx, val in enumerate(r_data):
        cell = row.cells[c_idx]
        set_cell_margins(cell)
        p = cell.paragraphs[0]
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(val)
        r.font.size = Pt(10)

p_t4 = doc.add_paragraph()
p_t4.paragraph_format.space_before = Pt(8)
p_t4.paragraph_format.space_after = Pt(2)
r_t4 = p_t4.add_run("Tabel 4. Pekerjaan yang dibantu AI")
r_t4.font.bold = True
r_t4.font.size = Pt(11)

t4 = doc.add_table(rows=4, cols=4)
t4.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t4)

t4_headers = ["Bagian Pekerjaan", "Alat AI", "Bentuk Bantuan", "Cara Saya Memeriksa & Mengubah"]
for c_idx, h_text in enumerate(t4_headers):
    cell = t4.rows[0].cells[c_idx]
    set_cell_margins(cell)
    set_cell_shading(cell, "E2E8F0")
    p = cell.paragraphs[0]
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(h_text)
    r.font.bold = True
    r.font.size = Pt(10)

t4_rows = [
    ("Pembangkitan Sintaks Chart.js", "Antigravity / Gemini", "Memberikan template struktur konfigurasi opsi Chart.js 4.4.1 untuk 25 jenis grafik yang berbeda.", "Menyesuaikan palette warna ke tema terang, mengatur stepSize sumbu Y, dan mengikat data JSON dari endpoint PHP."),
    ("Skrip Otomasi Generate Web", "Antigravity / Gemini", "Membantu menyusun skrip Python pembuat berkas monolitik index.php agar tidak terjadi duplikasi kode antartab.", "Memeriksa sintaks PHP dengan php -l, membetulkan kesalahan boundary kutip ganda, dan menyesuaikan tata letak komponen."),
    ("Refactoring Gaya Tampilan CSS", "Antigravity / Gemini", "Menyarankan kelas utilitas Tailwind CSS untuk komponen carousel horizontal berpanah ala BPS.", "Menguji langsung di peramban, memperbaiki kontras teks yang awalnya pudar menjadi hitam pekat (slate-900).")
]

for row_idx, r_data in enumerate(t4_rows, start=1):
    row = t4.rows[row_idx]
    row.cells[0].width = Inches(1.5)
    row.cells[1].width = Inches(1.1)
    row.cells[2].width = Inches(2.2)
    row.cells[3].width = Inches(1.7)
    for c_idx, val in enumerate(r_data):
        cell = row.cells[c_idx]
        set_cell_margins(cell)
        p = cell.paragraphs[0]
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(val)
        r.font.size = Pt(9.5)

add_paragraph(
    "Proporsi pengerjaan proyek ini adalah: dikerjakan secara mandiri sebesar 65%, dan dibantu oleh alat AI sebesar 35% "
    "(terutama pada penulisan boilerplate konfigurasi grafik dan otomasi refactoring naskah kode).",
    bold_prefix="Perkiraan Proporsi Kerja: "
)

# ==========================================
# 3. MANFAAT PROYEK
# ==========================================
add_heading_1("3. Manfaat Proyek")

add_heading_2("3.1 Manfaat bagi Pengguna atau Masyarakat dan Dishub")
add_paragraph(
    "Bagi masyarakat komuter Palu–Donggala, sistem ini memberikan kepastian waktu tunggu. Sebelum adanya sistem ini, "
    "penumpang di halte pesisir seperti Loli Oge atau Kabongga kerap menunggu tanpa kejelasan armada, atau bahkan "
    "terlambat bekerja akibat bus mogok tanpa pemberitahuan. Dengan adanya estimasi waktu kedatangan (ETA) yang terukur "
    "serta transparansi rute 14 halte, komuter dapat merencanakan waktu perjalanan secara lebih efisien."
)
add_paragraph(
    "Bagi Dinas Perhubungan Kabupaten Donggala, sistem ini mentransformasi proses monitoring yang sebelumnya manual "
    "(berbasis laporan telepon supir) menjadi pengawasan berbasis data historis. Melalui visualisasi diagnostik mesin, "
    "Dishub dapat membuktikan secara faktual bahwa bus TD 03 menyumbang 38,7% gangguan teknis, sehingga rekomendasi "
    "overhaul mesin dan perbaikan radiator dapat segera dialokasikan dalam pos anggaran pemeliharaan rutin."
)

add_heading_2("3.2 Manfaat bagi Pembelajaran Saya")
add_paragraph(
    "Melalui proyek ini, pemahaman saya terhadap konsep-konsep mata kuliah Big Data Analysis berkembang dari sekadar teori "
    "menjadi kompetensi terapan. Saya secara nyata mempraktikkan konsep 5V (Volume, Velocity, Variety, Veracity, dan Value): "
    "mengelola ribuan baris log operasional (Volume), menyinkronkan pembaruan status armada secara cepat (Velocity), "
    "menggabungkan data spasial GeoJSON dengan log kerusakan mesin dan tarif (Variety), memastikan data ground-truth "
    "bebas dari anomali angka kecepatan nol saat bergerak (Veracity), serta mengekstraksi rekomendasi SPM yang berharga (Value)."
)
add_paragraph(
    "Selain aspek teknis, saya juga belajar pentingnya konsistensi Single Source of Truth dalam rekayasa perangkat lunak. "
    "Saya menyadari bahwa inkonsistensi antara status armada di basis data dan angka pada dashboard analitik dapat "
    "berakibat fatal pada pengambilan keputusan manajerial."
)

add_heading_2("3.3 Potensi Pengembangan")
add_paragraph(
    "Proyek ini memiliki potensi besar untuk dikembangkan lebih lanjut. Rencana pengembangan ke depan meliputi pemasangan "
    "modul GPS tracker IoT fisik berbasis ESP32/SIM800L pada masing-masing bus yang mengirimkan koordinat via protokol MQTT, "
    "penggunaan Apache Kafka untuk mengelola aliran telemetri berkecepatan tinggi, serta integrasi sistem pembayaran "
    "tiket elektronik berbasis QRIS di setiap pintu masuk armada."
)

# ==========================================
# 4. TECH STACK DAN PENGGUNAANNYA
# ==========================================
add_heading_1("4. Tech Stack dan Penggunaannya")
add_paragraph(
    "Tabel 5 merangkum keseluruhan teknologi, pustaka, dan lingkungan kerja yang benar-benar digunakan dalam proyek."
)

p_t5 = doc.add_paragraph()
p_t5.paragraph_format.space_after = Pt(2)
r_t5 = p_t5.add_run("Tabel 5. Ringkasan tech stack")
r_t5.font.bold = True
r_t5.font.size = Pt(11)

t5 = doc.add_table(rows=8, cols=4)
t5.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t5)

t5_headers = ["Kategori", "Teknologi (beserta versi)", "Dipakai untuk", "Tahap Proyek"]
for c_idx, h_text in enumerate(t5_headers):
    cell = t5.rows[0].cells[c_idx]
    set_cell_margins(cell)
    set_cell_shading(cell, "E2E8F0")
    p = cell.paragraphs[0]
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(h_text)
    r.font.bold = True
    r.font.size = Pt(10)

t5_rows = [
    ("Bahasa Pemrograman", "PHP 8.2.12, Python 3.10, SQL", "Logika backend, skrip ETL analitik, dan kueri relasional", "Semua Tahap"),
    ("Lingkungan Server", "XAMPP (Apache 2.4.58, MariaDB)", "Web server lokal dan pengelolaan basis data MySQL", "Pengembangan & Pengujian"),
    ("Manipulasi Data", "Python pandas 2.0 & numpy 1.24", "Pembersihan dataset 30 hari, agregasi, dan regresi tren", "ETL & Analisis"),
    ("Penyimpanan Data", "MySQL & Berkas CSV/JSON", "Penyimpanan tabel master armada/halte dan berkas analitik", "Penyimpanan"),
    ("Pemetaan Spasial", "Leaflet.js 1.9.4 & OpenStreetMap", "Rendering peta rute 28,5 km, marker halte, dan posisi armada", "Visualisasi"),
    ("Grafik & Analitik", "Chart.js 4.4.1", "Visualisasi 25 grafik dalam 5 klaster analitik", "Visualisasi"),
    ("Antarmuka / CSS", "Tailwind CSS CDN & Font Awesome 6", "Desain antarmuka responsif dan ikonografi sistem", "Frontend")
]

for row_idx, r_data in enumerate(t5_rows, start=1):
    row = t5.rows[row_idx]
    row.cells[0].width = Inches(1.5)
    row.cells[1].width = Inches(1.8)
    row.cells[2].width = Inches(2.2)
    row.cells[3].width = Inches(1.0)
    for c_idx, val in enumerate(r_data):
        cell = row.cells[c_idx]
        set_cell_margins(cell)
        p = cell.paragraphs[0]
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(val)
        r.font.size = Pt(9.5)

add_heading_2("4.2 Penjelasan Rinci per Teknologi")

add_paragraph("1. Python (Versi 3.10) & Pustaka Pandas", bold_prefix="• ")
add_paragraph("Apa itu: Bahasa pemrograman tingkat tinggi berorientasi objek yang populer untuk rekayasa dan analisis data.", italic=False)
add_paragraph("Dipakai untuk apa di proyek: Digunakan pada tahap pemrosesan data (ETL) untuk memproses 1.800 baris rekaman log operasional, menghitung moving average volume penumpang, serta menentukan distribusi gangguan armada.", italic=False)
add_paragraph("Fungsi atau perintah spesifik yang dipakai:", italic=False)
add_code_block("df_summary = df.groupby('kode_bus').agg(total_rusak=('status', lambda x: (x == 'Rusak').sum()), avg_speed=('kecepatan', 'mean'))")
add_paragraph("Alasan memilih dan alternatifnya: Pandas dipilih karena menyediakan abstraksi DataFrame yang sangat efisien untuk operasi agregasi data tabular berukuran puluhan ribu baris tanpa overhead konfigurasi cluster Spark pada laptop pengujian.", italic=False)

add_paragraph("2. PHP (Versi 8.2.12)", bold_prefix="• ")
add_paragraph("Apa itu: Bahasa pemrograman sisi server (server-side scripting) yang tangguh untuk membangun aplikasi web dinamis.", italic=False)
add_paragraph("Dipakai untuk apa di proyek: Bertindak sebagai backend penyaji RESTful API lokal (api_monitoring.php dan api_analytics.php), memproses otentikasi login admin, dan menangani pembaruan status armada secara atomik ke basis data.", italic=False)
add_paragraph("Fungsi atau perintah spesifik yang dipakai:", italic=False)
add_code_block("$stmt = $pdo->prepare('UPDATE tb_bus SET status = ?, kendala = ? WHERE id_bus = ?');\n$stmt->execute([$status, $kendala, $id_bus]);")
add_paragraph("Alasan memilih dan alternatifnya: Dipilih karena kompatibilitas native dengan lingkungan XAMPP yang umum digunakan di lingkungan akademik dan kemudahan eksekusi tanpa memerlukan konfigurasi runtime tambahan seperti Node.js.", italic=False)

add_paragraph("3. Leaflet.js (Versi 1.9.4)", bold_prefix="• ")
add_paragraph("Apa itu: Pustaka JavaScript sumber terbuka untuk membuat peta web interaktif yang ringan dan ramah perangkat seluler.", italic=False)
add_paragraph("Dipakai untuk apa di proyek: Memetakan rute 28,5 km pesisir Palu–Donggala dengan 1.269 koordinat jalan aspal, menampilkan marker halte, dan memperbarui animasi posisi 5 armada secara real-time.", italic=False)
add_paragraph("Fungsi atau perintah spesifik yang dipakai:", italic=False)
add_code_block("L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);")
add_paragraph("Alasan memilih dan alternatifnya: Dipilih dibanding Google Maps API karena Leaflet tidak memerlukan kartu kredit untuk API key berbayar dan bebas dari watermark batasan kuota.", italic=False)

add_paragraph("4. Chart.js (Versi 4.4.1)", bold_prefix="• ")
add_paragraph("Apa itu: Pustaka visualisasi data berbasis elemen HTML5 Canvas yang sangat cepat dan interaktif.", italic=False)
add_paragraph("Dipakai untuk apa di proyek: Menghasilkan 25 grafik analitik pada Dashboard Big Data, meliputi grafik batang horizontal kerusakan mesin, grafik garis tren komuter per jam, dan grafik polar rasio okupansi.", italic=False)
add_paragraph("Fungsi atau perintah spesifik yang dipakai:", italic=False)
add_code_block("Chart.defaults.color = '#475569';\nChart.defaults.borderColor = '#e2e8f0';\nnew Chart(ctx, { type: 'bar', data: { labels: ['TD01', 'TD02', 'TD03', 'TD04', 'TD05'], datasets: [...] } });")
add_paragraph("Alasan memilih dan alternatifnya: Menghasilkan visualisasi yang tajam, ringan, dan mudah dikonfigurasi palet warnanya agar selaras dengan tema terang modern ala CekTrans.", italic=False)

add_paragraph("5. Tailwind CSS (Versi CDN Standalone)", bold_prefix="• ")
add_paragraph("Apa itu: Kerangka kerja CSS berbasis utility-first untuk membangun antarmuka web modern secara cepat tanpa menulis CSS kustom yang panjang.", italic=False)
add_paragraph("Dipakai untuk apa di proyek: Menyusun tata letak grid responsif, kartu KPI, bilah navigasi klaster, dan memastikan kontras rasio warna memenuhi standar aksesibilitas WCAG.", italic=False)
add_paragraph("Fungsi atau perintah spesifik yang dipakai:", italic=False)
add_code_block('<div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs hover:border-sky-300">')
add_paragraph("Alasan memilih dan alternatifnya: Jauh lebih fleksibel daripada Bootstrap karena tidak memaksakan komponen baku dan memungkinkan penyesuaian detail warna pastel yang spesifik.", italic=False)

add_heading_2("4.3 Model dan Dataset yang Digunakan")
p_t6 = doc.add_paragraph()
p_t6.paragraph_format.space_after = Pt(2)
r_t6 = p_t6.add_run("Tabel 6. Model dan dataset yang digunakan")
r_t6.font.bold = True
r_t6.font.size = Pt(11)

t6 = doc.add_table(rows=4, cols=4)
t6.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t6)

t6_headers = ["Komponen", "Nama dan Sumber", "Ukuran / Spesifikasi", "Fungsi di Proyek"]
for c_idx, h_text in enumerate(t6_headers):
    cell = t6.rows[0].cells[c_idx]
    set_cell_margins(cell)
    set_cell_shading(cell, "E2E8F0")
    p = cell.paragraphs[0]
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(h_text)
    r.font.bold = True
    r.font.size = Pt(10)

t6_rows = [
    ("Dataset Utama", "tb_log_historis_trans_donggala.csv (Rekap telemetri dan log armada)", "1.800 baris data, 30 hari, format CSV / JSON (~240 KB)", "Menyediakan data dasar analitik keterlambatan, reliabilitas, dan dinamika komuter 5 bus."),
    ("Data Pendukung", "Koordinat Jalur Pesisir & 14 Halte Simetris (Ground-truth survey)", "14 waypoint halte, 1.269 koordinat jalan aspal K1", "Membangun garis trayek spasial akurat dan menghitung jarak antarhalte."),
    ("Model Analitik", "Regresi Linier Polinomial (NumPy polyfit) & Formula Dynamic Headway", "Derajat 1 (linier) dengan moving window 7 hari", "Memprediksi tren volume penumpang mingguan dan menghitung headway adaptif.")
]

for row_idx, r_data in enumerate(t6_rows, start=1):
    row = t6.rows[row_idx]
    row.cells[0].width = Inches(1.3)
    row.cells[1].width = Inches(2.2)
    row.cells[2].width = Inches(1.5)
    row.cells[3].width = Inches(1.5)
    for c_idx, val in enumerate(r_data):
        cell = row.cells[c_idx]
        set_cell_margins(cell)
        p = cell.paragraphs[0]
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(val)
        r.font.size = Pt(9.5)

add_heading_2("4.4 Spesifikasi Lingkungan Kerja")
add_paragraph(
    "Pengembangan dan pengujian proyek dilakukan sepenuhnya pada satu unit laptop pribadi dengan spesifikasi perangkat keras: "
    "Prosesor Intel Core i5 Generasi ke-11 (4 Cores, 8 Threads), RAM 8 GB DDR4, dan media penyimpanan Solid State Drive (SSD) 512 GB NVMe. "
    "Sistem operasi yang digunakan adalah Microsoft Windows 11 Home 64-bit. Lingkungan eksekusi server web menggunakan Apache 2.4.58 "
    "dan PHP 8.2.12 yang terpasang melalui paket XAMPP. Skrip kalkulasi agregasi data 1.800 baris dieksekusi secara lokal "
    "dengan waktu pemrosesan rata-rata 0,42 detik, membuktikan efisiensi komputasi yang tinggi tanpa membebani memori kerja laptop."
)

add_heading_2("4.5 Penggunaan Alat Bantu AI")
add_paragraph(
    "Alat bantu AI yang digunakan dalam proyek ini adalah asisten pemrograman AI (Antigravity berbasis Gemini). "
    "AI dimanfaatkan secara spesifik untuk tiga hal: pertama, menghasilkan template kerangka konfigurasi JSON Chart.js untuk 25 jenis diagram; "
    "kedua, membantu merumuskan ekspresi reguler (regex) dan skrip Python pengganti string pada perbaikan layout web; "
    "dan ketiga, mendiskusikan pendekatan formula antrean transportasi perkotaan. "
    "Setiap potongan kode yang dihasilkan AI tidak pernah langsung diterima mentah-mentah, melainkan selalu diverifikasi fungsinya melalui linter "
    "PHP (php -l), diperiksa keterbacaan kontras warnanya di browser peramban, dan disinkronkan logikanya dengan data ground-truth lapangan."
)

# ==========================================
# 5. KESULITAN YANG DIHADAPI
# ==========================================
add_heading_1("5. Kesulitan yang Dihadapi")
add_paragraph(
    "Selama proses pengerjaan proyek dari tahap perencanaan hingga implementasi, saya menghadapi sejumlah kendala teknis dan analitis nyata. "
    "Empat kesulitan utama dan strategi penyelesaiannya disajikan pada Tabel 7."
)

p_t7 = doc.add_paragraph()
p_t7.paragraph_format.space_after = Pt(2)
r_t7 = p_t7.add_run("Tabel 7. Kesulitan dan penyelesaiannya")
r_t7.font.bold = True
r_t7.font.size = Pt(11)

t7 = doc.add_table(rows=5, cols=5)
t7.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t7)

t7_headers = ["No", "Kesulitan", "Jenis", "Penyebab", "Cara Mengatasi dan Pelajaran"]
for c_idx, h_text in enumerate(t7_headers):
    cell = t7.rows[0].cells[c_idx]
    set_cell_margins(cell)
    set_cell_shading(cell, "E2E8F0")
    p = cell.paragraphs[0]
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(h_text)
    r.font.bold = True
    r.font.size = Pt(10)

t7_rows = [
    ("1", "Watermark API Key pada Peta", "Teknis", "Tile server CARTO yang dipakai di awal memberlakukan kebijakan API Key berbayar sehingga memunculkan watermark teks besar.", "Mengganti sumber tile ke OpenStreetMap standar (tile.openstreetmap.org) yang bebas watermark dan stabil."),
    ("2", "Inkonsistensi Status Armada & Headway", "Data & Logika", "Data awal sempat mencatat 5 bus aktif dengan headway 5 menit, bertentangan dengan kondisi riil TD 03 yang rusak.", "Membangun Single Source of Truth terpusat pada basis data dan mengkalibrasi rumus headway K1 menjadi 6,5 menit (4 bus aktif)."),
    ("3", "Kalibrasi 1.800 Baris Data Log Historis", "Analitis", "Sulit menyelaraskan simulasi log per jam selama 30 hari agar agregat penumpangnya presisi 65.960 dan rasio mogok TD 03 tepat 38,7%.", "Menyusun algoritma penskalaan berbobot probabilitas pada skrip Python pandas/numpy sebelum diekspor ke basis data."),
    ("4", "Keterbacaan dan Kontras Tampilan", "Antarmuka", "Tema gelap pekat sebelumnya membuat teks label dan nama halte tampak pudar serta menyatu dengan latar belakang.", "Merancang ulang antarmuka ke tema terang modern ala CekTrans dengan teks kontras tinggi (slate-900) dan kartu warna pastel.")
]

for row_idx, r_data in enumerate(t7_rows, start=1):
    row = t7.rows[row_idx]
    row.cells[0].width = Inches(0.4)
    row.cells[1].width = Inches(1.5)
    row.cells[2].width = Inches(0.8)
    row.cells[3].width = Inches(1.8)
    row.cells[4].width = Inches(2.0)
    for c_idx, val in enumerate(r_data):
        cell = row.cells[c_idx]
        set_cell_margins(cell)
        p = cell.paragraphs[0]
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(val)
        r.font.size = Pt(9.5)

add_heading_2("5.2 Kesulitan Terbesar")
add_paragraph(
    "Kesulitan terbesar yang paling menguras waktu dan konsentrasi saya adalah menyelaraskan integritas data operasional "
    "(Single Source of Truth) antara modul Kelola Armada, kartu indikator KPI, visualisasi 25 grafik, dan simulator keputusan DSS. "
    "Pada tahap awal pengembangan, ketika armada TD 03 diubah statusnya menjadi rusak di formulir kontrol armada, badge peta berhasil berubah "
    "menjadi merah, tetapi kartu ringkasan di atas peta masih menampilkan '5 Aktif | 0 Rusak', dan simulator DSS masih menghitung dengan asumsi "
    "5 bus melaju (headway 5,3 menit). Hal ini menimbulkan cacat logika yang sangat fatal bagi sebuah sistem pendukung keputusan."
)
add_paragraph(
    "Setelah mencoba memperbaiki secara parsial dan sempat mengalami kegagalan sinkronisasi, saya memutuskan merombak total alur data. "
    "Saya memusatkan seluruh perhitungan metrik operasional ke dalam satu fungsi pengendali terpadu di berkas JavaScript. "
    "Fungsi ini membaca status aktual dari respons JSON basis data, menghitung ulang waktu tempuh dan headway secara real-time, "
    "lalu memperbarui seluruh elemen DOM tampilan secara serentak. Melalui pengalaman ini, saya memahami bahwa dalam arsitektur Big Data, "
    "konsistensi mutasi data antar-layer jauh lebih penting daripada sekadar menampilkan grafik yang menarik."
)

add_heading_2("5.3 Yang Akan Saya Lakukan Berbeda")
add_paragraph(
    "Jika saya diberi kesempatan mengulang proyek ini dari awal, saya akan menetapkan spesifikasi desain antarmuka dan palet warna "
    "sejak awal sebelum menulis kode frontend. Pada proyek ini, saya menghabiskan cukup banyak waktu merombak tema dari dark mode ke light mode "
    "karena di awal saya tidak melakukan uji keterbacaan pada kondisi pencahayaan ruangan kelas. Selain itu, saya akan menyusun rancangan "
    "skema API secara modular menggunakan pendekatan API-First agar pengujian modul backend dan visualisasi grafik dapat dilakukan secara paralel."
)

# ==========================================
# 6. SARAN UNTUK MATA KULIAH
# ==========================================
add_heading_1("6. Saran untuk Mata Kuliah")
add_paragraph(
    "Mata kuliah Big Data Analysis di Program Studi Sistem Informasi UIN Datokarama Palu ini sangat berbobot dan membuka wawasan mahasiswa "
    "mengenai pemanfaatan data skala besar untuk menyelesaikan masalah nyata di daerah Sulawesi Tengah. Pembelajaran berbasis proyek (Project-Based Learning) "
    "seperti ini sangat efektif karena memaksa mahasiswa tidak hanya memahami teori di kelas, tetapi juga merasakan kendala teknis penyiapan dataset riil. "
    "Sebagai saran perbaikan, alokasi waktu asistensi dan review teknis kode di laboratorium sebaiknya ditambah, sehingga mahasiswa dapat "
    "berkonsultasi langsung mengenai optimasi kueri atau pembersihan data anomali sebelum batas akhir pengumpulan proyek."
)

add_heading_2("6.1 Topik yang Ingin Ditambahkan")
add_paragraph(
    "Saya menyarankan agar materi perkuliahan di masa mendatang dapat menambahkan topik praktikum mengenai pengenalan Message Broker "
    "seperti Apache Kafka atau RabbitMQ untuk pemrosesan data aliran (data streaming), serta pengenalan dasar arsitektur Lakehouse. "
    "Pemahaman mengenai integrasi data geospasial real-time dan sensor IoT juga sangat relevan dengan kebutuhan industri smart-city saat ini."
)

add_heading_2("6.2 Pesan untuk Mahasiswa Angkatan Berikutnya")
add_paragraph(
    "Bagi rekan-rekan mahasiswa angkatan berikutnya yang akan mengambil mata kuliah ini, mulailah menentukan topik dan mencari data lapangan "
    "sejak awal semester. Jangan menunggu hingga mendekati batas waktu proyek. Kendala terbesar dalam proyek Big Data bukanlah saat membuat antarmuka web, "
    "melainkan saat mengumpulkan, memvalidasi, dan membersihkan data agar memiliki integritas yang dapat dipertanggungjawabkan secara logis."
)

# ==========================================
# 7. NILAI YANG DIHARAPKAN DAN ALASANNYA
# ==========================================
add_heading_1("7. Nilai yang Diharapkan dan Alasannya")
add_paragraph("Nilai yang saya harapkan: A", bold_prefix="")

add_heading_2("7.1 Alasan dan Penilaian Diri")
add_paragraph(
    "Harapan memperoleh nilai A didasarkan pada komitmen kerja mandiri, kedalaman eksplorasi teknis, serta kesesuaian hasil luaran "
    "dengan seluruh kriteria penilaian tugas akhir. Rincian penilaian diri secara jujur dan objektif disajikan pada Tabel 8."
)

p_t8 = doc.add_paragraph()
p_t8.paragraph_format.space_after = Pt(2)
r_t8 = p_t8.add_run("Tabel 8. Penilaian diri")
r_t8.font.bold = True
r_t8.font.size = Pt(11)

t8 = doc.add_table(rows=7, cols=3)
t8.alignment = WD_TABLE_ALIGNMENT.CENTER
set_table_borders(t8)

t8_headers = ["Kriteria", "Penilaian Diri (1–5)", "Alasan dan Bukti Nyata"]
for c_idx, h_text in enumerate(t8_headers):
    cell = t8.rows[0].cells[c_idx]
    set_cell_margins(cell)
    set_cell_shading(cell, "E2E8F0")
    p = cell.paragraphs[0]
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(h_text)
    r.font.bold = True
    r.font.size = Pt(10)

t8_rows = [
    ("Kontribusi dan peran saya di proyek", "5", "Mengerjakan proyek secara mandiri dari survei rute pesisir, perancangan skema database, skrip analitik, hingga pengkodean antarmuka web monitoring."),
    ("Pemahaman konsep big data", "4.8", "Mampu mengimplementasikan konsep 5V, agregasi data historis 1.800 log per jam, pemodelan regresi tren linier, dan rumus dynamic headway transportasi perkotaan."),
    ("Kualitas hasil proyek", "5", "Sistem beroperasi penuh tanpa error (zero syntax error), menghasilkan 25 chart analitik dalam 5 klaster simetris, peta interaktif OSM tanpa watermark, serta simulator DSS fungsional."),
    ("Usaha dan ketekunan menghadapi kesulitan", "5", "Mampu menyelesaikan 4 kendala teknis berat: penghapusan watermark peta, sinkronisasi Single Source of Truth, kalibrasi agregat 65.960 penumpang, dan perbaikan kontras UI."),
    ("Kehadiran dan keaktifan di kelas", "4.8", "Mengikuti seluruh rangkaian perkuliahan Big Data Analysis secara tertib, menyimak materi teoritis, dan aktif mengonfirmasi parameter proyek kepada dosen pengampu."),
    ("Ketepatan waktu dan kelengkapan pengumpulan", "5", "Menyelesaikan seluruh modul perangkat lunak, basis data terkalibrasi, repositori kode, dan dokumen laporan refleksi secara lengkap sesuai format sebelum batas waktu pengumpulan.")
]

for row_idx, r_data in enumerate(t8_rows, start=1):
    row = t8.rows[row_idx]
    row.cells[0].width = Inches(2.2)
    row.cells[1].width = Inches(1.1)
    row.cells[2].width = Inches(3.2)
    for c_idx, val in enumerate(r_data):
        cell = row.cells[c_idx]
        set_cell_margins(cell)
        p = cell.paragraphs[0]
        p.paragraph_format.line_spacing = 1.0
        p.paragraph_format.space_after = Pt(2)
        r = p.add_run(val)
        r.font.size = Pt(9.5)

add_heading_2("7.2 Hal yang Masih Kurang")
add_paragraph(
    "Secara jujur, aspek yang masih kurang dan belum sempurna dari proyek saya ini adalah belum tersedianya modul pelaporan "
    "yang dapat mengekspor rekap analitik ke format PDF secara otomatis dalam satu klik, serta simulasi pergerakan marker bus di peta "
    "masih menggunakan interpolasi koordinat rute statis, belum mengintegrasikan data GPS live streaming langsung dari perangkat keras IoT di dalam bus."
)

add_heading_2("7.3 Pernyataan Keaslian")
add_paragraph(
    "Saya menyatakan dengan sebenar-benarnya bahwa isi laporan ini merupakan hasil refleksi pengalaman belajar, perancangan, dan pekerjaan nyata "
    "yang saya lakukan secara mandiri. Seluruh rujukan teknologi, dataset terkalibrasi, dan bantuan alat AI telah saya jelaskan secara jujur dan transparan "
    "pada laporan ini sesuai etika akademik civitas akademika Universitas Islam Negeri Datokarama Palu."
)

p_sign = doc.add_paragraph()
p_sign.paragraph_format.space_before = Pt(30)
p_sign.paragraph_format.line_spacing = 1.3
p_sign.alignment = WD_ALIGN_PARAGRAPH.RIGHT

r_sign = p_sign.add_run(
    "Palu, 24 November 2026\n"
    "Yang Menyatakan,\n\n\n\n\n"
    "Rahmat Ilham\n"
    "NIM: [ISI NIM ANDA]"
)
r_sign.font.name = 'Times New Roman'
r_sign.font.size = Pt(12)
r_sign.font.bold = True

output_path = "D:\\semester 5\\BIG DATA\\Trans Donggala\\Laporan_Pengalaman_Belajar_Big_Data_Rahmat_Ilham.docx"
doc.save(output_path)
print("SUCCESS: File generated at", output_path)
