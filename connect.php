<?php

$host = "localhost";       
$username = "root";        
$password = "";            
$database = "BUSHMANTTCL"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
echo "Succesvol verbonden met de database!";
?>
