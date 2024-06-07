<script>
  var map = L.map('map', {
    editable: true
  }).setView([10.63897561229489, 106.78833399989654], 13);

  var streetmap = L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20,
    zIndex: 30
  }).addTo(map);

  var googlemap = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    zIndex: 29
  }).addTo(map);

  var googlesat = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    zIndex: 28
  }).addTo(map);

  var thuadat = L.tileLayer.wms('http://localhost:8080/geoserver/LuanVan_LMP/wms', {
    layers: 'LuanVan_LMP:thuadat',
    format: 'image/png',
    transparent: true,
    maxZoom: 20,
    zIndex: 44
  });

  var quyhoachsddcaphuyen = L.tileLayer.wms('http://localhost:8080/geoserver/LuanVan_LMP/wms', {
    layers: 'LuanVan_LMP:quyhoachsddcaphuyen',
    format: 'image/png',
    transparent: true,
    maxZoom: 20,
    zIndex: 41
  }).addTo(map);

  var vungthuyhe = L.tileLayer.wms('http://localhost:8080/geoserver/LuanVan_LMP/wms', {
    layers: 'LuanVan_LMP:vungthuyhe',
    format: 'image/png',
    transparent: true,
    maxZoom: 20,
    zIndex: 41
  })

  var matduongbo = L.tileLayer.wms('http://localhost:8080/geoserver/LuanVan_LMP/wms', {
    layers: 'LuanVan_LMP:matduongbo',
    format: 'image/png',
    transparent: true,
    maxZoom: 20,
    zIndex: 41
  })

  var timduong = L.tileLayer.wms('http://localhost:8080/geoserver/LuanVan_LMP/wms', {
    layers: 'LuanVan_LMP:timduong',
    format: 'image/png',
    transparent: true,
    maxZoom: 20,
    zIndex: 41
  })


  var bandonen = L.layerGroup([matduongbo, vungthuyhe, timduong]);

  var baseLayers = {
    "Google Map": googlemap,
    "Google Sat": googlesat,
    "Street Map": streetmap
  };

  var overlays = {
    "Bản đồ Nền": bandonen,
    "Bản đồ Địa chính": thuadat,
    "Bản đồ Quy hoạch": quyhoachsddcaphuyen
  };



  var opacityControl = L.control.opacity({
    "Bản đồ Địa chính": thuadat,
    "Bản đồ Quy hoạch": quyhoachsddcaphuyen
  }).addTo(map);


  L.control.layers(baseLayers, overlays).addTo(map);


  let measurePolygonControl = L.control.measurePolygon();
  measurePolygonControl.addTo(map);

  // Event listener for when overlays are added to the map
  map.on('overlayadd', function(eventLayer) {
    if (eventLayer.name === "Bản đồ Nền") {
      bandonen.bringToFront();
    } else if (eventLayer.name === "Bản đồ Địa chính") {
      thuadat.bringToFront();
    } else if (eventLayer.name === "Bản đồ Quy hoạch") {
      quyhoachsddcaphuyen.bringToFront();
    }
  });

  // Lấy tọa độ khi bấm vào map
  map.on('dblclick', function(e) {
    alert("Tọa độ: " + e.latlng.lat + ", " + e.latlng.lng);
  });


  map.on('click', function(e) {
    // Gửi yêu cầu AJAX tới file PHP để xử lý
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Hiển thị thông tin dạng popupContent
        L.popup()
          .setLatLng(e.latlng)
          .setContent(this.responseText)
          .openOn(map);
      }
    };
    xmlhttp.open("GET", "get_thongtinquyhoach.php?lat=" + e.latlng.lat + "&lng=" + e.latlng.lng, true);
    xmlhttp.send();
  });



  var marker;

  function updateMap(lat, lng) {
    if (marker) {
      map.removeLayer(marker);
    }
    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 20);
  }


</script>