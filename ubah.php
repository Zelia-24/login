<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])) {

    $username      = trim(mysqli_real_escape_string($con, $_POST['nim']) );
    $nama          = trim(mysqli_real_escape_string($con, $_POST['nama']) );
    $kontak        = trim(mysqli_real_escape_string($con, $_POST['kontak']) );
    $email         = trim(mysqli_real_escape_string($con, $_POST['email']) );
    $jenis_kelamin = trim(mysqli_real_escape_string($con, $_POST['jenis_kelamin']) );

    // Pengecekan jika tipe data jenis_kelamin di database disimpan sebagai singkatan (L/P)
    if ($jenis_kelamin == 'Laki-laki' || $jenis_kelamin == 'L') {
        $jenis_kelamin = 'L';
    } else if ($jenis_kelamin == 'Perempuan' || $jenis_kelamin == 'P') {
        $jenis_kelamin = 'P';
    }

    // Mengubah query ke tabel tbl_mahasiswa dan meng-update kolom yang sesuai
    $query_edit = mysqli_query($con, "UPDATE tbl_mahasiswa SET
    nama = '$nama',
    kontak = '$kontak',
    email = '$email',
    jenis_kelamin = '$jenis_kelamin' 
    WHERE nim = '$username'") or die(mysqli_error($con));

    echo '<script> alert ("Data Berhasil Diedit");
    window.location.href = "../data_mahasiswa";</script>';
    
}
?>