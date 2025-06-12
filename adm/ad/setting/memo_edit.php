<?php

include_once('../../../db/dbconfig.php');

$id = $_POST['id'];
$memo = $_POST['data'];


$modify_sql = "update ad_link_tbl
    set 
    memo = '$memo'
    where
    id = $id";

$updateStmt = $db_conn->prepare($modify_sql);
$updateStmt->execute();
$count = $updateStmt->rowCount();

?>
