<?php
// Database connection settings
$servername = "localhost"; // Change this if your database server is hosted elsewhere
$username = "root";       // Your database username
$password = "";           // Your database password
$dbname = "BushmanTactical"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Example query to fetch data from the Products table
$sql = "SELECT * FROM Products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Product ID: " . $row["ProductID"] . " - Name: " . $row["ProductName"] . " - Price: $" . $row["Price"] . "<br>";
    }
} else {
    echo "0 results";
}

// Close the connection
$conn->close();
?>