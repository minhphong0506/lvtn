<?php
// Kết nối đến cơ sở dữ liệu PostgreSQL
include('connection/connect.php');

// Lấy tọa độ từ yêu cầu AJAX
$lat = $_GET['lat'];
$lng = $_GET['lng'];

// Truy vấn cơ sở dữ liệu để lấy thông tin thửa đất và diện tích giao không gian với quy hoạch sử dụng đất cấp huyện
$query = "
    SELECT 
        thuadat.sohieutobando, 
        thuadat.sothututhua, 
        thuadat.loaidat, 
        thuadat.dientich, 
        quyhoachsddcaphuyen.mucdichsudungqh,
        mucdichsudungqh.giatri,
        CASE
            WHEN ST_IsEmpty(ST_Intersection(thuadat.geom, quyhoachsddcaphuyen.geom)) THEN 0
            ELSE CAST(ST_Area(ST_Transform(ST_Intersection(thuadat.geom, quyhoachsddcaphuyen.geom), 3405)) AS decimal(10,1))
        END AS dientich_qh
    FROM 
        thuadat 
    JOIN 
        quyhoachsddcaphuyen 
    ON 
        ST_Intersects(thuadat.geom, quyhoachsddcaphuyen.geom)
    JOIN 
        mucdichsudungqh
    ON 
        quyhoachsddcaphuyen.mucdichsudungqh = mucdichsudungqh.ma
    WHERE 
        ST_Contains(thuadat.geom, ST_SetSRID(ST_MakePoint($lng, $lat), 4326))
";

$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Xử lý kết quả
if (pg_num_rows($result) > 0) {
    // Lấy tất cả kết quả từ truy vấn
    $rows = pg_fetch_all($result);

    // Lấy thông tin chung của thửa đất từ dòng đầu tiên
    $firstRow = $rows[0];
    $thuadat_info = "<h5><strong>Thông tin thửa đất</strong></h5>"
        . "<strong>Tờ số:</strong> " . $firstRow['sohieutobando']
        . "<br><strong>Thửa số:</strong> " . $firstRow['sothututhua']
        . "<br><strong>Loại đất:</strong> " . $firstRow['loaidat']
        . "<br><strong>Diện tích:</strong> " . number_format($firstRow['dientich'], 2) . " m²";

    // Khởi tạo mảng để lưu các mục đích sử dụng quy hoạch và diện tích tương ứng
    $mucdichsudungqhArray = [];

    foreach ($rows as $row) {
        // Kiểm tra và thêm mục đích sử dụng quy hoạch, diện tích và giá trị vào mảng
        if (isset($row['dientich_qh']) && $row['dientich_qh'] > 0) {
            $mucdichsudungqhArray[] = "- " . $row['giatri'] . " - " . $row['mucdichsudungqh'] . " (" . number_format($row['dientich_qh'], 2) . " m²)";
        }
    }

    // Hiển thị các mục đích sử dụng quy hoạch nếu có giá trị
    $quyhoach_info = "<h5><strong>Thông tin quy hoạch</strong></h5>";
    if (!empty($mucdichsudungqhArray)) {
        $quyhoach_info .= implode("<br>", $mucdichsudungqhArray);
    } else {
        $quyhoach_info .= "Không có";
    }

    // Trả về thông tin
    echo "<div>" . $thuadat_info . "</div><div>" . $quyhoach_info . "</div>";
} else {
    // Nếu không có thông tin thửa đất, không có gì được trả về
    echo "Không có thông tin thửa đất.";
}
?>
