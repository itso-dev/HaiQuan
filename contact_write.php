<?php
include_once('./db/dbconfig.php');

session_start();
//    error_reporting(E_ALL);
//    ini_set('display_errors', '1');
error_reporting(0);
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Seoul');


$posted = date("Y-m-d H:i:s");
$flow = $_POST["flow"];
$sort = $_POST["sort"];
$adCode = isset($_POST["adCode"]) ? $_POST["adCode"] : '';
$b_name = $_POST["brand_name"];
$phone = $_POST["phone"];
$m_name = $_POST["manager_name"];
$email = $_POST["email"];
$desc = $_POST["contact_desc"];

$type = isset($_POST["abtype"]) ? '2' : '1';


$writer_ip = $_POST["writer_ip"];

$sql="
        insert into contact_tbl
            (flow, sort, ad_code, name, phone, manager_name, email, 
            contact_desc, result_status,
            consult_fk, writer_ip, write_date)
        value
            (?, ?, ?, ?, ?, ?, ?, 
            ?, ?, 
            ?, ?, ?)";

$db_conn->prepare($sql)->execute(
    [$flow, $sort, $adCode, $b_name, $phone, $m_name, $email,
        $desc, '대기',
        0, $writer_ip, $posted]);


$contact_cnt_sql = "insert into contact_log_tbl
                                  (contact_cnt,  reg_date)
                             value
                                  (? ,?)";


$db_conn->prepare($contact_cnt_sql)->execute(
    [1, $posted]);



$message = '<html><body>
                        <div style="width: 500px; margin: 0 auto; text-align: left;">
                             <p style="font-size: 25px; color:#06acc4; font-weight: 600; margin-bottom: 30px; text-align: center;">잇소 랜딩 문의</p>
                             <p style="font-size: 20px; margin-bottom: 30px;"><strong style="color:#06acc4;">신규 문의</strong> 안내입니다.</p>
                             <p style="font-size: 16px; margin-bottom: 40px;">
                                 <strong style="margin-right: 30px">유입경로: </strong> '.$flow.'<br>
                                 <strong style="margin-right: 30px">회사명: </strong> '.$b_name.'<br>
                                 <strong style="margin-right: 30px">연락처:</strong> '.$phone.'<br>
                                 <strong style="margin-right: 30px">담당자명:</strong> '.$m_name.'<br>
                                 <strong style="margin-right: 30px">이메일:</strong> '.$email.'<br>
                                 <strong style="margin-right: 30px">문의 내용:</strong><br>
                                 '.$desc.'
                             </p>
                         </div>
                 </body></html>';

$_SESSION['message'] = $message;



echo "<script type='text/javascript'>";
echo "alert('문의가 등록되었습니다.');";
echo "location.href = 'thankyou.php?c=0&type=".$type."';";
echo "</script>";

?>
