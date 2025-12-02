<?php
include('includes/header.php'); 

  if ($_SESSION['role'] != 1) {
        header("Location: index.php");
        exit();
    }
?>

<div class="container-fluid">
    <h3 class="mb-4">Tổng quan hệ thống</h3>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Thành viên</h5>
                            <h2 class="fw-bold">150</h2>
                        </div>
                        <i class='bx bxs-user bx-lg opacity-50'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Bài viết</h5>
                            <h2 class="fw-bold">45</h2>
                        </div>
                        <i class='bx bxs-file-txt bx-lg opacity-50'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3 shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Chủ đề</h5>
                            <h2 class="fw-bold">8</h2>
                        </div>
                        <i class='bx bxs-category bx-lg opacity-50'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>