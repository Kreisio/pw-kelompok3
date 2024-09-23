<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crud_khodam";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname); 

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
echo "";
?>
