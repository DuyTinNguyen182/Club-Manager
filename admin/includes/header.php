<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = isset($path_to_admin) ? $path_to_admin : '';

require_once($base_path . '../config.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 1) {
    header('Location: ' . $base_path . '../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CLB Tin Học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #2c3e50;
            color: #fff;
            min-height: 100vh;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #34495e;
            color: #fff;
            border-left: 4px solid #3498db;
        }

        .content {
            flex: 1;
            background: #f8f9fa;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="sidebar d-flex flex-column p-3">
        <h4 class="text-center py-3 border-bottom">CLB ADMIN</h4>
        <ul class="list-unstyled mt-3">
            <li>
                <a href="<?= $base_path ?>index.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                    <i class='bx bxs-dashboard'></i> Tổng quan
                </a>
            </li>

            <li>
                <a href="<?= $base_path ?>usermanager/members.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'usermanager') !== false) ? 'active' : '' ?>">
                    <i class='bx bxs-user-detail'></i> Quản lý Thành viên
                </a>
            </li>

            <li>
                <a href="<?= $base_path ?>topicManager/topics.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'topicManager') !== false) ? 'active' : '' ?>">
                    <i class='bx bx-category'></i> Quản lý Chủ đề
                </a>
            </li>

            <li>
                <a href="<?= $base_path ?>postManager/posts.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'postManager') !== false) ? 'active' : '' ?>">
                    <i class='bx bxs-edit'></i> Quản lý Bài viết
                </a>
            </li>
            <li>
                <a href="<?= $base_path ?>commentManager/comments.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'commentManager') !== false) ? 'active' : '' ?>">
                    <i class='bx bx-message-rounded-dots'></i> Quản lý Bình luận
                </a>
            </li>
            <li>
                <a href="<?= $base_path ?>activityManager/activities.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'activityManager') !== false) ? 'active' : '' ?>">
                    <i class='bx bx-calendar-event'></i> Quản lý Hoạt động
                </a>
            </li>

            <li>
                <a href="<?= $base_path ?>attendanceManager/attendance.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'attendance.php') !== false || strpos($_SERVER['PHP_SELF'], 'take_attendance.php') !== false) ? 'active' : '' ?>">
                    <i class='bx bx-check-square'></i> Quản lý Điểm danh
                </a>
            </li>

            <li>
                <a href="<?= $base_path ?>contactManager/contacts.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'contactManager') !== false) ? 'active' : '' ?>">
                    <i class='bx bx-envelope'></i> Quản lý Liên hệ
                </a>
            </li>

            <li>
                <a href="<?= $base_path ?>slideshowManager/slideshows.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'slideshowManager') !== false) ? 'active' : '' ?>">
                    <i class='bx bx-images'></i> Quản lý Slideshow
                </a>
            </li>

            <li class="mt-5">
                <a href="<?= $base_path ?>../logout.php" class="text-danger"><i class='bx bx-log-out'></i> Đăng xuất</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Xin chào, Admin</span>

                <a href="<?= $base_path ?>../index.php" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class='bx bx-home-alt'></i> Xem trang chủ
                </a>
            </div>
        </nav>