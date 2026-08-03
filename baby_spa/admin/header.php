<?php
require_once '../config.php';

// Auth Guard
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Little Blossom Baby Spa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-baby-carriage" style="color: #38BDF8;"></i> Little Blossom Admin
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard Utama
                </a>
            </li>
            <li>
                <a href="reservations.php" class="<?= $current_page == 'reservations.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-calendar-check"></i> Kelola Reservasi
                </a>
            </li>
            <li>
                <a href="services.php" class="<?= $current_page == 'services.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-spa"></i> Kelola Layanan
                </a>
            </li>
            <li>
                <a href="../index.php" target="_blank">
                    <i class="fa-solid fa-globe"></i> Lihat Website Utama
                </a>
            </li>
            <li style="margin-top: auto; padding-top: 30px;">
                <a href="logout.php" style="color: #F87171;">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar (Logout)
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">
        <header class="admin-header">
            <div>
                <h1 class="admin-title">Panel Administrator</h1>
                <p style="color: var(--admin-muted); font-size: 0.88rem;">Selamat Datang, <?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></p>
            </div>
            <div class="user-badge">
                <i class="fa-solid fa-circle-user"></i> <?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?>
            </div>
        </header>
