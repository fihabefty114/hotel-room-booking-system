<?php

function getConnection() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "hotel_booking_system";

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

?>