<?php
require_once 'helpers.php';
if(is_logged_in()){ header('Location:index.php'); exit; }
$error = null;

if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $stmt = $mysqli->prepare('SELECT id,name,email,password_hash,role FROM users WHERE email=?');
  $stmt->bind_param('s',$email);
  $stmt->execute();
  $res = $stmt->get_result();
  if($row = $res->fetch_assoc()){
    if(password_verify($password,$row['password_hash'])){
      $_SESSION['user']=['id'=>$row['id'],'name'=>$row['name'],'email'=>$row['email'],'role'=>$row['role']];
      header('Location:index.php'); exit;
    }
  }
  $error = 'Email atau kata sandi salah';
}
include 'partials/header.php';
?>
<h2>Masuk</h2>
<?php if($error): ?><div class="alert"><?php echo e($error); ?></div><?php endif; ?>
<form method="post" class="card form">
  <label>Email
    <input type="email" name="email" required>
  </label>
  <label>Kata Sandi
    <input type="password" name="password" required>
  </label>
  <button class="btn">Masuk</button>
</form>
<p>Belum punya akun? <a href="register.php">Daftar</a></p>
<?php include 'partials/footer.php'; ?>
