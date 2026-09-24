<?php
 require_once '../database/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
 include '../css.php';

 $hal = 'beranda_profil';
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
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user"></i> PROFILE
          </a>
          <div class="dropdown-divider"></div>
          <a href="../logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> LOGOUT
          </a>
          <div class="dropdown-divider"></div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">sistem manajemen</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <?php include '../sidebar_admin.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid"></div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="card card-primary card-outline">
          <?php 
            $nim = isset($_GET['nim']) ? mysqli_real_escape_string($con, $_GET['nim']) : '';
            $ambil_mhs = mysqli_query($con, "SELECT * FROM tbl_mahasiswa WHERE nim = '$nim'") or die(mysqli_error($con));
            $data_mhs = mysqli_fetch_array($ambil_mhs);
            
            $nama = $data_mhs['nama'] ?? '-';
            $kontak = $data_mhs['kontak'] ?? '-';
            $email = $data_mhs['email'] ?? '-';
            $jenis_kelamin = $data_mhs['jenis_kelamin'] ?? '-';
            $foto = !empty($data_mhs['img']) ? $data_mhs['img'] : '../asetweb/img/default.png';
          ?>
          <div class="card-body">
            <div class="row">
              <div class="col-md-5">
                <div class="box-profile">
                  <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="<?= $foto ?>" alt="User profile picture"> 
                  </div>

                  <h3 class="profile-username text-center"><?= $nama ?></h3>
                  <p class="text-muted text-center"><?= $nim ?></p>
                  <p class="text-muted text-center">Mahasiswa Informatika UPB</p>

                  <!-- PERBAIKAN: Mengaktifkan Modal Bootstrap -->
                  <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-profil">
                    <b>Edit Foto</b>
                  </button>
                </div>
              </div> 

              <div class="col-md-7">
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td style="width: 35%;"><strong>NIM</strong></td>
                        <td><?= $nim; ?></td>
                      </tr>
                      <tr>
                        <td><strong>Nama</strong></td>
                        <td><?= $nama; ?></td>
                      </tr>
                      <tr>
                        <td><strong>Kontak</strong></td>
                        <td><?= $kontak; ?></td>
                      </tr>
                      <tr>
                        <td><strong>Email</strong></td>
                        <td><?= $email; ?></td>
                      </tr>
                      <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td><?= ($jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <?php include '../footer.php'; ?>
</div>

<!-- PERBAIKAN: Struktur Modal Edit Foto -->
<div class="modal fade" id="modal-profil">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ubah Foto Profil</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form action="foto.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <!-- Input hidden untuk mengirimkan NIM ke foto.php -->
          <input type="hidden" name="nim" value="<?= $nim; ?>">

          <div class="form-group">
            <label for="file_foto">Upload Foto Baru</label>
            <input type="file" class="form-control" name="file_foto" id="file_foto" accept="image/*" required>
            <small class="form-text text-muted"></small>
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="btn-foto" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php'; ?>
</body>
</html>