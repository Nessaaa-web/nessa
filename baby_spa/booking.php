<?php
require_once 'config.php';

$error = '';
$preselected_service_id = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

// Fetch active services
$services_stmt = $pdo->query("SELECT * FROM services WHERE status='active' ORDER BY id ASC");
$services = $services_stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parent_name   = trim($_POST['parent_name'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $baby_name     = trim($_POST['baby_name'] ?? '');
    $baby_age      = (int)($_POST['baby_age_months'] ?? 0);
    $service_id    = (int)($_POST['service_id'] ?? 0);
    $res_date      = trim($_POST['reservation_date'] ?? '');
    $res_time      = trim($_POST['reservation_time'] ?? '');
    $therapist_pref = trim($_POST['therapist_preference'] ?? 'Sesuai Antrean');
    $notes         = trim($_POST['notes'] ?? '');

    if (empty($parent_name) || empty($phone) || empty($baby_name) || $service_id <= 0 || empty($res_date) || empty($res_time)) {
        $error = 'Mohon lengkapi seluruh kolom wajib bertanda (*).';
    } else {
        // Fetch selected service to get price
        $srv_stmt = $pdo->prepare("SELECT price FROM services WHERE id = ?");
        $srv_stmt->execute([$service_id]);
        $srv = $srv_stmt->fetch();

        if (!$srv) {
            $error = 'Layanan yang dipilih tidak valid.';
        } else {
            $total_price = $srv['price'];
            // Generate Booking Code: SPA-YYYYMMDD-XXXX
            $random_str = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
            $booking_code = 'SPA-' . date('Ymd') . '-' . $random_str;

            $insert_sql = "INSERT INTO reservations 
                (booking_code, parent_name, phone, email, baby_name, baby_age_months, service_id, reservation_date, reservation_time, therapist_preference, notes, total_price, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
            
            $stmt = $pdo->prepare($insert_sql);
            $success = $stmt->execute([
                $booking_code, $parent_name, $phone, $email, $baby_name, $baby_age,
                $service_id, $res_date, $res_time, $therapist_pref, $notes, $total_price
            ]);

            if ($success) {
                header("Location: booking_success.php?code=" . urlencode($booking_code));
                exit;
            } else {
                $error = 'Terjadi kesalahan sistem saat menyimpan reservasi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Reservasi | Little Blossom Baby Spa</title>
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
                <li><a href="index.php#home" class="nav-link">Beranda</a></li>
                <li><a href="index.php#services" class="nav-link">Layanan</a></li>
                <li><a href="check_booking.php" class="nav-link"><i class="fa-solid fa-magnifying-glass"></i> Cek Booking</a></li>
                <li><a href="booking.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Reservasi</a></li>
            </ul>

            <div class="mobile-toggle" id="mobileToggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- MAIN FORM CONTAINER -->
    <div class="container" style="padding-bottom: 80px;">
        <div class="booking-card">
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="badge"><i class="fa-solid fa-calendar-heart"></i> Form Pemesanan</span>
                <h2 style="font-family: var(--font-heading); font-size: 2rem; color: var(--text-main);">Reservasi Treatment Baby Spa</h2>
                <p style="color: var(--text-muted);">Isi data di bawah ini untuk mengamankan slot sesi perawatan si kecil.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div style="background: #FEE2E2; color: #991B1B; padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 25px; font-weight: 500;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="booking.php" method="POST" id="bookingForm">
                
                <h4 style="font-family: var(--font-heading); color: var(--primary); margin-bottom: 15px; border-bottom: 2px solid var(--primary-light); padding-bottom: 8px;">
                    <i class="fa-solid fa-user"></i> 1. Informasi Orang Tua / Wali
                </h4>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua / Bunda *</label>
                        <input type="text" name="parent_name" class="form-control" placeholder="Contoh: Bunda Anisa" required value="<?= htmlspecialchars($_POST['parent_name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp / HP *</label>
                        <input type="tel" name="phone" class="form-control" placeholder="Contoh: 081234567890" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Email (Opsional)</label>
                        <input type="email" name="email" class="form-control" placeholder="alamat@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>

                <h4 style="font-family: var(--font-heading); color: var(--primary); margin-top: 15px; margin-bottom: 15px; border-bottom: 2px solid var(--primary-light); padding-bottom: 8px;">
                    <i class="fa-solid fa-baby"></i> 2. Data Si Kecil & Treatment
                </h4>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Bayi / Anak *</label>
                        <input type="text" name="baby_name" class="form-control" placeholder="Contoh: Dek Kenzo" required value="<?= htmlspecialchars($_POST['baby_name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Usia Bayi (Dalam Bulan) *</label>
                        <input type="number" name="baby_age_months" min="1" max="60" class="form-control" placeholder="Contoh: 6" required value="<?= htmlspecialchars($_POST['baby_age_months'] ?? '') ?>">
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Pilih Layanan Perawatan *</label>
                        <select name="service_id" id="service_select" class="form-control" required>
                            <option value="">-- Pilih Layanan Baby Spa --</option>
                            <?php foreach ($services as $s): ?>
                                <option value="<?= $s['id'] ?>" data-price="<?= $s['price'] ?>" <?= ($preselected_service_id == $s['id'] || (isset($_POST['service_id']) && $_POST['service_id'] == $s['id'])) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['duration']) ?>) - Rp <?= number_format($s['price'], 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Reservasi *</label>
                        <input type="date" name="reservation_date" id="reservation_date" class="form-control" required value="<?= htmlspecialchars($_POST['reservation_date'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jam Kunjungan *</label>
                        <select name="reservation_time" class="form-control" required>
                            <option value="">-- Pilih Sesi Jam --</option>
                            <option value="08:30:00">08.30 WIB (Sesi Pagi 1)</option>
                            <option value="10:00:00">10.00 WIB (Sesi Pagi 2)</option>
                            <option value="11:30:00">11.30 WIB (Sesi Siang 1)</option>
                            <option value="13:30:00">13.30 WIB (Sesi Siang 2)</option>
                            <option value="15:00:00">15.00 WIB (Sesi Sore 1)</option>
                            <option value="16:30:00">16.30 WIB (Sesi Sore 2)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Preferensi Terapis</label>
                        <select name="therapist_preference" class="form-control">
                            <option value="Sesuai Antrean">Bidan / Terapis Tersedia (Recommended)</option>
                            <option value="Terapis Wanita Khusus">Terapis Wanita Senior</option>
                            <option value="Terapis Langganan">Terapis Langganan Sebelumnya</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Catatan Tambahan / Alergi Kulit Bayi (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Bayi agak rewel jika terkena air dingin, atau memiliki alergi minyak kelapa..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="price-summary-box">
                    <div>
                        <span style="font-size:0.9rem; color:var(--text-muted); display:block;">Total Estimasi Biaya</span>
                        <small style="color:var(--primary); font-weight:600;">Pembayaran dilakukan langsung di tempat / kasir</small>
                    </div>
                    <div class="price-val" id="display_price">Rp 0</div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 1.1rem;">
                    <i class="fa-solid fa-paper-plane"></i> Konfirmasi & Simpan Reservasi
                </button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>
