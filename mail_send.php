<?php
include_once('./db/dbconfig.php');
include_once('./mail_lib.php');

//통합관리에 속하지 않을시 추가
//include_once('./db/manage_db_config.php');

//배너 출력하기 위한 sql문
$banner_sql = "select * from banner_tbl where type = 2 order by id desc limit 1 ";
//$banner_stt = $db_conn_admin->prepare($banner_sql);
$banner_stt = $db_conn->prepare($banner_sql);
$banner_stt->execute();
$banner = $banner_stt->fetch();

$link = trim((string)($banner['link'] ?? ''));
$imgSrc = 'https://itsoadmin.com/data/banner/' . ($banner['chg_name'] ?? '');

if ($link !== '' && !preg_match('~^https?://~i', $link)) {
    $link = 'https://' . $link;
}
$linkEsc = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
$imgEsc  = htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8');


$messageArr = [];
if (isset($_POST['message'])) {
    $messageArr = json_decode($_POST['message'], true);
}
$message = mailForm($messageArr, $linkEsc, $imgEsc);

$email_sql = "select * from email_tbl";
$email_stt=$db_conn->prepare($email_sql);
$email_stt->execute();

// 반복문으로 모든 이메일에 메일 발송
while ($row = $email_stt->fetch(PDO::FETCH_ASSOC)) {
    $to_email = $row['email'];
    mailer_google("잇소", "dev@itso.co.kr", $to_email, "잇소 랜딩 문의", $message, 1);
}
?>
