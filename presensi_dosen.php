<?php
require_once '../database/koneksi.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Proses jika modal status presensi di-submit di halaman yang sama
if (isset($_POST['btn_simpan_status_ptm'])) {
    $id_ptm_proses = mysqli_real_escape_string($con, $_POST['id_pertemuan']);
    $status_proses = mysqli_real_escape_string($con, $_POST['status']);
    $kode_kls_proses = mysqli_real_escape_string($con, $_POST['kode_kelas']);

    $update_status = mysqli_query($con, "UPDATE tbl_pertemuan SET status = '$status_proses' WHERE id_pertemuan = '$id_ptm_proses'") or die(mysqli_error($con));

    if ($update_status) {
        echo "<script>alert('Status presensi berhasil diperbarui!'); window.location.href='presensi_dosen.php?id_pertemuan=".$id_ptm_proses."&kode_kelas=".$kode_kls_proses."';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include '../css.php';
  $hal = 'presensi_dosen';
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
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
         <?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?> - [<?= htmlspecialchars($_SESSION['peran'] ?? '-'); ?>] <i class="far fa-user"></i>
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

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>
      <?php include '../sidebar_dosen.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid"></div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <?php  
                $id_pertemuan = isset($_GET['id_pertemuan']) ? mysqli_real_escape_string($con, $_GET['id_pertemuan']) : ''; 
                $kode_kelas   = isset($_GET['kode_kelas']) ? mysqli_real_escape_string($con, $_GET['kode_kelas']) : '';

                if (empty($id_pertemuan) && !empty($kode_kelas)) {
                    $q_ptm = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas' ORDER BY id_pertemuan DESC LIMIT 1") or die(mysqli_error($con));
                    $data_pertemuan = mysqli_fetch_array($q_ptm);
                    $id_pertemuan   = isset($data_pertemuan['id_pertemuan']) ? $data_pertemuan['id_pertemuan'] : '';
                } else {
                    $q_ptm = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE id_pertemuan = '$id_pertemuan'") or die(mysqli_error($con));
                    $data_pertemuan = mysqli_fetch_array($q_ptm);
                }

                $status_pertemuan = isset($data_pertemuan['status']) ? (int)$data_pertemuan['status'] : 0;
                if (empty($kode_kelas)) {
                    $kode_kelas   = isset($data_pertemuan['kode_kelas']) ? $data_pertemuan['kode_kelas'] : '';
                }
                $tgl_raw          = isset($data_pertemuan['tgl']) ? $data_pertemuan['tgl'] : '';
                $tgl              = !empty($tgl_raw) ? date('d-m-Y', strtotime($tgl_raw)) : '-';
                $hari             = !empty($tgl_raw) ? date('l', strtotime($tgl_raw)) : '-';
                $pertemuan        = isset($data_pertemuan['pertemuan_ke']) ? $data_pertemuan['pertemuan_ke'] : '-';

                $ambil_kls = mysqli_query($con, "SELECT * FROM tbl_kelas_matkul WHERE kode_kelas ='$kode_kelas'") or die(mysqli_error($con)); 
                $data_kls  = mysqli_fetch_array($ambil_kls); 

                $nik          = isset($data_kls['nik']) ? $data_kls['nik'] : ''; 
                $kode_matkul  = isset($data_kls['kode_matkul']) ? $data_kls['kode_matkul'] : ''; 
                $nama_kelas   = isset($data_kls['nama_kelas']) ? $data_kls['nama_kelas'] : '-'; 
                $kode_jurusan = isset($data_kls['kode_jurusan']) ? $data_kls['kode_jurusan'] : '';

                $ambil_dsn = mysqli_query($con, "SELECT * FROM tbl_dosen WHERE nik ='$nik'") or die(mysqli_error($con)); 
                $data_dsn  = mysqli_fetch_array($ambil_dsn); 

                $nama          = isset($data_dsn['nama']) ? $data_dsn['nama'] : '-'; 
                $jenis_kelamin = isset($data_dsn['jenis_kelamin']) ? $data_dsn['jenis_kelamin'] : 'L'; 
                $foto          = isset($data_dsn['img']) ? $data_dsn['img'] : ''; 
                ?>
                <h3 class="card-title">
                  <i class="fas fa-chalkboard-teacher mr-2"></i>
                  Kelas Mata Kuliah <?= htmlspecialchars($kode_kelas); ?>
                </h3>
              </div>
              
              <!-- CARD BODY -->
              <form action="simpan_presensi.php" method="POST">
                <input type="hidden" name="id_pertemuan" value="<?= htmlspecialchars($id_pertemuan); ?>">

                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                      <?php 
                      $foto_default = ($jenis_kelamin == 'L') ? '../asetweb/img/dosen laki laki.png' : '../asetweb/img/dosen perempuan.jfif'; 
                      $foto_tampil = !empty($foto) ? $foto : $foto_default; 
                      ?>
                      <div class="border rounded p-2 mb-2">
                        <img src="<?= htmlspecialchars($foto_tampil); ?>" alt="Dosen" class="img-fluid rounded">
                      </div>

                      <!-- TOMBOL PEMBUKA MODAL STATUS -->
                      <?php if ($status_pertemuan == 0) : ?>
                          <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#modal-status-presensi">
                              <i class="fas fa-fingerprint mr-1"></i> Buka Presensi
                          </button>
                      <?php else : ?>
                          <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#modal-status-presensi">
                              <i class="fas fa-lock mr-1"></i> Tutup Presensi
                          </button>

                          <!-- Script Auto-Submit jika timer habis -->
                          <script>
                          let waktu = 10;
                          let hitung = setInterval(() => {
                              waktu--;
                              const timerText = document.getElementById('timer');
                              if (timerText) timerText.innerText = waktu;

                              if (waktu <= 0) {
                                  clearInterval(hitung);
                                  document.getElementById('form-auto-tutup').submit();
                              }
                          }, 1000);
                          </script>
                      <?php endif; ?>

                      <button type="submit" name="btn_simpan" class="btn btn-primary btn-block mb-2" id="btnSimpan"><i class="fas fa-save mr-1"></i>Simpan</button>
                      <a href="detail_kelas.php?kode_kelas=<?= htmlspecialchars($kode_kelas) ?>" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                    
                    <div class="col-md-6">
                      <table class="table table-borderless">
                        <tbody>
                          <tr>
                            <td width="30%"><b>NIK</b></td>
                            <td width="5%">:</td>
                            <td><?= htmlspecialchars($nik); ?></td>
                          </tr>
                          <tr>
                            <td><b>NAMA</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($nama); ?></td>
                          </tr>
                          <tr>
                            <td><b>MATA KULIAH</b></td>
                            <td>:</td>
                            <td><?php 
                            $query_matkul = mysqli_query($con, "SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'") or die(mysqli_error($con));
                            $data_matkul  = mysqli_fetch_array($query_matkul);
                            echo isset($data_matkul['nama_matkul']) ? htmlspecialchars($data_matkul['nama_matkul']) : '-';
                            ?></td>
                          </tr>
                          <tr>
                            <td><b>KELAS</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($nama_kelas); ?></td>
                          </tr>
                          <tr>
                            <td><b>JURUSAN</b></td>
                            <td>:</td>
                            <td><?php 
                            $query_jurusan = mysqli_query($con, "SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'") or die(mysqli_error($con));
                            $data_jurusan  = mysqli_fetch_array($query_jurusan);
                            echo isset($data_jurusan['nama_jurusan']) ? htmlspecialchars($data_jurusan['nama_jurusan']) : '-';
                            ?></td>
                          </tr>
                          <tr>
                            <td><b>HARI</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($hari) ?></td>
                          </tr>
                          <tr>
                            <td><b>TANGGAL</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($tgl) ?></td>
                          </tr>
                          <tr>
                            <td><b>PERTEMUAN KE - </b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($pertemuan) ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="col-md-3 text-center">
                      <div class="border rounded p-3">
                        <div id="qrcode" class="d-flex justify-content-center">
                          <?php
                          include_once('../asetweb/phpqrcode/qrlib.php');
                          $isi_qr = $kode_kelas;
                          $fileName = 'QR-presensi-'.$kode_kelas.'-'.round(microtime(true)).'.png';
                          $alamat_tujuan = 'qr/';
                          if (!file_exists($alamat_tujuan)) {
                              mkdir($alamat_tujuan, 0777, true);
                          }
                          $alamat_qr = $alamat_tujuan.$fileName;

                          if (!empty($kode_kelas)) {
                              QRcode::png($isi_qr, $alamat_qr);
                          }
                          ?>
                          <?php if (!empty($kode_kelas) && file_exists($alamat_qr)) : ?>
                            <img src="<?= htmlspecialchars($alamat_qr) ?>" alt="qr_presensi" class="img-fluid mb-2" style="width: 250px;">
                          <?php else : ?>
                            <p class="text-muted">QR Code tidak tersedia</p>
                          <?php endif; ?>
                        </div>
                      </div>

                      <p class="text-muted mt-2 mb-0">
                        <i class="fas fa-qrcode mr-1"></i> Scan QR untuk presensi
                      </p>

                      <?php if ($status_pertemuan == 1) : ?>
                          <div class="alert alert-warning text-center mt-2 p-2">
                              <small>Presensi otomatis tutup dalam:</small><br>
                              <span id="timer" style="font-weight: bold; font-size: 20px; color: red;">10</span> detik
                          </div>
                      <?php endif; ?>

                    </div>
                  </div>

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
                      $panggil_peserta = mysqli_query($con, "SELECT * FROM tbl_peserta WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));
                      $no = 1;

                      if (mysqli_num_rows($panggil_peserta) > 0) {
                          while ($peserta = mysqli_fetch_array($panggil_peserta)) {
                              $nim = isset($peserta['nim']) ? $peserta['nim'] : '';

                              $query_mahasiswa = mysqli_query($con, "SELECT nama FROM tbl_mahasiswa WHERE nim = '$nim'") or die(mysqli_error($con));
                              $data_mahasiswa  = mysqli_fetch_array($query_mahasiswa);
                              $nama_mhs        = isset($data_mahasiswa['nama']) ? $data_mahasiswa['nama'] : 'Nama tidak ditemukan';

                              $query_presensi = mysqli_query($con, "SELECT id_presensi, status_kehadiran FROM tbl_presensi WHERE nim = '$nim' AND id_pertemuan = '$id_pertemuan'") or die(mysqli_error($con));
                              $data_presensi  = mysqli_fetch_array($query_presensi);

                              $id_presensi = isset($data_presensi['id_presensi']) ? $data_presensi['id_presensi'] : '';
                              $status = isset($data_presensi['status_kehadiran']) && !empty($data_presensi['status_kehadiran']) ? $data_presensi['status_kehadiran'] : 'alfa';

                              $badge_class = 'badge-danger'; 
                              if (strtolower($status) == 'hadir') $badge_class = 'badge-success';
                              else if (strtolower($status) == 'izin') $badge_class = 'badge-info';
                              else if (strtolower($status) == 'sakit') $badge_class = 'badge-warning';
                              ?>
                              <tr>
                                  <td><?= $no++ ?></td>
                                  <td><?= htmlspecialchars($nim) . ' - ' . htmlspecialchars($nama_mhs); ?></td>
                                  <td><span class="badge <?= $badge_class; ?>"><?= htmlspecialchars(ucfirst($status)) ?></span></td>
                                  <td class="text-center">
                                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit" 
                                         data-id="<?= htmlspecialchars($id_presensi); ?>" 
                                         data-nim="<?= htmlspecialchars($nim); ?>"
                                         data-pertemuan="<?= htmlspecialchars($id_pertemuan); ?>" 
                                         data-status="<?= htmlspecialchars($status); ?>">
                                         <i class="fas fa-edit"></i>
                                      </button>
                                  </td>
                              </tr>
                              <?php
                          }
                      } else {
                          echo '<tr><td colspan="4" class="text-center">Data Peserta Tidak Ditemukan</td></tr>';
                      }
                      ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Form hidden khusus untuk auto-submit tutup presensi oleh timer -->
  <form id="form-auto-tutup" action="" method="POST" style="display: none;">
      <input type="hidden" name="id_pertemuan" value="<?= htmlspecialchars($id_pertemuan); ?>">
      <input type="hidden" name="kode_kelas" value="<?= htmlspecialchars($kode_kelas); ?>">
      <input type="hidden" name="status" value="0">
      <input type="hidden" name="btn_simpan_status_ptm" value="1">
  </form>

  <!-- MODAL BUKA / TUTUP PRESENSI -->
  <div class="modal fade" id="modal-status-presensi">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-<?= ($status_pertemuan == 0) ? 'success' : 'danger'; ?> text-white">
          <h4 class="modal-title"><?= ($status_pertemuan == 0) ? 'Buka Presensi' : 'Tutup Presensi'; ?></h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <input type="hidden" name="id_pertemuan" value="<?= htmlspecialchars($id_pertemuan); ?>">
            <input type="hidden" name="kode_kelas" value="<?= htmlspecialchars($kode_kelas); ?>">
            <input type="hidden" name="status" value="<?= ($status_pertemuan == 0) ? '1' : '0'; ?>">

            <p class="mb-0">
                Apakah Anda yakin ingin <b><?= ($status_pertemuan == 0) ? 'membuka' : 'menutup'; ?></b> presensi untuk kelas ini?
            </p>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <button type="submit" name="btn_simpan_status_ptm" class="btn btn-<?= ($status_pertemuan == 0) ? 'success' : 'danger'; ?>">
                <?= ($status_pertemuan == 0) ? 'Buka Sekarang' : 'Tutup Sekarang'; ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL EDIT KEHADIRAN MAHASISWA -->
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

            <div class="form-group">
              <label for="status_kehadiran">Status Kehadiran</label>
              <select class="form-control" name="status_kehadiran" id="status_kehadiran" required>
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

  <?php include '../footer.php' ?>
</div>

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