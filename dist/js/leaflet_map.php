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
      $sql_view = mysqli_query($koneksi, "SELECT * FROM t_titik");
      foreach ($sql_view as $value) { ?>
          L.marker([<?=$value->latitude?>,<?=$value->longitude?>]).addTo(mymap)
          .bindPopup("<b>Latitude: <?=$value->latitude?></b><br/>"+
          "<b>Longtitude: <?=$value->longitude?></b><br/>"+
          "<b>Luas Titik: <?=$value->luas_titik?></b><br/>");
    <?php } ?>

</script>

