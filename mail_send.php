<?php
include_once('./db/dbconfig.php');
include_once('./mail_lib.php');
$message = $_POST['message'];


 $email_sql = "select * from email_tbl where id = 1";
 $email_stt=$db_conn->prepare($email_sql);
 $email_stt->execute();
 $row = $email_stt -> fetch();

 $email = $row['email'];

while ($email_data = $email_stt->fetch()) {
 mailer_google("ITSO", "jh.oh@itso.co.kr", "jh.oh@itso.co.kr", "잇소 랜딩 문의", $message, 1);
}
?>
