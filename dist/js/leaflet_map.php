<?php include '../../build/config/connection.php'; ?>
<script >
    var mymap = L.map('mapid').setView([-6.9034443,107.5731165], 8)

    var layermap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1Ijoic2lkZTkxMCIsImEiOiJja2c3djF3a3gwYjU0MnBxb3lobDFtcmJ0In0.DwvJMR6_vALxyC1KyMiyTA'
    }).addTo(mymap);

    <?php
        $sql_view = mysqli_query($koneksi, "SELECT * FROM t_titik"); 
        foreach ($sql_view as $key => $value) { ?>
          L.marker([<?=$value->latitude?>,<?=$value->longitude?>]).addTo(mymap)
          .bindPopup("<b>Latitude: <?=$value->latitude?></b><br/>"+
          "<b>Longtitude: <?=$value->longitude?></b><br/>"+
          "<b>Luas Titik: <?=$value->luas_titik?></b><br/>");
    <?php } ?>

</script>

