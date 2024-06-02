<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'erta-junction';


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
