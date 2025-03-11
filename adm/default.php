<?
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
 if( isset( $_SESSION[ 'manager_id' ] ) ) {
     $adm_login = TRUE;
     $manager_key = $_SESSION[ 'manager_id' ];
 }

 if ( !$adm_login ) {
?>
<script>
</script>
<meta http-equiv="refresh" content="0;url=bbs/login.php" />
<?
 }

// 문의알림
if($manager_key == 1){
    $contact_alert_sql = "select count(*) as today_count from contact_tbl where result_status = '대기'";
} else {
    $contact_alert_sql = "select count(*) as today_count from contact_tbl where result_status = '대기' AND manager_fk = " .$manager_key;
}


$contact_alert_stt=$db_conn->prepare($contact_alert_sql);
$contact_alert_stt->execute();
$contact_alert_result = $contact_alert_stt->fetch(PDO::FETCH_ASSOC);
$contact_alert_count = $contact_alert_result['today_count'];

?>

<script type="text/javascript">

    $( document ).ready(function() {
        $('.navbar-toggler').click(function(){
            $('body').toggleClass('sidebar-open');
        });
        $(".sidebar-wrapper .nav li").click(function (){
            if($(this).hasClass("toggle-open")){
                $(this).find(".submenu").slideUp("300");

                $(this).removeClass("toggle-open");
            } else {
                $(this).find(".submenu").slideDown("300");
                $(this).addClass("toggle-open");
            }
        });
    });

</script>

<!-- Font Awesome 6.x CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body>
<!-- admin menu -->
<div class="gnb-container">
    <div class="sidebar">
        <div class="brand-wrapper">
            <a class="brand" ><img src="<?= $site_url ?>/img/logo.png" class="adm_logo"></a>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <li <?php if($menu == 0  || $menu == "") echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/index.php?menu=0">
                        <i class="fas fa-chart-bar"></i>
                        <p>홈</p>
                    </a>
                </li>
                <li <?php if($menu == 11) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/config_form.php?menu=11">
                        <i class="fas fa-list-ul"></i>
                        <p>기본 설정</p>
                    </a>
                </li>
                <li <?php if($menu == 77) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/ad/ad_list.php?menu=77">
                        <i class="fas fa-ad"></i>
                        <p>광고 관리</p>
                    </a>
                </li>
                <li <?php if($menu == 99) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/test/test_list.php?menu=99">
                        <i class="fas fa-laptop-code"></i>
                        <p>A/B 테스트</p>
                    </a>
                </li>
                <li <?php if($menu == 55) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/apply_list.php?menu=55">
                        <i class="far fa-envelope"></i>
                        <p class="contact_col">문의 관리 <span class="today-new"><?= $contact_alert_count ?></span></p>
                    </a>
                </li>
                <?php if($_SESSION[ 'manager_id' ] == 1){ ?>
                <li <?php if($menu == 66) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/popup_list.php?menu=66">
                        <i class="far fa-clone"></i>
                        <p>팝업 설정</p>
                    </a>
                </li>
                <?php } ?>
                <?php if($_SESSION[ 'manager_id' ] == 1){ ?>
                <li <?php if($menu == 111) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/manager_list.php?menu=111">
                        <i class="far fa-user"></i>
                        <p>담당자 설정</p>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<!-- 컨텐츠 영역 시작 -->
<div class="main-wrapper" id="wrapper">

    <!-- 상단 레이아웃 -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-toggle">
                    <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
                <!--a class="navbar-brand" href="apply_list.php">예반스</a-->
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/adm/apply_list.php"><i class="fas fa-redo-alt"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="./ajax/logout.php?is_login=<?= $adm_login ?>"> <i class="fas fa-sign-out-alt"></i></a>

                    </li>
                    <li class="nav-item">
                        <a href="/index.php"> <i class="fas fa-home"></i> </a>
                    </li>
                </ul>

            </div>

        </div>
    </nav>


    <div class="panel-header"></div>

    <div id="container">
        <div class="content-box-wrap">
            <div class="box">
                <div class="page-header">
