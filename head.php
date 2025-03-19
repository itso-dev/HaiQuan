<?php
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
session_start();

$site_path = $_SERVER["DOCUMENT_ROOT"]."/DB_Solution_new";
$site_url = "http://".$_SERVER["HTTP_HOST"]."/DB_Solution_new";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($site_path.'/db/dbconfig.php');


// 현재 URL 가져오기
$current_url = $_SERVER['REQUEST_URI'];

$site_info_sql = "";

try {
    if ($current_url === '/' || $current_url === '/index_bak.php') {
        // A사이트
        $site_info_sql = "SELECT * FROM site_setting_tbl WHERE id = 1";
    } elseif ($current_url === '/b/' || $current_url === '/B/') {
        // B사이트
        $site_info_sql = "SELECT * FROM site_setting_tbl WHERE id = 2";
    } else {
        $site_info_sql = "SELECT * FROM site_setting_tbl WHERE id = 1";
    }

    // SQL 실행
    $site_info_stt = $db_conn->prepare($site_info_sql);
    $site_info_stt->execute();
    $site = $site_info_stt->fetch();
} catch (Exception $e) {}


$menu = isset($_GET["menu"]) ? $_GET["menu"] : '';
$page = isset($_GET["page"]) ? $_GET["page"] : '';

$adCode = isset($_GET["adCode"]) ? $_GET["adCode"] : '';
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
    opcache_reset();
}


$view_cnt_sql = "SELECT * FROM view_log_tbl WHERE ip = '" . get_client_ip() . "' AND DATE(reg_date) = CURDATE()";
$view_cnt_stt = $db_conn->prepare($view_cnt_sql);
$view_cnt_stt->execute();
$view_chk = $view_cnt_stt->fetch();

if (!$view_chk) {
    $today = date("Y-m-d H:i:s");
    $view_sql = "insert into view_log_tbl
                              (view_cnt, ip, reg_date)
                         value
                              (? ,?, ?)";

    $db_conn->prepare($view_sql)->execute(
        [1, get_client_ip(), $today]
    );

    $update_view_sql = "UPDATE ad_link_tbl SET view = view + 1 WHERE link = '$adCode'";
    $update_view_stmt = $db_conn->prepare($update_view_sql);
    $update_view_stmt->execute();
}


$client_ip = get_client_ip();


$ip_sql = "SELECT * FROM ip_block_tbl WHERE ip = '$client_ip'";
$ip_stt = $db_conn->prepare($ip_sql);
$ip_stt->execute();
$ip_chk = $ip_stt -> fetch();


if ($ip_chk) {


    $modify_sql = "update ip_block_tbl
    set 
    view = view + 1
    where
    ip = '$client_ip'";

    $updateStmt = $db_conn->prepare($modify_sql);
    $updateStmt->execute();

    // 구글로 강제 이동
    header("Location: https://www.google.com");
    exit;
}

$flow = "";
if(isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
    if (strpos($referer, 'search.naver.com') !== false) {
        $flow = "네이버 검색 결과";
    } elseif (strpos($referer, 'brandsearch.naver.com') !== false) {
        $flow = "네이버 브랜드검색광고";
    } elseif (strpos($referer, 'm.place.naver.com') !== false || strpos($referer, 'place.naver.com') !== false) {
        $flow = "네이버 플레이스";
    } elseif (strpos($referer, 'blog.naver.com') !== false || strpos($referer, 'm.blog.naver.com') !== false) {
        $flow = "네이버 블로그";
    } elseif (strpos($referer, 'searchad.naver.com') !== false) {
        $flow = "네이버 파워링크 광고";
    } elseif (strpos($referer, 'facebook.com') !== false || strpos($referer, 'lm.facebook.com') !== false) {
        $flow = "메타 (페이스북)";
    } elseif (strpos($referer, 'instagram.com') !== false || strpos($referer, 'l.instagram.com') !== false) {
        $flow = "메타 (인스타그램)";
    } elseif (strpos($referer, 'kakao.com') !== false || strpos($referer, 'pf.kakao.com') !== false) {
        $flow = "카카오톡";
    } elseif (strpos($referer, 'google.com') !== false) {
        $flow = "구글 검색";
    } else {
        $flow = "기타 (" . $referer . ")";
    }
} else {
    $flow = "직접유입";
}
echo "<script>console.log('유입 경로: " . addslashes($flow) . "');</script>";

?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <!-- <meta property="og:image" content="/img/ogthumbnail.png"> -->
    <meta name="title" content="<?= $site['site_title'] ?>">
    <meta name="description" content="<?= $site['site_description'] ?>">

    <link rel="shortcut icon" href="<?= $site_url ?>/favicon.ico">

    <meta property="og:title" content="<?= $og_title ?? $site[1] ?>" />
    <meta property="og:description" content="<?= $og_description ?? $site[2] ?>" />
    <meta property="og:url" content="<?= $og_url ?? $site_url ?>" />
    <meta property="og:image" content="<?= $og_image ?? $site_url . '/img/og-800x400.png' ?>" />

    <link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/reset.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/common.css" rel="stylesheet" />

    <script type="text/javascript" src="<?= $site_url ?>/js/jquery-1.12.4.min.js"></script>

    <script src='https://www.google.com/recaptcha/api.js'></script>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="https://developers.kakao.com/sdk/js/kakao.js"></script>

    <!-- recapture -->
    <script src='https://www.google.com/recaptcha/api.js?render=6LcDiNQqAAAAAMogtpIaf56t7eREYodW1cX7huPJ'></script>
</head>
<body>
<!-- 상단 레이아웃 -->
<header id="header"></header>
