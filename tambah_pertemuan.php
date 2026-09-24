<?php
session_start();
require_once '../database/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
$authority = @$_SESSION['peran'];
if ($authority != 'A') {
    echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
    echo '<script>window.location.href="../logout.php"</script>';
    exit();
}

// Cek apakah tombol tambah sudah diklik
if (isset($_POST['btn_tambah'])) {
    $kode_kelas      = mysqli_real_escape_string($con, $_POST['kode_kelas']);
    $tgl             = mysqli_real_escape_string($con, $_POST['tgl']);
    $judul_pertemuan = mysqli_real_escape_string($con, $_POST['judul_pertemuan']);
    $pertemuan_ke    = mysqli_real_escape_string($con, $_POST['pertemuan_ke']);
    $status          = mysqli_real_escape_string($con, $_POST['status']);

    $tanggal_sekarang = date('Y-m-d');
    if ($tgl < $tanggal_sekarang) {
        echo "<script>alert('Data pertemuan berhasil ditambahkan!'); window.location='pertemuan.php?kode_kelas=".$kode_kelas."';</script>";
    }

    $pertemuan_ke = 1;
    $panggil_pertemuan = mysqli_query($con, "SELECT max(Pertemuan_ke) as pertemuan FROM tbl_pertemuan WHERE kode_kelas='$kode_kelas'")
    or die (mysqli_error($con));
    $data_pertemuan = mysqli_fetch_array($panggil_pertemuan);
    $pertemuan_terakhir = $data_pertemuan['pertemuan'];

    $status_pertemuan = '1';

    if ($pertemuan_terakhir < 1) {
        $simpan_pertemuan = mysqli_query($con, "INSERT INTO tbl_pertemuan VALUES (null, '$kode_kelas', '$tgl', '$judul_pertemuan', '$status', '$pertemuan_ke') ") or die(mysqli_error($con));
        echo '<script>alert("presensi pertemuan ke '.$pertemuan_ke.' berhasil di buat");
        window.location.href= "../admin_data_kelas_matakuliah/pertemuan.php?id_pertemuan='.$pertemuan_terakhir.'"; </script>';
    } else {
        $pertemuan_ke = $pertemuan_terakhir + 1;
        $simpan_pertemuan = mysqli_query($con, "INSERT INTO tbl_pertemuan VALUES (null, '$kode_kelas', '$tgl', '$judul_pertemuan', '$status', '$pertemuan_ke')") or die(mysqli_error($con));
        $id_pertemuan = mysqli_insert_id($con);
        echo '<script>
        alert("presensi Pertemuan ke '.$pertemuan_ke.' Berhasil Dibuat");
        window.location.href = "../admin_data_kelas_matakuliah/presensi.php?id_pertemuan='.$id_pertemuan.'"; </script>';
    }
}
?>