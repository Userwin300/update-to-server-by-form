<?php
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Database connection
$mysqli = new mysqli("localhost", "root", "", "warehouse"); // Adjust credentials as necessary

// Check connection
if ($mysqli->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $mysqli->connect_error]));
}

// Initialize variables
$key_search = "";

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request
    $key_search = isset($_GET['key']) ? $_GET['key'] : '';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request
    $postData = file_get_contents("php://input");
    $request = json_decode($postData, true);
    $key_search = isset($request['key']) ? $request['key'] : '';
}

// Prepare SQL query with a placeholder
$sql = "SELECT `productCode`, `productName`, `productLine`, `productScale`, `productVendor`, 
        `productDescription`, `quantityInStock`, `buyPrice`, `MSRP` 
        FROM `products`
        WHERE `productCode` = ?";

// Prepare the statement
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die(json_encode(["error" => "Prepare failed: " . $mysqli->error]));
}

// Bind the parameter to the statement
$stmt->bind_param("s", $key_search);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

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

// Close statement and connection
$stmt->close();
$mysqli->close();
?>
