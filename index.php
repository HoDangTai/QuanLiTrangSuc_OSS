<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlts";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, 'utf8');
$query = 'SELECT * FROM sanpham';
$result = mysqli_query($conn, $query);
?>