<?php
// 1. Gọi phần đầu trang (Header, Menu, Kết nối CSDL)
require("phandau.php");
?>

<style>
    /* Container chính */
    .activity-container {
        padding: 20px 0;
        background-color: #f8f9fa;
        /* Màu nền nhẹ cho sạch sẽ */
    }

    /* Tiêu đề trang */
    .page-title {
        color: var(--primary-color, #0d6efd);
        /* Dùng màu chủ đạo của web hoặc xanh mặc định */
        margin-bottom: 30px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Lưới hiển thị hoạt động (Responsive Grid) */
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        /* Tự động chia cột */
        gap: 20px;
    }

    /* Style lại Card hoạt động (tận dụng class cũ từ index.php nhưng chỉnh lại chút cho trang danh sách) */
    .item-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e2e8f0;
    }

    .item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .date-badge {
        background: #e0f2fe;
        color: #0284c7;
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .item-content {
        flex-grow: 1;
    }

    .item-title {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: #334155;
        text-decoration: none;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .item-title:hover {
        color: #0284c7;
    }

    .item-meta {
        font-size: 0.85rem;
        color: #64748b;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .item-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 8px;
    }

    .status-future {
        background: #dcfce7;
        color: #166534;
    }

    /* Xanh lá - Sắp diễn ra */
    .status-past {
        background: #f1f5f9;
        color: #64748b;
    }

    /* Xám - Đã qua */
</style>

<div class="content-section activity-container">
    <div class="container">
        <div class="page-title">
            <i class="fa-solid fa-list-check"></i>
            <h3>Tất cả hoạt động CLB</h3>
        </div>

        <div class="activity-grid">
            <?php
            // 3. Truy vấn SQL: Lấy TẤT CẢ hoạt động (bỏ LIMIT)
            // Sắp xếp: Mới nhất lên đầu (ORDER BY ngay_bat_dau DESC)
            $sql_all_hd = "SELECT * FROM tblhoatdong WHERE trang_thai = 1 ORDER BY ngay_bat_dau DESC";
            $result_all_hd = $conn->query($sql_all_hd);

            if ($result_all_hd && $result_all_hd->num_rows > 0) {
                while ($hd = $result_all_hd->fetch_assoc()) {
                    // Xử lý kiểm tra hoạt động đã qua hay chưa để đổi màu
                    $ngay_hd = strtotime($hd['ngay_bat_dau']);
                    $is_past = $ngay_hd < time();
            ?>
                    <div class="item-card">
                        <div class="date-badge" style="<?php echo $is_past ? 'background:#f1f5f9; color:#94a3b8;' : ''; ?>">
                            <i class="fa-regular fa-calendar-days"></i>
                        </div>
                        <div class="item-content">
                            <a href="chitiethoatdong.php?id=<?php echo $hd['hoatdong_id']; ?>" class="item-title">
                                <?php echo $hd['ten_hoat_dong']; ?>
                            </a>

                            <div class="item-meta">
                                <span>
                                    <i class="fa-regular fa-clock"></i>
                                    <?php echo date('H:i - d/m/Y', $ngay_hd); ?>
                                </span>
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    <?php echo $hd['dia_diem']; ?>
                                </span>
                            </div>

                            <?php if (!$is_past): ?>
                                <span class="status-badge status-future">Sắp diễn ra</span>
                            <?php else: ?>
                                <span class="status-badge status-past">Đã kết thúc</span>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align:center; padding:40px; color:#64748b;'>Hiện chưa có hoạt động nào được ghi nhận.</div>";
            }
            ?>
        </div>

    </div>
</div>

<?php
// 4. Gọi phần chân trang
require("phancuoi.php");
?>