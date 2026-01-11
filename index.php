<?php
require("phandau.php");
?>

<style>
    /* Style cho từng bài viết (giống status Facebook) */
    .feed-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .feed-item:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Phần Header của bài viết (Avatar + Tên) */
    .feed-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .feed-avatar {
        width: 45px;
        height: 45px;
        background: #e0f2fe;
        /* Xanh nhạt */
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .feed-info {
        display: flex;
        flex-direction: column;
    }

    .feed-author {
        font-weight: 700;
        color: var(--text-main);
        font-size: 1rem;
    }

    .feed-time {
        font-size: 0.8rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Phần Nội dung bài viết */
    .feed-content {
        color: var(--text-main);
        font-size: 1rem;
        line-height: 1.6;
        overflow-wrap: break-word;
        /* Chống tràn chữ */
    }

    .feed-content p {
        margin-bottom: 10px;
        text-align: justify;
    }

    /* Nút xem chi tiết */
    .feed-readmore {
        display: inline-block;
        margin-top: 10px;
        color: var(--primary-color);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .feed-readmore:hover {
        text-decoration: underline;
    }

    /* Mobile Tweaks */
    @media (max-width: 576px) {
        .feed-item {
            padding: 15px;
        }

        .item-card {
            flex-direction: column;
            /* Hoạt động thẻ dọc trên mobile */
            align-items: flex-start;
        }

        .item-card .date-badge {
            flex-direction: row;
            width: 100%;
            height: 40px;
            margin-bottom: 10px;
            border-radius: 8px;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
    }
</style>

<div class="content-section">
    <div class="section-header">
        <i class="fa-solid fa-bullhorn text-danger"></i>
        <h3>Hoạt động sắp diễn ra</h3>
    </div>

    <div class="item-list">
        <?php
        // Cột trang_thai và ngay_bat_dau giữ nguyên tên
        $sql_hoatdong = "SELECT * FROM tblhoatdong WHERE trang_thai = 0 AND ngay_bat_dau >= NOW() ORDER BY ngay_bat_dau ASC LIMIT 3";
        $result_hoatdong = $conn->query($sql_hoatdong);

        if ($result_hoatdong && $result_hoatdong->num_rows > 0) {
            while ($hd = $result_hoatdong->fetch_assoc()) {
                ?>
                <div class="item-card">
                    <div class="date-badge">
                        <i class="fa-regular fa-calendar-days"></i>
                    </div>
                    <div class="item-content">
                        <a href="chitiethoatdong.php?id=<?php echo $hd['ma_hoat_dong']; ?>" class="item-title">
                            <?php echo $hd['ten_hoat_dong']; ?>
                        </a>
                        <div class="item-meta">
                            <span><i class="fa-regular fa-clock"></i>
                                <?php echo date('H:i d/m/Y', strtotime($hd['ngay_bat_dau'])); ?></span>
                            <span><i class="fa-solid fa-location-dot"></i> <?php echo $hd['dia_diem']; ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div style='text-align:center; color:var(--text-secondary); padding:20px;'>Hiện chưa có hoạt động nào sắp diễn ra.</div>";
        }
        ?>
    </div>
</div>

<div style="margin-top: 40px;">
    <div class="section-header">
        <i class="fa-solid fa-newspaper text-info"></i>
        <h3>Bài viết mới nhất</h3>
    </div>

    <div class="feed-list">
        <?php
        // Cập nhật câu truy vấn với tên cột mới
        // tbluser: fullname -> ho_va_ten, avatar -> anh_dai_dien
        // tblbaiviet: Username -> username, Trangthai -> trang_thai, Ngaytao -> ngay_tao
        $sql_baiviet = "SELECT bv.*, u.ho_va_ten, u.anh_dai_dien 
                FROM tblbaiviet bv 
                JOIN tbluser u ON bv.username = u.username 
                WHERE bv.trang_thai = 1 
                ORDER BY bv.ngay_tao DESC LIMIT 5";

        $result_baiviet = $conn->query($sql_baiviet);

        if ($result_baiviet && $result_baiviet->num_rows > 0) {
            while ($bv = $result_baiviet->fetch_assoc()) {
                ?>
                <div class="feed-item">
                    <div class="feed-header">
                        <div class="feed-avatar">
                            <img src="uploads/<?php echo $bv['anh_dai_dien']; ?>" alt="Avatar"
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo $bv['username']; ?>&background=random';">
                        </div>
                        <div class="feed-info">
                            <span
                                class="feed-author"><?php echo !empty($bv['ho_va_ten']) ? $bv['ho_va_ten'] : $bv['username']; ?></span>
                            <span class="feed-time">
                                <?php echo date('H:i d/m/Y', strtotime($bv['ngay_tao'])); ?>
                                <i class="fa-solid fa-earth-americas" style="font-size: 10px; margin-left: 4px;"></i>
                            </span>
                        </div>
                    </div>

                    <div class="feed-content">
                        <p>
                            <?php
                            // Sửa Noidung -> noi_dung
                            $noidung = strip_tags($bv['noi_dung']);
                            if (strlen($noidung) > 300)
                                echo substr($noidung, 0, 300) . "...";
                            else
                                echo $noidung;
                            ?>
                        </p>

                        <a href="danhmuc_baiviet.php?id=<?php echo $bv['ma_chu_de']; ?>&open=<?php echo $bv['ma_bai_viet']; ?>#post-<?php echo $bv['ma_bai_viet']; ?>"
                            class="feed-readmore">
                            Xem chi tiết bài viết
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div style='text-align:center; color:var(--text-secondary); padding:20px; background:#fff; border-radius:12px;'>Hiện chưa có bài viết nào.</div>";
        }
        ?>
    </div>
</div>

<?php
require("phancuoi.php");
?>