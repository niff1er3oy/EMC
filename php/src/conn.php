<?php
    $servername = "158.108.110.95";
    $username = "datatech";
    $password = "datatech@csc";
    $dbname = "datatech";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>