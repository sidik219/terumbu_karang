<script>
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
    $getdata = mysqli_query($koneksi,"SELECT * FROM t_titik");
    foreach ($getdata as $key => $value) {?>
        L.marker([<?=$value->latitude?>,<?=$value->longitude?>]).addTo(mymap);
<?php } ?>

</script>