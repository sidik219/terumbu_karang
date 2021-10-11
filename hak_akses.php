<?php
//!!!!!!!!!!! -----------  Include file ini setelah session_start()  !!!!!!!!
//!!!!!!!!!!! -----------  Include file ini setelah session_start()  !!!!!!!!
if (!$_SESSION['level_user']) { //Belum log in
    header('location: login.php?status=restrictedaccess');
} else {
    $level_user = $_SESSION['level_user'];

    //Logo/branding
    $file_gambar_logo = 'dist/img/gokarang_coral_purple.png';

    $logo_website = '<img src="' . $file_gambar_logo . '"  class="brand-image">
                <!-- BRAND TEXT (TOP) -->
                <span class="brand-text"><span class="text-orange">Go</span><span>Karang<span></span>';

    //Format logo lama
    // $logo_website = '<img src="'.$file_gambar_logo.'"  class="brand-image img-circle elevation-3" style="opacity: .8">
    //               <!-- BRAND TEXT (TOP) -->
    //               <span class="brand-text font-weight-bold">GoKarang</span>';

    //Footer dinamis
    $tahun = date("Y");

    $footer = "<a href='about_us.php' style='text-decoration: none !important; color:grey;'><strong>Copyright &copy; $tahun </strong> GoKarang</a>";


    function cek_url_aktif($nav_url, $nama_file_php)
    {
        if ($nav_url == $nama_file_php) {
            echo ' active';
        }
    }

    //Favicon website
    $favicon = '<link rel="icon" href="dist/img/gokarang_coral_favicon.png" type="image/x-icon" />';




    function print_sidebar($url_sekarang, $level)
    {
        include 'build/config/connection.php';
        $level_user = $_SESSION['level_user'];

        if ($level_user == 2 || $level_user == 3) {
            //Data notifikasi sidebar pengelola wilayah & lokasi
            // $level_user = $_SESSION['level_user'];

            if ($level_user == 2) {
                $id_wilayah = $_SESSION['id_wilayah_dikelola'];
                $extra_query = " AND t_wilayah.id_wilayah = $id_wilayah ";
                $extra_query_noand = " t_wilayah.id_wilayah = $id_wilayah ";

                $wilayah_join_donasi = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_donasi.id_lokasi
                                            LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";

                $wilayah_join_reservasi = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_reservasi_wisata.id_lokasi
                                            LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";

                $wilayah_join_batch = "   LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_batch.id_lokasi
                                            LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";
            } else if ($level_user == 3) {
                $id_lokasi = $_SESSION['id_lokasi_dikelola'];
                $extra_query = " AND id_lokasi = $id_lokasi ";
                $extra_query_noand = " id_lokasi = $id_lokasi ";
                $wilayah_join_donasi = " ";
                $wilayah_join_reservasi = " ";
                $wilayah_join_batch = " ";
            } else if ($level_user == 4) {
                $extra_query = "  ";
                $extra_query_noand = " 1 = 1 AND  ";
                $wilayah_join_donasi = " ";
                $wilayah_join_reservasi = " ";
                $wilayah_join_batch = " ";
            }

            $sqlviewdonasi = 'SELECT (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi ' . $wilayah_join_donasi . '
                                WHERE t_donasi.id_status_donasi = 1 ' . $extra_query . ') AS donasi_baru,
                                (SELECT COUNT(t_donasi.id_status_donasi)
                                                FROM t_donasi ' . $wilayah_join_donasi . '
                                WHERE t_donasi.id_status_donasi = 2 ' . $extra_query . ') AS donasi_verifikasi,
                                (SELECT COUNT(t_donasi.id_status_donasi)
                                                FROM t_donasi ' . $wilayah_join_donasi . '
                                WHERE t_donasi.id_batch IS NULL AND t_donasi.id_status_donasi = 3  ' . $extra_query . ') AS donasi_tanpa_batch,
                                (SELECT COUNT(t_donasi.id_status_donasi)
                                                FROM t_donasi ' . $wilayah_join_donasi . '
                                WHERE t_donasi.id_status_donasi = 7 ' . $extra_query . ') AS donasi_bermasalah';
            $stmt = $pdo->prepare($sqlviewdonasi);
            $stmt->execute();
            $rowdonasi = $stmt->fetch();


            $sqlviewreservasi = 'SELECT (SELECT COUNT(t_reservasi_wisata.id_status_reservasi_wisata)
                                FROM t_reservasi_wisata ' . $wilayah_join_reservasi . '
                                WHERE t_reservasi_wisata.id_status_reservasi_wisata = 1 ' . $extra_query . ') AS reservasi_baru,
                                (SELECT COUNT(t_reservasi_wisata.id_status_reservasi_wisata)
                                                FROM t_reservasi_wisata ' . $wilayah_join_reservasi . '
                                WHERE t_reservasi_wisata.id_status_reservasi_wisata = 3 ' . $extra_query . ') AS reservasi_bermasalah';
            $stmt = $pdo->prepare($sqlviewreservasi);
            $stmt->execute();
            $rowreservasi = $stmt->fetch();




            $sqlviewbatch = 'SELECT (SELECT COUNT(id_status_batch)
                                FROM t_batch ' . $wilayah_join_batch . '
                                WHERE id_status_batch = 1 ' . $extra_query . ') AS batch_penyemaian,
                                (SELECT COUNT(id_status_batch)
                                FROM t_batch ' . $wilayah_join_batch . '
                                WHERE id_status_batch = 2 ' . $extra_query . ') AS batch_siap_tanam';

            $stmt = $pdo->prepare($sqlviewbatch);
            $stmt->execute();
            $rowbatch = $stmt->fetch();

            $sqlviewpemeliharaan = 'SELECT (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, tanggal_pemeliharaan_terakhir, NOW()) AS lama_sejak_pemeliharaan 
                FROM t_batch  ' . $wilayah_join_batch . ' WHERE  ' . $extra_query_noand . ' HAVING lama_sejak_pemeliharaan >= 3) AS jl_pml) AS perlu_pemeliharaan,
                                        (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, `tanggal_penanaman`, NOW()) AS lama_sejak_tanam FROM t_batch  ' . $wilayah_join_batch . ' 
                                        WHERE status_cabut_label = 0 ' . $extra_query . ' HAVING lama_sejak_tanam >= 11) AS jl_pml) AS perlu_cabut_label';

            $stmt = $pdo->prepare($sqlviewpemeliharaan);
            $stmt->execute();
            $rowperlupml = $stmt->fetch();
        } else if ($level_user == 4) {
            $sqlviewdonasi = 'SELECT (SELECT COUNT(t_donasi.id_status_donasi)
                FROM t_donasi
                WHERE t_donasi.id_status_donasi = 1) AS donasi_baru,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi
                WHERE t_donasi.id_status_donasi = 2) AS donasi_verifikasi,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi
                WHERE t_donasi.id_batch IS NULL AND t_donasi.id_status_donasi = 3 ) AS donasi_tanpa_batch,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi
                WHERE t_donasi.id_status_donasi = 7) AS donasi_bermasalah';
            $stmt = $pdo->prepare($sqlviewdonasi);
            $stmt->execute();
            $rowdonasi = $stmt->fetch();


            $sqlviewreservasi = 'SELECT (SELECT COUNT(t_reservasi_wisata.id_status_reservasi_wisata)
                                FROM t_reservasi_wisata
                                WHERE t_reservasi_wisata.id_status_reservasi_wisata = 1) AS reservasi_baru,
                                (SELECT COUNT(t_reservasi_wisata.id_status_reservasi_wisata)
                                                FROM t_reservasi_wisata
                                WHERE t_reservasi_wisata.id_status_reservasi_wisata = 3) AS reservasi_bermasalah';
            $stmt = $pdo->prepare($sqlviewreservasi);
            $stmt->execute();
            $rowreservasi = $stmt->fetch();




            $sqlviewbatch = 'SELECT (SELECT COUNT(id_status_batch)
                                FROM t_batch
                                WHERE id_status_batch = 1) AS batch_penyemaian,
                                (SELECT COUNT(id_status_batch)
                                FROM t_batch
                                WHERE id_status_batch = 2) AS batch_siap_tanam';

            $stmt = $pdo->prepare($sqlviewbatch);
            $stmt->execute();
            $rowbatch = $stmt->fetch();

            $sqlviewpemeliharaan = 'SELECT (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, tanggal_pemeliharaan_terakhir, NOW()) AS lama_sejak_pemeliharaan FROM t_batch HAVING lama_sejak_pemeliharaan >= 3) AS jl_pml) AS perlu_pemeliharaan,
                                        (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, `tanggal_penanaman`, NOW()) AS lama_sejak_tanam FROM t_batch WHERE status_cabut_label = 0 HAVING lama_sejak_tanam >= 11) AS jl_pml) AS perlu_cabut_label';

            $stmt = $pdo->prepare($sqlviewpemeliharaan);
            $stmt->execute();
            $rowperlupml = $stmt->fetch();
        }

        if (in_array($level_user, [2, 3, 4])) {
            //Pill notifikasi per menu
            $notifikasi_donasi = '<span class="badge text-sm badge-pill badge-info">' . $rowdonasi->donasi_baru . '</span>';
            $notifikasi_donasi_verifikasi = '<span class="badge text-sm badge-pill badge-info">' . $rowdonasi->donasi_verifikasi . '</span>';
            $notifikasi_reservasi = '<span class="badge text-sm badge-pill badge-info">' . $rowreservasi->reservasi_baru . '</span>';
            $notifikasi_batch_penyemaian = '<span class="badge text-sm badge-pill badge-info">' . $rowbatch->batch_penyemaian . '</span>';
            $notifikasi_pemeliharaan = '<span class="badge text-sm badge-pill badge-info">' . $rowperlupml->perlu_pemeliharaan . '</span>';
        }



        //Data Notifikasi Sidebar End


        if ($level == 4) { //sidebar Pusat & debug
            $sidebar = '
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="dashboard_admin_pusat.php" class="nav-link  ' . ('dashboard_admin_pusat.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-home"></i>
                        <p> Home</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_kode_wilayah.php" class="nav-link ' . (('kelola_kode_wilayah.php' == $url_sekarang)  ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-list-ol"></i>
                        <p> Kelola Kode Wilayah </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_donasi.php" class="nav-link ' . (('kelola_donasi.php' == $url_sekarang) || ('edit_donasi.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p> Kelola Donasi ' . $notifikasi_donasi . '</p>
                    </a>
                </li>
                
                <li class="nav-item"> 
                    <a href="kelola_reservasi_wisata.php" class="nav-link ' . (('kelola_reservasi_wisata.php' == $url_sekarang) || ('edit_reservasi_wisata.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p> Kelola Reservasi ' . $notifikasi_reservasi . '</p>
                    </a>
                </li>

		        <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_asuransi.php" class="nav-link ' . (('kelola_asuransi.php' == $url_sekarang) || ('edit_asuransi.php'  == $url_sekarang) || ('input_asuransi.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-heartbeat"></i>
                        <p> Kelola Asuransi </p>
                    </a>
                </li>

                <!--
                <li class="nav-item">
                    <a href="kelola_organisasi.php" class="nav-link  ' . ('kelola_organisasi.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p> Kelola Organisasi</p>
                    </a>
                </li> -->

                <li class="nav-item"> <!-- Pusat -->
                    <a href="kelola_rekening_bersama.php" class="nav-link ' . ('kelola_rekening_bersama.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p> Rekening Bersama</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_wilayah.php" class="nav-link ' . ('kelola_wilayah.php' == $url_sekarang || ('edit_wilayah.php'  == $url_sekarang) || ('input_wilayah.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-globe-asia"></i>
                        <p> Kelola Wilayah </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link ' . ('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) || ('kelola_harga_terumbu.php'  == $url_sekarang) || ('kelola_biaya_operasional.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                        <p> Kelola Lokasi </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_jenis_tk.php" class="nav-link ' . ('kelola_jenis_tk.php' == $url_sekarang || ('edit_jenis_tk.php'  == $url_sekarang) || ('input_jenis_tk.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-certificate"></i>
                            <p> Kelola Jenis Terumbu </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_tk.php" class="nav-link ' . ('kelola_tk.php' == $url_sekarang || ('edit_tk.php'  == $url_sekarang) || ('input_tk.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="fas fa-disease nav-icon"></i>
                        <p>Sub-Jenis Terumbu </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_perizinan.php" class="nav-link ' . ('kelola_perizinan.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-scroll"></i>
                            <p> Kelola Perizinan </p>
                    </a>
                </li>
                
                <!--
                <li class="nav-item">
                    <a href="kelola_user.php" class="nav-link ' . ('kelola_user.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-user"></i>
                            <p> Kelola User </p>
                    </a>
                </li> -->
                

                <!-- LAPORAN COLLAPSE START -->        
        <li class="nav-item ' . (in_array($url_sekarang, [
                'kelola_laporan_baru.php', 'kelola_laporan.php', 'laporan_kondisi.php', 'laporan_donasi.php',
                'laporan_periode_wisata.php', 'kelola_arsip_laporan_sebaran.php', 'edit_arsip_luas_wilayah.php'
            ])  ? ' active menu-open ' : '') . '"> 
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Kelola Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                        <a href="laporan_donasi.php" class="nav-link  ' . ('laporan_donasi.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p> Laporan Donasi</p>
                        </a>
                    </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="laporan_periode_wisata.php" class="nav-link  ' . ('laporan_periode_wisata.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Laporan Wisata</p>
                    </a>
                </li>   

                    <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_laporan_baru.php" class="nav-link ' . ('kelola_laporan_baru.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-globe-americas"></i>
                            <p> Laporan Sebaran</p>
                    </a>
                </li>
                  
                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_laporan.php" class="nav-link ' . ('kelola_laporan.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-angle-down"></i>
                            <p> Sebaran Per-Wilayah </p>
                    </a>
                </li>

                   <li class="nav-item"> <!-- Wilayah & Pusat -->
                    <a href="laporan_kondisi.php" class="nav-link ' . ('laporan_kondisi.php' == $url_sekarang   ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-heartbeat"></i>
                            <p> Laporan Kondisi </p>
                    </a>
                </li>                

                <li class="nav-item"> <!-- Wilayah & Pusat -->
                    <a href="kelola_arsip_laporan_sebaran.php" class="nav-link ' . ('kelola_arsip_laporan_sebaran.php' == $url_sekarang || ('edit_arsip_luas_wilayah.php'  == $url_sekarang)  ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-history"></i>
                            <p> Kelola Arsip Laporan </p>
                    </a>
                </li>
            </ul> <!-- LAPORAN COLLAPSE END -->
            ';

            echo $sidebar;
        } elseif ($level == 1) { //sidebar Donatur
            $sidebar = '
                <li class="nav-item">
                    <a href="dashboard_user.php" class="nav-link ' . ('dashboard_user.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-home"></i>
                        <p> Home </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="donasi_saya.php" class="nav-link ' . (('donasi_saya.php' == $url_sekarang) || ('edit_donasi_saya.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p> Donasi Saya </p>
                    </a>
                </li>
            
                <li class="nav-item">
                    <a href="reservasi_saya.php" class="nav-link ' . ('reservasi_saya.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-suitcase"></i>
                        <p> Reservasi Saya  </p>
                    </a>
                </li>

                <!--
                <li class="nav-item">
                    <a href="profil_saya.php" class="nav-link ' . ('profil_saya.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fas fa-user"></i>
                        <p> Profil Saya  </p>
                    </a>
                </li> -->
                        
                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_laporan_baru.php" class="nav-link ' . ('kelola_laporan_baru.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-globe-americas"></i>
                            <p> Laporan Sebaran</p>
                    </a>
                </li>
                  
                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_laporan.php" class="nav-link ' . ('kelola_laporan.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-angle-down"></i>
                            <p> Sebaran Per-Wilayah </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah -->
                    <a href="arsip_perizinan.php" class="nav-link ' . ('arsip_perizinan.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-scroll"></i>
                            <p> Arsip Perizinan </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="laporan_donasi.php" class="nav-link  ' . ('laporan_donasi.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Laporan Donasi</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="laporan_periode_wisata.php" class="nav-link  ' . ('laporan_periode_wisata.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Laporan Wisata</p>
                    </a>
                </li>
                ';

            echo $sidebar;
        } elseif ($level == 2) { //sidebar Pengelola Wilayah
            $sidebar = '
                <!-- SESSION lvl Untuk wilayah -->
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="dashboard_admin.php" class="nav-link  ' . ('dashboard_admin.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-home"></i>
                        <p> Home</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah Lokasi Pusat -->
                    <a href="kelola_donasi.php" class="nav-link ' . (('kelola_donasi.php' == $url_sekarang) || ('edit_donasi.php'  == $url_sekarang) || ('kelola_pengadaan_bibit.php' == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p> Kelola Donasi  ' . $notifikasi_donasi_verifikasi . '</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="kelola_reservasi_wisata.php" class="nav-link ' . (('kelola_reservasi_wisata.php' == $url_sekarang) || ('edit_reservasi_wisata.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p> Kelola Reservasi ' . $notifikasi_reservasi . '</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link ' . ('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) || ('kelola_harga_terumbu.php'  == $url_sekarang) || ('kelola_biaya_operasional.php'  == $url_sekarang)  ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                        <p> Kelola Lokasi </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_titik.php" class="nav-link ' . ('kelola_titik.php' == $url_sekarang || ('edit_titik.php'  == $url_sekarang) || ('input_titik.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                          <i class="nav-icon fas fa-crosshairs"></i>
                          <p> Kelola Titik </p>
                    </a>
                </li>



                <!-- WISATA COLLAPSE START -->        
        <li class="nav-item ' . (in_array($url_sekarang, [
                'kelola_wisata.php', 'edit_wisata.php', 'kelola_fasilitas_wisata.php', 'input_wisata.php',
                'input_fasilitas_wisata.php', 'input_paket_wisata.php', 'edit_arsip_luas_wilayah.php',
                'kelola_pengadaan_fasilitas.php', 'edit_pengadaan_fasilitas.php',
                'input_pengadaan_fasilitas.php', 'kelola_kerjasama.php', 'edit_kerjasama.php', 'input_kerjasama.php', 'kelola_asuransi.php',
                'edit_asuransi.php', 'input_asuransi.php'
            ])  ? ' active menu-open ' : '') . '"> 
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-briefcase"></i>
              <p>
                Kelola Pariwisata
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
                    <li class="nav-item"> 
                    <a href="kelola_wisata.php" class="nav-link ' . (('kelola_wisata.php' == $url_sekarang) || ('edit_wisata.php'  == $url_sekarang) ||
                ('kelola_fasilitas_wisata.php'  == $url_sekarang) || ('input_wisata.php'  == $url_sekarang) ||
                ('input_fasilitas_wisata.php'  == $url_sekarang) || ('input_paket_wisata.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-suitcase"></i>
                        <p> Kelola Wisata </p>
                    </a>
                </li>

                    <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_pengadaan_fasilitas.php" class="nav-link ' . (('kelola_pengadaan_fasilitas.php' == $url_sekarang) || ('edit_pengadaan_fasilitas.php'  == $url_sekarang) || ('input_pengadaan_fasilitas.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-truck-loading"></i>
                        <p> Kelola Pengadaan </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_kerjasama.php" class="nav-link ' . (('kelola_kerjasama.php' == $url_sekarang) || ('edit_kerjasama.php'  == $url_sekarang) || ('input_kerjasama.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p> Kelola Kerjasama </p>
                    </a>
                </li>

		        <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_asuransi.php" class="nav-link ' . (('kelola_asuransi.php' == $url_sekarang) || ('edit_asuransi.php'  == $url_sekarang) || ('input_asuransi.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-heartbeat"></i>
                        <p> Kelola Asuransi </p>
                    </a>
                </li>

                
            </ul> <!-- WISATA COLLAPSE END -->

                
                
                

                <li class="nav-item"> <!-- Konten Wilayah -->
                    <a href="kelola_konten.php" class="nav-link ' . (('kelola_konten.php' == $url_sekarang) || ('edit_konten.php'  == $url_sekarang) || ('input_konten.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-book"></i>
                        <p> Kelola Konten </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Pusat & Wilayah -->
                    <a href="kelola_rekening_bersama.php" class="nav-link ' . ('kelola_rekening_bersama.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p> Rekening Bersama</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_perizinan.php" class="nav-link ' . ('kelola_perizinan.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-scroll"></i>
                            <p> Kelola Perizinan </p>
                    </a>
                </li>

        <!-- LAPORAN COLLAPSE START -->        
        <li class="nav-item ' . (in_array($url_sekarang, [
                'kelola_laporan_baru.php', 'kelola_laporan.php', 'laporan_kondisi.php', 'laporan_donasi.php',
                'laporan_periode_wisata.php', 'kelola_arsip_laporan_sebaran.php', 'edit_arsip_luas_wilayah.php'
            ])  ? ' active menu-open ' : '') . '"> 
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Kelola Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                        <a href="laporan_donasi.php" class="nav-link  ' . ('laporan_donasi.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p> Laporan Donasi</p>
                        </a>
                    </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="laporan_periode_wisata.php" class="nav-link  ' . ('laporan_periode_wisata.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Laporan Wisata</p>
                    </a>
                </li>   

                    <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_laporan_baru.php" class="nav-link ' . ('kelola_laporan_baru.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-globe-americas"></i>
                            <p> Laporan Sebaran</p>
                    </a>
                </li>
                  
                <li class="nav-item"> <!-- Wilayah -->
                    <a href="kelola_laporan.php" class="nav-link ' . ('kelola_laporan.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-angle-down"></i>
                            <p> Sebaran Per-Wilayah </p>
                    </a>
                </li>

                   <li class="nav-item"> <!-- Wilayah & Pusat -->
                    <a href="laporan_kondisi.php" class="nav-link ' . ('laporan_kondisi.php' == $url_sekarang   ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-heartbeat"></i>
                            <p> Laporan Kondisi </p>
                    </a>
                </li>                

                <li class="nav-item"> <!-- Wilayah & Pusat -->
                    <a href="kelola_arsip_laporan_sebaran.php" class="nav-link ' . ('kelola_arsip_laporan_sebaran.php' == $url_sekarang || ('edit_arsip_luas_wilayah.php'  == $url_sekarang)  ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-history"></i>
                            <p> Kelola Arsip Laporan </p>
                    </a>
                </li>
            </ul> <!-- LAPORAN COLLAPSE END -->

                
                ';

            echo $sidebar;
        } elseif ($level == 3) { //sidebar Pengelola lokasi
            $sidebar = '
                <!-- SESSION lvl Untuk Lokasi -->
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="dashboard_admin.php" class="nav-link  ' . ('dashboard_admin.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-home"></i>
                        <p> Home</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_donasi.php" class="nav-link ' . (('kelola_donasi.php' == $url_sekarang) || ('edit_donasi.php'  == $url_sekarang || 'kelola_pengadaan_bibit.php' == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p> Kelola Donasi ' . $notifikasi_donasi . '</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_wisata_donasi.php?status=baru" class="nav-link  ' . ('kelola_wisata_donasi.php?status=baru' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fab fa-bandcamp"></i>
                        <p> Kelola Donasi Wisata </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="kelola_reservasi_wisata.php" class="nav-link ' . (('kelola_reservasi_wisata.php' == $url_sekarang) || ('edit_reservasi_wisata.php'  == $url_sekarang) || ('kelola_laporan_paket.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p> Kelola Reservasi ' . $notifikasi_reservasi . '</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_batch.php?id_status_batch=1" class="nav-link ' . (('kelola_batch.php' == $url_sekarang) || ('edit_batch.php' == $url_sekarang)  || ('input_batch.php'  == $url_sekarang)  ? ' active ' : '') . ' ">
                          <i class="nav-icon fas fa-boxes"></i>
                          <p> Kelola Batch ' . $notifikasi_batch_penyemaian . '</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Lokasi -->
                    <a href="kelola_pemeliharaan.php?id_status_pemeliharaan=1" class="nav-link ' . ('kelola_pemeliharaan.php' == $url_sekarang  || ('edit_pemeliharaan.php' == $url_sekarang)  || ('input_pemeliharaan.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                          <i class="nav-icon fas fa-heart"></i>
                          <p> Kelola Pemeliharaan ' . $notifikasi_pemeliharaan . '</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Konten Lokasi -->
                    <a href="kelola_konten_master.php" class="nav-link ' . (('kelola_konten_master.php' == $url_sekarang) || ('kelola_konten_tangkolak.php' == $url_sekarang) || ('edit_konten_tangkolak.php'  == $url_sekarang) || ('input_konten_tangkolak.php'  == $url_sekarang) || ('kelola_konten_ketentuan.php' == $url_sekarang)  || ('edit_konten_ketentuan.php' == $url_sekarang)  || ('input_konten_Ketentuan.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-book"></i>
                        <p> Kelola Konten </p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_lokasi.php" class="nav-link ' . ('kelola_lokasi.php' == $url_sekarang  || ('edit_lokasi.php'  == $url_sekarang) || ('input_lokasi.php'  == $url_sekarang) || ('kelola_harga_terumbu.php'  == $url_sekarang) || ('kelola_biaya_operasional.php'  == $url_sekarang)  ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                        <p> Kelola Lokasi </p>
                    </a>
                </li>
                
                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="kelola_titik.php" class="nav-link ' . (('kelola_titik.php' == $url_sekarang) || ('edit_titik.php' == $url_sekarang)  || ('input_titik.php'  == $url_sekarang) ? ' active ' : '') . ' ">
                          <i class="nav-icon fas fa-crosshairs"></i>
                          <p> Kelola Titik </p>
                    </a>
                </li>

                

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="laporan_donasi.php" class="nav-link  ' . ('laporan_donasi.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Laporan Donasi</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Wilayah & Lokasi -->
                    <a href="laporan_periode_wisata.php" class="nav-link  ' . ('laporan_periode_wisata.php' == $url_sekarang ? ' active ' : '') . ' ">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p> Laporan Wisata</p>
                    </a>
                </li>

                <li class="nav-item"> <!-- Lokasi -->
                    <a href="laporan_jenis_terumbu.php" class="nav-link ' . ('laporan_jenis_terumbu.php' == $url_sekarang ? ' active ' : '') . ' ">
                            <i class="nav-icon fas fa-bacteria"></i>
                            <p> Laporan Jenis Terumbu </p>
                    </a>
                </li>
                ';

            echo $sidebar;
        }
    }
}
