<?php
require_once 'header.php';

$message = '';
$error = '';

// Handle Create Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $desc = trim($_POST['description'] ?? '');

    if (empty($name) || empty($category) || empty($duration) || $price <= 0) {
        $error = "Seluruh kolom layanan wajib diisi!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO services (name, category, duration, price, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $duration, $price, $desc]);
        $message = "Layanan '$name' berhasil ditambahkan!";
    }
}

// Handle Delete Service
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Layanan berhasil dihapus.";
}

// Handle Toggle Status
if (isset($_GET['action']) && $_GET['action'] === 'toggle_status' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("UPDATE services SET status = IF(status='active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Status layanan berhasil diperbarui.";
}

// Fetch all services
$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
?>

<?php if (!empty($message)): ?>
    <div style="background: #DCFCE7; color: #15803D; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
        <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div style="background: #FEE2E2; color: #991B1B; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
        <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    
    <!-- ADD SERVICE FORM -->
    <div class="table-card" style="padding: 25px; height: fit-content;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--admin-dark); margin-bottom: 20px;">
            <i class="fa-solid fa-plus-circle" style="color: var(--admin-primary);"></i> Tambah Layanan Baru
        </h3>

        <form action="services.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Nama Layanan Treatment *</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Baby Floating Swim" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Kategori *</label>
                <select name="category" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px; background:white;">
                    <option value="Pijat & Renang">Pijat & Renang</option>
                    <option value="Paket Hemat">Paket Hemat</option>
                    <option value="Perawatan Khusus">Perawatan Khusus</option>
                    <option value="Grooming">Grooming</option>
                    <option value="Sensori & Fit">Sensori & Fit</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Durasi Sesi *</label>
                <input type="text" name="duration" placeholder="Contoh: 45 Menit" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Harga (Rp) *</label>
                <input type="number" name="price" step="1000" placeholder="120000" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Deskripsi Singkat</label>
                <textarea name="description" rows="3" placeholder="Penjelasan manfaat treatment..." style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;"></textarea>
            </div>

            <button type="submit" name="add_service" class="btn-sm btn-complete" style="width: 100%; padding: 12px; font-weight: 700;">
                <i class="fa-solid fa-save"></i> Simpan Layanan Baru
            </button>
        </form>
    </div>

    <!-- SERVICES TABLE -->
    <div class="table-card">
        <div class="table-header">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--admin-dark);">Daftar Treatment Spa (<?= count($services) ?>)</h3>
        </div>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Kategori</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $s): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($s['name']) ?></strong>
                                <div style="font-size:0.8rem; color:var(--admin-muted);"><?= htmlspecialchars(substr($s['description'], 0, 50)) ?>...</div>
                            </td>
                            <td><span style="background:#F1F5F9; padding:4px 8px; border-radius:4px; font-size:0.8rem;"><?= htmlspecialchars($s['category']) ?></span></td>
                            <td><?= htmlspecialchars($s['duration']) ?></td>
                            <td><strong>Rp <?= number_format($s['price'], 0, ',', '.') ?></strong></td>
                            <td>
                                <a href="services.php?action=toggle_status&id=<?= $s['id'] ?>" class="status-badge <?= $s['status'] == 'active' ? 'status-completed' : 'status-cancelled' ?>" style="text-decoration:none;">
                                    <?= ucfirst($s['status']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="services.php?action=delete&id=<?= $s['id'] ?>" onclick="return confirm('Hapus layanan ini?');" class="btn-sm btn-delete"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</main>
</div>
</body>
</html>
