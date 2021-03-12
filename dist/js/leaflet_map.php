<?php include 'build/config/connection.php'; ?>
<script >
  //Map Leaflet
  var mymap = L.map('mapid').setView([-6.6454502,107.5168079], 9);

  var layermap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 13,
      id: 'mapbox/streets-v11',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: 'pk.eyJ1Ijoic2lkZTkxMCIsImEiOiJja2c3djF3a3gwYjU0MnBxb3lobDFtcmJ0In0.DwvJMR6_vALxyC1KyMiyTA'
  });
  mymap.addLayer(layermap);
  //End

  //Mengambil data GEOJson dan menentukan warna untuk tiap-tiap GEOJson
  <?php
    $kecamatan = [
      "wilayah_bekasi"=>"#173F5F", //Biru Tua
      "wilayah_cianjur"=>"#173F5F", //Biru Tua
      "wilayah_cirebon"=>"#173F5F", //Biru Tua
      "wilayah_garut"=>"#173F5F", //Biru Tua
      "wilayah_indramayu"=>"#173F5F", //Biru Tua
      "wilayah_karawang"=>"#f37735", //Orange
      "wilayah_kota_cirebon"=>"#173F5F", //Biru Tua
      "wilayah_kota_sukabumi"=>"#173F5F", //Biru Tua
      "wilayah_pangandaran"=>"#173F5F", //Biru Tua
      "wilayah_subang"=>"#173F5F", //Biru Tua
      "wilayah_sukabumi"=>"#173F5F", //Biru Tua
      "wilayah_tasikmalaya"=>"#173F5F" //Biru Tuaz
    ];
  ?>
  //End

  //Fungsi popUp, untuk menampilkan properti yang terdapat di GEOJson
  function popUp(f,l){
      var out = [];
      if (f.properties){
          //for(key in f.properties){}
          out.push("<b>Wilayah: "+"</b>"+f.properties['kemendagri_nama']);
          l.bindPopup(out.join("<br/>"));
      }
  }
  //End

  //Legend
  function iconByName(name) {
      return '<i class="icon icon-'+name+'"></i>';
  }

  function featureToMarker(feature, latlng) {
      return L.marker(latlng, {
          icon: L.divIcon({
              className: 'marker-'+feature.properties.amenity,
              html: iconByName(feature.properties.amenity),
              iconUrl: '../images/markers/'+feature.properties.amenity+'.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34],
              shadowSize: [41, 41]
          })
      });
  }

  var baseLayers = [
      {
          name: "JawaBarat",
          layer: layermap
      }
  ];

  //Meloop GEOJson
  <?php
    foreach ($kecamatan as $key => $value) { ?>

      var warnaMap<?=$key?> = {
        "color": "<?=$value?>",
        "weight": 3,
        "opacity": 0.65
      };

      <?php
      $arrayKec[]='{
        name: "'.$key.'",
        layer: new L.GeoJSON.AJAX(["dist/js/geojson/'.$key.'.geojson"],{onEachFeature:popUp,style: warnaMap'.$key.',pointToLayer: featureToMarker}).addTo(mymap)
      }';
    }
  ?>

  var overLayers = [
    <?=implode(',', $arrayKec);?>
  ];

  //var panelLayers = new L.Control.PanelLayers(baseLayers, overLayers);
  //mymap.addControl(panelLayers);
  //End

  //SESSION MAP UNTUK ADMIN
  <?php //if($_SESSION['level_user'] == '2' || $_SESSION['level_user'] == '3') { ?>
    //Query untuk menampilkan lat long titik pada map
    //Clustering marker pada bagian titik
    var marker_titik = L.markerClusterGroup();

    <?php
      $sql_map = "SELECT * FROM t_titik";
      $stmt = $pdo->prepare($sql_map);
      $stmt->execute();
      $sql_view = $stmt->fetchAll();
      foreach ($sql_view as $value) { ?>

      //Icon marker sesuai kondisi titik
      var myIcon<?=$value->id_titik?> = L.icon({
          iconUrl: '<?=($value->kondisi_titik=='')?('images/foto_kondisi_titik/baik.png'):('images/foto_kondisi_titik/'.$value->kondisi_titik.'.png')?>',
          iconSize: [38, 45]
      });

      // var id_titik = <?=$value->id_titik?>;

      // var marker = L.marker([<?=$value->latitude?>,<?=$value->longitude?>], {icon: myIcon<?=$value->id_titik?>})
      // .bindPopup(
      // "<b>Longitude: </b><?=$value->longitude?><br/>"+
      // "<b>Latitude: </b><?=$value->latitude?><br/>"+
      // "<b>Luas Titik: </b><?=$value->luas_titik?> ha<br/>"+
      // "<b>Kondisi Titik: </b><?=$value->kondisi_titik?><p>"+
      // "<a href='pilih_jenis_tk.php?id_lokasi=<?=$value->id_lokasi?>' class='btn btn-primary' style='color:white;'>Pilih Lokasi</a>");
      // marker_titik.addLayer(marker);

    <?php } ?>
    mymap.addLayer(marker_titik);
    //End
  <?php //} ?>
  //End SESSION

  //SESSION MAP UNTUK USER
  <?php //if($_SESSION['level_user'] == '1') { ?>
    //Query untuk menampilkan lat long lokasi pada map
    //Clustering marker pada bagian lokasi
    var marker_lokasi = L.markerClusterGroup();

    //Icon marker sesuai lokasi pantai
    var myIcon = L.icon({
        iconUrl: '<?=('images/foto_lokasi/icon_lokasi/icon_lokasi.png')?>',
        iconSize: [38, 45]
    });

    <?php
      //Reservasi Wisata
      if (isset($_GET['aksi'])) {
        $wisata = $_GET['aksi'];
      } else {
        $wisata = null;
      }

      $sql_map = 'SELECT *, SUM(luas_titik) AS total_titik,
                                  COUNT(DISTINCT id_titik) AS jumlah_titik,
                                  SUM(DISTINCT luas_lokasi) AS total_lokasi,
                                  SUM(DISTINCT luas_titik) / SUM(DISTINCT luas_lokasi) * 100 AS persentase_sebaran,
                                  COUNT(id_titik) AS jumlah_titik, COUNT(case when kondisi_titik = "Kurang" then 1 else null end) as jumlah_kurang,
                                  COUNT(case when kondisi_titik = "Cukup" then 1 else null end) as jumlah_cukup,
                                  COUNT(case when kondisi_titik = "Baik" then 1 else null end) as jumlah_baik,
                                  COUNT(case when kondisi_titik = "Sangat Baik" then 1 else null end) as jumlah_sangat_baik

                                  FROM `t_titik`, t_lokasi, t_wilayah
                                  WHERE t_titik.id_lokasi = t_lokasi.id_lokasi
                                  AND t_lokasi.id_wilayah = t_wilayah.id_wilayah
                                  GROUP BY t_lokasi.id_lokasi';

      $stmt = $pdo->prepare($sql_map);
      $stmt->execute();
      $sql_viewlokasi = $stmt->fetchAll();

      foreach ($sql_viewlokasi as $value) {
        $ps = $value->persentase_sebaran;
                      if($ps >= 0 && $ps < 25){
                        $kondisi_wilayah = 'Kurang';
                        $warna_teks = 'text-danger';
                      }
                      else if($ps >= 25 && $ps < 50){
                        $kondisi_wilayah = 'Cukup';
                        $warna_teks = 'text-warning';
                      }
                      else if($ps >= 50 && $ps < 75){
                        $kondisi_wilayah = 'Baik';
                        $warna_teks = 'text-success';
                      }
                      else{
                        $kondisi_wilayah = 'Sangat Baik';
                        $warna_teks = 'text-primary';
                      }

        ?>

        var marker = L.marker([<?=$value->latitude?>,<?=$value->longitude?>], {icon: myIcon})
        .bindPopup(
        "<b>Nama Lokasi: </b><?=$value->nama_lokasi?><br/>"+
        "<b>Persentase Sebaran: </b><?=number_format($value->persentase_sebaran, 1)?>%<br/>"+
        "<b>Kondisi Terumbu Karang: </b><b class='<?=$warna_teks?>'><?=$kondisi_wilayah?></b><br/>"+
        "<b>Foto Lokasi: <br/></b><img src='<?=$value->foto_lokasi?>' class='card-img-top rounded mb-2'><br/>"+
        "<?php if($wisata == null) { ?>"+
        "<div class='col text-center'><a href='pilih_terumbu_karang.php?id_lokasi=<?=$value->id_lokasi?>' class='btn btn-primary text-center' style='color:white;'>Pilih Lokasi</a></div>"+
        "<?php } else {?>"+
        "<div class='col text-center'><a href='pilih_lokasi_wisata.php?id_lokasi=<?=$value->id_lokasi?>' class='btn btn-primary text-center' style='color:white;'>Pilih Lokasi</a></div>"+
        "<?php } ?>"
        );
        marker_lokasi.addLayer(marker);




      // $sql_map = "SELECT * FROM t_lokasi";
      // $stmt = $pdo->prepare($sql_map);
      // $stmt->execute();
      // $sql_viewlokasi = $stmt->fetchAll();
      // foreach ($sql_viewlokasi as $value) { ?>

      // var marker = L.marker([<?=$value->latitude?>,<?=$value->longitude?>], {icon: myIcon})
      // .bindPopup(
      // "<b>Nama Lokasi: </b><?=$value->nama_lokasi?><br/>"+
      // "<b>Luas Lokasi: </b><?=$value->luas_lokasi?> ha<br/>"+
      // "<b>Foto Lokasi: <br/></b><img src='<?=$value->foto_lokasi?>' class='card-img-top mb-2'><br/>"+
      // "<a href='pilih_jenis_tk.php?id_lokasi=<?=$value->id_lokasi?>' class='btn btn-primary' style='color:white;'>Pilih Lokasi</a>");
      // marker_lokasi.addLayer(marker);

    <?php } ?>
    mymap.addLayer(marker_lokasi);
    //End
  <?php //} ?>
  //End SESSION
</script>
