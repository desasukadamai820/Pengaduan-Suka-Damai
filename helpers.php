<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__.'/config.php';

function is_logged_in(){ return isset($_SESSION['user']); }
function require_login(){
  if(!is_logged_in()){
    header('Location: login.php'); exit;
  }
}
function is_admin(){ return is_logged_in() && $_SESSION['user']['role']==='admin'; }
function e($str){ return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

function categories(){
  return [
    'Pelayanan Pemerintahan' => ['BPJS','Bansos','Rumah Layak Huni','Kesejahteraan','KDRT','Kekerasan Anak'],
    'Fasilitas Umum' => ['Jalan Rusak','Akses/Jembatan Tidak Memadai','Drainase'],
    'Lingkungan Sosial' => ['Sampah Menumpuk','Hewan Ternak Dilepas','Pencurian','Konflik Antar Warga']
  ];
}

function statuses(){ return ['Baru','Diproses','Selesai','Ditolak']; }

?>
