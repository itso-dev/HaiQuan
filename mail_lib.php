<?


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once(dirname(__FILE__)."/PHPMailer-master/src/Exception.php");
require_once(dirname(__FILE__)."/PHPMailer-master/src/PHPMailer.php");
require_once(dirname(__FILE__)."/PHPMailer-master/src/SMTP.php");

// 네이버 SMTP 이용한 메일발송 함수 //
function mailer_naver($fname, $fmail, $tomail, $subject, $content, $file="", $cc="", $bcc="")
{
    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;	// 1 = errors and messages, 2 = messages only, 0 디버깅 안함
    $mail->SMTPAuth = true;	// SMTP 인증 사용함
    $mail->SMTPSecure = "ssl";	// SSL을 사용함
    $mail->Host = "smtp.naver.com";	// 보낼때 사용할 서버를 지정
    $mail->Port = "465";	// 보낼때 사용할 포트지정
    $mail->IsHTML(true);	// HTML 사용
    $mail->Username = "네이버 아이디";
    $mail->Password = "네이버 비번";
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->From = $fmail;	// 보내는 네이버메일주소
    $mail->FromName = $fname;	// 보내는이
    $mail->Subject = $subject;	// 메일 제목
    $mail->AltBody = "";	// optional, comment out and test
    $mail->msgHTML($content);	// 메일 내용
    $mail->addAddress($tomail);	// 받는사람 메일, 받는이
    if($cc) $mail->addCC($cc);
    if($bcc) $mail->addBCC($bcc);

    return $mail->send();
}

// 네이버 웍스 SMTP 이용한 메일발송 함수 //
function mailer_naverworks($fname, $fmail, $tomail, $subject, $content, $file = "", $cc = "", $bcc = "")
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Host = "smtp.worksmobile.com"; // 네이버 웍스 SMTP 서버
    $mail->Port = "587";
    $mail->isHTML(true);
    $mail->Username = "네이버 웍스 아이디"; // 네이버 웍스 이메일 주소
    $mail->Password = "앱 비밀번호"; // 앱 비밀번호(비밀번호 생성)
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->From = $fmail;
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = "";
    $mail->msgHTML($content);
    $mail->addAddress($tomail);

    if ($cc) $mail->addCC($cc);
    if ($bcc) $mail->addBCC($bcc);

    if (!empty($file) && file_exists($file)) {
        $mail->addAttachment($file);
    }

    return $mail->send();
}

// 구글 SMTP 이용한 메일발송 함수 //
function mailer_google($fname, $fmail, $tomail, $subject, $content, $file="", $cc="", $bcc="")
{
    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;	// 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;	// SMTP 인증 사용함
    // $mail->SMTPSecure = "ssl";	// SSL을 사용함
    $mail->SMTPSecure = "tls";	// TLS 사용함
    $mail->Host = "smtp.gmail.com";	// 보낼때 사용할 서버를 지정
    $mail->Port = "587";	// 보낼때 사용할 포트지정
    $mail->IsHTML(true);	// HTML 사용
    $mail->Username = "jh.oh@itso.co.kr"; // 구글 이메일
    $mail->Password = "cyzl qadc ndpv cvem"; // 구글 앱 비밀번호
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->charSet = PHPMailer::CHARSET_UTF8;
    $mail->From = $fmail;
    $mail->FromName = $fname;	// 보내는이
    $mail->Subject = $subject;	// 메일 제목
    $mail->AltBody = "";	// optional, comment out and test
    $mail->msgHTML($content);	// 메일 내용
    $mail->addAddress($tomail);	// 받는사람 메일, 받는이
    if($cc) $mail->addCC($cc);
    if($bcc) $mail->addBCC($bcc);

    return $mail->send();
}




?>
