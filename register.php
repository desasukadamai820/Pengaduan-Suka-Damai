<?php
require_once 'helpers.php';
if(is_logged_in()){ header('Location:index.php'); exit; }

$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm'] ?? '';

  if($name==='') $errors[] = 'Nama wajib diisi';
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid';
  if(strlen($password) < 6) $errors[] = 'Kata sandi minimal 6 karakter';
  if($password !== $confirm) $errors[] = 'Konfirmasi sandi tidak sama';

  if(!$errors){
    $stmt = $mysqli->prepare('SELECT id FROM users WHERE email=?');
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows>0){
      $errors[] = 'Email sudah terdaftar';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $role = 'warga';
      $stmt = $mysqli->prepare('INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?)');
      $stmt->bind_param('ssss',$name,$email,$hash,$role);
      $stmt->execute();
      $_SESSION['user'] = ['id'=>$stmt->insert_id,'name'=>$name,'email'=>$email,'role'=>$role];
      header('Location:index.php'); exit;
    }
  }
}
include 'partials/header.php';
?>
<h2>Daftar</h2>
<?php if($errors): ?><div class="alert"><?php echo implode('<br>',array_map('e',$errors)); ?></div><?php endif; ?>
<form method="post" class="card form">
  <label>Nama
    <input type="text" name="name" value="<?php echo e($_POST['name'] ?? ''); ?>" required>
  </label>
  <label>Email
    <input type="email" name="email" value="<?php echo e($_POST['email'] ?? ''); ?>" required>
  </label>
  <label>Kata Sandi
    <input type="password" name="password" required>
  </label>
  <label>Ulangi Kata Sandi
    <input type="password" name="confirm" required>
  </label>
  <button class="btn">Daftar</button>
</form>
<p>Sudah punya akun? <a href="login.php">Masuk</a></p>
<?php include 'partials/footer.php'; ?>
