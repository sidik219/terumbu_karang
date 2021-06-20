<?php
//!!!!!!!!!!! -----------  Include file ini setelah session_start()  !!!!!!!!
//!!!!!!!!!!! -----------  Include file ini setelah session_start()  !!!!!!!!

if (!$_SESSION['level_user'])  { //Belum log in
    header('location: login.php?status=restrictedaccess');
}
else{
  $level = $_SESSION['level_user'];

  //Logo/branding
  $file_gambar_logo = 'dist/img/gokarang_coral_purple.png';

  $logo_website = '<img src="'.$file_gambar_logo.'"  class="brand-image">
                <!-- BRAND TEXT (TOP) -->
                <span class="brand-text"><span class="text-orange">Go</span><span>Karang<span></span>';

  //Format logo lama
  // $logo_website = '<img src="'.$file_gambar_logo.'"  class="brand-image img-circle elevation-3" style="opacity: .8">
  //               <!-- BRAND TEXT (TOP) -->
  //               <span class="brand-text font-weight-bold">GoKarang</span>';

  //Footer dinamis
  $tahun = date("Y");
  $footer = "<strong>Copyright &copy; $tahun </strong> GoKarang";


  function cek_url_aktif($nav_url, $nama_file_php){
    if($nav_url == $nama_file_php){
      echo ' active';
    }
  }

  //Favicon website
  $favicon = '<link rel="icon" href="dist/img/gokarang_coral_favicon.png" type="image/x-icon" />';


  function print_sidebar($url_sekarang, $level){
    if($level == 4){ //sidebar Pusat & debug
      $sidebar = '
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="dashboard_admin_pusat.php" class="nav-link  '.('dashboard_admin_pusat.php' == $url_sekarang ? ' active ' : '').' ">
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
                    <a href="kelola_reservasi_wisata.php" class="nav-link '.(('kelola_reservasi_wisata.php' == $url_sekarang) || ('edit_reservasi_wisata.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p> Kelola Reservasi </p>
                    </a>
                </li>

                <!--
                <li class="nav-item">
                    <a href="kelola_organisasi.php" class="nav-link  '.('kelola_organisasi.php' == $url_sekarang ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p> Kelola Organisasi</p>
                    </a>
                </li> -->
                <li class="nav-item"> <!-- Pusat -->
                              <a href="kelola_rekening_bersama.php" class="nav-link '.('kelola_rekening_bersama.php' == $url_sekarang ? ' active ' : '').' ">
                                  <i class="nav-icon fas fa-money-check-alt"></i>
                                  <p> Rekening Bersama</p>
                              </a>
                </li>

                <li class="nav-item"> <!-- Wilayah -->
                              <a href="kelola_wilayah.php" class="nav-link '.('kelola_wilayah.php' == $url_sekarang || ('edit_wilayah.php'  == $url_sekarang) || ('input_wilayah.php'  == $url_sekarang) ? ' active ' : '').' ">
                                  <i class="nav-icon fas fa-globe-asia"></i>
                                  <p> Kelola Wilayah </p>
                              </a>
                </li>
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link '.('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) || ('kelola_harga_terumbu.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                        <p> Kelola Lokasi </p>
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
                            <p>Sub-Jenis Terumbu </p>
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
                <!--
                <li class="nav-item">
                    <a href="kelola_user.php" class="nav-link '.('kelola_user.php' == $url_sekarang ? ' active ' : '').' ">
                            <i class="nav-icon fas fa-user"></i>
                            <p> Kelola User </p>
                    </a>
                </li> -->
            ';

            echo $sidebar;

    }

    elseif($level == 1){ //sidebar Donatur
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
            <!--
            <li class="nav-item">
                <a href="profil_saya.php" class="nav-link '.('profil_saya.php' == $url_sekarang ? ' active ' : '').' ">
                    <i class="nav-icon fas fas fa-user"></i>
                    <p> Profil Saya  </p>
                </a>
            </li> -->';

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
                <li class="nav-item"> <!-- Wilayah Lokasi Pusat -->
                    <a href="kelola_donasi.php" class="nav-link '.(('kelola_donasi.php' == $url_sekarang) || ('edit_donasi.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p> Kelola Donasi </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link '.('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) || ('kelola_harga_terumbu.php'  == $url_sekarang) ? ' active ' : '').' ">
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
                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_wisata.php" class="nav-link '.(('kelola_wisata.php' == $url_sekarang) || ('edit_wisata.php'  == $url_sekarang) || ('kelola_fasilitas_wisata.php'  == $url_sekarang) || ('input_wisata.php'  == $url_sekarang) || ('input_fasilitas_wisata.php'  == $url_sekarang) || ('input_paket_wisata.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-suitcase"></i>
                        <p> Kelola Wisata </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Pusat & Wilayah -->
                              <a href="kelola_rekening_bersama.php" class="nav-link '.('kelola_rekening_bersama.php' == $url_sekarang ? ' active ' : '').' ">
                                  <i class="nav-icon fas fa-money-check-alt"></i>
                                  <p> Rekening Bersama</p>
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
                    <a href="kelola_reservasi_wisata.php" class="nav-link '.(('kelola_reservasi_wisata.php' == $url_sekarang) || ('edit_reservasi_wisata.php'  == $url_sekarang) ? ' active ' : '').' ">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p> Kelola Reservasi </p>
                    </a>
                </li>
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link '.('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) || ('kelola_harga_terumbu.php'  == $url_sekarang) ? ' active ' : '').' ">
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
                ';

                echo $sidebar;
    }
  }


}
?>
