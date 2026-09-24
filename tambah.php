<?php 
require_once'../database/koneksi.php';

if (isset($_POST['btn-tambah'])) {
    $nim = trim(mysqli_real_escape_string($con, $_POST['nim'] ?? ''));
    $nama = trim(mysqli_real_escape_string($con, $_POST['nama'] ?? ''));
    $kontak = trim(mysqli_real_escape_string($con, $_POST['kontak'] ?? ''));
    $email = trim(mysqli_real_escape_string($con, $_POST['email'] ?? ''));
    $jenis_kelamin = trim(mysqli_real_escape_string($con, $_POST['jenis_kelamin'] ?? ''));
    $img = trim(mysqli_real_escape_string($con, $_POST['img'] ?? ''));

        $cek_mahasiswa = mysqli_query($con, "SELECT nim FROM tbl_mahasiswa WHERE nim = '$nim'")or die(mysqli_error($con));

        $rv = mysqli_num_rows($cek_mahasiswa);
        if ($rv > 0) {
        echo '<script> alert ("username sudah terdaftar") </script>';
        }else {
            $cek_nim = mysqli_query($con, "SELECT username FROM tbl_pengguna WHERE username = '$nim' ")or die(mysqli_error($con));
            $rv = mysqli_num_rows($cek_nim);
            if ($rv > 0) {
            echo '<script> alert ("pengguna sudah terdaftar");
            window.location.href = "../data_mahasiswa/"</script>';
            } else {
            $query_simpan_msh = mysqli_query($con, "INSERT INTO tbl_mahasiswa VALUES ('$nim','$nama','$kontak','$email','$jenis_kelamin','$img')")or die(mysqli_error($con));

            $peran = 'M';
            $sandi = sha1($nim);
            $pin = '123456';

            $query_simpan_pengguna = mysqli_query($con, "INSERT INTO tbl_pengguna VALUES (NULL, '$nim','$sandi','$peran','$pin','$nama')")or die(mysqli_error($con)); 

            echo '<script> alert ("data berhasil di simpan");
            window.location.href = "../data_mahasiswa/"</script>';
            }
        }
}

?>

