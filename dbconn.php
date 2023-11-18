<?php 

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "sample_db";

    $conn = mysqli_connect($host,$user,$password,$dbname);
    if (!$conn) {
        echo "Error connecting to database: " . mysqli_connect_error();
    }
?>