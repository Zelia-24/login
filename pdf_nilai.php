<?php
require_once '../database/koneksi.php';
require('../asetweb/fpdf/fpdf.php');

// Tangkap kode_kelas atau id_kelas dari URL
$kode_kelas = $_GET['kode_kelas'] ?? $_GET['id_kelas'] ?? '';

// Jika kosong, ambil kode_kelas pertama secara otomatis
if (empty($kode_kelas)) {
    $q_kls_def  = mysqli_query($con, "SELECT kode_kelas FROM tbl_kelas LIMIT 1");
    $d_kls_def  = mysqli_fetch_assoc($q_kls_def);
    $kode_kelas = $d_kls_def['kode_kelas'] ?? '';
}

// 1. Ambil Data Kelas, Matkul, Dosen, dan Jurusan (Tanpa JOIN)
$nama_kelas   = '-';
$kode_matkul  = '-';
$nama_matkul  = '-';
$nama_jurusan = '-';
$kode_akd     = '-';
$nama_periode = '-';
$nik_dosen    = '-';
$nama_dosen   = '-';

if (!empty($kode_kelas)) {
    $q_kelas = mysqli_query($con, "SELECT * FROM tbl_kelas_matkul WHERE kode_kelas = '$kode_kelas'");
    if ($q_kelas && mysqli_num_rows($q_kelas) > 0) {
        $d_kelas     = mysqli_fetch_assoc($q_kelas);
        
        $nama_kelas  = $d_kelas['nama_kelas'] ?? '-';
        $kode_matkul = $d_kelas['kode_matkul'] ?? '-';
        $nama_matkul = $d_kelas['nama_matkul'] ?? $nama_kelas;
        $kode_akd    = $d_kelas['kode_akd'] ?? '';
        $nik_dosen   = $d_kelas['nik'] ?? '';

        // --- BACA JURUSAN LANGSUNG DARI DATABASE ---
        // Cek apakah ada kolom jurusan/prodi langsung di tbl_kelas_matkul
        $jurusan_raw = $d_kelas['jurusan'] ?? $d_kelas['nama_jurusan'] ?? $d_kelas['prodi'] ?? $d_kelas['nama_prodi'] ?? $d_kelas['kode_jurusan'] ?? $d_kelas['kode_prodi'] ?? '';

        if (!empty($jurusan_raw)) {
            // Jika yang tersimpan di tbl_kelas_matkul berupa kode, cari nama jurusannya di tbl_jurusan / tbl_prodi
            $q_jur = mysqli_query($con, "SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$jurusan_raw' OR nama_jurusan = '$jurusan_raw'");
            if ($q_jur && mysqli_num_rows($q_jur) > 0) {
                $d_jur = mysqli_fetch_assoc($q_jur);
                $nama_jurusan = $d_jur['nama_jurusan'];
            } else {
                // Cek ke tbl_prodi jika tbl_jurusan tidak ditemukan
                $q_prd = mysqli_query($con, "SELECT nama_prodi FROM tbl_prodi WHERE kode_prodi = '$jurusan_raw' OR id_prodi = '$jurusan_raw'");
                if ($q_prd && mysqli_num_rows($q_prd) > 0) {
                    $d_prd = mysqli_fetch_assoc($q_prd);
                    $nama_jurusan = $d_prd['nama_prodi'];
                } else {
                    // Jika teks langsung (contoh: "Manajemen")
                    $nama_jurusan = $jurusan_raw;
                }
            }
        }

        // Ambil Nama Matkul dari tbl_matkul (Tanpa JOIN)
        if (!empty($kode_matkul)) {
            $q_mk = mysqli_query($con, "SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'");
            if ($q_mk && mysqli_num_rows($q_mk) > 0) {
                $d_mk = mysqli_fetch_assoc($q_mk);
                if (!empty($d_mk['nama_matkul'])) {
                    $nama_matkul = $d_mk['nama_matkul'];
                }
            }
        }

        // Ambil Data Periode Akademik (Tanpa JOIN)
        if (!empty($kode_akd)) {
            $q_akd = mysqli_query($con, "SELECT tahun, semester FROM tbl_akademik WHERE kode_akd = '$kode_akd'");
            if ($q_akd && mysqli_num_rows($q_akd) > 0) {
                $d_akd   = mysqli_fetch_assoc($q_akd);
                $tahun   = $d_akd['tahun'] ?? '';
                $sem_raw = strtoupper(trim($d_akd['semester'] ?? ''));

                if ($sem_raw == 'GL' || $sem_raw == 'GANJIL') {
                    $sem_teks = 'Ganjil';
                } elseif ($sem_raw == 'GN' || $sem_raw == 'GENAP') {
                    $sem_teks = 'Genap';
                } else {
                    $sem_teks = $sem_raw;
                }

                $nama_periode = $tahun . ' - ' . $sem_teks;
            } else {
                $nama_periode = $kode_akd;
            }
        }

        // Ambil Nama Dosen (Tanpa JOIN)
        if (!empty($nik_dosen)) {
            $q_dosen = mysqli_query($con, "SELECT nama FROM tbl_dosen WHERE nik = '$nik_dosen'");
            if ($q_dosen && mysqli_num_rows($q_dosen) > 0) {
                $d_dosen    = mysqli_fetch_assoc($q_dosen);
                $nama_dosen = $d_dosen['nama'];
            }
        }
    }
}

