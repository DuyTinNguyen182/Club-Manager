<?php
// 1. Cấu hình múi giờ Việt Nam ĐẦU TIÊN để đảm bảo tính toán thời gian đúng
date_default_timezone_set('Asia/Ho_Chi_Minh');

require("phandau.php");
?>

<style>
    /* Container chính */
    .activity-container {
        padding: 30px 0;
        background-color: #f8fafc; /* Màu nền hiện đại hơn */
    }

    .page-title {
        color: #0f172a;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 800;
        font-size: 1.5rem;
        display: flex; align-items: center; gap: 12px;
    }

    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); /* Card rộng hơn chút */
        gap: 25px;
    }

    /* Card Item */
    .item-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        gap: 20px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    /* Cột Ngày tháng (Badge) */
    .date-badge {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: 700;
        line-height: 1;
        border: 1px solid transparent;
    }
    .date-badge .day { font-size: 1.5rem; margin-bottom: 2px; }
    .date-badge .month { font-size: 0.85rem; text-transform: uppercase; }

    /* Nội dung bên phải */
    .item-content { flex: 1; display: flex; flex-direction: column; }

    .item-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        text-decoration: none;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Giới hạn 2 dòng */
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    .item-title:hover { color: #0d6efd; }

    /* Meta info (Giờ, địa điểm) */
    .item-meta {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* CSS Grid để căn thẳng hàng chữ Từ - Đến */
    .time-grid {
        display: grid;
        grid-template-columns: 45px 1fr; /* Cột 1 cố định, Cột 2 tự giãn */
        gap: 2px;
        align-items: baseline;
    }
    .time-label { color: #94a3b8; font-size: 0.85rem; }
    .time-value { color: #334155; font-weight: 500; }

    .meta-row { display: flex; gap: 10px; align-items: flex-start; }
    .meta-icon { width: 16px; text-align: center; margin-top: 3px; color: #94a3b8; }

    /* Trạng thái */
    .status-badge {
        align-self: flex-start;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* MÀU SẮC TRẠNG THÁI (Đồng bộ Badge và Label) */
    /* 1. Sắp diễn ra (Xanh lá) */
    .st-future .date-badge { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .st-future .status-badge { background: #dcfce7; color: #15803d; }

    /* 2. Đang diễn ra (Cam) */
    .st-ongoing .item-card { border-color: #fdba74; background: #fff7ed; } /* Highlight card */
    .st-ongoing .date-badge { background: #ffedd5; color: #c2410c; border-color: #fed7aa; }
    .st-ongoing .status-badge { background: #fb923c; color: #fff; }

    /* 3. Đã kết thúc (Xám) */
    .st-past .item-card { opacity: 0.9; background: #f8fafc; }
    .st-past .date-badge { background: #e2e8f0; color: #64748b; border-color: #cbd5e1; }
    .st-past .status-badge { background: #e2e8f0; color: #64748b; }
</style>

<div class="content-section activity-container">
    <div class="container">
        <div class="page-title">
            <i class="fa-solid fa-calendar-check" style="color: #0d6efd;"></i>
            <span>Hoạt động CLB</span>
        </div>

        <div class="activity-grid">
            <?php
            $sql = "SELECT * FROM tblhoatdong ORDER BY ngay_bat_dau DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // --- XỬ LÝ LOGIC THỜI GIAN ---
                    $start = strtotime($row['ngay_bat_dau']);
                    // Nếu không có ngày kết thúc, mặc định = ngày bắt đầu
                    $end = !empty($row['ngay_ket_thuc']) ? strtotime($row['ngay_ket_thuc']) : $start;
                    $now = time(); // Thời gian hiện tại (theo múi giờ VN đã set ở trên)

                    // Xác định trạng thái & class CSS tương ứng
                    if ($now < $start) {
                        $st_class = "st-future";
                        $st_text = "Sắp diễn ra";
                    } elseif ($now >= $start && $now <= $end) {
                        $st_class = "st-ongoing";
                        $st_text = "Đang diễn ra";
                    } else {
                        $st_class = "st-past";
                        $st_text = "Đã kết thúc";
                    }
            ?>
                    <div class="item-card <?php echo $st_class; ?>">
                        
                        <div class="date-badge">
                            <span class="day"><?php echo date('d', $start); ?></span>
                            <span class="month">Th<?php echo date('m', $start); ?></span>
                        </div>

                        <div class="item-content">
                            <a href="chitiethoatdong.php?id=<?php echo $row['hoatdong_id']; ?>" class="item-title">
                                <?php echo $row['ten_hoat_dong']; ?>
                            </a>

                            <div class="item-meta">
                                <div class="meta-row">
                                    <i class="fa-regular fa-clock meta-icon"></i>
                                    <div class="time-grid">
                                        <span class="time-label">Từ:</span>
                                        <span class="time-value"><?php echo date('H:i - d/m/Y', $start); ?></span>
                                        
                                        <span class="time-label">Đến:</span>
                                        <span class="time-value"><?php echo date('H:i - d/m/Y', $end); ?></span>
                                    </div>
                                </div>

                                <div class="meta-row">
                                    <i class="fa-solid fa-location-dot meta-icon"></i>
                                    <span style="color: #334155;"><?php echo $row['dia_diem']; ?></span>
                                </div>
                            </div>

                            <div class="status-badge">
                                <?php echo $st_text; ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p style='grid-column:1/-1; text-align:center; color:#64748b;'>Chưa có hoạt động nào.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php require("phancuoi.php"); ?>