<script>
    // Trở về Trang chủ
    function reloadPage() {
        location.reload();
    }



    document.getElementById('search').addEventListener('click', function() {
        var formData = {
            so_to: document.getElementById('soto').value,
            loai_dat: document.getElementById('loaidat').value,
            so_thua: document.getElementById('sothua').value,
            loai_quyhoach: document.getElementById('loaiquyhoach').value,
        };
        $.ajax({
            url: 'process_thongtin.php', // Call process_thongtin.php for infothongtin
            type: 'POST',
            data: formData,
            success: function(data) {
                document.getElementById('infoThongtin').innerHTML = data;
            },
            error: function() {
                alert("Dữ liệu không được tìm thấy!");
            }
        });

        $.ajax({
            url: 'process_quyhoach.php', // Call process_quyhoach.php for infoquyhoach
            type: 'POST',
            data: formData,
            success: function(data) {
                document.getElementById('infoQuyhoach').innerHTML = data;
            },
            error: function() {
                alert("Dữ liệu không được tìm thấy!");
            }
        });
    });

    // Xóa thông tin tra cứu đã nhập và kết quả tìm kiếm
    document.getElementById('reset').addEventListener('click', function() {
        document.getElementById('soto').value = '';
        document.getElementById('loaidat').value = '';
        document.getElementById('sothua').value = '';
        document.getElementById('loaiquyhoach').value = '';

        document.getElementById('infoThongtin').innerHTML = '';
        document.getElementById('infoQuyhoach').innerHTML = '';
    });
</script>