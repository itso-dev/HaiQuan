<?
include_once('../head.php');

$type = $_POST['type'];
$site_title = $_POST['site_title'];
$site_description = $_POST['site_description'];
$head_script = $_POST['head_script'];
$body_script = $_POST['body_script'];
$conversion_script = $_POST['conversion_script'];


$modify_sql = "update site_setting_tbl
    set 
    site_title = '$site_title',
    site_description = '$site_description',
    head_script = :head_script,
    body_script = :body_script,
    conversion_script = :conversion_script
    where
    id = $type";


$updateStmt = $db_conn->prepare($modify_sql);
$updateStmt->bindParam(':head_script', $head_script);
$updateStmt->bindParam(':body_script', $body_script);
$updateStmt->bindParam(':conversion_script', $conversion_script);
$updateStmt->execute();

$count = $updateStmt->rowCount();

echo "<script type='text/javascript'>";
echo "alert('등록 되었습니다.'); location.href='../config_form.php?menu=11'";
echo "</script>";

?>

