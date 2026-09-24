<html>
    <head>
    </head>
    <body>
       <?php
       session_start();
       require_once '../database/koneksi.php';
      
        $query_reset = mysqli_query($con, "TRUNCATE TABLE tbl_mahasiswa")or die(mysqli_error($con));

        $query_reset_pengguna = mysqli_query($con, "DELETE FROM tbl_pengguna WHERE peran = 'M'")or die(mysqli_error($con));


        echo '<script> alert("Data Mahasiswa '.$pengguna.' beserta akun loginnya Berhasil Dihapus!!!!!");
            window.location.href="../data_mahasiswa";
        </script>';
       ?> 
    </body>
</html>