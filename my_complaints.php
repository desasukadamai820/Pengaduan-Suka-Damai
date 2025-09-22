<?php
require_once 'helpers.php';
require_login();

$stmt = $mysqli->prepare('SELECT * FROM complaints WHERE user_id=? ORDER BY created_at DESC');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$res = $stmt->get_result();
include 'partials/header.php';
?>
<h2>Pengaduan Saya</h2>
<table class="table card">
  <thead><tr><th>Tanggal</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody>
  <?php while($row=$res->fetch_assoc()): ?>
    <tr>
      <td><?php echo e($row['created_at']); ?></td>
      <td><?php echo e($row['subject']); ?></td>
      <td><?php echo e($row['category']); ?></td>
      <td><span class="badge status-<?php echo strtolower($row['status']); ?>"><?php echo e($row['status']); ?></span></td>
      <td><a class="btn small" href="view.php?id=<?php echo $row['id']; ?>">Detail</a></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<?php include 'partials/footer.php'; ?>
