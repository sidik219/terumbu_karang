//Fungsi variabel mymap, untuk view keseluaran map di jabar
var mymap = L.map('mapid').setView([-6.8474505,108.1206506], 8);

//Berfungi untuk menambahkan title layer di map, atribut, maksimum zoom, dan yang paling utama memberikan accesstoken untuk map
var layermap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoic2lkZTkxMCIsImEiOiJja2c3djF3a3gwYjU0MnBxb3lobDFtcmJ0In0.DwvJMR6_vALxyC1KyMiyTA'
});
mymap.addLayer(layermap);

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
        layer: layermap
    }
];

var overLayers = [
{
    name: "Bekasi",
    icon: iconByName('bar'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-bekasi.geojson"],{onEachFeature:popUp,style: bekasi,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Karawang",
    icon: iconByName('drinking_water'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-karawang.geojson"],{onEachFeature:popUp,style: karawang,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Subang",
    icon: iconByName('fuel'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-subang.geojson"],{onEachFeature:popUp,style: subang,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Indramayu",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-indramayu.geojson"],{onEachFeature:popUp,style: indramayu,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Cirebon",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-cirebon.geojson"],{onEachFeature:popUp,style: cirebon,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Kota Cirebon",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-kota-cirebon.geojson"],{onEachFeature:popUp,style: kota_cirebon,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Pangandaran",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-pangandaran.geojson"],{onEachFeature:popUp,style: pangandaran,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Tasikmalaya",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-tasikmalaya.geojson"],{onEachFeature:popUp,style: tasikmalaya,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Garut",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-garut.geojson"],{onEachFeature:popUp,style: garut,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Cianjur",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-cianjur.geojson"],{onEachFeature:popUp,style: cianjur,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Sukabumi",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-sukabumi.geojson"],{onEachFeature:popUp,style: sukabumi,pointToLayer: featureToMarker}).addTo(mymap)
},
{
    name: "Kota Sukabumi",
    icon: iconByName('parking'),
    layer: new L.GeoJSON.AJAX(["dist/js/geojson/wilayah-kota-sukabumi.geojson"],{onEachFeature:popUp,style: kota_sukabumi,pointToLayer: featureToMarker}).addTo(mymap)
}
];

var panelLayers = new L.Control.PanelLayers(baseLayers, overLayers);

mymap.addControl(panelLayers);

//icon map
/*
var LeafIcon = L.Icon.extend({
    options: {
        iconSize:     [20, 30],
        shadowSize:   [50, 64],
        iconAnchor:   [22, 94],
        shadowAnchor: [4, 62],
        popupAnchor:  [-3, -76]
    }
});

var iconA = new LeafIcon({iconUrl: 'dist/img/marker-icon-1.png'}),
    iconB = new LeafIcon({iconUrl: 'dist/img/marker-icon-2.png'}),
    iconC = new LeafIcon({iconUrl: 'dist/img/marker-icon-3.png'});

L.marker([-6.2841796,106.833289], {icon: iconA}).addTo(mymap); //Bekasi
L.marker([-6.2640495,107.083529], {icon: iconB}).addTo(mymap); //Karawang
L.marker([-6.4945321,107.4543786], {icon: iconC}).addTo(mymap); //Subang*/