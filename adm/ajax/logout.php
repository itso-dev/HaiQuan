<?php
    session_start();
    include_once('../head.php');

    $adm_login = isset($_GET["is_login"]) ? $_GET["is_login"] : '';


    if ( $adm_login ) {
        session_unset();
        session_destroy();
        echo "<script>alert('세션이 만료되어 자동 로그아웃 되었습니다. 다시 로그인해주세요.'); location.href='" . $site_url . "/bbs/login.php';</script>";

      } else {
        echo "<script type='text/javascript'>alert('로그인 중이 아닙니다.'); location.href='" . $site_url . "/bbs/login.php';</script>";
    }
?>
