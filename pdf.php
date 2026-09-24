<?php
require_once('../database/koneksi.php');
require('../asetweb/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../asetweb/img/logo.png', 10, 15, 40);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 14);
        // Move to the right              
        $this->Cell(80);
        // Title
        $this->Cell(30, 7, 'Fakultas Sains Dan Teknologi', 0, 2, 'C');
        $this->Cell(30, 7, 'Prodi Informatika', 0, 2, 'C');
         $this->SetFont('Arial', '', 10, );
        $this->Cell(30, 5, 'Jalan Raya Pagojengan KM.3, kecamatan Paguyangan', 0, 2, 'C');
        $this->Cell(30, 5, 'Kabupaten Brebes, Jawa Tengah, kode pos 52276.', 0, 1, 'C');
        $this->SetLineWidth(1);
        $this->Line(10, 37, 200, 37);
        // Line break
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(80);
$pdf->Cell(30, 7, 'Data Mahasiswa UPB', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Times', '', 10);
$pdf->Cell(12, 7, 'No', 1, 0, 'C');
$pdf->Cell(30, 7, 'Nim', 1, 0, 'C');
$pdf->Cell(53, 7, 'Nama', 1, 0, 'C');
$pdf->Cell(30, 7, 'Kontak', 1, 0, 'C');
$pdf->Cell(35, 7, 'email', 1, 0, 'C');
$pdf->Cell(30, 7, 'jenis kelamin', 1, 1, 'C');

$query_ambil_mahasiswa = mysqli_query($con, "SELECT * FROM tbl_mahasiswa")or die(mysqli_error($con));

$rv = mysqli_num_rows($query_ambil_mahasiswa);
if ($rv . 0) {
    $no = 1;
  while ($data = mysqli_fetch_array($query_ambil_mahasiswa)) {
$pdf->Cell(12, 7, $no++, 1, 0, 'C');
$pdf->Cell(30, 7, $data ['nim'], 1, 0, 'C');
$pdf->Cell(53, 7, $data ['nama'], 1, 0, 'L');
$pdf->Cell(30, 7, $data ['kontak'], 1, 0, 'C');
$pdf->Cell(35, 7, $data ['email'], 1, 0, 'L');
$pdf->Cell(30, 7, ($data ['jenis_kelamin']== 'L') ? 'laki laki' : "perempuan" , 1, 1, 'L');

  }

}
$pdf->Output();
?>