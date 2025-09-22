<?php
// Ubah sesuai kredensial MySQL Anda
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'pengaduan_sukadamai';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die('Gagal koneksi database: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
?>
