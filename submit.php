<?php
require_once 'helpers.php';
require_login();

$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nama = trim($_POST['nama'] ?? '');
  $nik = trim($_POST['nik'] ?? '');
  $alamat = trim($_POST['alamat'] ?? '');
  $category = $_POST['category'] ?? '';
  $subcategory = $_POST['subcategory'] ?? '';
  $subject = trim($_POST['subject'] ?? '');
  $content = trim($_POST['content'] ?? '');
  $location = trim($_POST['location'] ?? '');
  $filename = null;

  // Validasi
  if($nama==='') $errors[]='Nama wajib diisi';
  if($nik==='') $errors[]='NIK wajib diisi';
  if($alamat==='') $errors[]='Alamat wajib diisi';
  if(!$category) $errors[]='Kategori wajib dipilih';
  if($subject==='') $errors[]='Judul/Laporan singkat wajib diisi';
  if($content==='') $errors[]='Uraian wajib diisi';

  // Upload lampiran
  if(isset($_FILES['attachment']) && $_FILES['attachment']['error']==UPLOAD_ERR_OK){
    $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
    $safe = uniqid('lampiran_').'.'.$ext;
    $dest = __DIR__.'/uploads/'.$safe;
    if(move_uploaded_file($_FILES['attachment']['tmp_name'], $dest)){
      $filename = $safe;
    }
  }

  // Simpan ke database
  if(!$errors){
    $status = 'Baru';
    $stmt = $mysqli->prepare('INSERT INTO complaints(user_id,nama,nik,alamat,category,subcategory,subject,content,location,attachment,status) 
                              VALUES(?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->bind_param(
      'issssssssss',
      $_SESSION['user']['id'],
      $nama,
      $nik,
      $alamat,
      $category,
      $subcategory,
      $subject,
      $content,
      $location,
      $filename,
      $status
    );
    $stmt->execute();
    header('Location: my_complaints.php'); exit;
  }
}

include 'partials/header.php';
?>
<h2>Buat Pengaduan</h2>
<?php if($errors): ?><div class="alert"><?php echo implode('<br>',array_map('e',$errors)); ?></div><?php endif; ?>

<form method="post" class="card form" enctype="multipart/form-data">
  <label>Nama
    <input type="text" name="nama" value="<?php echo e($_POST['nama'] ?? ''); ?>" required>
  </label>
  <label>NIK
    <input type="text" name="nik" value="<?php echo e($_POST['nik'] ?? ''); ?>" required>
  </label>
  <label>Alamat
    <input type="text" name="alamat" value="<?php echo e($_POST['alamat'] ?? ''); ?>" required>
  </label>

  <label>Kategori
    <select name="category" id="category" required>
      <option value="">-- pilih --</option>
      <?php foreach(categories() as $cat=>$subs): ?>
        <option value="<?php echo e($cat); ?>" <?php echo (($_POST['category']??'')===$cat)?'selected':''; ?>><?php echo e($cat); ?></option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Subkategori
    <select name="subcategory" id="subcategory">
      <option value="">-- pilih --</option>
    </select>
  </label>

  <label>Judul/Laporan Singkat
    <input type="text" name="subject" value="<?php echo e($_POST['subject'] ?? ''); ?>" required>
  </label>

  <label>Uraian
    <textarea name="content" rows="5" required><?php echo e($_POST['content'] ?? ''); ?></textarea>
  </label>

  <label>Lokasi Kejadian (opsional)
    <input type="text" name="location" value="<?php echo e($_POST['location'] ?? ''); ?>">
  </label>

  <label>Lampiran (gambar/pdf, opsional)
    <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
  </label>

  <button class="btn">Kirim Pengaduan</button>
</form>

<script>
// subcategory options source
const CATS = <?php echo json_encode(categories(), JSON_UNESCAPED_UNICODE); ?>;
const catSel = document.getElementById('category');
const subSel = document.getElementById('subcategory');
function refreshSub(){
  const c = catSel.value;
  subSel.innerHTML = '<option value="">-- pilih --</option>';
  if(CATS[c]){
    CATS[c].forEach(s=>{
      const opt = document.createElement('option'); opt.value=s; opt.textContent=s;
      if('<?php echo e($_POST['subcategory'] ?? ''); ?>'===s) opt.selected=true;
      subSel.appendChild(opt);
    })
  }
}
catSel.addEventListener('change', refreshSub);
refreshSub();
</script>
<?php include 'partials/footer.php'; ?>
