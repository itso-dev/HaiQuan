<?php
include_once('./db/dbconfig.php');
include_once('./mail_lib.php');

session_start();

$check = isset($_GET["c"]) ? $_GET["c"]: true ;
$ab_type = isset($_GET["type"]) ? $_GET["type"] : '';
$client_key = 'itso';

$type = '';
$redirect = "";

if($ab_type === 'A'){
    $type = 1;
    $redirect = './index.php';
} elseif ($ab_type === 'B') {
    $type = 2;
    $redirect = './B/index.php';
} else {
    $type = 1;
    $redirect = './index.php';
}
$message = isset($_SESSION['messageArr']) ? $_SESSION["messageArr"] : '';

//if($check || $type == '' || $message == ''){
//    header("Location: http://".$_SERVER["HTTP_HOST"]);
//}

//사이트 정보 쿼리
$site_info_sql = "select * from site_setting_tbl where client_key = '$client_key'";
$site_info_stt=$db_conn->prepare($site_info_sql);
$site_info_stt->execute();
$site = $site_info_stt -> fetch();

?>

<head>
    <?= $site['conversion_script'] ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title><?=$site[1]?></title>

    <meta name="description" content="<?=$site[2]?>" />


    <link rel="shortcut icon" href="./img/favicon.png">

    <link rel="stylesheet" type="text/css" href="./css/reset.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="./css/thank.css" rel="stylesheet" />

</head>

<body>
<?= $site['body_script'] ?>

<div id="thank-page">
    <div class="thank-div">
        <div class="thank-top">
            <img src="img/logo.png" class="logo">
            <p>
                문의가 등록되었습니다.<br>
                담당자 확인 후 곧 연락드리겠습니다.
            </p>
        </div>
        <a href="<?= $redirect ?>" class="go-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                <path d="M10.4062 7.06944C10.2969 7.08897 10.1992 7.14366 10.125 7.22569L4.67188 12.6632L4.32812 13.0226L4.67188 13.3819L10.125 18.8194C10.2461 18.9679 10.4395 19.0362 10.627 18.9933C10.8125 18.9503 10.959 18.8038 11.002 18.6183C11.0449 18.4308 10.9766 18.2374 10.8281 18.1163L6.23438 13.5226H21C21.1797 13.5245 21.3477 13.4308 21.4395 13.2745C21.5293 13.1183 21.5293 12.9269 21.4395 12.7706C21.3477 12.6144 21.1797 12.5206 21 12.5226H6.23438L10.8281 7.92882C10.9902 7.77843 11.0332 7.54015 10.9355 7.34288C10.8398 7.14366 10.623 7.03429 10.4062 7.06944Z" fill="black"/>
            </svg>
            <span>이전 페이지로</span>
        </a>
    </div>
</div>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var message = <?= json_encode($message ?? '') ?>;
        fetch("/mail_send.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "message=" + encodeURIComponent(JSON.stringify(message))
        })
            .then(response => response.text())
            .then(data => console.log("메일 전송 완료:", data))
            .catch(error => console.error("메일 전송 오류:", error));
    });
</script>
