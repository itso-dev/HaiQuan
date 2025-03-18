<?php
include_once('../../../db/dbconfig.php');

$id = $_POST['id'];
$val = intval($_POST['val']);
$sort = intval($_POST['sort']);

$role_sql = "select * from admin_role_tbl where id = " .$id;
$role_stt=$db_conn->prepare($role_sql);
$role_stt->execute();
$role = $role_stt -> fetch();

$authority = json_decode($role['authority'], true);

if (is_array($authority) && isset($authority[$sort])) {
    $authority[$sort] = $val; // 특정 위치의 값을 변경
}

// JSON 형식으로 다시 인코딩
$updated_authority = json_encode($authority, JSON_UNESCAPED_UNICODE);

    $modify_sql = "update admin_role_tbl
               set
          authority = '$updated_authority'
               where
          id = $id";

    $updateStmt = $db_conn->prepare($modify_sql);
    $updateStmt->execute();
?>
