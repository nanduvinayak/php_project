<?php
$host = '172.31.22.43';
$username = 'Vinayak200549292';
$password = 'ewbAg3_AQ9';
$database = 'Vinayak200549292';


$db = mysqli_connect($host, $username, $password, $database);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
