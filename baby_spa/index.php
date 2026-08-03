<?php
require_once 'config.php';

// Fetch services from DB
$services_stmt = $pdo->query("SELECT * FROM services WHERE status='active' ORDER BY id ASC");
$services = $services_stmt->fetchAll();

// Fetch testimonials from DB
$testi_stmt = $pdo->query("SELECT * FROM testimonials WHERE is_featured=1 ORDER BY id DESC LIMIT 6");
$testimonials = $testi_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little Blossom Baby Spa & Care | Perawatan Sensori & Renang Bayi</title>
    <meta name="description" content="Baby Spa & Care profesional dengan fasilitas air hangat steril, terapis bersertifikat, dan pijat bayi organik untuk tumbuh kembang optimal si kecil.">
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon"><i class="fa-solid fa-baby"></i></div>
                <span>LittleBlossom</span>
            </a>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="#home" class="nav-link active">Beranda</a></li>
                <li><a href="#services" class="nav-link">Layanan & Harga</a></li>
                <li><a href="#why-us" class="nav-link">Fasilitas</a></li>
                <li><a href="#testimonials" class="nav-link">Testimoni</a></li>
                <li><a href="check_booking.php" class="nav-link"><i class="fa-solid fa-magnifying-glass"></i> Cek Booking</a></li>
                <li><a href="booking.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Reservasi Sekarang</a></li>
            </ul>

            <div class="mobile-toggle" id="mobileToggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="home">
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="badge"><i class="fa-solid fa-sparkles"></i> Baby Spa & Hydrotherapy Terbaik</div>
                <h1 class="hero-title">Cinta & Perawatan <span>Terbaik</span> Untuk Buah Hati Anda</h1>
                <p class="hero-desc">Bantu tumbuh kembang, stimulasikan motorik, dan berikan tidur paling nyenyak untuk si kecil bersama terapis profesional kami yang penuh kasih sayang.</p>
                <div class="hero-btns">
                    <a href="booking.php" class="btn btn-primary"><i class="fa-solid fa-calendar-plus"></i> Reservasi Online</a>
                    <a href="#services" class="btn btn-outline"><i class="fa-solid fa-list"></i> Lihat Layanan</a>
                </div>
            </div>
            <div class="hero-img-wrapper">
                <img src="assets/images/hero.jpg" alt="Baby Spa Hydrotherapy" class="hero-img">
                <div class="hero-card-float">
                    <div class="hero-card-icon"><i class="fa-solid fa-heart"></i></div>
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:var(--text-main);">100% Steril & Aman</strong>
                        <span style="font-size:0.85rem; color:var(--text-muted);">Air Terfilter Triple UV</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section class="section" id="services">
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Pilihan Treatment Terbaik</span>
                <h2 class="section-title">Layanan Favorit Si Kecil</h2>
                <p style="color:var(--text-muted); margin-top:8px;">Setiap treatment dirancang khusus untuk kenyamanan dan kesehatan emosional serta fisik bayi Anda.</p>
            </div>

            <div class="services-grid">
                <?php foreach ($services as $srv): ?>
                <div class="service-card">
                    <div class="service-img-box">
                        <img src="assets/images/<?= htmlspecialchars($srv['image']) ?>" alt="<?= htmlspecialchars($srv['name']) ?>" class="service-img">
                        <span class="service-badge"><?= htmlspecialchars($srv['category']) ?></span>
                    </div>
                    <div class="service-body">
                        <h3 class="service-title"><?= htmlspecialchars($srv['name']) ?></h3>
                        <p class="service-desc"><?= htmlspecialchars($srv['description']) ?></p>
                        <div class="service-footer">
                            <div>
                                <span class="service-price">Rp <?= number_format($srv['price'], 0, ',', '.') ?></span>
                                <span class="service-duration">/ <?= htmlspecialchars($srv['duration']) ?></span>
                            </div>
                            <a href="booking.php?service_id=<?= $srv['id'] ?>" class="btn btn-secondary" style="padding: 8px 18px; font-size:0.85rem;">
                                Booking <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="section" id="why-us" style="background: var(--bg-alt);">
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Keunggulan Kami</span>
                <h2 class="section-title">Mengapa Memilih Little Blossom?</h2>
            </div>

            <div class="features-grid">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-user-nurse"></i></div>
                    <h3 class="feature-title">Terapis Bersertifikat</h3>
                    <p class="feature-desc">Tim bidan & fisioterapis berlisensi resmi yang berpengalaman menangani bayi newborn hingga balita.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-shower"></i></div>
                    <h3 class="feature-title">Air Warm-Hydro UV</h3>
                    <p class="feature-desc">Air kolam hangat selalu diganti tiap sesi & disaring menggunakan saringan UV 3 tahap agar steril.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-leaf"></i></div>
                    <h3 class="feature-title">Minyak 100% Organik</h3>
                    <p class="feature-desc">Hanya menggunakan minyak pijat alami bebas paraben dan alkohol, sangat lembut di kulit sensitif.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-shield-cat"></i></div>
                    <h3 class="feature-title">Ruang Privat & Nyaman</h3>
                    <p class="feature-desc">Ruangan ber-AC dengan musik relaksasi bayi dan sistem aromaterapi alami yang menenangkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="section" id="testimonials">
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Kata Bunda & Mama</span>
                <h2 class="section-title">Pengalaman Senang Si Kecil</h2>
            </div>

            <div class="testi-grid">
                <?php foreach ($testimonials as $t): ?>
                <div class="testi-card">
                    <div class="testi-stars">
                        <?php for($i=0; $i<$t['rating']; $i++): ?>
                            <i class="fa-solid fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="testi-comment">"<?= htmlspecialchars($t['comment']) ?>"</p>
                    <div class="testi-author">
                        <div class="testi-avatar"><?= substr(htmlspecialchars($t['parent_name']), 0, 1) ?></div>
                        <div>
                            <div class="author-name"><?= htmlspecialchars($t['parent_name']) ?></div>
                            <div class="author-baby"><?= htmlspecialchars($t['baby_name']) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="section" style="background: linear-gradient(135deg, var(--primary) 0%, #2A6A5E 100%); color: white; text-align: center;">
        <div class="container">
            <h2 style="font-family: var(--font-heading); font-size:2.5rem; margin-bottom:15px;">Siap Memanjakan Si Kecil Hari Ini?</h2>
            <p style="font-size:1.1rem; max-width:600px; margin: 0 auto 30px; opacity:0.9;">Pesan slot reservasi Anda sekarang untuk mendapatkan jam kunjungan yang fleksibel dan penawaran spesial!</p>
            <a href="booking.php" class="btn btn-secondary" style="font-size:1.1rem; padding: 15px 35px;">
                <i class="fa-solid fa-calendar-check"></i> Reservasi Kunjungan Sekarang
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <div class="logo-icon"><i class="fa-solid fa-baby"></i></div>
                        <span>LittleBlossom</span>
                    </div>
                    <p class="footer-desc">Pusat perawatan bayi & anak terpercaya untuk mendukung tumbuh kembang yang sehat, ceria, dan bahagia.</p>
                </div>
                <div>
                    <h4 class="footer-title">Menu Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#services">Layanan Spa</a></li>
                        <li><a href="booking.php">Reservasi Online</a></li>
                        <li><a href="check_booking.php">Cek Status Booking</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Jam Operasional</h4>
                    <p style="font-size:0.9rem; line-height:1.8; color:#A0AEC0;">
                        Senin - Sabtu: 08.00 - 17.00 WIB<br>
                        Minggu: 09.00 - 16.00 WIB<br>
                        <em>(Buka Setiap Hari)</em>
                    </p>
                </div>
                <div>
                    <h4 class="footer-title">Kontak & Lokasi</h4>
                    <p style="font-size:0.9rem; line-height:1.8; color:#A0AEC0; margin-bottom:12px;">
                        <i class="fa-solid fa-location-dot" style="color:var(--primary);"></i> Jl. Melati Harapan No. 45, Kota<br>
                        <i class="fa-solid fa-phone" style="color:var(--primary);"></i> (021) 555-8899<br>
                        <i class="fa-brands fa-whatsapp" style="color:#25D366;"></i> 0812-3456-7890
                    </p>
                    <a href="admin/login.php" style="font-size:0.8rem; color:#718096;"><i class="fa-solid fa-lock"></i> Area Administrator</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Little Blossom Baby Spa & Care. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6281234567890?text=Halo%20Little%20Blossom%20Baby%20Spa,%20saya%20ingin%20tanya%20informasi%20layanan" target="_blank" class="float-wa" title="Hubungi via WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>
