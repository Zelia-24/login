  <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         
          <li class="nav-item">
            <a href="../home_admin/" class="nav-link <?php if ($hal == 'beranda_admin') {
                echo 'active';
                } ?>
                ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>BERANDA</p>
            </a>
          </li>

           <li class="nav-item">
            <a href="../admin_akademik" class="nav-link <?= $aktif = ($hal == 'beranda_akademik') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>AKADEMIK</p>
            </a>
          </li>

           <li class="nav-item">
            <a href="../admin_mata_kuliah" class="nav-link <?= $aktif = ($hal == 'beranda_matkul') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-book"></i>
              <p> MATA KULIAH</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../admin_data_kelas_matakuliah/" class="nav-link <?= $aktif = ($hal == 'beranda_kelas') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-home"></i>
              <p>KELAS MATA KULIAH</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../data_jurusan/" class="nav-link <?= $aktif = ($hal == 'beranda_jurusan') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-laptop-code"></i>
              <p>JURUSAN</p>
            </a>
          </li>

           <li class="nav-item">
            <a href="../data_dosen/" class="nav-link <?= $aktif = ($hal == 'beranda_dosen') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>DATA DOSEN</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../data_mahasiswa/" class="nav-link <?= $aktif = ($hal == 'beranda_mahasiswa') ? 'active' : '' ?>">
              <i class="fas fa-graduation-cap"></i>
              <p>DATA MAHASISWA</p>
            </a>
          </li>

         <li class="nav-item">
            <a href="../admin_data_administrator/" class="nav-link <?= $aktif = ($hal == 'beranda_administrator') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>DATA PENGGUNA</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../admin_ganti_password/" class="nav-link <?= $aktif = ($hal == 'beranda_password') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-lock"></i>
              <p>GANTI PASSWORD</p>
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