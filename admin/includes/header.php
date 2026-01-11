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
            background-color: #f8f9fa;
            overflow-x: hidden;
            /* Ngăn cuộn ngang */
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            height: 100vh;
            position: fixed;
            /* Cố định sidebar */
            top: 0;
            left: 0;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 15px 20px;
            display: flex;
            /* Canh icon và chữ thẳng hàng */
            align-items: center;
            gap: 10px;
            transition: 0.2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #34495e;
            color: #fff;
            border-left: 4px solid #3498db;
        }

        /* --- CONTENT --- */
        .content {
            margin-left: 250px;
            /* Chừa chỗ cho sidebar */
            padding: 20px;
            transition: all 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- OVERLAY (Lớp phủ mờ khi mở menu mobile) --- */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            /* Ẩn mặc định */
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* --- RESPONSIVE CSS --- */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
                /* Ẩn sidebar sang trái */
            }

            .sidebar.active {
                left: 0;
                /* Hiện lại khi có class active */
            }

            .content {
                margin-left: 0;
                /* Content tràn ra toàn màn hình */
            }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar d-flex flex-column p-3" id="sidebar">
        <h4 class="text-center py-3 border-bottom">CLB ADMIN</h4>
        <ul class="list-unstyled mt-3">
            <li>
                <a href="<?= $base_path ?>index.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                    <i class='bx bxs-dashboard'></i> Tổng quan
                </a>
            </li>

            <li>
                <a href="<?= $base_path ?>userManager/members.php"
                    class="<?= (strpos($_SERVER['PHP_SELF'], 'userManager') !== false) ? 'active' : '' ?>">
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
        <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded px-3">
            <div class="d-flex align-items-center w-100 justify-content-between">

                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary d-md-none me-2" id="sidebarToggle">
                        <i class='bx bx-menu'></i>
                    </button>
                    <span class="navbar-brand mb-0 h1">Xin chào, Admin</span>
                </div>

                <a href="<?= $base_path ?>../index.php" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class='bx bx-home-alt'></i> Xem trang chủ
                </a>
            </div>
        </nav>
