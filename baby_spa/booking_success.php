<?php
require_once 'config.php';

$booking_code = trim($_GET['code'] ?? '');

if (empty($booking_code)) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT r.*, s.name as service_name, s.duration, s.price 
                       FROM reservations r 
                       JOIN services s ON r.service_id = s.id 
                       WHERE r.booking_code = ?");
$stmt->execute([$booking_code]);
$data = $stmt->fetch();

if (!$data) {
    die("Kode booking tidak ditemukan.");
}

$wa_text = urlencode("Halo Little Blossom Baby Spa! Saya ingin konfirmasi booking berikut:\n\n" .
           "📌 Kode Booking: " . $data['booking_code'] . "\n" .
           "👤 Orang Tua: " . $data['parent_name'] . "\n" .
           "👶 Nama Bayi: " . $data['baby_name'] . " (" . $data['baby_age_months'] . " Bln)\n" .
           "✨ Treatment: " . $data['service_name'] . "\n" .
           "📅 Tanggal: " . date('d-m-Y', strtotime($data['reservation_date'])) . " Jam " . date('H:i', strtotime($data['reservation_time'])) . " WIB\n" .
           "💰 Total: Rp " . number_format($data['total_price'], 0, ',', '.') . "\n\n" .
           "Mohon konfirmasinya. Terima kasih!");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Reservasi #<?= htmlspecialchars($data['booking_code']) ?> | Little Blossom Baby Spa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @media print {
            .navbar, .btn-no-print, .float-wa { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .ticket-card { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #EBF7F5 0%, #FAF7F2 100%); min-height: 100vh; padding-top: 100px;">

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon"><i class="fa-solid fa-baby"></i></div>
                <span>LittleBlossom</span>
            </a>
            
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">Beranda</a></li>
                <li><a href="check_booking.php" class="nav-link">Cek Status Booking</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="max-width: 680px; padding-bottom: 80px;">
        <div class="booking-card ticket-card" style="text-align: center; border-top: 6px solid var(--primary);">
            <div style="width: 70px; height: 70px; background: #DCFCE7; color: #16A34A; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 20px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h2 style="font-family: var(--font-heading); color: var(--text-main); font-size: 1.8rem; margin-bottom: 5px;">Reservasi Berhasil Dibuat!</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 25px;">Simpan atau cetak bukti tiket ini saat datang ke lokasi.</p>

            <div style="background: var(--primary-light); padding: 15px 20px; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 30px;">
                <span style="font-size: 0.85rem; color: var(--text-muted); display: block;">KODE BOOKING ANDA:</span>
                <strong style="font-size: 1.6rem; color: var(--primary); letter-spacing: 2px;"><?= htmlspecialchars($data['booking_code']) ?></strong>
            </div>

            <!-- TICKET DETAILS -->
            <div style="text-align: left; background: #FAFAFA; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 25px; margin-bottom: 30px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                    <tr style="border-bottom: 1px dashed #E2E8F0;">
                        <td style="padding: 10px 0; color: var(--text-muted);">Status Reservasi</td>
                        <td style="padding: 10px 0; text-align: right;">
                            <span style="background: #FEF3C7; color: #B45309; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem;">
                                <?= htmlspecialchars($data['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px dashed #E2E8F0;">
                        <td style="padding: 10px 0; color: var(--text-muted);">Orang Tua / Bunda</td>
                        <td style="padding: 10px 0; text-align: right; font-weight: 600;"><?= htmlspecialchars($data['parent_name']) ?></td>
                    </tr>
                    <tr style="border-bottom: 1px dashed #E2E8F0;">
                        <td style="padding: 10px 0; color: var(--text-muted);">Nama Bayi (Usia)</td>
                        <td style="padding: 10px 0; text-align: right; font-weight: 600;"><?= htmlspecialchars($data['baby_name']) ?> (<?= $data['baby_age_months'] ?> Bulan)</td>
                    </tr>
                    <tr style="border-bottom: 1px dashed #E2E8F0;">
                        <td style="padding: 10px 0; color: var(--text-muted);">No. WhatsApp</td>
                        <td style="padding: 10px 0; text-align: right;"><?= htmlspecialchars($data['phone']) ?></td>
                    </tr>
                    <tr style="border-bottom: 1px dashed #E2E8F0;">
                        <td style="padding: 10px 0; color: var(--text-muted);">Layanan Treatment</td>
                        <td style="padding: 10px 0; text-align: right; font-weight: 600; color: var(--primary);"><?= htmlspecialchars($data['service_name']) ?></td>
                    </tr>
                    <tr style="border-bottom: 1px dashed #E2E8F0;">
                        <td style="padding: 10px 0; color: var(--text-muted);">Jadwal Kunjungan</td>
                        <td style="padding: 10px 0; text-align: right; font-weight: 600;">
                            <?= date('d F Y', strtotime($data['reservation_date'])) ?> | Pukul <?= date('H:i', strtotime($data['reservation_time'])) ?> WIB
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0 0; font-weight: 700; font-size: 1.1rem; color: var(--text-main);">Total Biaya</td>
                        <td style="padding: 14px 0 0; text-align: right; font-weight: 700; font-size: 1.3rem; color: var(--primary);">
                            Rp <?= number_format($data['total_price'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- BUTTON ACTIONS -->
            <div class="btn-no-print" style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="https://wa.me/6281234567890?text=<?= $wa_text ?>" target="_blank" class="btn" style="background: #25D366; color: white; flex: 1;">
                    <i class="fa-brands fa-whatsapp"></i> Konfirmasi via WA
                </a>
                <button onclick="window.print()" class="btn btn-outline" style="flex: 1;">
                    <i class="fa-solid fa-print"></i> Cetak Tiket
                </button>
            </div>
            
            <div class="btn-no-print" style="margin-top: 20px;">
                <a href="index.php" style="color: var(--text-muted); font-size: 0.9rem;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>

</body>
</html>
