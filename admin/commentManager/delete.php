<?php
session_start();
require_once('../../config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../../login.php"); exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM tblbinhluan WHERE Mabinhluan = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Xóa bình luận thành công!'); window.location.href='comments.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "'); window.location.href='comments.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>