<?php
    header('Content-Type: application/json');
    // $servername = "158.108.110.95";
    $servername = "db";
    $username = "datatech";
    $password = "datatech@csc";
    $dbname = "datatech";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>