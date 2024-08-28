<?php
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Database connection
$mysqli = new mysqli("localhost", "root", "", "warehouse"); //แก้ warehouse เมื่อใช้คอมในห้อง

// Check connection
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $mysqli->connect_error]));
}

// Prepare SQL query
$sql = "SELECT `productCode`, `productName`, `productLine`, `productScale`, `productVendor`, 
        `productDescription`, `quantityInStock`, `buyPrice`, `MSRP` FROM `products`
       ";
$result = $mysqli->query($sql);

// Initialize an array to hold the result
$data = [];

if ($result->num_rows > 0) {
    // Fetch all rows and add them to the array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data[] = ["message" => "No records found"];
}

// Output data in JSON format
echo json_encode($data);

// Close connection
$mysqli->close();
?>
