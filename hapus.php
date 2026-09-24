<html> 
    <head> 
 
    </head> 
    <body> 
 
        <?php  
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once '../database/koneksi.php'; 

        $pengguna_login = $_SESSION['username'] ?? ''; 
        // Mengambil nim (fallback ke user jika parameter dari tempat lain)
        $pengguna = mysqli_real_escape_string($con, $_GET['nim'] ?? $_GET['user'] ?? ''); 

        $cek_admin = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM tbl_mahasiswa WHERE nim = '$pengguna'") or die(mysqli_error($con)); 
        $data = mysqli_fetch_assoc($cek_admin); 
        $jumlah = $data['jumlah']; 
 
        if ($pengguna_login == $pengguna && $jumlah == 1) { 
            echo '<script> alert ("ANDA TIDAK DAPAT MENGHAPUS AKUN DIRI ANDA SENDIRI!!!"); 
            window.location.href="../data_mahasiswa" 
            </script>'; 
 
        } elseif ($pengguna_login != $pengguna && $jumlah == 1) { 
            $hapus_pengguna = mysqli_query($con, "DELETE FROM tbl_mahasiswa WHERE nim = '$pengguna'") or die (mysqli_error($con)); 
            echo '<script> alert ("data mahasiswa '.$pengguna.' berhasil di hapus"); 
            window.location.href="../data_mahasiswa" 
            </script>'; 
 
        } else { 
            echo '<script> alert ("data mahasiswa '.$pengguna.' tidak ditemukan"); 
            window.location.href="../data_mahasiswa" 
            </script>'; 
        } 
        ?> 
    </body> 
</html>