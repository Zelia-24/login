<?php
require_once '../database/koneksi.php';

if (isset($_GET['id_pertemuan'])) {
    $id_pertemuan = mysqli_real_escape_string($con, $_GET['id_pertemuan']);

    // 1. Ambil status saat ini dari database
    $query_cek = mysqli_query($con, "SELECT status FROM tbl_pertemuan WHERE id_pertemuan = '$id_pertemuan'") or die(mysqli_error($con));
    $data      = mysqli_fetch_array($query_cek);

    if ($data) {
        $status_sekarang = $data['status'];

        // 2. Jika status 0 (tutup), saat dipencet diubah jadi 1 (buka)
        if ($status_sekarang == '0') {
            $status_baru = '1';
            $pesan = "Presensi berhasil DIBUKA!";
        } 
        // Jika status 1 (buka), saat dipencet diubah jadi 0 (tutup)
        else {
            $status_baru = '0';
            $pesan = "Presensi berhasil DITUTUP!";
        }

        // 3. Simpan status baru ke database
        $update = mysqli_query($con, "UPDATE tbl_pertemuan SET status = '$status_baru' WHERE id_pertemuan = '$id_pertemuan'") or die(mysqli_error($con));

        if ($update) {
            echo "<script>
                alert('$pesan');
                window.location.href='presensi.php?id_pertemuan=$id_pertemuan';
            </script>";
        } else {
            echo "<script>
                alert('Gagal mengubah status presensi!');
                window.location.href='presensi.php?id_pertemuan=$id_pertemuan';
            </script>";
        }
    } else {
        echo "<script>
            alert('Data pertemuan tidak ditemukan!');
            window.location.href='presensi.php?id_pertemuan=$id_pertemuan';
        </script>";
    }
} else {
    echo "<script>window.history.back();</script>";
    exit();
}
?>