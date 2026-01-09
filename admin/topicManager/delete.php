<?php
session_start();
require_once('../../config.php'); // Kết nối CSDL

// Kiểm tra quyền Admin (role -> quyen)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Bạn không có quyền thực hiện thao tác này!'); window.location.href='../../login.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // CẬP NHẬT: Machude -> ma_chu_de
    $sql = "DELETE FROM tblchude WHERE ma_chu_de = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Xóa chủ đề thành công!'); window.location.href='topics.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "'); window.location.href='topics.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>