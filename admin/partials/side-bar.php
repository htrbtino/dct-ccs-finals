<?php
// Determine the base path dynamically based on the current script's directory
$base_path = (basename(dirname($_SERVER['PHP_SELF'])) === 'admin') ? '' : '../';
?>
<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary vh-100">
    <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">Company Name</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
            <ul class="nav flex-column">
                <?php
                // Get the current page filename
                $current_page = basename($_SERVER['PHP_SELF']);
                ?>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 <?= $current_page == 'dashboard.php' ? 'fw-bold' : '' ?>" href="<?= $base_path ?>dashboard.php">
                        <i class="fa-solid fa-gauge fa-fw me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 <?= $current_page == 'add.php' ? 'fw-bold' : '' ?>" href="<?= $base_path ?>subject/add.php">
                        <i class="fa-solid fa-file fa-fw me-2"></i> Subjects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 <?= $current_page == 'students.php' ? 'fw-bold' : '' ?>" href="<?= $base_path ?>student/register.php">
                        <i class="fa-solid fa-user fa-fw me-2"></i> Students
                    </a>
                </li>               
            </ul>
           
            <hr class="my-3">

            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="<?= $base_path ?>logout.php">
                        <i class="fa-solid fa-right-to-bracket fa-fw me-2"></i>                        
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
