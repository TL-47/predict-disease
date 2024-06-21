<?php
$host = 'localhost';
$user = 'root';
$pass = 'Centoria123';
$dbname = 'predisease';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
