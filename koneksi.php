<?php
$host = "mysql.railway.internal";
$user = "root";
$pass = "HDYttyeqFfDUKMqkkfOndFaMdPgEBAiR";
$db = "railway";

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error) {
    die("Koneksi gagal :" . $conn->connect_error);
}

?>