<?php
session_start();
require_once('../../config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../login.php"); exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $current_status = $_GET['status'];
    
    // Nếu đang là 1 thì chuyển thành 0, và ngược lại
    $new_status = ($current_status == 1) ? 0 : 1;

    $sql = "UPDATE tblcontact SET Trangthai = '$new_status' WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: contacts.php"); // Quay lại trang danh sách ngay
    } else {
        echo "Lỗi: " . $conn->error;
    }
} else {
    header("Location: index.php");
}
?>