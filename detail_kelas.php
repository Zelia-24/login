<?php
require_once '../database/koneksi.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek otoritas peran Dosen (misal: 'D')
$authority = isset($_SESSION['peran']) ? $_SESSION['peran'] : '';
if ($authority != 'D') {
    echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
    echo '<script>window.location.href="../logout.php";</script>';
    exit(); // Hentikan eksekusi script
} else {
    // Ambil NIK Dosen dari session login dan amankan input
    $nik_dosen = mysqli_real_escape_string($con, $_SESSION['username']); 

    // Tangkap parameter kode_kelas dari URL dan amankan input
    $kode_kelas_get = isset($_GET['kode_kelas']) ? mysqli_real_escape_string($con, $_GET['kode_kelas']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include '../css.php';
  $hal = 'kelas_matkul';
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
          <?= htmlspecialchars($_SESSION['nama']); ?> - [<?= htmlspecialchars($_SESSION['peran']); ?>] <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user"></i> Profile
          </a>
          <a href="../logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> Logout
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
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>

      <!-- Sidebar Menu Dosen -->
      <?php include '../sidebar_dosen.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
          <?php
          // Query spesifik berdasarkan kode_kelas dan NIK dosen
          $q_km = mysqli_query($con, "SELECT * FROM tbl_kelas_matkul WHERE kode_kelas = '$kode_kelas_get' AND nik = '$nik_dosen'");

          if ($q_km && mysqli_num_rows($q_km) > 0) {
              $km = mysqli_fetch_assoc($q_km);
              $kode_kelas  = $km['kode_kelas'];
              $kode_matkul = $km['kode_matkul'];
              $nama_kelas  = isset($km['nama_kelas']) ? $km['nama_kelas'] : '-';

              // Ambil nama matkul dari tbl_matkul
              $q_matkul = mysqli_query($con, "SELECT * FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'");
              $d_matkul = mysqli_fetch_assoc($q_matkul);
              $nama_matkul = isset($d_matkul['nama_matkul']) ? $d_matkul['nama_matkul'] : 'Tidak ditemukan';

              // Ambil data peserta KHUSUS untuk kelas ini saja
              $q_peserta = mysqli_query($con, "SELECT * FROM tbl_peserta WHERE kode_kelas = '$kode_kelas'");
          ?>

          <!-- Box Card Mata Kuliah & Kelas Spesifik -->
          <div class="card card-primary card-outline mb-4">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                  <h5 class="card-title m-0 font-weight-bold text-primary">
                      <i class="fas fa-book mr-2"></i> Mata Kuliah: <?= htmlspecialchars($nama_matkul); ?> | Kelas: <?= htmlspecialchars($nama_kelas); ?>
                  </h5>

                    <a href="pdf_presensi.php?kode_kelas=<?= $kode_kelas; ?>" target="_blank" class="btn btn-danger btn-sm ml-auto">
                          <i class="fas fa-file-pdf"></i>
                      </a>

                  <a href="../dosen_kelas_matkul/" class="btn btn-warning btn-sm ml-auto"><i class="fas fa-arrow-left"></i></a>
              </div>
              <div class="card-body p-0">
                  <table class="table table-bordered table-striped m-0">
                      <thead>
                          <tr>
                              <th width="50" class="text-center">No</th>
                              <th>Mahasiswa (NIM - Nama)</th>
                              <th>Status Kehadiran</th>
                              <th width="100" class="text-center">Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php
                      if ($q_peserta && mysqli_num_rows($q_peserta) > 0) {
                          $no = 1;
                          while ($p = mysqli_fetch_assoc($q_peserta)) {
                              $nim = mysqli_real_escape_string($con, $p['nim']);

                              // 1. Ambil nama mahasiswa
                              $q_mhs = mysqli_query($con, "SELECT nama FROM tbl_mahasiswa WHERE nim = '$nim'");
                              $d_mhs = mysqli_fetch_assoc($q_mhs);
                              $nama_mhs = isset($d_mhs['nama']) ? $d_mhs['nama'] : '-';

                              // 2. Ambil status_kehadiran dengan JOIN ke tbl_pertemuan
                              $query_presensi = "SELECT pr.status_kehadiran 
                                                FROM tbl_presensi pr 
                                                JOIN tbl_pertemuan pt ON pr.id_pertemuan = pt.id_pertemuan 
                                                WHERE pt.kode_kelas = '$kode_kelas' AND pr.nim = '$nim' 
                                                ORDER BY pr.id_presensi DESC LIMIT 1";

                              $q_presensi = mysqli_query($con, $query_presensi) or die(mysqli_error($con));
                              $d_presensi = mysqli_fetch_assoc($q_presensi);

                              // Ubah fallback dari 'Belum Absen' menjadi 'Alfa'
                            $status = isset($d_presensi['status_kehadiran']) ? $d_presensi['status_kehadiran'] : 'Alfa';
                               ?>
                              <tr>
                                  <td class="text-center"><?= $no++; ?></td>
                                  <td><?= htmlspecialchars($p['nim']); ?> - <?= htmlspecialchars($nama_mhs); ?></td>
                                  <td>
                                    <?php 
                                    $status_clean = strtolower(trim($status));

                                    if ($status_clean == 'hadir') {
                                        echo '<span class="badge badge-success">Hadir</span>';
                                    } elseif ($status_clean == 'sakit') {
                                        echo '<span class="badge badge-warning">Sakit</span>';
                                    } elseif ($status_clean == 'izin') {
                                        echo '<span class="badge badge-info">Izin</span>';
                                    } elseif ($status_clean == 'alfa') {
                                        echo '<span class="badge badge-danger">Alfa</span>';
                                    } else {
                                        echo '<span class="badge badge-secondary">' . htmlspecialchars($status) . '</span>';
                                    }
                                    ?>
                                  </td>
                                  <td class="text-center">
                                      <a href="#" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                  </td>
                              </tr>
                      <?php 
                          } 
                      } else {
                          echo '<tr><td colspan="4" class="text-center">Belum ada mahasiswa di kelas ini.</td></tr>';
                      }
                      ?>
                      </tbody>
                  </table>
              </div>
          </div>

          <?php 
          } else {
              echo '<div class="alert alert-warning">Data kelas tidak ditemukan atau Anda tidak memiliki akses ke kelas ini.</div>';
          }
          ?>
        </div>
    </div>
  </div>

  <!-- Main Footer -->
  <?php include '../footer.php'; ?>
</div>

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php'; ?>

</body>
</html>
<?php 
}
?>