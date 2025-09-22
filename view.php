<?php
require_once 'helpers.php';
require_login();

$id = intval($_GET['id'] ?? 0);
$stmt = $mysqli->prepare('SELECT c.*, u.name as user_name, u.email FROM complaints c JOIN users u ON c.user_id=u.id WHERE c.id=?');
$stmt->bind_param('i',$id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if(!$row){ http_response_code(404); die('Pengaduan tidak ditemukan'); }

// Only owner or admin can view
if(!is_admin() && $_SESSION['user']['id']!=$row['user_id']){
  http_response_code(403); die('Akses ditolak');
}

include 'partials/header.php';
?>
<h2>Detail Pengaduan</h2>
<div class="card">
  <p><strong>Pelapor:</strong> <?php echo e($row['user_name']); ?> (<?php echo e($row['email']); ?>)</p>
  <p><strong>Tanggal:</strong> <?php echo e($row['created_at']); ?></p>
  <p><strong>Kategori:</strong> <?php echo e($row['category']); ?> &raquo; <?php echo e($row['subcategory']); ?></p>
  <p><strong>Judul:</strong> <?php echo e($row['subject']); ?></p>
  <p><strong>Uraian:</strong><br><?php echo nl2br(e($row['content'])); ?></p>
  <?php if($row['location']): ?><p><strong>Lokasi:</strong> <?php echo e($row['location']); ?></p><?php endif; ?>
  <?php if($row['attachment']): ?>
    <p><strong>Lampiran:</strong>
      <a class="btn small" target="_blank" href="uploads/<?php echo e($row['attachment']); ?>">Lihat</a>
    </p>
  <?php endif; ?>
  <p><strong>Status:</strong> <span class="badge status-<?php echo strtolower($row['status']); ?>"><?php echo e($row['status']); ?></span></p>
  <?php if(is_admin()): ?>
  <form method="post" action="update_status.php" class="inline">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <select name="status">
      <?php foreach(statuses() as $s): ?>
        <option value="<?php echo e($s); ?>" <?php echo $s===$row['status']?'selected':''; ?>><?php echo e($s); ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn small">Ubah Status</button>
  </form>
  <?php endif; ?>
</div>
<?php include 'partials/footer.php'; ?>
