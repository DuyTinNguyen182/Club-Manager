<?php

session_start();

require_once('../../config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Bạn không có quyền!'); window.location.href='../../login.php';</script>";
    exit();
}

if (isset($_GET['user'])) {
    $user_to_delete = $_GET['user'];

    if ($user_to_delete == $_SESSION['username']) {
        echo "<script>alert('Không thể tự xóa tài khoản đang đăng nhập!'); window.location.href='members.php';</script>";
        exit();
    }

    $sql = "DELETE FROM tbluser WHERE username = '$user_to_delete'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Xóa thành viên thành công!'); window.location.href='members.php';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
} else {
    header("Location: members.php");
}
?>