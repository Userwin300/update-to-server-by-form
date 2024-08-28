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
UPDATE `product` 
SET `names`=?,
`picture`=?,
`category`=?,
`price`=?,
`cost`= ?
WHERE `code` = ?";

// prepare and bind
$stmt = $conn->prepare($sql );
$stmt->bind_param("sssdds", $v1, $v2, $v3,$v4,$v5,$v6);

$v1 = "โค้กลิตร";
$v2 = "coke,jpg";
$v3 = "เครื่องดื่ม";
$v4 = 24.00;
$v5 = 5.00;
$v6 = 'A001' ;
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