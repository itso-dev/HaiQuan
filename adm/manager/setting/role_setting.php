<?
include_once('../../head.php');

$posted = date("Y-m-d H:i:s");

$type = $_POST['type'];
$id = isset($_POST["id"]) ? $_POST["id"] : '';
$name = isset($_POST["name"]) ? $_POST["name"] : '';


//입력
if($type == 'insert'){
    $authority = [1, 1, 1, 1, 1, 1];
    $authority_json = json_encode($authority);

    $insert_sql = "insert into admin_role_tbl
                          (name, authority, reg_date)
                     value
                          (?, ?, ?)";

    echo $insert_sql;

    $db_conn->prepare($insert_sql)->execute(
        [$name, $authority_json ,$posted]);

    echo "<script type='text/javascript'>";
    echo "alert('등록 되었습니다.'); location.href='../access_list.php?menu=111&'";
    echo "</script>";
}


?>
