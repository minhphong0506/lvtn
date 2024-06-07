<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WebGIS HuyenCanGio TPHCM</title>

  <?php require_once 'style.php'; ?>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-win8.css">

  <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

  <script src="plugin/Leaflet.Control.Opacity/dist/L.Control.Opacity.js"></script>
  <link href="plugin/Leaflet.Control.Opacity/dist/L.Control.Opacity.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/aspirity/leaflet-opacity@0.5.0/Control.Opacity.css" />
  <script src="https://cdn.jsdelivr.net/gh/aspirity/leaflet-opacity@0.5.0/Control.Opacity.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/dayjournal/Leaflet.Control.Opacity/dist/L.Control.Opacity.css" />
  <script src="https://cdn.jsdelivr.net/gh/dayjournal/Leaflet.Control.Opacity/dist/L.Control.Opacity.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
  <link rel="stylesheet" href="libs/leaflet-measure-path.css" />
  <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
  <script src="libs/editable.js"></script>
  <script src="libs/measure.js"></script>
  <script src="leaflet.MeasurePolygon.js"></script>


</head>

<body>
  <!-- Code container-fluid Full màn hình chia thành 12 cột, Hiển thị đầu tiên -->
  <div class="container-fluid">
    <!-- Hàng 1 chứa nội dung Đầu tiên -->
    <div class="row" style="height: 63px">
      <nav class="navbar navbar-expand-sm navbar-text-dark w3-amber">
        <div class="container-fluid">
          <a class="navbar-brand" href="javascript:void(0)">
            <h3><strong>Thông Tin Quy Hoạch Xã Bình Khánh</strong></h3>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)">
                  <button type="button" class="btn btn-outline-dark" id="home" onclick="reloadPage()">
                    Trang chủ <i class="fa-solid fa-house"></i>
                  </button>
                </a>
              </li>
              <li class="nav-item">
                <div class="nav-link" href="javascript:void(0)">
                  <div class="dropdown">
                    <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">
                      Hỗ trợ <i class="fa-solid fa-handshake-angle"></i>
                    </button>
                    <ul class="dropdown-menu" id="hotro">
                      <li><a class="dropdown-item" href="75_2015_TT-BTNMT.pdf" target="_blank">Hướng dẫn</a></li>
                      <li><a class="dropdown-item" href="ChuGiai.pdf" target="_blank">Chú giải</a></li>
                    </ul>
                  </div>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="update/indexquantri.php">
                  <button type="button" class="btn btn-outline-dark">
                    Quản trị <i class="fa-solid fa-right-to-bracket"></i>
                  </button>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://mail.google.com/mail/?view=cm&fs=1&to=0950040002@sv.hcmunre.edu.vn" target="_blank">
                  <button type="button" class="btn btn-outline-dark">
                    Giới thiệu <i class="fa-solid fa-circle-info"></i>
                  </button>
                </a>
              </li>
            </ul>
            <form class="d-flex">
              <button class="btn w3-button w3-brown" type="button" data-bs-toggle="offcanvas" data-bs-target="#tracuu">
                <strong>Tra cứu</strong> <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </form>
          </div>
        </div>
      </nav>
    </div>
    <!-- Hàng 2 chứa Bản đồ-->
    <div class="row">
      <div class="col-md-12" id="map">

      </div>
    </div>
    <!-- Hàng 3 chứa nội dung Chân của Web-->
    <div class="row">
      <div class="col-md-12 w3-amber">
        <h6 style="text-align: center;">
          <strong>
            Lê Minh Phong -
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=0950040002@sv.hcmunre.edu.vn" target="_blank">0950040002@sv.hcmunre.edu.vn</a> -
            Trường Đại học Tài nguyên và Môi trường Thành phố Hồ Chí Minh
          </strong>
        </h6>
      </div>
    </div>
  </div>
  <!-- Code của thẻ div offcanvas xuất sau khi ấn vào nút Tra cứu-->
  <div class="offcanvas offcanvas-end bg-dark-subtle text-dark w-50" id="tracuu">
    <div class="offcanvas-header">
      <h3 class="offcanvas-title">Tra cứu thông tin</h3>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-md-6">
          <div class="input-group input-group-sm mb-3">
            <span class="input-group-text bg-warning-subtle">
              <strong>Tờ số</strong>
            </span>
            <input type="number" min="1" class="form-control" id="soto" placeholder="Nhập số tờ bản đồ" name="soto">
          </div>
          <div class="input-group input-group-sm mb-3">
            <span class="input-group-text bg-warning-subtle">
              <strong>Loại đất</strong>
            </span>
            <input type="search" class="form-control" id="loaidat" placeholder="Nhập loại đất" name="loaidat">
          </div>
          <button type="search" class="w3-button w3-light-blue w3-border w3-border-black w3-round-large w-100" style="height: 35px" id="search">
            <strong>Tìm kiếm</strong>
          </button>
        </div>
        <div class="col-md-6">
          <div class="input-group input-group-sm mb-3">
            <span class="input-group-text bg-warning-subtle">
              <strong>Thửa số</strong>
            </span>
            <input type="number" min="1" class="form-control" id="sothua" placeholder="Nhập số thửa đất" name="sothua">
          </div>
          <div class="input-group input-group-sm mb-3">
            <span class="input-group-text bg-warning-subtle">
              <strong>Quy hoạch</strong>
            </span>
            <input type="search" class="form-control" id="loaiquyhoach" placeholder="Nhập loại đất quy hoạch" name="loaiquyhoach">
          </div>
          <button type="reset" class="w3-button w3-light-blue w3-border w3-border-black w3-round-large w-100" style="height: 35px" id="reset">
            <strong>Nhập lại</strong>
          </button>
        </div>
      </div>

      <div class="row">
        <div class="container mt-3">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#thuadat">Thông tin</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#quyhoach">Quy hoạch</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div id="thuadat" class="container tab-pane active"><br>
              <table class="table table-striped">
                <thead>
                  <tr>
                    
                  </tr>
                </thead>
                <tbody id='infoThongtin'>

                </tbody>
              </table>
            </div>
            <div id="quyhoach" class="container tab-pane fade"><br>
              <table class="table table-striped">
                <thead>
                  <tr>
                    
                  </tr>
                </thead>
                <tbody id="infoQuyhoach">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



</body>

<?php require_once 'main.php'; ?>
<?php require_once 'map.php'; ?>

</html>