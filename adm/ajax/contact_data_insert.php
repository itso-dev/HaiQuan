<?php
include_once('../head.php');

session_start();
//    error_reporting(E_ALL);
//    ini_set('display_errors', '1');
error_reporting(0);
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Seoul');

$posted = date("Y-m-d H:i:s");
$name = $_POST["name"];
$phone = $_POST["phone"];
$location = $_POST["location"];
$manager_name = isset($_POST["manager_name"]) ? $_POST["manager_name"] : '';
$desc = isset($_POST["contact_desc"]) ? $_POST["contact_desc"] : '';

$sql="
        insert into contact_tbl
            (ad_code, name, phone, location,
            contact_desc, result_status,
            consult_fk, write_date)
        value
            (?, ?, ?, ?,
            ?, ?, 
            ?, ?)";

$db_conn->prepare($sql)->execute(
    ['', $name, $phone, $location,
        $desc, '대기',
        0, $posted]);


echo "<script type='text/javascript'>";
echo "alert('문의 데이터가 추가 되었습니다.'); location.href='../apply_list.php?menu=55'";
echo "</script>";

?>
