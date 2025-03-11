<?
include_once('../head.php');

$posted = date("Y-m-d H:i:s");

$id = $_POST['id'];
$type = $_POST['type'];
$role = $_POST['role'];
$login_id = $_POST['login_id'];
$password = $_POST['password'];
$phone = $_POST['phone'];
$login_name = $_POST['login_name'];

//비밀번호 암호화
$encrypted_password = password_hash($password , PASSWORD_DEFAULT );


$login_sql = "select * from admin_tbl WHERE login_id = '$login_id'";
$login_stt=$db_conn->prepare($login_sql);
$login_stt->execute();
$chk = $login_stt -> fetch();

//입력
if($type == 'insert'){

    if($chk){
        echo "<script type='text/javascript'>";
        echo "alert('중복된 아이디 입니다. 다른 아이디를 이용해주세요.'); location.href='../manager_form.php?menu=4&type=insert'";
        echo "</script>";
    }
    else{
        $insert_sql = "insert into admin_tbl
                              (login_id, password, phone, role,
                              login_name, consult_cnt, success_cnt, reg_date)
                         value
                              (?, ?, ?, ?,
                              ?, ?, ?, ?)";


        $db_conn->prepare($insert_sql)->execute(
            [$login_id, $encrypted_password, $phone, $role,
                $login_name, 0, 0, $posted]);

        echo "<script type='text/javascript'>";
        echo "alert('등록 되었습니다.'); location.href='../manager_list.php?menu=4&'";
        echo "</script>";
    }
}

//수정
if($type == 'modify'){

    $modify_sql = "update admin_tbl
               set 
          login_id = '$login_id',
          password = '$encrypted_password',
          role = '$role',
          phone = '$phone',
          login_name = '$login_name'
               where
          id = $id";

    $updateStmt = $db_conn->prepare($modify_sql);
    $updateStmt->execute();

    $count = $updateStmt->rowCount();


    echo "<script type='text/javascript'>";
    echo "alert('수정을 완료했습니다.'); location.href='../manager_form.php?menu=4&type=modify&id=$id'";
    echo "</script>";

}


?>
