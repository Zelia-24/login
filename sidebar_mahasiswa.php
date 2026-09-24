  <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         
          <li class="nav-item">
            <a href="../home_mahasiswa/" class="nav-link <?php if ($hal == 'beranda_mahasiswa') {
                echo 'active';
                } ?>
                ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>BERANDA</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../mahasiswa_ganti_password/" class="nav-link <?= $aktif = ($hal == 'beranda_password') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>GANTI PASSWORD</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../mahasiswa_presensi/" class="nav-link <?= $aktif = ($hal == 'beranda_absen') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-fingerprint"></i>
              <p>PRESENSI</p>
            </a>
          </li>

         <li class="nav-item">
            <a href="../logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>LOGOUT</p>
            </a>
          </li>

        </ul>
      </nav>