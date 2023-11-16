<?php 

    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "db_dashboard";

    $conn = mysqli_connect($host,$user,$password,$dbname);
    if (!$conn) {
        echo "Error connecting to database: " . mysqli_error_string;
    }
?>