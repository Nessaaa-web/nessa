<?php
require_once 'header.php';

// Stats queries
$total_res = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$pending_res = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status='Pending'")->fetchColumn();
$confirmed_res = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status='Dikonfirmasi'")->fetchColumn();
$completed_res = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status='Selesai'")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_price) FROM reservations WHERE status!='Dibatalkan'")->fetchColumn() ?: 0;

// Fetch 5 latest reservations
$stmt = $pdo->query("SELECT r.*, s.name as service_name 
                       FROM reservations r 
                       JOIN services s ON r.service_id = s.id 
                       ORDER BY r.id DESC LIMIT 5");
$latest_reservations = $stmt->fetchAll();
?>

<!-- STATS CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-calendar-days"></i></div>
        <div>
            <div class="stat-num"><?= number_format($total_res) ?></div>
            <div class="stat-label">Total Reservasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fa-solid fa-clock"></i></div>
        <div>
            <div class="stat-num"><?= number_format($pending_res) ?></div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div>
            <div class="stat-num"><?= number_format($completed_res) ?></div>
            <div class="stat-label">Sesi Selesai</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-wallet"></i></div>
        <div>
            <div class="stat-num">Rp <?= number_format($total_revenue, 0, ',', '.') ?></div>
            <div class="stat-label">Estimasi Omset</div>
        </div>
    </div>
</div>

<!-- LATEST RESERVATIONS TABLE -->
<div class="table-card">
    <div class="table-header">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--admin-dark);">Reservasi Terbaru</h3>
        <a href="reservations.php" style="color: var(--admin-primary); font-size: 0.88rem; font-weight: 600; text-decoration: none;">
            Lihat Semua <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Orang Tua & Bayi</th>
                    <th>Treatment</th>
                    <th>Jadwal Kunjungan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($latest_reservations) > 0): ?>
                    <?php foreach ($latest_reservations as $r): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($r['booking_code']) ?></strong></td>
                            <td>
                                <div><strong><?= htmlspecialchars($r['parent_name']) ?></strong></div>
                                <div style="font-size: 0.8rem; color: var(--admin-muted);">Bayi: <?= htmlspecialchars($r['baby_name']) ?> (<?= $r['baby_age_months'] ?> bln)</div>
                            </td>
                            <td><?= htmlspecialchars($r['service_name']) ?></td>
                            <td>
                                <div><?= date('d-m-Y', strtotime($r['reservation_date'])) ?></div>
                                <div style="font-size: 0.8rem; color: var(--admin-muted);"><?= date('H:i', strtotime($r['reservation_time'])) ?> WIB</div>
                            </td>
                            <td><strong>Rp <?= number_format($r['total_price'], 0, ',', '.') ?></strong></td>
                            <td>
                                <?php
                                $status_cls = 'status-pending';
                                if ($r['status'] == 'Dikonfirmasi') $status_cls = 'status-confirmed';
                                elseif ($r['status'] == 'Selesai') $status_cls = 'status-completed';
                                elseif ($r['status'] == 'Dibatalkan') $status_cls = 'status-cancelled';
                                ?>
                                <span class="status-badge <?= $status_cls ?>"><?= htmlspecialchars($r['status']) ?></span>
                            </td>
                            <td>
                                <a href="print_ticket.php?id=<?= $r['id'] ?>" target="_blank" class="btn-sm btn-print" title="Cetak"><i class="fa-solid fa-print"></i></a>
                                <a href="reservations.php?search=<?= urlencode($r['booking_code']) ?>" class="btn-sm btn-confirm" title="Kelola"><i class="fa-solid fa-pen-to-square"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--admin-muted); padding: 30px;">Belum ada reservasi masuk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</main>
</div>
</body>
</html>
