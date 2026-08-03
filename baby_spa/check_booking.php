<?php
require_once 'config.php';

$search_term = trim($_GET['search'] ?? '');
$results = [];
$searched = false;

if (!empty($search_term)) {
    $searched = true;
    $stmt = $pdo->prepare("SELECT r.*, s.name as service_name 
                           FROM reservations r 
                           JOIN services s ON r.service_id = s.id 
                           WHERE r.booking_code LIKE ? OR r.phone LIKE ? 
                           ORDER BY r.id DESC");
    $stmt->execute(['%' . $search_term . '%', '%' . $search_term . '%']);
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Reservasi | Little Blossom Baby Spa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, #FAF7F2 0%, #EBF7F5 100%); min-height: 100vh; padding-top: 100px;">

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon"><i class="fa-solid fa-baby"></i></div>
                <span>LittleBlossom</span>
            </a>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="nav-link">Beranda</a></li>
                <li><a href="index.php#services" class="nav-link">Layanan</a></li>
                <li><a href="check_booking.php" class="nav-link active"><i class="fa-solid fa-magnifying-glass"></i> Cek Booking</a></li>
                <li><a href="booking.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Reservasi</a></li>
            </ul>

            <div class="mobile-toggle" id="mobileToggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 750px; padding-bottom: 80px;">
        <div class="booking-card">
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="badge"><i class="fa-solid fa-magnifying-glass"></i> Lacak Jadwal</span>
                <h2 style="font-family: var(--font-heading); font-size: 2rem; color: var(--text-main);">Cek Status Reservasi</h2>
                <p style="color: var(--text-muted);">Masukkan Kode Booking (contoh: SPA-2026...) atau Nomor WhatsApp Anda.</p>
            </div>

            <!-- SEARCH FORM -->
            <form action="check_booking.php" method="GET" style="display: flex; gap: 10px; margin-bottom: 35px;">
                <input type="text" name="search" class="form-control" placeholder="Masukkan Kode Booking / No. WhatsApp..." value="<?= htmlspecialchars($search_term) ?>" required style="flex:1;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Cari Tiket
                </button>
            </form>

            <!-- RESULTS LIST -->
            <?php if ($searched): ?>
                <?php if (count($results) > 0): ?>
                    <h3 style="font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 20px; color: var(--primary);">
                        Ditemukan <?= count($results) ?> Reservasi:
                    </h3>

                    <?php foreach ($results as $item): ?>
                        <div style="background: #FAFAFA; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                            <div>
                                <span style="font-weight: 700; color: var(--primary); font-size: 1.1rem;"><?= htmlspecialchars($item['booking_code']) ?></span>
                                <div style="font-size: 0.9rem; color: var(--text-main); margin-top: 4px;">
                                    <strong><?= htmlspecialchars($item['parent_name']) ?></strong> (Bayi: <?= htmlspecialchars($item['baby_name']) ?>)
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">
                                    Treatment: <?= htmlspecialchars($item['service_name']) ?> | <?= date('d-m-Y', strtotime($item['reservation_date'])) ?> <?= date('H:i', strtotime($item['reservation_time'])) ?> WIB
                                </div>
                            </div>
                            
                            <div style="text-align: right;">
                                <?php
                                $badge_bg = '#FEF3C7'; $badge_color = '#B45309';
                                if ($item['status'] == 'Dikonfirmasi') { $badge_bg = '#DBEAFE'; $badge_color = '#1D4ED8'; }
                                elseif ($item['status'] == 'Selesai') { $badge_bg = '#DCFCE7'; $badge_color = '#15803D'; }
                                elseif ($item['status'] == 'Dibatalkan') { $badge_bg = '#FEE2E2'; $badge_color = '#B91C1C'; }
                                ?>
                                <span style="background: <?= $badge_bg ?>; color: <?= $badge_color ?>; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; margin-bottom: 8px;">
                                    <?= htmlspecialchars($item['status']) ?>
                                </span>
                                <div>
                                    <a href="booking_success.php?code=<?= urlencode($item['booking_code']) ?>" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.8rem;">
                                        Lihat Tiket <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                        <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; margin-bottom: 12px; color: #CBD5E0;"></i>
                        <p>Data reservasi dengan kata kunci "<strong><?= htmlspecialchars($search_term) ?></strong>" tidak ditemukan.</p>
                        <p style="font-size: 0.85rem; margin-top: 5px;">Pastikan kode booking atau nomor handphone yang Anda ketik sudah benar.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>
