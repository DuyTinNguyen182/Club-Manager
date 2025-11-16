<?php
    require("phandau.php");
?>
<!-- RIGHT CONTENT -->
<div class="col-sm-9">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3><span class="glyphicon glyphicon-bullhorn"></span> HOẠT ĐỘNG SẮP DIỄN RA</h3>
        </div>
        <div class="panel-body">
            
            <?php
            $sql_hoatdong = "SELECT * FROM tblhoatdong WHERE trang_thai = 0 AND ngay_bat_dau >= NOW() ORDER BY ngay_bat_dau ASC LIMIT 3";
            $result_hoatdong = $conn->query($sql_hoatdong);

            if ($result_hoatdong->num_rows > 0) {
                while ($hd = $result_hoatdong->fetch_assoc()) {
            ?>
                    <div class="media">
                        <div class="media-left">
                            <span class="glyphicon glyphicon-calendar media-object" style="font-size: 50px; color: #d9534f;"></span>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="#"><?php echo $hd['ten_hoat_dong']; ?></a>
                            </h4>
                            <p style="margin-bottom: 5px;">
                                <span class="glyphicon glyphicon-time"></span> <strong>Thời gian:</strong> <?php echo date('H:i \N\g\à\y d/m/Y', strtotime($hd['ngay_bat_dau'])); ?>
                            </p>
                            <p>
                                <span class="glyphicon glyphicon-map-marker"></span> <strong>Địa điểm:</strong> <?php echo $hd['dia_diem']; ?>
                            </p>
                        </div>
                    </div>
                    <hr>
            <?php
                }
            } else {
                echo "<p>Hiện chưa có hoạt động nào sắp diễn ra.</p>";
            }
            ?>
        </div>
    </div>

    <br>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3><span class="glyphicon glyphicon-list-alt"></span> CÁC BÀI VIẾT MỚI NHẤT</h3>
        </div>
        <div class="panel-body">
            
            <?php
            // Lấy các bài viết mới nhất
            $sql_baiviet = "SELECT * FROM tblbaiviet WHERE Trangthai = 0 ORDER BY Ngaytao DESC LIMIT 5";
            $result_baiviet = $conn->query($sql_baiviet);

            if ($result_baiviet->num_rows > 0) {
                while ($bv = $result_baiviet->fetch_assoc()) {
            ?>
                    <div class="media">
                        <div class="media-left">
                            <img src="images/post_icon.png" class="media-object" style="width:60px">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="chitietbaiviet.php?id=<?php echo $bv['Mabaiviet']; ?>"><?php echo substr($bv['Noidung'], 0, 100); ?>...</a>
                            </h4>
                            <p>
                                <span class="glyphicon glyphicon-user"></span> Đăng bởi: <strong><?php echo $bv['Username']; ?></strong> 
                                | <span class="glyphicon glyphicon-time"></span> Vào ngày: <em><?php echo date('d/m/Y', strtotime($bv['Ngaytao'])); ?></em>
                            </p>
                        </div>
                    </div>
                    <hr>
            <?php
                }
            } else {
                echo "<p>Hiện chưa có bài viết nào.</p>";
            }
            ?>
        </div>
    </div>

</div> 
   
<?php
    require("phancuoi.php");
?>