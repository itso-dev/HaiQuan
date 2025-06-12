<?php
include_once('../../head.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');
$reg_date = date("Y-m-d H:i:s");

$type = $_POST['type'];
$link = $_POST['link'];

// 현재 ad_link_tbl의 데이터 개수를 확인하는 SQL
$count_sql = "SELECT COUNT(*) FROM ad_link_tbl";
$count_stmt = $db_conn->prepare($count_sql);
$count_stmt->execute();
$count = $count_stmt->fetchColumn();

// ad_link_tbl의 데이터가 10개 미만일 때만 INSERT 실행
$insert_sql = "INSERT INTO ad_link_tbl (type, link, view, regdate) VALUES (?, ?, ?, ?)";
$db_conn->prepare($insert_sql)->execute(
    [
        $type,
        $link,
        "0",
        $reg_date
    ]
);

echo "<script type='text/javascript'>";
echo "alert('등록 되었습니다.'); location.href='../ad_list.php?menu=44'";
echo "</script>";


?>
