<?php
require_once 'helpers.php';
require_login();
if(!is_admin()){ http_response_code(403); die('Hanya admin'); }
$id = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'Baru';
if(!in_array($status, statuses())) $status='Baru';
$stmt = $mysqli->prepare('UPDATE complaints SET status=? WHERE id=?');
$stmt->bind_param('si',$status,$id);
$stmt->execute();
header('Location: view.php?id='.$id);
exit;
