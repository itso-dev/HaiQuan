<?php
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
session_start();

$site_path = $_SERVER["DOCUMENT_ROOT"]."/HaiQuan";
$site_url = "http://".$_SERVER["HTTP_HOST"]."/HaiQuan";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($site_path.'/db/dbconfig.php');

// CSRF 토큰 생성
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

//스팸봇 차단
$bad_agents = ['MJ12bot', 'AhrefsBot', 'SemrushBot', 'DotBot'];
foreach ($bad_agents as $bot) {
    if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
}


// 현재 URL 가져오기
$current_url = strtolower($_SERVER['REQUEST_URI']);

$site_info_sql = "";
$ab_type = "";
$ab_id = "";
$client_key = "itso";

try {
    if ($current_url === '/' || $current_url === '/index_bak.php') {
        // A 사이트
        $site_info_sql = "SELECT * FROM site_setting_tbl WHERE client_key = '$client_key'";
        $ab_type = 'A';
        $ab_id = 1;
    } elseif (strpos($current_url, '/b/') === 0) {
        // B 사이트
        $site_info_sql = "SELECT * FROM site_setting_tbl WHERE client_key = '$client_key'";
        $ab_type = 'B';
        $ab_id = 2;
    } else {
        $site_info_sql = "SELECT * FROM site_setting_tbl WHERE client_key = '$client_key'";
        $ab_type = 'A';
        $ab_id = 1;
    }

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

$client_ip = get_client_ip();

if (!$view_chk) {
    $today = date("Y-m-d H:i:s");
    $view_sql = "insert into view_log_tbl
                              (view_cnt, ip, client_key, ab_test, reg_date)
                         value
                              (? ,?, ?, ?, ?)";

    $db_conn->prepare($view_sql)->execute(
        [1, $client_ip, $client_key, $ab_type, $today]
    );

    $update_view_sql = "UPDATE ad_link_tbl SET view = view + 1 WHERE link = '$adCode'";
    $update_view_stmt = $db_conn->prepare($update_view_sql);
    $update_view_stmt->execute();

    $update_ab_sql = "UPDATE ab_test_tbl SET view = view + 1 WHERE id = $ab_id";
    $update_ab_stmt = $db_conn->prepare($update_ab_sql);
    $update_ab_stmt->execute();
}


$ip_sql = "SELECT * FROM ip_block_tbl WHERE client_key = '$client_key' and ip = '$client_ip'";
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
    } elseif (strpos($referer, 'cafe.naver.com') !== false) {
        $flow = "네이버 카페";
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
    <?= $site['head_script'] ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title><?= $site['site_title'] ?></title>
    <meta name="title" content="<?= $site['site_title'] ?>">
    <meta name="description" content="<?= $site['site_description'] ?>">

    <link rel="shortcut icon" href="<?= $site_url ?>/img/favicon.png">

    <meta property="og:title" content="<?= $og_title ?? $site['site_title'] ?>" />
    <meta property="og:description" content="<?= $og_description ?? $site['site_description'] ?>" />
    <meta property="og:url" content="<?= $og_url ?? $site_url ?>" />
    <meta property="og:image" content="<?= $og_image ?? $site_url . '/img/og.png' ?>" />

    <link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/reset.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/common.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script type="text/javascript" src="<?= $site_url ?>/js/jquery-1.12.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src='https://www.google.com/recaptcha/api.js'></script>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="https://developers.kakao.com/sdk/js/kakao.js"></script>

</head>

<body>
    <?= $site['body_script'] ?>

    <div id="header">
        <img src="img/head-logo.png" class="logo">
        <nav>
            <ul>
                <li class="link" data-target="section2">브랜드</li>
                <li class="link" data-target="section4">매출증명</li>
                <li class="link" data-target="section10">창업경쟁력</li>
                <li class="link" data-target="contact">메뉴소개</li>
                <li class="link" data-target="contact">인테리어</li>
                <li class="link" data-target="contact">창업비용</li>
                <li class="link" data-target="contact">창업문의</li>
            </ul>
        </nav>
        <a href="tel:00000000" class="call">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
            <path d="M13.7738 2.99999C13.6634 2.99843 13.5538 3.01883 13.4513 3.06001C13.3488 3.10119 13.2556 3.16232 13.1769 3.23986C13.0983 3.3174 13.0358 3.40979 12.9932 3.51167C12.9506 3.61355 12.9286 3.72289 12.9286 3.83333C12.9286 3.94376 12.9506 4.0531 12.9932 4.15498C13.0358 4.25686 13.0983 4.34925 13.1769 4.42679C13.2556 4.50433 13.3488 4.56546 13.4513 4.60664C13.5538 4.64782 13.6634 4.66822 13.7738 4.66666C17.926 4.66666 21.2738 8.0145 21.2738 12.1667C21.2723 12.2771 21.2927 12.3867 21.3339 12.4892C21.375 12.5917 21.4362 12.6849 21.5137 12.7636C21.5912 12.8422 21.6836 12.9047 21.7855 12.9473C21.8874 12.9899 21.9967 13.0119 22.1072 13.0119C22.2176 13.0119 22.3269 12.9899 22.4288 12.9473C22.5307 12.9047 22.6231 12.8422 22.7006 12.7636C22.7782 12.6849 22.8393 12.5917 22.8805 12.4892C22.9217 12.3867 22.9421 12.2771 22.9405 12.1667C22.9405 7.11382 18.8267 2.99999 13.7738 2.99999ZM6.88907 4.66991C6.78455 4.66125 6.67815 4.66991 6.57169 4.69596C6.15835 4.79679 5.62214 5.04166 5.05964 5.60416C3.29881 7.36499 2.36459 10.3334 8.98542 16.9551C15.6063 23.5767 18.5747 22.6425 20.3363 20.8808C20.9005 20.3167 21.1453 19.7797 21.2462 19.3656C21.3487 18.9414 21.1655 18.5048 20.7888 18.2848C19.8488 17.7348 17.8201 16.5456 16.8793 15.9948C16.5701 15.8139 16.1907 15.8172 15.8832 15.9997L14.3272 16.9274C13.9789 17.1349 13.5437 17.1115 13.2237 16.8623C12.6712 16.4306 11.7827 15.7037 11.0085 14.9303C10.2344 14.1562 9.5074 13.2677 9.07657 12.7152C8.8274 12.396 8.80313 11.96 9.01147 11.6116L9.9392 10.0557C10.1225 9.74816 10.1233 9.36547 9.94246 9.05631L7.6573 5.15494C7.49105 4.87244 7.20262 4.69589 6.88907 4.66991ZM13.7738 6.33333C13.6634 6.33176 13.5538 6.35216 13.4513 6.39334C13.3488 6.43452 13.2556 6.49565 13.1769 6.57319C13.0983 6.65073 13.0358 6.74312 12.9932 6.845C12.9506 6.94688 12.9286 7.05622 12.9286 7.16666C12.9286 7.2771 12.9506 7.38643 12.9932 7.48831C13.0358 7.59019 13.0983 7.68259 13.1769 7.76013C13.2556 7.83766 13.3488 7.8988 13.4513 7.93997C13.5538 7.98115 13.6634 8.00155 13.7738 7.99999C16.0852 7.99999 17.9405 9.85527 17.9405 12.1667C17.9389 12.2771 17.9593 12.3867 18.0005 12.4892C18.0417 12.5917 18.1028 12.6849 18.1804 12.7636C18.2579 12.8422 18.3503 12.9047 18.4522 12.9473C18.5541 12.9899 18.6634 13.0119 18.7738 13.0119C18.8843 13.0119 18.9936 12.9899 19.0955 12.9473C19.1974 12.9047 19.2898 12.8422 19.3673 12.7636C19.4448 12.6849 19.506 12.5917 19.5472 12.4892C19.5883 12.3867 19.6087 12.2771 19.6072 12.1667C19.6072 8.95471 16.9858 6.33333 13.7738 6.33333ZM13.7738 9.66666C13.6634 9.6651 13.5538 9.6855 13.4513 9.72668C13.3488 9.76785 13.2556 9.82899 13.1769 9.90653C13.0983 9.98406 13.0358 10.0765 12.9932 10.1783C12.9506 10.2802 12.9286 10.3896 12.9286 10.5C12.9286 10.6104 12.9506 10.7198 12.9932 10.8216C13.0358 10.9235 13.0983 11.0159 13.1769 11.0935C13.2556 11.171 13.3488 11.2321 13.4513 11.2733C13.5538 11.3145 13.6634 11.3349 13.7738 11.3333C14.2447 11.3333 14.6072 11.6958 14.6072 12.1667C14.6056 12.2771 14.626 12.3867 14.6672 12.4892C14.7084 12.5917 14.7695 12.6849 14.847 12.7636C14.9246 12.8422 15.017 12.9047 15.1188 12.9473C15.2207 12.9899 15.3301 13.0119 15.4405 13.0119C15.5509 13.0119 15.6603 12.9899 15.7622 12.9473C15.864 12.9047 15.9564 12.8422 16.034 12.7636C16.1115 12.6849 16.1726 12.5917 16.2138 12.4892C16.255 12.3867 16.2754 12.2771 16.2738 12.1667C16.2738 10.7958 15.1446 9.66666 13.7738 9.66666Z" fill="#FFF100"/>
            </svg>
        </a>
        <div class="mo-menu-open">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M2.39994 3.84C2.27273 3.8382 2.14643 3.8617 2.02838 3.90914C1.91033 3.95658 1.80289 4.027 1.71229 4.11633C1.6217 4.20565 1.54976 4.31209 1.50066 4.42945C1.45156 4.54682 1.42627 4.67278 1.42627 4.8C1.42627 4.92722 1.45156 5.05318 1.50066 5.17055C1.54976 5.28791 1.6217 5.39435 1.71229 5.48367C1.80289 5.573 1.91033 5.64342 2.02838 5.69086C2.14643 5.7383 2.27273 5.7618 2.39994 5.76H21.5999C21.7272 5.7618 21.8535 5.7383 21.9715 5.69086C22.0896 5.64342 22.197 5.573 22.2876 5.48367C22.3782 5.39435 22.4501 5.28791 22.4992 5.17055C22.5483 5.05318 22.5736 4.92722 22.5736 4.8C22.5736 4.67278 22.5483 4.54682 22.4992 4.42945C22.4501 4.31209 22.3782 4.20565 22.2876 4.11633C22.197 4.027 22.0896 3.95658 21.9715 3.90914C21.8535 3.8617 21.7272 3.8382 21.5999 3.84H2.39994ZM2.39994 11.04C2.27273 11.0382 2.14643 11.0617 2.02838 11.1091C1.91033 11.1566 1.80289 11.227 1.71229 11.3163C1.6217 11.4056 1.54976 11.5121 1.50066 11.6295C1.45156 11.7468 1.42627 11.8728 1.42627 12C1.42627 12.1272 1.45156 12.2532 1.50066 12.3705C1.54976 12.4879 1.6217 12.5944 1.71229 12.6837C1.80289 12.773 1.91033 12.8434 2.02838 12.8909C2.14643 12.9383 2.27273 12.9618 2.39994 12.96H21.5999C21.7272 12.9618 21.8535 12.9383 21.9715 12.8909C22.0896 12.8434 22.197 12.773 22.2876 12.6837C22.3782 12.5944 22.4501 12.4879 22.4992 12.3705C22.5483 12.2532 22.5736 12.1272 22.5736 12C22.5736 11.8728 22.5483 11.7468 22.4992 11.6295C22.4501 11.5121 22.3782 11.4056 22.2876 11.3163C22.197 11.227 22.0896 11.1566 21.9715 11.1091C21.8535 11.0617 21.7272 11.0382 21.5999 11.04H2.39994ZM2.39994 18.24C2.27273 18.2382 2.14643 18.2617 2.02838 18.3091C1.91033 18.3566 1.80289 18.427 1.71229 18.5163C1.6217 18.6056 1.54976 18.7121 1.50066 18.8295C1.45156 18.9468 1.42627 19.0728 1.42627 19.2C1.42627 19.3272 1.45156 19.4532 1.50066 19.5705C1.54976 19.6879 1.6217 19.7944 1.71229 19.8837C1.80289 19.973 1.91033 20.0434 2.02838 20.0909C2.14643 20.1383 2.27273 20.1618 2.39994 20.16H21.5999C21.7272 20.1618 21.8535 20.1383 21.9715 20.0909C22.0896 20.0434 22.197 19.973 22.2876 19.8837C22.3782 19.7944 22.4501 19.6879 22.4992 19.5705C22.5483 19.4532 22.5736 19.3272 22.5736 19.2C22.5736 19.0728 22.5483 18.9468 22.4992 18.8295C22.4501 18.7121 22.3782 18.6056 22.2876 18.5163C22.197 18.427 22.0896 18.3566 21.9715 18.3091C21.8535 18.2617 21.7272 18.2382 21.5999 18.24H2.39994Z" fill="#FFF100"/>
            </svg>
        </div>
        <div class="mo-menu-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                <path d="M21 7L7 21M7 7L21 21" stroke="#FFF100" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <div class="mo-menu">
        <div class="mo-menu-bottom">
            <nav>
                <ul>
                    <li class="link" data-target="section2">브랜드</li>
                    <li class="link" data-target="section4">차별점</li>
                    <li class="link" data-target="section10">메뉴</li>
                    <li class="link" data-target="section10">후기</li>
                    <li class="link" data-target="section10">창업비용</li>
                    <li class="link" data-target="contact">창업문의</li>
                </ul>
            </nav>
            <a href="tel:00000000" class="menu-call">
                <a class="call-btn" href="tel:00000000">0000.0000</a>
            </a>
        </div>
    </div>

    <div id="wrapper">
        <div id="container">

<script>

    $(document).ready(function () {

        // checkActivemenu();

        $(window).on('scroll', function() {
            // checkActivemenu();
        });

        function checkActivemenu() {
            var scrollPosition = $(window).scrollTop();

            menuOffsets = {
                'section2': $('#section2').offset().top - 100,
                'section4': $('#section4').offset().top - 100,
                'section10': $('#section10').offset().top - 100,
                'contact': $('#contact').offset().top - 100,
            };

            $.each(menuOffsets, function(menu, offset) {
                if (scrollPosition >= offset && scrollPosition < offset + $('#' + menu).outerHeight()) {
                    $('nav ul li').removeClass('tap');
                    $('nav ul li[data-target="' + menu + '"]').addClass('tap');
                }
            });
        }

        $('nav ul li').on('click', function(){
            var target = $(this).data('target');

            history.pushState(null, null, `#${target}`);

            $('html, body').animate({
                scrollTop: $('#' + target).offset().top
            }, 500);

            // 클릭한 생생 고객메뉴안내 항목에 'tap' 클래스 추가
            $('.link').removeClass('tap');
            $(this).addClass('tap');

            $(".mo-menu").fadeOut(200, function () {
                $("html").css("overflow", "auto");
            });
        });

        $('.logo').on('click', function() {
            $('html, body').animate({
                scrollTop: 0 }, 500);
        });

    });

    $(".mo-menu-open").click(function () {
        $(this).hide();
        $(".mo-menu-close").show();
        $(".mo-menu").fadeIn(200);
        $("html").css("overflow", "hidden");
    });

    $(".mo-menu-close").click(function () {
        $(this).hide();
        $(".mo-menu-open").show();
        $(".mo-menu").fadeOut(200, function () {
            $("html").css("overflow", "auto");
        });
    });

    // window.onscroll = function() {

    //     if (window.innerWidth <= 1000) {
    //         return;
    //     }

    //     var header = document.getElementById("header");

    //     if (window.scrollY > 0) {
    //         header.classList.add("gnb-blur");
    //     } else {
    //         header.classList.remove("gnb-blur");
    //     }
    // };

</script>