// 2. Hitung Total Pertemuan Kelas Ini
$total_pertemuan = 0;
$q_tot_ptm = mysqli_query($con, "SELECT COUNT(*) as total FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas'");
if ($q_tot_ptm) {
    $d_tot_ptm       = mysqli_fetch_assoc($q_tot_ptm);
    $total_pertemuan = (int)$d_tot_ptm['total'];
}

class PDF extends FPDF
{
    function Header()
    {
        $logo_path = __DIR__ . '/../asetweb/img/Logo.png';
        if (file_exists($logo_path)) {
            $this->Image($logo_path, 10, 15, 35);
        }

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(80);
        $this->Cell(30, 7, 'Fakultas Sains dan Teknologi', 0, 2, 'C');
        $this->Cell(30, 7, 'Prodi Informatika', 0, 2, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 5, 'Alamat : Jalan Raya Pagojengan KM 3, Kecamatan Paguyangan,', 0, 2, 'C');
        $this->Cell(30, 5, 'Kabupaten Brebes, Jawa Tengah 52276', 0, 1, 'C');
        $this->SetLineWidth(1);
        $this->Line(10, 37, 200, 37);
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Judul Utama
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 7, 'REKAPITULASI NILAI MAHASISWA', 0, 1, 'C');
$pdf->Ln(3);

// Informasi Header (Menampilkan Jurusan Asli dari DB)
$pdf->SetFont('Times', '', 10);

$pdf->Cell(35, 6, 'Mata Kuliah', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(60, 6, $nama_matkul . ' ', 0, 0, 'L');

$pdf->Cell(30, 6, 'Jurusan', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_jurusan, 0, 1, 'L');

$pdf->Cell(35, 6, 'Kelas', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(60, 6, $nama_kelas, 0, 0, 'L');

$pdf->Cell(30, 6, 'Periode Akademik', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_periode, 0, 1, 'L');

$pdf->Cell(35, 6, 'Dosen Pengampu', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(60, 6, $nama_dosen, 0, 0, 'L');

$pdf->Cell(30, 6, 'Total Pertemuan', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $total_pertemuan . ' Pertemuan', 0, 1, 'L');
$pdf->Ln(5);

// Header Tabel Sesuai Format Rekap
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(10, 8, 'No', 1, 0, 'C');
$pdf->Cell(30, 8, 'NIM', 1, 0, 'C');
$pdf->Cell(65, 8, 'Nama Mahasiswa', 1, 0, 'C');
$pdf->Cell(12, 8, 'Hadir', 1, 0, 'C');
$pdf->Cell(12, 8, 'Izin', 1, 0, 'C');
$pdf->Cell(12, 8, 'Sakit', 1, 0, 'C');
$pdf->Cell(12, 8, 'alfa', 1, 0, 'C');
$pdf->Cell(23, 8, 'Kehadiran (%)', 1, 0, 'C');
$pdf->Cell(14, 8, 'Nilai', 1, 1, 'C');

// 3. Ambil Daftar Mahasiswa (Menggunakan WHERE AND, Tanpa JOIN)
$q_mhs = mysqli_query($con, "SELECT DISTINCT m.nim, m.nama 
                             FROM tbl_presensi p, tbl_mahasiswa m, tbl_pertemuan pt 
                             WHERE TRIM(p.nim) = TRIM(m.nim) 
                             AND p.id_pertemuan = pt.id_pertemuan 
                             AND pt.kode_kelas = '$kode_kelas' 
                             ORDER BY m.nim ASC");

$pdf->SetFont('Times', '', 9);

if ($q_mhs && mysqli_num_rows($q_mhs) > 0) {
    $no = 1;
    while ($mhs = mysqli_fetch_array($q_mhs)) {
        $nim_clean = trim($mhs['nim']);
        $nama      = $mhs['nama'];

        // Hitung Kehadiran yang tercatat
        $q_count = mysqli_query($con, "SELECT 
            SUM(CASE WHEN LOWER(TRIM(p.status_kehadiran)) = 'hadir' THEN 1 ELSE 0 END) as total_hadir,
            SUM(CASE WHEN LOWER(TRIM(p.status_kehadiran)) = 'izin' THEN 1 ELSE 0 END) as total_izin,
            SUM(CASE WHEN LOWER(TRIM(p.status_kehadiran)) = 'sakit' THEN 1 ELSE 0 END) as total_sakit
            FROM tbl_presensi p, tbl_pertemuan pt 
            WHERE p.id_pertemuan = pt.id_pertemuan 
            AND pt.kode_kelas = '$kode_kelas' 
            AND TRIM(p.nim) = '$nim_clean'");

        $row_count = mysqli_fetch_assoc($q_count);

        $hadir = (int)($row_count['total_hadir'] ?? 0);
        $izin  = (int)($row_count['total_izin'] ?? 0);
        $sakit = (int)($row_count['total_sakit'] ?? 0);
        
        $alfa = $total_pertemuan - ($hadir + $izin + $sakit);
        if ($alfa < 0) { $alfa = 0; }

        // Murni menghitung persentase dan nilai (20%) hanya dari total "hadir"
        if ($total_pertemuan > 0) {
            $persentase = ($hadir / $total_pertemuan) * 100;
            $nilai_20p  = $persentase * 0.20;
        } else {
            $persentase = 0;
            $nilai_20p  = 0;
        }

        // Render Baris Tabel
        $pdf->Cell(10, 6, $no++, 1, 0, 'C');
        $pdf->Cell(30, 6, $nim_clean, 1, 0, 'C');
        $pdf->Cell(65, 6, $nama, 1, 0, 'L');
        $pdf->Cell(12, 6, $hadir, 1, 0, 'C');
        $pdf->Cell(12, 6, $izin, 1, 0, 'C');
        $pdf->Cell(12, 6, $sakit, 1, 0, 'C');
        $pdf->Cell(12, 6, $alfa, 1, 0, 'C');
        $pdf->Cell(23, 6, number_format($persentase, 1) . '%', 1, 0, 'C');
        $pdf->Cell(14, 6, number_format($nilai_20p, 2), 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 7, 'Belum ada data presensi mahasiswa untuk kelas ini.', 1, 1, 'C');
}

$pdf->Output();
?>