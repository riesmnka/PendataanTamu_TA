<?php
$host     = 'localhost';
$user     = 'root';
$password = '';
$database = 'tugasakhir_db';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

date_default_timezone_set('Asia/Jakarta');

$hapus_otomatis = mysqli_query($conn, "
    DELETE FROM kunjungan 
    WHERE status = 'done' 
    AND waktu_keluar < DATE_SUB(NOW(), INTERVAL 6 MONTH)
");