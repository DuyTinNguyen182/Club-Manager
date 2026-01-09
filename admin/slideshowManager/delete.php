<?php
session_start();
require_once('../../config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Không có quyền!'); window.location.href='../../login.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql_get = "SELECT hinh_anh FROM tblslideshow WHERE ma_slide = '$id'";
    $result = $conn->query($sql_get);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = "../../" . $row['hinh_anh'];

        $sql = "DELETE FROM tblslideshow WHERE ma_slide = '$id'";
        if ($conn->query($sql) === TRUE) {
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            echo "<script>alert('Xóa slide thành công!'); window.location.href='slideshows.php';</script>";
        } else {
            echo "<script>alert('Lỗi: " . $conn->error . "'); window.location.href='slideshows.php';</script>";
        }
    }
} else {
    header("Location: slideshows.php");
}
?>