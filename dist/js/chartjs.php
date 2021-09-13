<?php include 'build/config/connection.php'; ?>
<script>
    // Donasi
    $('#btn-donasi').click(function () {
        $('#donasi').get(0).toBlob(function (blob) {
            saveAs(blob, 'data_donasi.png')
        });
    });

    const Donatur = document.getElementById('Donatur');
    Donatur.addEventListener('change', pertahunDonatur);

    function pertahunDonatur() {
        const label = Donatur.options[Donatur.selectedIndex].text;
        donasi.data.datasets[0].label = label;
        donasi.data.datasets[0].data = Donatur.value.split(',');
        donasi.update();
    }

    // Any of the following formats may be used
    var ctx = document.getElementById('donasi');
    var donasi = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($label); ?>, //12 Bulan
            datasets: [{
                label: '2021',
                data: <?php echo json_encode($total_donasi); ?>, //Total Donasi Berdasarkan Bulan
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
                    text: 'Data Donatur',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            }
        }
    });

    // ==============================================================
    // Wisata
    $('#btn-wisata').click(function () {
        $('#wisata').get(0).toBlob(function (blob) {
            saveAs(blob, 'data_pengunjung.png')
        });
    });

    // Any of the following formats may be used
    var ctx = document.getElementById('wisata');
    var wisata = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($label); ?>, //12 Bulan
            datasets: [{
                label: '2021',
                data: <?php echo json_encode($total_reservasi); ?>, //Total Pengunjung Berdasarkan Bulan
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
                    text: 'Data Wisatawan',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            }
        }
    });

    // ==============================================================
    // Test View Data Money Yang Masuk Per Bulan

    // Donasi
    $('#btn-duid-donasi').click(function () {
        $('#duid-donasi').get(0).toBlob(function (blob) {
            saveAs(blob, 'data_pendapatan_perbulan.png')
        });
    });

    // Any of the following formats may be used
    var ctx = document.getElementById('duid-donasi');
    var wisata = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($label); ?>, //12 Bulan
            datasets: [{
                label: '2021',
                data: <?php echo json_encode($pendapatan_donasi); ?>, //Total Pendapatan Berdasarkan Bulan
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
                borderWidth: 1,
                showLine: true,
                spanGaps: true
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
                    text: 'Data Donasi',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            }
        }
    });

    // Wisata
    $('#btn-duid-wisata').click(function () {
        $('#duid-wisata').get(0).toBlob(function (blob) {
            saveAs(blob, 'data_pendapatan_perbulan.png')
        });
    });

    // Any of the following formats may be used
    var ctx = document.getElementById('duid-wisata');
    var wisata = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($label); ?>, //12 Bulan
            datasets: [{
                label: '2021',
                data: <?php echo json_encode($pendapatan_wisata); ?>, //Total Pendapatan Berdasarkan Bulan
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
                borderWidth: 1,
                showLine: true,
                spanGaps: true
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
                    text: 'Data Reservasi Wisata',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            }
        }
    });
</script>