<?php
require_once 'helpers.php';
require_login();
if(!is_admin()){ http_response_code(403); die('Hanya admin'); }

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'all';

// buat query sesuai mode
if($mode == "month"){
    $month = (int)$_GET['month'];
    $year  = (int)$_GET['year'];
    $title = "Rekap Pengaduan Bulan ".date("F", mktime(0,0,0,$month,1))." $year";
    $stmt = $mysqli->prepare("
        SELECT c.id,c.subject,c.category,c.status,c.created_at,u.name 
        FROM complaints c 
        JOIN users u ON c.user_id=u.id 
        WHERE MONTH(c.created_at)=? AND YEAR(c.created_at)=?
        ORDER BY c.created_at ASC
    ");
    $stmt->bind_param('ii',$month,$year);
}
elseif($mode == "year"){
    $year = (int)$_GET['year_only'];
    $title = "Rekap Pengaduan Tahun $year";
    $stmt = $mysqli->prepare("
        SELECT c.id,c.subject,c.category,c.status,c.created_at,u.name 
        FROM complaints c 
        JOIN users u ON c.user_id=u.id 
        WHERE YEAR(c.created_at)=?
        ORDER BY c.created_at ASC
    ");
    $stmt->bind_param('i',$year);
}
else { // all
    $title = "Rekap Pengaduan Keseluruhan";
    $stmt = $mysqli->prepare("
        SELECT c.id,c.subject,c.category,c.status,c.created_at,u.name 
        FROM complaints c 
        JOIN users u ON c.user_id=u.id 
        ORDER BY c.created_at ASC
    ");
}

$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title; ?></title>
  <style>
    body { font-family: Arial; }
    table { border-collapse: collapse; width: 100%; margin-top:20px; }
    th, td { border:1px solid #000; padding:6px; text-align:left; }
  </style>
</head>
<body onload="window.print()">
  <h2><?php echo $title; ?></h2>
  <table>
    <thead>
      <tr><th>Tanggal</th><th>Pelapor</th><th>Judul</th><th>Kategori</th><th>Status</th></tr>
    </thead>
    <tbody>
      <?php while($row=$res->fetch_assoc()): ?>
      <tr>
        <td><?php echo e($row['created_at']); ?></td>
        <td><?php echo e($row['name']); ?></td>
        <td><?php echo e($row['subject']); ?></td>
        <td><?php echo e($row['category']); ?></td>
        <td><?php echo e($row['status']); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
