<?php
require_once'../database/koneksi.php';
require '../asetweb/phpexcel-xls/phpexcel-xls/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
error_reporting(0);
if (isset ($_POST['btn-impor'])) {
    $file =$_FILES['file_excel']['name'];
    $extensi = explode('.', $file);
    
    $nama_file ='file'.round(microtime(true)).'.'.end($extensi);

    $alamat_tujuan ='template/' .$nama_file;

    $file_alamat_sumber =$_FILES['file_excel']['tmp_name'];

    move_uploaded_file($file_alamat_sumber,$alamat_tujuan);

    $file_excel = PHPExcel_IOFactory::load($alamat_tujuan);

    $data_excel = $file_excel-> getActiveSheet()->toArray(null, true, true, true);

    for ($i=2; $i <= count($data_excel) ; $i++) { 
       $nim = $data_excel[$i]['B'];
       $nama = $data_excel[$i]['C'];
       $kontak = $data_excel[$i]['D'];
       $email = $data_excel[$i]['E'];
       $jenis_kelamin = $data_excel[$i]['F'];

       if ($nim == '' || $nama =='' || $kontak =='' || $email =='' || $jenis_kelamin =='') {
        continue;
       }

       $query_cek = mysqli_query($con, "SELECT nim FROM tbl_mahasiswa WHERE nim='$nim'")
       or die(mysqli_error($con));

       if (mysqli_num_rows($query_cek)==0) {
         $query_insert = mysqli_query($con, "INSERT INTO tbl_mahasiswa VALUES ('$nim', '$nama', '$kontak', '$email', '$jenis_kelamin')")or die(mysqli_error($con));

         $username = $nim;
            $sandi = sha1($nim);
            $peran = 'M';
            $pin = 1234;
            $query_pengguna = mysqli_query($con, "INSERT INTO tbl_pengguna (username, sandi, peran, pin, nama) VALUES ('$username', '$sandi', '$peran', '$pin', '$nama')") or die(mysqli_error($con));
       }

    }

    echo '<script>
    alert("Data Berhasil Diimport");
     window.location.href = "../data_mahasiswa";
    </script>';

}

    
?>