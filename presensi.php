<?php
require_once '../database/koneksi.php';
$authority = @$_SESSION['peran'];
if ($authority != 'A') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href="../logout.php"</script>';
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include'../css.php';
  require_once '../database/koneksi.php';
  $hal ='kelas_matkul';
  ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
         <?= $_SESSION['nama']; ?> - [<?= $_SESSION['peran']; ?>] <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user"></i> Profile
          </a>
          <a href="../logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <?php include '../sidebar_admin.php' ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <?php  
                $id_pertemuan = @$_GET['id_pertemuan']; 

                // Ambil data pertemuan dari database
                $ambil_pertemuan = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE id_pertemuan = '$id_pertemuan'") or die (mysqli_error($con));
                $data_pertemuan  = mysqli_fetch_array($ambil_pertemuan);

                $status_pertemuan = isset($data_pertemuan['status']) ? (int)$data_pertemuan['status'] : 0;
                $kode_kelas = $data_pertemuan['kode_kelas'];
                $tgl = $data_pertemuan['tgl'];
                $hari = date('l', strtotime($tgl));
                $pertemuan = $data_pertemuan['pertemuan_ke'];

                $ambil_kls = mysqli_query($con, "SELECT * FROM tbl_kelas_matkul WHERE kode_kelas ='$kode_kelas'") or die(mysqli_error($con)); 
                $data_kls = mysqli_fetch_array($ambil_kls); 
                $nik = $data_kls['nik']; 
                $kode_matkul = $data_kls['kode_matkul']; 
                $nama_kelas = $data_kls['nama_kelas']; 
                $kode_jurusan = $data_kls['kode_jurusan'];

                $ambil_dsn = mysqli_query($con,"SELECT * FROM tbl_dosen WHERE nik ='$nik'") or die(mysqli_error($con)); 
                $data_dsn = mysqli_fetch_array($ambil_dsn); 
                $nama = $data_dsn['nama']; 
                $jenis_kelamin = $data_dsn['jenis_kelamin']; 
                $foto = $data_dsn['img']; 
                ?>
                <h3 class="card-title">
                  <i class="fas fa-chalkboard-teacher mr-2"></i>
                  Kelas Mata Kuliah <?= $kode_kelas; ?>
                </h3>
              </div>
              <!-- /.card-header -->
              
              <!-- CARD BODY -->
              <!-- Form pembungkus agar tombol Simpan berfungsi -->
              <form action="simpan_presensi.php" method="POST">
                <input type="hidden" name="id_pertemuan" value="<?= $id_pertemuan; ?>">

                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                      <?php 
                      if ($jenis_kelamin == 'L') { 
                          $foto_default = '../asetweb/img/dosen laki laki.png'; 
                      } else { 
                          $foto_default = '../asetweb/img/dosen perempuan.jfif'; 
                      } 
                      $foto_tampil = !empty($foto) ? $foto : $foto_default; 
                      ?>
                      <div class="border rounded p-2 mb-2">
                        <img 
                            src="<?= $foto_tampil; ?>"
                            alt="Dosen"
                            class="img-fluid rounded"
                            >
                      </div>

                      <?php 
                      // 1. Jika status = 0 (Presensi TUTUP)
                      if ($status_pertemuan == 0) {
                      ?>
                          <a href="ubah_status.php?id_pertemuan=<?= $id_pertemuan; ?>" class="btn btn-success btn-block mb-2" onclick="return confirm('Apakah Anda yakin ingin membuka presensi ini?');">
                              <i class="fas fa-fingerprint mr-1"></i> Buka Presensi
                          </a>

                      <?php
                      // 2. Jika status = 1 (Presensi BUKA)
                      } else { 
                      ?>
                          <a href="ubah_status.php?id_pertemuan=<?= $id_pertemuan; ?>" class="btn btn-danger btn-block mb-2" onclick="return confirm('Apakah Anda yakin ingin menutup presensi ini?');">
                              <i class="fas fa-lock mr-1"></i> Tutup Presensi Manual
                          </a>

                          <!-- Script Timer (Tetap ditaruh di sini agar siap jalan) -->
                          <script>
                          let waktu = 10; // Set 10 detik
                          
                          let hitung = setInterval(() => {
                              waktu--;
                              const timerText = document.getElementById('timer');
                              if (timerText) {
                                  timerText.innerText = waktu;
                              }

                              if (waktu <= 0) {
                                  clearInterval(hitung);
                                  // Otomatis pindah ke ubah_status.php untuk TUTUP presensi di database
                                  window.location.href = "ubah_status.php?id_pertemuan=<?= $id_pertemuan; ?>";
                              }
                          }, 1000);
                          </script>
                      <?php
                      }
                      ?>
                      <!-- Tombol simpan menggunakan type submit -->
                      <button type="submit" name="btn_simpan" class="btn btn-primary btn-block mb-2" id="btnSimpan"><i class="fas fa-save mr-1"></i>Simpan</button>



                      <a href="../admin_data_kelas_matakuliah/pertemuan.php?kode_kelas=<?= $kode_kelas ?>" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                    <!-- /.col-md-3 -->
                    
                    <div class="col-md-6">
                      <?php
                      $query_matkul = mysqli_query($con,"SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'") or die(mysqli_error($con));
                      $data_matkul = mysqli_fetch_array($query_matkul);
                      $nama_matkul = $data_matkul['nama_matkul'];
                      ?>
                      <table class="table table-borderless">
                        <tbody>
                          <tr>
                            <td width="30%"><b>NIK</b></td>
                            <td width="5%">:</td>
                            <td><?= $nik; ?></td>
                          </tr>
                          <tr>
                            <td><b>NAMA</b></td>
                            <td>:</td>
                            <td><?= $nama; ?></td>
                          </tr>
                          <tr>
                            <td><b>MATA KULIAH</b></td>
                            <td>:</td>
                            <td><?php 
                            $query_matkul = mysqli_query($con,"SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'")or die(mysqli_error($con));
                            $data_matkul = mysqli_fetch_array($query_matkul);
                            echo $data_matkul['nama_matkul'];
                            ?></td>
                          </tr>
                          <tr>
                            <td><b>KELAS</b></td>
                            <td>:</td>
                            <td><?= $nama_kelas; ?></td>
                          </tr>
                          <tr>
                            <td><b>JURUSAN</b></td>
                            <td>:</td>
                            <td><?php 
                            $query_jurusan = mysqli_query($con,"SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'")or die(mysqli_error($con));
                            $data_jurusan = mysqli_fetch_array($query_jurusan);
                            echo $data_jurusan['nama_jurusan'];
                            ?></td>
                          </tr>
                          <tr>
                            <td><b>HARI</b></td>
                            <td>:</td>
                            <td><?= $hari ?></td>
                          </tr>
                          <tr>
                            <td><b>TANGGAL</b></td>
                            <td>:</td>
                            <td><?= $tgl ?></td>
                          </tr>
                          <tr>
                            <td><b>PERTEMUAN KE - </b></td>
                            <td>:</td>
                            <td><?= $pertemuan ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.col-md-6 -->

                    <div class="col-md-3 text-center">
                      <div class="border rounded p-3">
                        <div id="qrcode" class="d-flex justify-content-center">
                          <?php
                          include('../asetweb/phpqrcode/qrlib.php');

                          $isi_qr =$id_pertemuan;
                          $fileName = 'QR-presensi-'.$id_pertemuan.'png';

                          $alamat_tujuan = 'qr/';
                          $alamat_qr = $alamat_tujuan.$fileName;
                          // generating
                          QRcode::png($isi_qr, $alamat_qr);

                          // displaying
                          ?>
                          <img src="<?= $alamat_qr ?>" alt="qr_presensi" class="img-fluid mb-2" style="width: 250px;">
                        </div>
                        </div>
                     

                      <p class="text-muted mt-2 mb-0">
                        <i class="fas fa-qrcode mr-1"></i>
                        Scan QR untuk presensi
                      </p>

                    <!-- NOTIF TIMER DITARUH DI SINI (Di bawah tulisan Scan QR) -->
                        <?php if ($status_pertemuan == 1) : ?>
                            <div class="alert alert-warning text-center mt-2 p-2">
                                <small>Presensi otomatis tutup dalam:</small><br>
                                <span id="timer" style="font-weight: bold; font-size: 20px; color: red;">10</span> detik
                            </div>
                        <?php endif; ?>

                    </div>
                    <!-- /.col-md-3 -->
                  </div>
                  <!-- /.row -->

                  <hr class="my-4">

                  <!-- TABEL PRESENSI MAHASISWA -->
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead class="bg-light">
                        <tr>
                          <th width="5%">No</th>
                          <th>Mahasiswa (NIM - Nama)</th>
                          <th width="20%">Status Kehadiran</th>
                          <th width="20%" class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      // 1. Cari tau dulu kode_kelas berdasarkan id_pertemuan
                      $query_pertemuan = mysqli_query($con, "SELECT kode_kelas FROM tbl_pertemuan WHERE id_pertemuan = '$id_pertemuan'") or die(mysqli_error($con));
                      $data_pertemuan  = mysqli_fetch_assoc($query_pertemuan);
                      $kode_kelas      = isset($data_pertemuan['kode_kelas']) ? $data_pertemuan['kode_kelas'] : '';

                      // 2. Ambil semua peserta kelas dari tbl_peserta
                      $panggil_peserta = mysqli_query($con, "SELECT * FROM tbl_peserta WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));
                      $no = 1;

                      $rv = mysqli_num_rows($panggil_peserta);
                      if ($rv > 0) {
                          while ($peserta = mysqli_fetch_array($panggil_peserta)) {
                              $nim = $peserta['nim'];

                              // 3. Ambil Nama Mahasiswa dari tbl_mahasiswa
                              $query_mahasiswa = mysqli_query($con, "SELECT nama FROM tbl_mahasiswa WHERE nim = '$nim'") or die(mysqli_error($con));
                              $data_mahasiswa  = mysqli_fetch_array($query_mahasiswa);
                              $nama_mhs        = isset($data_mahasiswa['nama']) ? $data_mahasiswa['nama'] : 'Nama tidak ditemukan';

                              // 4. Cek apakah sudah ada catatan presensi di tbl_presensi
                              $query_presensi = mysqli_query($con, "SELECT id_presensi, status_kehadiran FROM tbl_presensi WHERE nim = '$nim' AND id_pertemuan = '$id_pertemuan'") or die(mysqli_error($con));
                              $data_presensi  = mysqli_fetch_array($query_presensi);

                              $id_presensi = isset($data_presensi['id_presensi']) ? $data_presensi['id_presensi'] : '';
                              
                              // Jika data belum ada presensi, default di-set 'alfa'
                              $status = isset($data_presensi['status_kehadiran']) && !empty($data_presensi['status_kehadiran']) ? $data_presensi['status_kehadiran'] : 'alfa';

                              // Tentukan warna badge status
                              $badge_class = 'badge-danger'; // default alfa (merah)
                              if (strtolower($status) == 'hadir') {
                                  $badge_class = 'badge-success';
                              } else if (strtolower($status) == 'izin') {
                                  $badge_class = 'badge-info';
                              } else if (strtolower($status) == 'sakit') {
                                  $badge_class = 'badge-warning';
                              }
                              ?>
                              <tr>
                                  <td><?= $no++ ?></td>
                                  <td><?= $nim . ' - ' . $nama_mhs; ?></td>
                                  <td><span class="badge <?= $badge_class; ?>"><?= ucfirst($status) ?></span></td>
                                  <td class="text-center">
                                      <!-- Tombol Edit Modal -->
                                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit" 
                                         data-id="<?= $id_presensi; ?>" 
                                         data-nim="<?= $nim; ?>"
                                         data-pertemuan="<?= $id_pertemuan; ?>" 
                                         data-status="<?= $status; ?>">
                                         <i class="fas fa-edit"></i>
                                      </button>
                                  </td>
                              </tr>
                              <?php
                          }
                      } else {
                          echo '<tr><td colspan="4" class="text-center">Data Tidak Ditemukan</td></tr>';
                      }
                      ?>

                      </tbody>
                    </table>
                  </div>

                </div>
                <!-- /.card-body -->
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>

  <!-- Modal Edit Status Kehadiran -->
  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Status Kehadiran</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="ubah_kehadiran.php" method="post">
          <div class="modal-body">
            
            <input type="hidden" name="id_presensi" id="id_presensi">
            <input type="hidden" name="id_pertemuan" id="id_pertemuan">
            <input type="hidden" name="nim" id="nim">

            <!-- Dropdown Status Kehadiran -->
            <div class="form-group">
              <label for="status_kehadiran">Status Kehadiran</label>
              <select class="form-control" name="status_kehadiran" required>
                <option value="">-- Pilih kehadiran --</option>
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
                <option value="alfa">Alfa</option>
              </select>
            </div>

          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn_edit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Main Footer -->
  <?php include '../footer.php' ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php' ?>
<script>
  $('#modal-edit').on('show.bs.modal', function(e) {
    var id = $(e.relatedTarget).data('id');
    var nim = $(e.relatedTarget).data('nim');
    var pertemuan = $(e.relatedTarget).data('pertemuan');
    var status = $(e.relatedTarget).data('status');

    $(e.currentTarget).find('input[name="id_presensi"]').val(id);
    $(e.currentTarget).find('input[name="nim"]').val(nim);
    $(e.currentTarget).find('input[name="id_pertemuan"]').val(pertemuan);
    $(e.currentTarget).find('select[name="status_kehadiran"]').val(status);
  });
</script>
</body>
</html>
<?php 
}
?>