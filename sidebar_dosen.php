  <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         
          <li class="nav-item">
            <a href="../home_dosen/" class="nav-link <?php if ($hal == 'beranda_dosen') {
                echo 'active';
                } ?>
                ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>BERANDA</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../dosen_ganti_password/" class="nav-link <?= $aktif = ($hal == 'beranda_password') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>GANTI PASSWORD</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="../dosen_kelas_matkul/" class="nav-link <?= $aktif = ($hal == 'beranda_matkul') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>KELAS MATA KULIAH </p>
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