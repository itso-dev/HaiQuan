<?php
include_once('../../head.php');


$id = $_POST['del-id'];

$ad_sql1 = "select * from ad_link_tbl where type = ".$id;

echo $ad_sql1;

$ad_stt1=$db_conn->prepare($ad_sql1);
$ad_stt1->execute();

while ($ad1 = $ad_stt1->fetch()) {
    $modify_sql = "update ad_link_tbl
               set
          type = 1
               where
          id = ".$ad1['id'];

    $updateStmt = $db_conn->prepare($modify_sql);
    $updateStmt->execute();

    $count = $updateStmt->rowCount();
}



$delete_sql = "delete from ad_type_tbl
    where
        id = $id";

$deleteStmt = $db_conn->prepare($delete_sql);
$deleteStmt->execute();

$count = $deleteStmt->rowCount();




echo "<script type='text/javascript'>";
echo "alert('삭제 되었습니다.'); location.href='../category_list.php?menu=77&'";
echo "</script>";
?>
