<?php include 'build/config/connection.php'; ?>
<script >
    var tiles = L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="//openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
    });

    var mymap = L.map('mapid', {
        center: L.latLng(-6.9034443,107.5731165),
        zoom: 8,
        layers: [tiles]
    });


     <?php 
              $sql_map = "SELECT * FROM t_titik";
              $stmt = $pdo->prepare($sql_map);
              $stmt->execute();
              $sql_view = $stmt->fetchAll();
              foreach ($sql_view as $value) { ?>

              var id_titik = <?=$value->id_titik?>;

                L.marker([<?=$value->longitude?>,<?=$value->latitude?>]).addTo(mymap)
          .bindPopup("<b>Latitude: <?=$value->latitude?></b><br/>"+
          "<b>Longitude: <?=$value->longitude?></b><br/>"+
          "<b>Luas Titik: <?=$value->luas_titik?> m2</b><br/>"+
          "<a href='donasi.php?id_titik=<?=$value->id_titik?>' class='btn btn-primary'>Pilih Lokasi</a>");

             <?php
              }
            ?>

</script>

