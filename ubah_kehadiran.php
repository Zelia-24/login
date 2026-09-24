<?php
require_once '../database/koneksi.php';

// Cek apakah tombol submit diklik
if (isset($_POST['btn_edit'])) {

    $id_presensi      = trim(mysqli_real_escape_string($con, $_POST['id_presensi']));
    $id_pertemuan     = trim(mysqli_real_escape_string($con, $_POST['id_pertemuan']));
    $nim              = trim(mysqli_real_escape_string($con, $_POST['nim']));
    $status_kehadiran = trim(mysqli_real_escape_string($con, $_POST['status_kehadiran']));

    // Jika id_presensi sudah ada, lakukan UPDATE
    if (!empty($id_presensi)) {
        $query_edit_status = mysqli_query($con, "UPDATE tbl_presensi SET status_kehadiran = '$status_kehadiran' WHERE id_presensi = '$id_presensi'") or die(mysqli_error($con));
    } 
    // Jika id_presensi masih kosong (baru pertama kali diubah), lakukan INSERT
    else {
        $query_edit_status = mysqli_query($con, "INSERT INTO tbl_presensi (id_pertemuan, nim, status_kehadiran) VALUES ('$id_pertemuan', '$nim', '$status_kehadiran')") or die(mysqli_error($con));
    }

    if ($query_edit_status) {
        echo "<script>alert('Status Kehadiran Berhasil Diubah!'); window.location='presensi.php?id_pertemuan=$id_pertemuan';</script>";
    } else {
        echo "<script>alert('Gagal Mengubah Status!'); window.location='presensi.php?id_pertemuan=$id_pertemuan';</script>";
    }

} else {
    // Jika diakses langsung tanpa lewat form modal, kembalikan ke halaman sebelumnya
    echo "<script>window.history.back();</script>";
    exit();
}
?>