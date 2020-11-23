<?php include 'build/config/connection.php'; ?>
<script >
  //Map Leaflet
  var mymap = L.map('mapid').setView([-6.9032739,107.5731167], 8);

  var layermap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
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

  //Query untuk menampilkan lat long titik pada map
  <?php 
    $sql_map = "SELECT * FROM t_titik";
    $stmt = $pdo->prepare($sql_map);
    $stmt->execute();
    $sql_view = $stmt->fetchAll();
    foreach ($sql_view as $value) { ?>

    var id_titik = <?=$value->id_titik?>;

    L.marker([<?=$value->longitude?>,<?=$value->latitude?>]).addTo(mymap)
    .bindPopup("<b>Longitude:</b> <?=$value->longitude?><br/>"+
    "<b>Latitude:</b> <?=$value->latitude?><br/>"+
    "<b>Luas Titik:</b> <?=$value->luas_titik?> m2<p>"+
    "<a href='pilih_jenis_tk.php?id_titik=<?=$value->id_titik?>' class='btn btn-primary' style='color:white;'>Pilih Titik</a>");

  <?php
    }
  ?>
  //End
</script>

