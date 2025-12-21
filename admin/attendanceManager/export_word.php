<?php
session_start();
require_once('../../config.php');

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    die("Bạn không có quyền truy cập.");
}

// 2. Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Thiếu ID hoạt động.");
}
$id = intval($_GET['id']);

// 3. Lấy thông tin hoạt động
$sql_hd = "SELECT * FROM tblhoatdong WHERE hoatdong_id = $id";
$result_hd = $conn->query($sql_hd);
$row_hd = $result_hd->fetch_assoc();

if (!$row_hd) {
    die("Hoạt động không tồn tại.");
}

$sql_user = "SELECT u.fullname, u.email 
             FROM tbldangkyhoatdong dk 
             JOIN tbluser u ON dk.username = u.username 
             WHERE dk.hoatdong_id = $id AND dk.trang_thai = 1
             ORDER BY u.fullname ASC";
$result_user = $conn->query($sql_user);

// 5. Cấu hình Header để tải về file Word
$filename = "Danh_sach_" . date('dmY') . ".doc";
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=$filename");

?>
<html>

<head>
    <meta charset="utf-8">
    <style>
        /* CSS nội tuyến để định dạng trong Word */
        body {
            font-family: 'Times New Roman', serif;
            font-size: 13pt;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .date {
            text-align: right;
            font-style: italic;
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16pt;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .sub-info {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            font-size: 13pt;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        HỘI SINH VIÊN TRƯỜNG ĐẠI HỌC TRÀ VINH<br>
        BCN CÂU LẠC BỘ TIN HỌC<br>
        ***
    </div>

    <div class="date">
        Vĩnh Long, ngày <?php echo date("d"); ?> tháng <?php echo date("m"); ?> năm <?php echo date("Y"); ?>
    </div>

    <div class="title">
        DANH SÁCH THAM GIA <?php echo htmlspecialchars($row_hd['ten_hoat_dong']); ?>
    </div>

    <div class="sub-info">
        Thời gian: <?php echo date("H:i - d/m/Y", strtotime($row_hd['ngay_bat_dau'])); ?><br>
        Địa điểm: <?php echo htmlspecialchars($row_hd['dia_diem']); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">STT</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th style="width: 150px;">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stt = 1;
            if ($result_user->num_rows > 0) {
                while ($u = $result_user->fetch_assoc()) {
            ?>
                    <tr>
                        <td class="text-center"><?php echo $stt++; ?></td>
                        <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>Chưa có thành viên đăng ký</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>

    <table style="border: none;">
        <tr style="border: none;">
            <td style="border: none; width: 50%; text-align: center;"></td>
            <td style="border: none; width: 50%; text-align: center; font-weight: bold;">
                TM. BAN CHỦ NHIỆM<br>
                (Ký và ghi rõ họ tên)
                <br><br><br><br>
            </td>
        </tr>
    </table>

</body>

</html>