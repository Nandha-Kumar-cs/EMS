<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'ems';
$dbPort = 3307;

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName , $dbPort);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
