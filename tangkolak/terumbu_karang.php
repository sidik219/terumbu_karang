<?php
include '../build/config/connection.php';

// $sqlviewwilayah = 'SELECT *, SUM(luas_titik) AS total_titik,
//                     COUNT(t_titik.id_titik) AS jumlah_titik,
//                     SUM(t_lokasi.luas_lokasi) / (SELECT COUNT(t_titik.id_titik) GROUP BY t_titik.id_titik) AS total_lokasi,
//                     (SUM(t_titik.luas_titik) / (SUM(t_lokasi.luas_lokasi) / (SELECT COUNT(t_titik.id_titik) GROUP BY t_titik.id_titik))) * 100 AS persentase_sebaran

//                     FROM t_titik, t_lokasi, t_wilayah
// 				    WHERE t_titik.id_lokasi = t_lokasi.id_lokasi
//                     AND t_lokasi.id_wilayah = t_wilayah.id_wilayah
//                     GROUP BY t_wilayah.id_wilayah
//                     ORDER BY t_lokasi.id_wilayah ASC';

$sqlviewwilayah = 'SELECT * FROM t_detail_lokasi 
LEFT JOIN t_lokasi ON t_detail_lokasi.id_lokasi = t_lokasi.id_lokasi
LEFT JOIN t_terumbu_karang ON t_detail_lokasi.id_terumbu_karang=t_terumbu_karang.id_terumbu_karang
WHERE t_lokasi.kode_lokasi="KARA001"';
$stmt = $pdo->prepare($sqlviewwilayah);
$stmt->execute();
$rowdetail = $stmt->fetchAll();
// var_dump($rowdetail);
// die;

// 'SELECT *,
//             SUM(luas_titik) AS luas_total, COUNT(id_titik) AS jumlah_titik,

//             -- COUNT(case when kondisi_titik = "Kurang" then 1 else null end) as jumlah_kurang,
//             -- COUNT(case when kondisi_titik = "Cukup" then 1 else null end) as jumlah_cukup,
//             -- COUNT(case when kondisi_titik = "Baik" then 1 else null end) as jumlah_baik,
//             -- COUNT(case when kondisi_titik = "Sangat Baik" then 1 else null end) as jumlah_sangat_baik

//             FROM t_wilayah
//             LEFT JOIN t_titik ON t_wilayah.id_wilayah = t_titik.id_wilayah
//             LEFT JOIN t_lokasi ON t_wilayah.id_wilayah = t_lokasi.id_wilayah
//             GROUP BY nama_wilayah';

$stmt = $pdo->prepare($sqlviewwilayah);
$stmt->execute();
$rowwilayah = $stmt->fetchAll();

// ChartJS Content
$kondisi = array('Kurang', 'Cukup', 'Baik', 'Sangat Baik');

