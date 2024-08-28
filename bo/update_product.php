<?php
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Create connection
$mysqli = new mysqli("localhost", "root", "", "warehouse");

// Check connection
if ($mysqli->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $mysqli->connect_error]));
}

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Decode the JSON data sent in the POST request
    $data = json_decode(file_get_contents('php://input'), true);

    // Log the received data for debugging
    error_log(print_r($data, true));

    // Validate that all required fields are provided
    if (isset($data['productCode'], $data['productName'], $data['productLine'], $data['productScale'], 
              $data['productVendor'], $data['productDescription'], $data['quantityInStock'], 
              $data['buyPrice'], $data['MSRP'])) {

        // Prepare and bind
        $stmt = $mysqli->prepare("UPDATE `products` SET `productName`=?, `productLine`=?, `productScale`=?, 
                                  `productVendor`=?, `productDescription`=?, `quantityInStock`=?, 
                                  `buyPrice`=?, `MSRP`=? WHERE `productCode`=?");

        if ($stmt === false) {
            die(json_encode(["success" => false, "error" => "Prepare failed: " . $mysqli->error]));
        }

        // Bind parameters
        $stmt->bind_param(
            "sssssidis", 
            $data['productName'], 
            $data['productLine'], 
            $data['productScale'], 
            $data['productVendor'], 
            $data['productDescription'], 
            $data['quantityInStock'], // integer
            $data['buyPrice'], // double
            $data['MSRP'], // double
            $data['productCode']
        );

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Product updated successfully."]);
        } else {
            echo json_encode(["success" => false, "error" => "Error updating product: " . $stmt->error]);
        }

        // Close statement
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method. Only POST requests are allowed."]);
}

// Close connection
$mysqli->close();
?>
