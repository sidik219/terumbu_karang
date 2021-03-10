<?php
//!!!!!!!!!!! -----------  Include file ini setelah session_start()  !!!!!!!!
//!!!!!!!!!!! -----------  Include file ini setelah session_start()  !!!!!!!!

if (!$_SESSION['level_user'])  { //Belum log in
    header('location: login.php?status=unrestrictedaccess');
}
else{
  $level = $_SESSION['level_user'];

  function cek_url_aktif($nav_url, $nama_file_php){
    if($nav_url == $nama_file_php){
      echo ' active';
    }
  }


  function print_sidebar($url_sekarang, $level){
    if($level == 1){ //sidebar Donatur
      $sidebar = '
      <li class="nav-item">
                <a href="dashboard_user.php" class="nav-link '.('dashboard_user.php' == $url_sekarang ? ' active ' : '').' ">
                    <i class="nav-icon fas fa-home"></i>
                    <p> Home </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="donasi_saya.php" class="nav-link '.('donasi_saya.php' == $url_sekarang ? ' active ' : '').' ">
                    <i class="nav-icon fas fa-hand-holding-usd"></i>
                    <p> Donasi Saya </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="reservasi_saya.php" class="nav-link '.('reservasi_saya.php' == $url_sekarang ? ' active ' : '').' ">
                    <i class="nav-icon fas fa-suitcase"></i>
                    <p> Reservasi Saya  </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="profil_saya.php" class="nav-link '.('profil_saya.php' == $url_sekarang ? ' active ' : '').' ">
                    <i class="nav-icon fas fas fa-user"></i>
                    <p> Profil Saya  </p>
                </a>
            </li>';

            echo $sidebar;

    }



    elseif($level == 2){ //sidebar Pengelola Wilayah
      $sidebar =
              '<!-- SESSION lvl Untuk wilayah -->
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="dashboard_admin.php" class="nav-link  '.('dashboard_admin.php' == $url_sekarang ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-home"></i>
                        <p> Home</p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Wilayah -->
                              <a href="kelola_wilayah.php" class="nav-link '.('kelola_wilayah.php' == $url_sekarang || ('edit_wilayah.php'  == $url_sekarang) || ('input_wilayah.php'  == $url_sekarang) ? ' active ' : '').' ">
                                  <i class="nav-icon fas fa-globe-asia"></i>
                                  <p> Kelola Wilayah </p>
                              </a>
                          </li>
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link '.('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                        <p> Kelola Lokasi </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_titik.php" class="nav-link '.('kelola_titik.php' == $url_sekarang || ('edit_titik.php'  == $url_sekarang) || ('input_titik.php'  == $url_sekarang) ? ' active ' : '').' ">
                          <i class="nav-icon fas fa-crosshairs"></i>
                          <p> Kelola Titik </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Wilayah -->
                        <a href="kelola_jenis_tk.php" class="nav-link '.('kelola_jenis_tk.php' == $url_sekarang || ('edit_jenis_tk.php'  == $url_sekarang) || ('input_jenis_tk.php'  == $url_sekarang) ? ' active ' : '').' ">
                              <i class="nav-icon fas fa-certificate"></i>
                              <p> Kelola Jenis Terumbu </p>
                        </a>
                  </li>
                  <li class="nav-item"> <!-- Wilayah -->
                      <a href="kelola_tk.php" class="nav-link '.('kelola_tk.php' == $url_sekarang || ('edit_tk.php'  == $url_sekarang) || ('input_tk.php'  == $url_sekarang) ? ' active ' : '').' ">
                            <i class="fas fa-disease nav-icon"></i>
                            <p>Kelola Sub-Jenis Terumbu </p>
                      </a>
                  </li>
                  <li class="nav-item"> <!-- Wilayah -->
                        <a href="kelola_perizinan.php" class="nav-link '.('kelola_perizinan.php' == $url_sekarang ? ' active ' : '').' ">
                              <i class="nav-icon fas fa-scroll"></i>
                              <p> Kelola Perizinan </p>
                        </a>
                  </li>
                  <li class="nav-item"> <!-- Wilayah -->
                      <a href="kelola_laporan.php" class="nav-link '.('kelola_laporan.php' == $url_sekarang ? ' active ' : '').' ">
                              <i class="nav-icon fas fa-book"></i>
                              <p> Kelola Laporan </p>
                      </a>
                  </li>
                <li class="nav-item">
                    <a href="kelola_user.php" class="nav-link '.('kelola_user.php' == $url_sekarang ? ' active ' : '').' ">
                            <i class="nav-icon fas fa-user"></i>
                            <p> Kelola User </p>
                    </a>
                </li>
                ';

                echo $sidebar;
    }


    elseif($level == 3){ //sidebar Pengelola lokasi
      $sidebar =
              '<!-- SESSION lvl Untuk Lokasi -->
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="dashboard_admin.php" class="nav-link  '.('dashboard_admin.php' == $url_sekarang ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-home"></i>
                        <p> Home</p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_donasi.php" class="nav-link '.(('kelola_donasi.php' == $url_sekarang) || ('edit_donasi.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p> Kelola Donasi </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_wisata.php" class="nav-link '.(('kelola_wisata.php' == $url_sekarang) || ('edit_wisata.php'  == $url_sekarang) || ('input_wisata.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-suitcase"></i>
                        <p> Kelola Wisata </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_reservasi_wisata.php" class="nav-link '.(('kelola_reservasi_wisata.php' == $url_sekarang) || ('edit_reservasi_wisata.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p> Kelola Reservasi </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link '.('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                        <p> Kelola Lokasi </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_titik.php" class="nav-link '.(('kelola_titik.php' == $url_sekarang) || ('edit_titik.php' == $url_sekarang)  || ('input_titik.php'  == $url_sekarang) ? ' active ' : '').' ">
                          <i class="nav-icon fas fa-crosshairs"></i>
                          <p> Kelola Titik </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_batch.php" class="nav-link '.(('kelola_batch.php' == $url_sekarang) || ('edit_batch.php' == $url_sekarang)  || ('input_batch.php'  == $url_sekarang)  ? ' active ' : '').' ">
                          <i class="nav-icon fas fa-boxes"></i>
                          <p> Kelola Batch </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_pemeliharaan.php" class="nav-link '.('kelola_pemeliharaan.php' == $url_sekarang  || ('edit_pemeliharaan.php' == $url_sekarang)  || ('input_pemeliharaan.php'  == $url_sekarang) ? ' active ' : '').' ">
                          <i class="nav-icon fas fa-heart"></i>
                          <p> Kelola Pemeliharaan </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kelola_user.php" class="nav-link '.('kelola_user.php' == $url_sekarang ? ' active ' : '').' ">
                            <i class="nav-icon fas fa-user"></i>
                            <p> Kelola User </p>
                    </a>
                </li>';

                echo $sidebar;
    }
  }


}
?>
