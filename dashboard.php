<?php
require_once 'helpers.php';
require_login();
if(!is_admin()){ http_response_code(403); die('Hanya admin'); }

// ambil bulan & tahun dari filter (default bulan ini)
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('m');
$selected_year  = isset($_GET['year']) ? $_GET['year'] : date('Y');

// simple stats
$counts = [];
foreach(statuses() as $s){
  $stmt = $mysqli->prepare('SELECT COUNT(*) as c FROM complaints WHERE status=?');
  $stmt->bind_param('s',$s); 
  $stmt->execute(); 
  $counts[$s] = $stmt->get_result()->fetch_assoc()['c'];
}

// ambil data pengaduan sesuai bulan filter
$stmt = $mysqli->prepare("
  SELECT c.id,c.subject,c.category,c.status,c.created_at,u.name 
  FROM complaints c 
  JOIN users u ON c.user_id=u.id 
  WHERE MONTH(c.created_at)=? AND YEAR(c.created_at)=?
  ORDER BY c.created_at DESC
");
$stmt->bind_param('ii',$selected_month,$selected_year);
$stmt->execute();
$res = $stmt->get_result();

include 'partials/header.php';
?>
<h2>Dashboard Admin</h2>
<div class="grid three">
  <?php foreach($counts as $k=>$v): ?>
    <div class="card center">
      <div class="stat"><?php echo e($v); ?></div>
      <div class="muted"><?php echo e($k); ?></div>
    </div>
  <?php endforeach; ?>
</div>

<h3>Pengaduan Terbaru</h3>

<!-- Form filter bulan untuk tabel -->
<form method="get" class="card" style="padding:10px; margin-bottom:15px;">
  <label>Pilih Bulan:
    <select name="month">
      <?php for($m=1;$m<=12;$m++): ?>
        <option value="<?php echo $m; ?>" <?php if($m==$selected_month) echo "selected"; ?>>
          <?php echo date("F", mktime(0,0,0,$m,1)); ?>
        </option>
      <?php endfor; ?>
    </select>
  </label>
  <label>Tahun:
    <select name="year">
      <?php for($y=date('Y')-5;$y<=date('Y');$y++): ?>
        <option value="<?php echo $y; ?>" <?php if($y==$selected_year) echo "selected"; ?>>
          <?php echo $y; ?>
        </option>
      <?php endfor; ?>
    </select>
  </label>
  <button type="submit" class="btn">Filter</button>
</form>

<!-- Form Print Rekap -->
<div class="card" style="padding:10px; margin-bottom:15px;">
  <form method="get" action="print_rekap.php" target="_blank">
    <label>Pilih Jenis Rekap:</label>
    <select name="mode" id="mode" onchange="toggleFilter()">
      <option value="month">Per Bulan</option>
      <option value="year">Per Tahun</option>
      <option value="all">Keseluruhan</option>
    </select>

    <span id="monthYearFilter">
      <select name="month">
        <?php for($m=1;$m<=12;$m++): ?>
          <option value="<?php echo $m; ?>" <?php if($m==date('m')) echo "selected"; ?>>
            <?php echo date("F", mktime(0,0,0,$m,1)); ?>
          </option>
        <?php endfor; ?>
      </select>
      <select name="year">
        <?php for($y=date('Y')-5;$y<=date('Y');$y++): ?>
          <option value="<?php echo $y; ?>" <?php if($y==date('Y')) echo "selected"; ?>>
            <?php echo $y; ?>
          </option>
        <?php endfor; ?>
      </select>
    </span>

    <span id="yearFilter" style="display:none;">
      <select name="year_only">
        <?php for($y=date('Y')-5;$y<=date('Y');$y++): ?>
          <option value="<?php echo $y; ?>" <?php if($y==date('Y')) echo "selected"; ?>>
            <?php echo $y; ?>
          </option>
        <?php endfor; ?>
      </select>
    </span>

    <button type="submit" class="btn">Print Rekap</button>
  </form>
</div>

<table class="table card">
  <thead>
    <tr>
      <th>Tanggal</th><th>Pelapor</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php while($row=$res->fetch_assoc()): ?>
    <tr>
      <td><?php echo e($row['created_at']); ?></td>
      <td><?php echo e($row['name']); ?></td>
      <td><?php echo e($row['subject']); ?></td>
      <td><?php echo e($row['category']); ?></td>
      <td><span class="badge status-<?php echo strtolower($row['status']); ?>"><?php echo e($row['status']); ?></span></td>
      <td><a class="btn small" href="view.php?id=<?php echo $row['id']; ?>">Detail</a></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<script>
function toggleFilter(){
  var mode = document.getElementById("mode").value;
  document.getElementById("monthYearFilter").style.display = (mode=="month") ? "inline" : "none";
  document.getElementById("yearFilter").style.display      = (mode=="year") ? "inline" : "none";
}
</script>

<?php include 'partials/footer.php'; ?>
