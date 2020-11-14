<script>
//Fungsi variabel mymap, untuk view keseluaran map di jabar
//var mymap = L.map('mapid').setView([-6.8474505,108.1206506], 8);

//Berfungi untuk menambahkan title layer di map, atribut, maksimum zoom, dan yang paling utama memberikan accesstoken untuk map
/*
var layermap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoic2lkZTkxMCIsImEiOiJja2c3djF3a3gwYjU0MnBxb3lobDFtcmJ0In0.DwvJMR6_vALxyC1KyMiyTA'
});
*/
var tiles = L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
  attribution: '&copy; <a href="//openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
});

var mymap = L.map('mapid', {
    center: L.latLng(-6.9034443,107.5731165),
    zoom: 8,
    layers: [tiles]
});

//Untuk memberikan warna pada geojson map
var bekasi = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var karawang = {
    "color": "#f37735",
    "weight": 3,
    "opacity": 0.65
};
var subang = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var indramayu = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var cirebon = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var kota_cirebon = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var pangandaran = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var tasikmalaya = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var garut = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var cianjur = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var sukabumi = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};
var kota_sukabumi = {
    "color": "#65737e",
    "weight": 2,
    "opacity": 0.65
};

//Fungsi popUp, untuk menampilkan properti yang terdapat di geojson
function popUp(f,l){
    var out = [];
    if (f.properties){
        //for(key in f.properties){}
        out.push("Wilayah: "+f.properties['kemendagri_nama']);
        l.bindPopup(out.join("<br />"));
    }
}

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
        layer: tiles
    }
];

var overLayers = [
    {
        name: "Bekasi",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-bekasi.geojson"],{onEachFeature:popUp,style: bekasi,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Karawang",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-karawang.geojson"],{onEachFeature:popUp,style: karawang,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Subang",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-subang.geojson"],{onEachFeature:popUp,style: subang,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Indramayu",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-indramayu.geojson"],{onEachFeature:popUp,style: indramayu,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Cirebon",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-cirebon.geojson"],{onEachFeature:popUp,style: cirebon,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Kota Cirebon",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-kota-cirebon.geojson"],{onEachFeature:popUp,style: kota_cirebon,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Pangandaran",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-pangandaran.geojson"],{onEachFeature:popUp,style: pangandaran,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Tasikmalaya",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-tasikmalaya.geojson"],{onEachFeature:popUp,style: tasikmalaya,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Garut",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-garut.geojson"],{onEachFeature:popUp,style: garut,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Cianjur",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-cianjur.geojson"],{onEachFeature:popUp,style: cianjur,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Sukabumi",
        layer: new L.GeoJSON.AJAX(["../../dist/js/geojson/wilayah-sukabumi.geojson"],{onEachFeature:popUp,style: sukabumi,pointToLayer: featureToMarker}).addTo(mymap)
    },
    {
        name: "Kota Sukabumi",
        layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-kota-sukabumi.geojson"],{onEachFeature:popUp,style: kota_sukabumi,pointToLayer: featureToMarker}).addTo(mymap)
    }
];

//var panelLayers = new L.Control.PanelLayers(baseLayers, overLayers);
//mymap.addControl(panelLayers);

//icon map
/*
var LeafIcon = L.Icon.extend({
    options: {
        iconSize:     [20, 30],
    }
});
var icon = new LeafIcon({iconUrl: 'dist/img/marker-icon-1.png'});*/

//titik koordinat kabupaten untuk wilayah di jabar
var addressPoints = [
    //Latlong Wilayah
    /*
    [-6.2841796,106.833289], //Bekasi
    [-6.2640495,107.083529], //Karawang
    [-6.4945321,107.4543786], //Subang
    [-6.451024,107.9145671], //Indramayu
    [-6.7605414,108.3014452], //Cirebon
    [-6.7427761,108.5190193], //Kota Cirebon
    [-7.6400957,108.4166047], //Pangandaran
    [-7.3598063,108.1627121], //Tasikmalaya
    [-7.3425596,107.4979527], //Garut
    [-7.0516816,106.5699387], //Cianjur
    [-7.0750637,106.4375612], //Sukabumi
    [-6.9356245,106.8807472], //Kota Sukabumi
    */
    //Latlong Lokasi Pantai
    [-6.1815819,107.5597572], //Pantai Tengkolak Karawang
    //Latlong Titik Terumbu Karang
    [-6.178155,107.5640759],
    [-6.178134,107.564295],
    [-6.178347,107.564445],
    [-6.1788646,107.564263],
    [-6.178795,107.564016],
];

//clustering map
var groupMarkers = L.markerClusterGroup({
    chunkedLoading: true,
    //singleMarkerMode: true,
    spiderfyOnMaxZoom: false
});

for (var i = 0; i < addressPoints.length; i++) {
    var a = addressPoints[i];
    //var title = a[2];
    var marker = L.marker(new L.LatLng(a[0], a[1]),/*{ title: title }*/);
    //marker.bindPopup(title);
    groupMarkers.addLayer(marker);
}
mymap.addLayer(groupMarkers);

/*
L.marker([-6.2841796,106.833289], {icon: icon}).addTo(mymap); //Bekasi
L.marker([-6.2640495,107.083529], {icon: icon}).addTo(mymap); //Karawang
L.marker([-6.4945321,107.4543786], {icon: icon}).addTo(mymap); //Subang
L.marker([-6.451024,107.9145671], {icon: icon}).addTo(mymap); //Indramayu
L.marker([-6.7605414,108.3014452], {icon: icon}).addTo(mymap); //Cirebon
L.marker([-6.7427761,108.5190193], {icon: icon}).addTo(mymap); //Kota Cirebon
L.marker([-7.6400957,108.4166047], {icon: icon}).addTo(mymap); //Pangandaran
L.marker([-7.3598063,108.1627121], {icon: icon}).addTo(mymap); //Tasikmalaya
L.marker([-7.3425596,107.4979527], {icon: icon}).addTo(mymap); //Garut
L.marker([-7.0516816,106.5699387], {icon: icon}).addTo(mymap); //Cianjur
L.marker([-7.0750637,106.4375612], {icon: icon}).addTo(mymap); //Sukabumi
L.marker([-6.9356245,106.8807472], {icon: icon}).addTo(mymap); //Kota Sukabumi
*/
</script>