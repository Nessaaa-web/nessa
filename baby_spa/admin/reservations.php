<?php
require_once 'header.php';

$message = '';
$error = '';

// Handle Status Change
if (isset($_GET['action']) && $_GET['action'] === 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $new_status = $_GET['status'];
    $allowed_statuses = ['Pending', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'];

    if (in_array($new_status, $allowed_statuses)) {
        $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);
        $message = "Status reservasi berhasil diperbarui menjadi '$new_status'.";
    }
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Data reservasi berhasil dihapus.";
}

// Filters & Search
$status_filter = trim($_GET['status'] ?? '');
$search = trim($_GET['search'] ?? '');

$sql = "SELECT r.*, s.name as service_name 
        FROM reservations r 
        JOIN services s ON r.service_id = s.id 
        WHERE 1=1";
$params = [];

if (!empty($status_filter)) {
    $sql .= " AND r.status = ?";
    $params[] = $status_filter;
}

if (!empty($search)) {
    $sql .= " AND (r.booking_code LIKE ? OR r.parent_name LIKE ? OR r.baby_name LIKE ? OR r.phone LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$sql .= " ORDER BY r.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservations = $stmt->fetchAll();
?>

<?php if (!empty($message)): ?>
    <div style="background: #DCFCE7; color: #15803D; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
        <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<!-- SEARCH & FILTER BAR -->
<div class="table-card" style="padding: 20px; margin-bottom: 25px;">
    <form action="reservations.php" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 250px;">
            <input type="text" name="search" placeholder="Cari Kode Booking / Nama / HP..." value="<?= htmlspecialchars($search) ?>" style="width: 100%; padding: 10px 14px; border: 1px solid var(--admin-border); border-radius: 8px;">
        </div>

        <div>
            <select name="status" style="padding: 10px 14px; border: 1px solid var(--admin-border); border-radius: 8px; background: white;">
                <option value="">-- Semua Status --</option>
                <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Dikonfirmasi" <?= $status_filter == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                <option value="Selesai" <?= $status_filter == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                <option value="Dibatalkan" <?= $status_filter == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
            </select>
        </div>

        <button type="submit" class="btn-sm btn-confirm" style="padding: 10px 20px;">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        <a href="reservations.php" class="btn-sm btn-print" style="padding: 10px 15px; text-decoration: none;">Reset</a>
    </form>
</div>

<!-- RESERVATION LIST TABLE -->
<div class="table-card">
    <div class="table-header">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--admin-dark);">Daftar Seluruh Reservasi (<?= count($reservations) ?>)</h3>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Orang Tua & WA</th>
                    <th>Bayi & Usia</th>
                    <th>Treatment</th>
                    <th>Jadwal Kunjungan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservations) > 0): ?>
                    <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($r['booking_code']) ?></strong></td>
                            <td>
                                <div><strong><?= htmlspecialchars($r['parent_name']) ?></strong></div>
                                <div style="font-size: 0.8rem; color: var(--admin-muted);"><i class="fa-brands fa-whatsapp" style="color:#25D366;"></i> <?= htmlspecialchars($r['phone']) ?></div>
                            </td>
                            <td>
                                <div><strong><?= htmlspecialchars($r['baby_name']) ?></strong></div>
                                <div style="font-size: 0.8rem; color: var(--admin-muted);"><?= $r['baby_age_months'] ?> Bulan</div>
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
                                <select onchange="location = this.value;" style="padding: 4px 8px; border-radius: 6px; border: 1px solid #CBD5E0; font-size: 0.8rem;">
                                    <option value="">Pilih...</option>
                                    <option value="reservations.php?action=update_status&id=<?= $r['id'] ?>&status=Dikonfirmasi">Dikonfirmasi</option>
                                    <option value="reservations.php?action=update_status&id=<?= $r['id'] ?>&status=Selesai">Selesai</option>
                                    <option value="reservations.php?action=update_status&id=<?= $r['id'] ?>&status=Dibatalkan">Dibatalkan</option>
                                    <option value="reservations.php?action=update_status&id=<?= $r['id'] ?>&status=Pending">Pending</option>
                                </select>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="print_ticket.php?id=<?= $r['id'] ?>" target="_blank" class="btn-sm btn-print" title="Cetak Struk"><i class="fa-solid fa-print"></i></a>
                                    <a href="reservations.php?action=delete&id=<?= $r['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data reservasi ini?');" class="btn-sm btn-delete" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--admin-muted); padding: 30px;">Data reservasi tidak ditemukan.</td>
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
