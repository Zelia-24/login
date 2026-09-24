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

// 1. Ambil Data Kelas Berdasarkan kode_kelas (Tanpa JOIN)
$nama_kelas   = '-';
$kode_matkul  = '-';
$kode_akd     = '-';
$nama_periode = '-';
$nik_dosen    = '-';
$nama_dosen   = '-';

if (!empty($kode_kelas)) {
    $q_kelas = mysqli_query($con, "SELECT * FROM tbl_kelas_matkul WHERE kode_kelas = '$kode_kelas'");
    if ($q_kelas && mysqli_num_rows($q_kelas) > 0) {
        $d_kelas     = mysqli_fetch_assoc($q_kelas);
        $nama_kelas  = $d_kelas['nama_kelas'] ?? $d_kelas['nama_matkul'] ?? '-';
        $kode_matkul = $d_kelas['kode_matkul'] ?? '-';
        $kode_akd    = $d_kelas['kode_akd'] ?? '-';
        $nik_dosen   = $d_kelas['nik'] ?? '';

        // Ambil Data Periode Akademik dari tbl_akademik (Tanpa JOIN)
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

        // Ambil Nama Dosen dari tbl_dosen (Tanpa JOIN)
        if (!empty($nik_dosen)) {
            $q_dosen = mysqli_query($con, "SELECT nama FROM tbl_dosen WHERE nik = '$nik_dosen'");
            if ($q_dosen && mysqli_num_rows($q_dosen) > 0) {
                $d_dosen    = mysqli_fetch_assoc($q_dosen);
                $nama_dosen = $d_dosen['nama'];
            }
        }
    }
}

class PDF extends FPDF
{
    function Header()
    {
        $logo_path = __DIR__ . '/../asetweb/img/Logo.png';
        if (file_exists($logo_path)) {
            $this->Image($logo_path, 10, 15, 40);
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
$pdf->Cell(0, 7, 'LAPORAN REKAP PERTEMUAN KELAS', 0, 1, 'C');
$pdf->Ln(3);

// Informasi Header
$pdf->SetFont('Times', '', 10);
$pdf->Cell(35, 6, 'Kelas ', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(60, 6, $nama_kelas .'', 0, 0, 'L');

$pdf->Cell(30, 6, 'Periode Akademik', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(55, 6, $nama_periode, 0, 1, 'L');

$pdf->Cell(35, 6, 'Dosen Pengampu', 0, 0, 'L');
$pdf->Cell(5, 6, ':', 0, 0, 'C');
$pdf->Cell(60, 6, $nama_dosen . '', 0, 1, 'L');
$pdf->Ln(5);

// 2. Ambil Semua Pertemuan Berdasarkan Kelas (Tanpa JOIN)
$q_ptm = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas' ORDER BY pertemuan_ke ASC, id_pertemuan ASC") or die(mysqli_error($con));

if (mysqli_num_rows($q_ptm) > 0) {
    while ($ptm = mysqli_fetch_array($q_ptm)) {
        $id_ptm       = $ptm['id_pertemuan'];
        $pertemuan_ke = $ptm['pertemuanke'] ?? $ptm['pertemuan_ke'] ?? $id_ptm;
        
        // Cek nama kolom tanggal di database
        $tgl_raw = $ptm['tgl_pertemuan'] ?? $ptm['tanggal'] ?? $ptm['tgl'] ?? '';
        
        if (!empty($tgl_raw) && $tgl_raw != '0000-00-00') {
            $tgl_ptm = date('d-m-Y', strtotime($tgl_raw));
        } else {
            $tgl_ptm = '-';
        }

        // Header Sub-Pertemuan
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(0, 6, 'Pertemuan Ke-' . $pertemuan_ke . ' (' . $tgl_ptm . ')', 0, 1, 'L');

        // Header Tabel
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(15, 7, 'No', 1, 0, 'C');
        $pdf->Cell(45, 7, 'NIM', 1, 0, 'C');
        $pdf->Cell(85, 7, 'Nama Mahasiswa', 1, 0, 'C');
        $pdf->Cell(45, 7, 'Status Kehadiran', 1, 1, 'C');

        // 3. Ambil Mahasiswa & Presensi menggantikan JOIN dengan relasi WHERE AND
        $q_mhs = mysqli_query($con, "SELECT DISTINCT m.nim, m.nama 
                                    FROM tbl_presensi p, tbl_mahasiswa m 
                                    WHERE TRIM(p.nim) = TRIM(m.nim) 
                                    AND p.id_pertemuan = '$id_ptm' 
                                    ORDER BY m.nim ASC");

        $pdf->SetFont('Times', '', 10);
        if ($q_mhs && mysqli_num_rows($q_mhs) > 0) {
            $no = 1;
            while ($mhs = mysqli_fetch_array($q_mhs)) {
                $nim_clean = trim($mhs['nim']);
                $nama      = $mhs['nama'];

                // Ambil status kehadiran mahasiswa pada pertemuan ini
                $q_prs = mysqli_query($con, "SELECT status_kehadiran FROM tbl_presensi WHERE id_pertemuan = '$id_ptm' AND TRIM(nim) = '$nim_clean'");
                
                if ($q_prs && mysqli_num_rows($q_prs) > 0) {
                    $d_prs  = mysqli_fetch_assoc($q_prs);
                    $st_raw = trim($d_prs['status_kehadiran']);
                    $status = !empty($st_raw) ? ucfirst(strtolower($st_raw)) : 'Alfa';
                } else {
                    $status = 'Alfa';
                }

                $pdf->Cell(15, 6, $no++, 1, 0, 'C');
                $pdf->Cell(45, 6, $nim_clean, 1, 0, 'C');
                $pdf->Cell(85, 6, $nama, 1, 0, 'L');
                $pdf->Cell(45, 6, $status, 1, 1, 'C');
            }
        } else {
            $pdf->Cell(190, 6, 'Tidak ada data mahasiswa untuk pertemuan ini.', 1, 1, 'C');
        }

        $pdf->Ln(5);
    }
} else {
    $pdf->SetFont('Times', 'I', 10);
    $pdf->Cell(190, 7, 'Belum ada data pertemuan untuk kelas ini.', 1, 1, 'C');
}

$pdf->Output();
?>