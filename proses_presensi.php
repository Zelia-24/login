<?php
require_once '../database/koneksi.php';

if (isset($_GET['id_pertemuan'])){
    $id_pertemuan = mysqli_real_escape_string($con, $_GET['id_pertemuan']);
    $nim_login    = $_SESSION['username'] ;

    $query_ambil_status_pertemuan = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE id_pertemuan='$id_pertemuan'") or die (mysqli_error($con));
    $data_pertemuan = mysqli_fetch_array($query_ambil_status_pertemuan);
    $status_pertemuan = $data_pertemuan['status'];

    if ($status_pertemuan == 0) {
        echo '<script> alert ("Presensi Telah Di Tutup");
        window.location.href = "../mahasiswa_presensi";
        </script> ';
    } else {
        $query_status_kehadiran = mysqli_query($con, "SELECT status_kehadiran FROM tbl_presensi WHERE id_pertemuan='$id_pertemuan' AND TRIM(nim)='$nim_login'") or die (mysqli_error($con));
        $data_status_kehadiran = mysqli_fetch_array ($query_status_kehadiran);
        $status_kehadiran = strtolower(trim($data_status_kehadiran['status_kehadiran'] ?? ''));

        if ($status_kehadiran == 'hadir') {
            echo '<script> alert ("Anda sudah melakukan presensi");
            window.location.href = "../mahasiswa_presensi";
            </script>';
        } else {

           // Ganti nilai 'hadir' menjadi 'Hadir' (Huruf H Kapital)
                mysqli_query($con, "UPDATE tbl_presensi SET status_kehadiran = 'Hadir' WHERE id_pertemuan = '$id_pertemuan' AND TRIM(nim) = '$nim_login'");
                mysqli_query($con, "INSERT INTO tbl_presensi (id_pertemuan, nim, status_kehadiran) VALUES ('$id_pertemuan', '$nim_login', 'Hadir')");
            echo '<script> alert ("Anda Berhasil Presensi");
            window.location.href = "../mahasiswa_presensi";
            </script>';
        }
    }
}
?>