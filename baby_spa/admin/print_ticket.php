<?php
require_once '../config.php';

// Auth Guard
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT r.*, s.name as service_name, s.duration, s.price 
                       FROM reservations r 
                       JOIN services s ON r.service_id = s.id 
                       WHERE r.id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die("Data reservasi tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Reservasi #<?= htmlspecialchars($data['booking_code']) ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 320px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            color: #000;
        }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 1.2rem; font-weight: bold; text-transform: uppercase; }
        .info { font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px; }
        .table { width: 100%; font-size: 0.85rem; border-collapse: collapse; margin-bottom: 15px; }
        .table td { padding: 4px 0; }
        .total { border-top: 2px dashed #000; border-bottom: 2px dashed #000; padding: 8px 0; font-weight: bold; font-size: 1rem; display: flex; justify-content: space-between; }
        .footer { text-align: center; font-size: 0.8rem; margin-top: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3E9283; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Cetak Struk Ini
        </button>
    </div>

    <div class="header">
        <div class="title">LITTLE BLOSSOM</div>
        <div style="font-size:0.8rem;">BABY SPA & CHILD CARE</div>
        <div style="font-size:0.75rem; margin-top:4px;">Jl. Melati Harapan No. 45</div>
        <div style="font-size:0.75rem;">WA: 0812-3456-7890</div>
    </div>

    <div class="info">
        <div><strong>Kode:</strong> <?= htmlspecialchars($data['booking_code']) ?></div>
        <div><strong>Tgl Cetak:</strong> <?= date('d/m/Y H:i') ?></div>
        <div><strong>Orang Tua:</strong> <?= htmlspecialchars($data['parent_name']) ?></div>
        <div><strong>No HP:</strong> <?= htmlspecialchars($data['phone']) ?></div>
        <div><strong>Nama Bayi:</strong> <?= htmlspecialchars($data['baby_name']) ?> (<?= $data['baby_age_months'] ?> bln)</div>
        <div><strong>Terapis:</strong> <?= htmlspecialchars($data['therapist_preference']) ?></div>
    </div>

    <table class="table">
        <tr style="border-bottom: 1px solid #000;">
            <td style="font-weight:bold;">TREATMENT</td>
            <td style="font-weight:bold; text-align:right;">HARGA</td>
        </tr>
        <tr>
            <td>
                <?= htmlspecialchars($data['service_name']) ?><br>
                <small>Jadwal: <?= date('d/m/y', strtotime($data['reservation_date'])) ?> <?= date('H:i', strtotime($data['reservation_time'])) ?></small>
            </td>
            <td style="text-align:right; vertical-align:top;">
                <?= number_format($data['total_price'], 0, ',', '.') ?>
            </td>
        </tr>
    </table>

    <div class="total">
        <span>TOTAL BIAYA</span>
        <span>Rp <?= number_format($data['total_price'], 0, ',', '.') ?></span>
    </div>

    <div class="footer">
        <p>*** TERIMA KASIH ***</p>
        <p>Semoga Si Kecil Sehat & Ceria Always!</p>
    </div>

</body>
</html>
