<?php
$host = "localhost";
$db = "test";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
