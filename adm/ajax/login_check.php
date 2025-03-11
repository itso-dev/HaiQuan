<?php
include_once('../head.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$login_id = $_POST["login_id"];
$password = $_POST["password"];

// 데이터베이스 쿼리
$login_sql = "SELECT * FROM admin_tbl WHERE login_id = :login_id";
$login_stt = $db_conn->prepare($login_sql);
$login_stt->bindParam(':login_id', $login_id);
$login_stt->execute();

// 플래그 변수 추가
$is_user_found = false;

while ($list_row = $login_stt->fetch()) {
    $is_user_found = true; // 사용자가 존재함
    if (password_verify($password, $list_row['password'])) {
        // 로그인 성공
        $_SESSION['manager_id'] = $list_row['id'];
        $_SESSION['manager_name'] = $list_row['login_name'];
        ?>
        <meta http-equiv="refresh" content="0;url=../index.php" />
        <?php
        exit; // 실행 종료
    } else {
        // 비밀번호 불일치
        echo "<script type='text/javascript'>";
        echo "alert('비밀번호가 틀립니다.'); location.href='../bbs/login.php'";
        echo "</script>";
        exit; // 실행 종료
    }
}

// 사용자를 찾지 못했을 경우 처리
if (!$is_user_found) {
    echo "<script type='text/javascript'>";
    echo "alert('가입된 회원 아이디가 아닙니다.'); location.href='../bbs/login.php'";
    echo "</script>";
}
?>
