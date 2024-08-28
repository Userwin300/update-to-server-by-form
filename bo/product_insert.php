<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Create connection
$conn = new mysqli("localhost", "root", "", "warehouse");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// create cmd new record
$sql = "
INSERT INTO product (code,names,picture,category,price,cost )
VALUES (?, ?, ?, ?, ?, ?)";

$category_sql = "
INSERT INTO categories (CategoryID,CategoryName,Description)
VALUES (?, ?, ?)";

$orderdetail_sql = "
INSERT INTO orderdetails (OrderDetailID,OrderID,ProductID,Quantity)
VALUES (?, ?, ?,?)";

$order_sql = "
INSERT INTO orders (OrderID,CustomerID,EmployeeID,OrderDate,ShipperID)
VALUES (?, ?, ?,?,?)";


// prepare and bind
$stmt = $conn->prepare($sql );
$stmt->bind_param("ssssdd", $v1, $v2, $v3,$v4,$v5,$v6);

$v1 = "A002";
$v2 = "นมถั่วเหลือง";
$v3 = "milk1,jpg";
$v4 = "เครื่องดื่ม";
$v5 = 10.00;
$v6 = 4.25 ;
$stmt->execute();

$bo = '{
"v1": "A002", 
"v2" : "นมถั่วเหลือง", 
"v3" : "milk1,jpg" ,
"v4" : "เครื่องดื่ม" , 
"v5" : 10.00, 
"v6" : 4.25}';

$data = json_decode($bo,true);


$conn->close();
?>