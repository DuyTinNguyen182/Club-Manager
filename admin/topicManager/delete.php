<?php
session_start();
require_once('../../config.php'); // Kết nối CSDL

// Kiểm tra quyền Admin (Nếu cần)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Bạn không có quyền thực hiện thao tác này!'); window.location.href='../../login.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Có thể thêm kiểm tra: Nếu chủ đề đang có bài viết thì không cho xóa (Tùy chọn)
    
    $sql = "DELETE FROM tblchude WHERE Machude = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Xóa chủ đề thành công!'); window.location.href='topics.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "'); window.location.href='topics.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>