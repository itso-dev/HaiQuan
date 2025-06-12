<?php
    session_start();
    include_once('../head.php');

    $adm_login = isset($_GET["is_login"]) ? $_GET["is_login"] : '';


    if ( $adm_login ) {
        session_unset();
        session_destroy();
        echo "<script type='text/javascript'>";
        echo "location.href='../bbs/login.php'";
        echo "</script>";

      } else {
        echo "<script type='text/javascript'>";
        echo "alert('로그인 중이 아닙니다.'); location.href='../bbs/login.php'";
        echo "</script>";
    }
?>
