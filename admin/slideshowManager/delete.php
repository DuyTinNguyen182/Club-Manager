<?php
session_start();
require_once('../../config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Không có quyền!'); window.location.href='../../login.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin ảnh để xóa file vật lý
    $sql_get = "SELECT ImageUrl FROM tblslideshow WHERE Id = '$id'";
    $result = $conn->query($sql_get);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = "../../" . $row['ImageUrl'];

        // Xóa trong database
        $sql = "DELETE FROM tblslideshow WHERE Id = '$id'";
        if ($conn->query($sql) === TRUE) {
            // Nếu xóa DB thành công thì xóa file ảnh
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