for ($i = 0; $i < count($kondisi); $i++) {

    $kondisi_titik = $kondisi[$i];

    // Donasi
    $sqlTitik = 'SELECT COUNT(id_titik) AS jumlah_titik FROM t_titik
                            WHERE kondisi_titik = :kondisi_titik
                            AND id_lokasi = 5';

    $stmt = $pdo->prepare($sqlTitik);
    $stmt->execute(['kondisi_titik' => $kondisi_titik]);
    $totalTitik = $stmt->fetch();

    $jumlah_titik[] = $totalTitik->jumlah_titik;
    // var_dump($kondisi_titik);
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icon Title -->
    <link rel="icon" href="img/KKPlogo.png">
    <title>Wisata Bahari Tangkolak</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/pantai.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b41ecad032.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- Navbar Container-->
    <div class="navbar-tkjb fixed-top">
        <!-- Navbar -->
        <nav class="flex-wrap navpadd navbar navbar-expand-lg navbar-light ">
            <!-- Navbar First Layer -->
            <!-- Logo Holder -->
            <a class="navbar-brand" href="index.php">
                <img id="logo-tkjb-navbar" src="img/TANGKOLAK3.png">
            </a>
            <!-- Menu Toogler -->
            <button class="navbar-toggler custom-toggler hamburger-menu" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>
            <!-- Button & Link Action -->
            <ul class="ml-auto d-none d-lg-block navbar-nav">
                <button class="btn radius-50 py-1.5 px-4 ml-3 btn-wisata " onclick="window.location.href='wisata_tangkolak.php'">Reservasi Wisata</button>
                <button class="btn radius-50 py-1.5 px-5 ml-3 btn-login " onclick="window.location.href='login.php'">Login</button>
            </ul>
            <!-- END Navbar First Layer -->
            <!-- Navbar Second Layer -->
            <div class="navbar-tkjb-navigation col px-0 collapse navbar-collapse" id="navbarTogglerDemo02">
                <!-- Navbar Menu -->
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item ">
                        <a class="nav-link " href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="wisata_tangkolak.php">Wisata Bahari</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="paket_wisata.php">Paket Wisata</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link current" href="terumbu_karang.php">Terumbu Karang Tangkolak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://tkjb.or.id/" target="_blank">Website GoKarang</a>
                    </li>
                </ul>
                <!-- END Navbar Menu -->
                <!-- Navbar Button & Link Action Mobile Version-->
                <div class="d-flex d-lg-none p-3 mobile-act-button">
                    <div class="row-mid">
                        <button class="btn radius-50 py-1.5 px-4  btn-wisata " onclick="window.location.href='wisata_tangkolak.php'">Reservasi Wisata</button>
                    </div>
                    <div class="row-mid d-none d-md-block">
                        <p>

                        </p>
                    </div>
                    <div class="row-mid">
                        <button class="btn radius-50 py-1.5 px-5 btn-login " onclick="window.location.href='login.php'">Login</button>
                    </div>
                </div>
                <!-- END Navbar Button & Link Action Mobile Version-->
            </div>
            <!-- END Navbar Second Layer -->
        </nav>
        <!-- END Navbar -->
    </div>
    <!-- END Navbar Container -->

    <!-- Konten -->
    <div class="informational">
        <div class="informational-container">


            <!-- Konten Mid Container -->
            <div class="header-tkjb">
                <div class="tkjb-banner">
                    <div>
                        <picture>
                            <source srcset="img/donasi1.jpg" media="(min-width: 604px)">
                            <source srcset="img/donasi2.jpg" media="(max-width: 604px)">
                            <img src="img/jembatan.jpg" alt="Slide 1 Image" class="d-block img-fluid" width="100%">
                        </picture>
                        <div class="carousel-caption donasi-caption">
                            <div>
                                <h2>Bersama GoKarang Bantu lestarikan terumbu karang di pantai Tangkolak</h2>
                            </div>
                            <a href="../konten-donasi.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                                Info Donasi
                            </a>
                        </div>
                    </div>
                </div>




            </div>
            <!-- End Konten Mid Container -->
            <div class="coralpedia-card coralpedia-title">
                <h2> Eksplor Terumbu Karang Tangkolak </h2>
                <div class="coralpedia-paragraph2">
                    Berikut merupakan jenis terumbu karang yang terdapat di wilayah pantai Tangkolak :
                </div>
                <div class="row">
                    <?php foreach ($rowdetail as $terumbu) : ?>
                        <div class="col-md-4">
                            <div class="card card-pilihan mb-4 shadow-sm">
                                <a href="">
                                    <img class="card-img-top berita-img" width="100%" src="../<?= $terumbu->foto_terumbu_karang; ?>">
                                </a>
                                <div class="card-body">
                                    <p>
                                    <h5 class="max-length"><?= $terumbu->nama_terumbu_karang; ?></h5>
                                    </p>
                                    <p class="max-length2"><?= $terumbu->deskripsi_terumbu_karang; ?></p>
                                    <div class="collapse" id="collapseExample<?= $terumbu->id_terumbu_karang; ?>">
                                        <div class="card card-body">
                                            <?= $terumbu->deskripsi_terumbu_karang; ?>
                                        </div>
                                        <div class="card card-body">
                                            Tumbuh di daerah : Karawang, Indramayu, Cirebon
                                        </div>
                                    </div>
                                    <p>
                                        <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" data-toggle="collapse" href="#collapseExample<?= $terumbu->id_terumbu_karang; ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Lihat Detail
                                        </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>



            </div>
        </div>
        <!-- End Konten -->



        <!-- Footer -->
        <section id="footer">
            <div class="row">
                <div class="blogo col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <a href="#"><img src="img/footer-logo.png" id="footer-logo" alt="Tangkolak Footer Logo"></a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 cr-tkjb">
                    <div class="cpt text-light text-center">
                        <p>© 2021 - Wisata Bahari Tangkolak</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <div class="ftaw text-light text-center">
                        <a href="#" target="_blank"><i class="fa fa-phone-square-alt"></i></a>
                        <a href="#" target="_blank"><i class="fas fa-envelope-square"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-facebook-square"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Footer -->

        <!-- Bootstrap JS & JQuery -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- CharJs CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js" integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.js" integrity="sha512-XcsV/45eM/syxTudkE8AoKK1OfxTrlFpOltc9NmHXh3HF+0ZA917G9iG6Fm7B6AzP+UeEzV8pLwnbRNPxdUpfA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- Chartjs -->
        <script>
            // ==============================================================
            // Test View Data Kondisi TA Untuk Content

            // TK
            $('#btn-titik').click(function() {
                $('#titik').get(0).toBlob(function(blob) {
                    saveAs(blob, 'data_kondisi_titik.png')
                });
            });

            // Any of the following formats may be used
            var ctx = document.getElementById('titik');
            var wisata = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Kurang", "Cukup", "Baik", "Sangat Baik"], //kondisi titik
                    datasets: [{
                        label: '2021',
                        data: <?php echo json_encode($jumlah_titik); ?>, //Total Pendapatan Berdasarkan Bulan
                        // data: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Data Kondisi Titik',
                            padding: {
                                top: 10,
                                bottom: 30
                            }
                        }
                    }
                }
            });
        </script>

</body>

</html>