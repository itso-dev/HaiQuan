<?
if( isset( $_SESSION[ 'manager_id' ] ) ) {
     $adm_login = TRUE;
     $manager_key = $_SESSION[ 'manager_id' ];
     echo "
        <script>
            console.log(".$manager_key."); 
        </script>
     ";
}

if (!$adm_login) {
    // 세션 만료 또는 미로그인 상태로 접근 시
    header('Location: ' . $site_url . '/bbs/login.php?expired=1');
    exit;
}

header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');


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

// 관리자 테이블에서 role 값 먼저 가져오기
$admin_sql = "select role from admin_tbl where id = $manager_key";
$admin_stt = $db_conn->prepare($admin_sql);
$admin_stt->execute();
$admin_result = $admin_stt->fetch(PDO::FETCH_ASSOC);

$role = $admin_result['role'];

// 역할(Role)에 따른 권한 정보 가져오기
$access_sql = "select * from admin_role_tbl WHERE id = $role";
$access_stt = $db_conn->prepare($access_sql);
$access_stt->execute();
$access_result = $access_stt->fetch(PDO::FETCH_ASSOC);

// 권한 파싱, [1, 1, 1, 1, 1, 1] = [홈, 기본설정, 광고관리, A/B테스트, 문의관리, 팝업설정]
$authority_json = $access_result['authority'];
$authority = json_decode($authority_json);

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

        $('.sidebar-wrapper .nav .active').each(function () {
            $(this).find('.submenu').each(function () {
                if ($(this).find('.this').length) {
                    $(this).show();
                    $(this).prev('.menu').addClass('toggle-open');
                }
            });
        });
    });

</script>

<!-- Font Awesome 6.x CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body>
<!-- admin menu -->
<div class="gnb-container">
    <div class="sidebar">
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="<?= $root_url ?>/index.php"> <i class="fas fa-home"></i> </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:location.reload();"><i class="fas fa-redo-alt"></i></a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $site_url ?>/ajax/logout.php?is_login=<?= $adm_login ?>"> <i class="fas fa-sign-out-alt"></i></a>

                        </li>
                    </ul>
                    <p class="manager-info"><?= $_SESSION['manager_name'] ?></p>
                </div>

            </div>
        </nav>
        <div class="brand-wrapper">
            <a class="brand" href="<?= $site_url ?>/index.php">랜딩페이지명</a>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <?php if ($authority[0]) { ?>
                <li <?php if($menu == 0  || $menu == "") echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/index.php?menu=0">
                        <i class="fas fa-chart-bar"></i>
                        <p>홈</p>
                    </a>
                </li>
                <?php } ?>

                <?php if ($authority[1]) { ?>
                <li <?php if($menu == 11) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/config_form.php?menu=11">
                        <i class="fas fa-list-ul"></i>
                        <p>기본 설정</p>
                    </a>
                </li>
                <?php } ?>

                <?php if ($authority[2]) { ?>
                <li <?php if($menu == 77) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/ad/ad_list.php?menu=77">
                        <i class="fas fa-ad"></i>
                        <p>광고 관리</p>
                    </a>
                </li>
                <?php } ?>

                <!-- <?php if ($authority[3]) { ?>
                <li <?php if($menu == 99) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/test/test_list.php?menu=99">
                        <i class="fas fa-laptop-code"></i>
                        <p>A/B 테스트</p>
                    </a>
                </li>
                <?php } ?> -->

                <?php if ($authority[3]) { ?>
                <li <?php if($menu == 55) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/apply_list.php?menu=55">
                        <i class="far fa-envelope"></i>
                        <p class="contact_col">문의 관리 <span class="today-new"><?= $contact_alert_count ?></span></p>
                    </a>
                </li>
                <?php } ?>

                <?php if($_SESSION[ 'manager_id' ] == 1 && ($authority[4]) ){ ?>
                <li <?php if($menu == 66) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/popup_list.php?menu=66">
                        <i class="far fa-clone"></i>
                        <p>팝업 설정</p>
                    </a>
                </li>
                <?php } ?>

                <?php if($_SESSION[ 'manager_id' ] == 1){ ?>
                <li <?php if($menu == 111) echo "class='active'" ?> >
                    <a class="menu" href="<?= $site_url ?>/manager/manager_list.php?menu=111">
                        <i class="far fa-user"></i>
                        <p>담당자 설정</p>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="service-center-wrap">
            <a href="<?= $site_url ?>/service_center.php?menu=10"><i class="fas fa-headphones"></i> 고객센터</a>
        </div>
    </div>
</div>

<!-- 컨텐츠 영역 시작 -->
<div class="main-wrapper" id="wrapper">
    <div class="navbar-wrapper">
        <div class="navbar-toggle">
            <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </button>
        </div>
    </div>
    <div id="container">
        <div class="content-box-wrap">
            <div class="box">
                <div class="page-header">
