<?php
session_start();
require_once('../../config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../login.php"); exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql_get_img = "SELECT tep_tin FROM tblbaiviet WHERE ma_bai_viet = '$id'";
    $res = $conn->query($sql_get_img);
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $file_path = "../../uploads/" . $row['tep_tin'];
        if (!empty($row['tep_tin']) && file_exists($file_path)) {
            unlink($file_path); // Xóa file vật lý
        }
    }

    $sql = "DELETE FROM tblbaiviet WHERE ma_bai_viet = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Xóa bài viết thành công!'); window.location.href='posts.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "'); window.location.href='posts.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>