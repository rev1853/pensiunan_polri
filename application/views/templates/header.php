<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/dist/css/adminlte.min.css">
    <!-- Select2 css -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline">
                    <a href="<?= base_url(); ?>" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="<?= base_url('logout'); ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="badge badge-danger navbar-badge"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>


        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= base_url(); ?>" class="brand-link">
                <img src="<?= base_url(); ?>assets/dist/img/default.jpg" class="brand-image img-circle elevation-2" alt="User Image">
                <span class="brand-text font-weight-light"><?= logged_in('user') ? get_name('user') : get_name('personil'); ?> </span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?= base_url(); ?>" class="nav-link">
                                <i class="fas fa-home"></i>
                                <p>
                                    Home
                                    <span class="right badge badge-danger"></span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url(); ?>polres/dt_Personil" class="nav-link">
                                <i class="fas fa-users"></i>
                                <p>
                                    Data Personil
                                    <span class="right badge badge-danger"></span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url(); ?>polres/dt_CalonPensiun" class="nav-link">
                                <i class="fas fa-address-book"></i>
                                <p>
                                    Data Calon Pensiun
                                    <span class="badge badge-info right"></span>
                                </p>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url(); ?>data-pensiun" class="nav-link">
                                <i class="far fa-address-book"></i>
                                <p>
                                    Data Pensiunan
                                </p>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url(); ?>polres/dt_PengajuanPensiun" class="nav-link">
                                <i class="fas fa-user-plus"></i>
                                <p>
                                    Data Pengajuan Pensiun
                                </p>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url(); ?>user" class="nav-link">
                                <i class="fas fa-user"></i>
                                <p>
                                    Data User
                                </p>
                            </a>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>