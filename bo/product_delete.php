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
DELETE FROM `product`
WHERE `code` = ?";

// prepare and bind
$stmt = $conn->prepare($sql );
$stmt->bind_param("s", $v1);

$v1 = '1' ;

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