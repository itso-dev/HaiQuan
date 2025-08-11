<?php
include_once('./db/dbconfig.php');

session_start();
//    error_reporting(E_ALL);
//    ini_set('display_errors', '1');
error_reporting(0);
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Seoul');


// recapcha
$secret = '';
$response = isset($_POST["g-recaptcha-response"]) ? $_POST["g-recaptcha-response"] : '';

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
$captcha_success = json_decode($verify);

if (!$captcha_success->success || $captcha_success->score < 0.5) {
    die("스팸봇으로 의심되어 제출이 거부되었습니다.");
}

//CSRF 처리
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    exit('잘못된 접근입니다 (CSRF 오류)');
}
unset($_SESSION['csrf_token']);


$posted = date("Y-m-d H:i:s");
$flow = $_POST["flow"];
$adCode = isset($_POST["adCode"]) ? $_POST["adCode"] : '';
$name = $_POST["name"];
$phone = $_POST["phone"];
$location = $_POST["location"];
$sort = isset($_POST["sort"]) ? $_POST["sort"] : '';
$desc = isset($_POST["contact_desc"]) ? $_POST["contact_desc"] : '';

$type = isset($_POST["abtype"]) ? 'B' : 'A';


$writer_ip = $_POST["writer_ip"];

$sql="
        insert into contact_tbl
            (flow, ab_test, ad_code, name, phone, location,
            sort, contact_desc, result_status,
            consult_fk, writer_ip, write_date)
        value
            (?, ?, ?, ?, ?, ?, 
            ?, ?, ?, 
            ?, ?, ?)";

$db_conn->prepare($sql)->execute(
    [$flow, $type, $adCode, $name, $phone, $location,
        $sort, $desc, '대기',
        0, $writer_ip, $posted]);


$contact_cnt_sql = "insert into contact_log_tbl
                                  (contact_cnt,  reg_date)
                             value
                                  (? ,?)";


$db_conn->prepare($contact_cnt_sql)->execute(
    [1, $posted]);

$update_ab_sql = "UPDATE ab_test_tbl SET contact = contact + 1 WHERE id = 1";
$update_ab_stmt = $db_conn->prepare($update_ab_sql);
$update_ab_stmt->execute();


//문의 필드에 맞게 수정
$message = [
    "유입경로" => $flow,
    "성함" => $name,
    "연락처" => $phone,
    "창업희망지역" => $location,
    "문의내용" => $desc
];

$_SESSION['messageArr'] = $message;




echo "<script type='text/javascript'>";
echo "location.href = 'thankyou.php?c=0&type=".$type."';";
echo "</script>";

?>
