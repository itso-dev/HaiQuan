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
    $mail->Username = "dev@itso.co.kr"; // 구글 이메일
    $mail->Password = "iuxc ivro vqqw ojby"; // 구글 앱 비밀번호
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


function mailForm($arr){
    $site_url = "https://" . $_SERVER["HTTP_HOST"] . "";
    $date =  date("Y년 m월 d일 H시 i분");
    $data = "";
    foreach($arr as $key => $value) {
        $data .= '
        <div style="display: flex; align-items: center; border-bottom: 1px solid rgba(0, 0, 0, 0.10);">
            <div style="width: 140px; padding: 8px 5px; background: #DFEBF0;color: #333;font-size: 18px;font-weight: 400;letter-spacing: -0.45px; text-align: center; background: #EDF0F2;">
                ' . $key . '
            </div>   
             <div style="width: calc(100% - 140px); padding: 8px 5px; background: #DFEBF0;color: #333;font-size: 18px;font-weight: 400;letter-spacing: -0.45px; text-align: center; background: #fff;">
                ' . $value . '
            </div>   
        </div>';
    }


    $message = '<html><body>
                        <div style="width: 570px; margin: 0 auto; border-radius: 4px; border: 2px solid #0CB2C9; background: #FFF; padding: 15px">
                             <a href="https://payhere.in/" target="_blank" style="width: 100%;">
                                <img src="' . $site_url . '/img/email-banner.png" style="width: 100%; margin-bottom: 24px;">
                            </a>
                            <div style="display: flex; gap:8px; align-items: center; margin-bottom: 8px;">
                                <div style="width: 125px; padding: 3px 5px; background: #DFEBF0;color: #11488C;font-size: 18px;font-weight: 600;letter-spacing: -0.45px; text-align: center">
                                    접수일시
                                </div>
                                 <div style="width:calc(100% - 125px); padding: 3px 5px;">
                                     ' . $date . '
                                </div>
                            </div>
                             <div style="display: flex; gap:8px; align-items: center; margin-bottom: 24px;">
                                <div style="width: 125px; padding: 3px 5px; background: #DFEBF0;color: #11488C;font-size: 18px;font-weight: 600;letter-spacing: -0.45px; text-align: center">
                                    접수된 웹사이트
                                </div>
                                 <div style="width:calc(100% - 125px); padding: 3px 5px;">
                                   ' . $site_url . '
                                </div>
                            </div>
                            <p style="color: #505050; font-size: 18px;letter-spacing: -0.45px;font-weight: 400; margin-bottom: 24px;">
                                안녕하세요. 잇소 담당자님.<br>
                                새로운 문의가 접수되어 아래와 같이 전달드립니다.
                            </p>
                            <div style="display: flex; align-items: center">
                                <div style="width: 140px; padding: 8px 5px; background: #DFEBF0;color: #333;font-size: 18px;font-weight: 600;letter-spacing: -0.45px; text-align: center; background: #A8D0DF;">
                                    DB항목
                                </div>   
                                 <div style="width: calc(100% - 140px); padding: 8px 5px; background: #DFEBF0;color: #333;font-size: 18px;font-weight: 600;letter-spacing: -0.45px; text-align: center; background: #B4DCEA;">
                                    고객정보
                                </div>   
                            </div>
                             ' . $data . '
                         </div>
                         
                 </body></html>';

    return $message;
}



?>
