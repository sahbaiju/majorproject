<?php
// Create connection
$con = mysqli_connect("localhost", "root", "", "hospital_management");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} 
?>
