<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Create connection

function mysqlConnect(){
    $db = null;
    $dsn = "mysql:host=localhost;port=3306;dbname=SolutionManagement;charset=utf8";
    // $dsn = "mysql:host=175.126.123.155;port=3306;dbname=SolutionManagement;charset=utf8";
    try {
        $db = new PDO($dsn, "root", "root");
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
    return $db;
}

$db_conn = mysqlConnect();
// // Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }


?>
