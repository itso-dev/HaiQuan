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


$posted = date("Y-m-d H:i:s");
$flow = $_POST["flow"];
$adCode = isset($_POST["adCode"]) ? $_POST["adCode"] : '';
$name = $_POST["name"];
$phone = $_POST["phone"];
$location = $_POST["location"];
$store = isset($_POST["sort"]) ? $_POST["sort"] : '';
$desc = isset($_POST["contact_desc"]) ? $_POST["contact_desc"] : '';

$type = isset($_POST["abtype"]) ? 'B' : 'A';


$writer_ip = $_POST["writer_ip"];

$sql="
        insert into contact_tbl
            (flow, ad_code, name, phone, location,
            sort, contact_desc, result_status,
            consult_fk, writer_ip, write_date)
        value
            (?, ?, ?, ?, ?, 
            ?, ?, ?, 
            ?, ?, ?)";

$db_conn->prepare($sql)->execute(
    [$flow, $adCode, $name, $phone, $email, $location,
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


$message = '<html><body>
                        <div style="width: 500px; margin: 0 auto; text-align: left;">
                             <p style="font-size: 25px; color:#06acc4; font-weight: 600; margin-bottom: 30px; text-align: center;">회사명 랜딩 문의</p>
                             <p style="font-size: 20px; margin-bottom: 30px;"><strong style="color:#06acc4;">신규 문의</strong> 안내입니다.</p>
                             <p style="font-size: 16px; margin-bottom: 40px;">
                                 <strong style="margin-right: 30px">유입경로: </strong> '.$flow.'<br>
                                 <strong style="margin-right: 30px">성함: </strong> '.$name.'<br>
                                 <strong style="margin-right: 30px">연락처:</strong> '.$phone.'<br>
                                 <strong style="margin-right: 30px">이메일:</strong> '.$email.'<br>
                                 <strong style="margin-right: 30px">창업희망지역:</strong> '.$location.'<br>
                                 <strong style="margin-right: 30px">창업형태:</strong> '.$sort.'<br>
                                 <strong style="margin-right: 30px">문의 내용:</strong><br>
                                 '.$desc.'
                             </p>
                         </div>
                 </body></html>';

$_SESSION['message'] = $message;



echo "<script type='text/javascript'>";
echo "location.href = 'thankyou.php?c=0&type=".$type."';";
echo "</script>";

?>
