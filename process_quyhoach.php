<?php
include('connection/connect.php');

$so_to = $_POST['so_to'];
$so_thua = $_POST['so_thua'];
$loai_dat = $_POST['loai_dat'];
$loai_quyhoach = $_POST['loai_quyhoach'];
$where = 'WHERE 1=1 ';

if($so_to){
    $where .= " AND thuadat.sohieutobando = $so_to";
}
if($so_thua){
    $where .= " AND thuadat.sothututhua = $so_thua";
}
if($loai_dat){
    $where .= " AND thuadat.loaidat ILIKE '%$loai_dat%'";
}
if($loai_quyhoach){
    $where .= " AND quyhoachsddcaphuyen.mucdichsudungqh ILIKE '%$loai_quyhoach%'";
}

$infoQuyhoachTable = '<table><thead><tr><th>STT</th><th>Tờ số</th><th>Thửa số</th><th>Quy hoạch</th><th>Diện tích (m²)</th></tr></thead><tbody>';

// Fetch data for infoquyhoach
$sqlQuyhoach = "SELECT thuadat.sohieutobando, thuadat.sothututhua, quyhoachsddcaphuyen.mucdichsudungqh, 
        CAST(ST_AREA(ST_TRANSFORM(ST_INTERSECTION(thuadat.geom, quyhoachsddcaphuyen.geom), 3405)) AS decimal(10,1)) as dientich_qh
        FROM thuadat 
        JOIN quyhoachsddcaphuyen ON ST_INTERSECTS(thuadat.geom, quyhoachsddcaphuyen.geom)
        $where AND CAST(ST_AREA(ST_TRANSFORM(ST_INTERSECTION(thuadat.geom, quyhoachsddcaphuyen.geom), 3405)) AS decimal(10,1)) > 0.0
        ORDER BY thuadat.sothututhua ASC";
$queryQuyhoach = pg_query($sqlQuyhoach) or die('Query failed: ' . pg_last_error());

// Kiểm tra số lượng hàng trả về
$row_count = pg_num_rows($queryQuyhoach);

// Nếu không có dữ liệu
if($row_count == 0) {
    echo "<p>Không tìm thấy dữ liệu phù hợp.</p>";
} else {
    // Có dữ liệu, thực hiện việc hiển thị bảng
    $stt = 1; // Khởi tạo số thứ tự
    while ($arr = pg_fetch_array($queryQuyhoach)) {
        $infoQuyhoachTable .= "<tr><td>". $stt++ ."</td><td>". $arr['sohieutobando']."</td><td>".  
        $arr['sothututhua']. "</td><td>".  
        $arr['mucdichsudungqh']. "</td><td>".  
        $arr['dientich_qh']. "</td></tr>";
    }
    $infoQuyhoachTable .= '</tbody></table>';
    echo $infoQuyhoachTable;
}
?>
