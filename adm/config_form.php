<?php
include_once('./head.php');
include_once('./default.php');

// 리스트에 출력하기 위한 sql문
$site_sql1 = "select * from site_setting_tbl where id = 1";
$site_stt1=$db_conn->prepare($site_sql1);
$site_stt1->execute();
$row1 = $site_stt1 -> fetch();

$site_sql2 = "select * from site_setting_tbl where id = 2";
$site_stt2=$db_conn->prepare($site_sql2);
$site_stt2->execute();
$row2 = $site_stt2 -> fetch();

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<link rel="stylesheet" type="text/css" href="./css/config_form.css" rel="stylesheet" />

<div class="page-header">
    <h4 class="page-title">기본 설정</h4>

    <!-- <div class="tab-wrap">
        <span class="tab-item tab1  active">사이트 기본설정</span>
        <span class="tab-item tab2">B사이트 기본설정</span>
    </div> -->


    <!--     A사이트      -->
    <form name="config_form" id="config_form_a" method="post" action="./ajax/site_modify.php" style="margin-top: 30px">
        <input type="hidden" name="type" value="1">
        <div class="row">
            <div class="col-md-5 pr-1">
                <div class="form-group">
                    <label>사이트명*</label>
                    <input type="text" name="site_title" value="<?=$row1[1]?>" id="site_title" required class="required frm_input form-control" size="30">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">사이트명은 Browser Title 로 보이게 됩니다.</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-5 pr-1">
                <div class="form-group">
                    <label>사이트 소개*</label>
                    <input type="text" name="site_description" value="<?=$row1[2]?>" id="site_description" required class="required frm_input form-control" size="30">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">meta 태그의 og:description 에 해당됩니다.</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-8 pr-1">
                <div class="form-group">
                    <label>head 코드 삽입</label>
                    <textarea name="head_script" id="head_script" class="frm_input form-control"><?=$row1['head_script']?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">'head' 태그 안에 들어갈 코드를 입력해주세요. (ex. <strong>gtag, 구글태그매니저, 메타픽셀 등</strong>)</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-8 pr-1">
                <div class="form-group">
                    <label>body 코드 삽입</label>
                    <textarea name="body_script" id="body_script" class="frm_input form-control body"><?=$row1['body_script']?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">'body' 태그 뒤에 들어갈 코드를 입력해주세요. (ex. <strong>구글태그매니저</strong>)</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-8 pr-1">
                <div class="form-group">
                    <label>전환페이지 코드 삽입</label>
                    <textarea name="conversion_script" id="conversion_script" class="frm_input form-control"><?=$row1['conversion_script']?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">전환페이지(문의 완료 후 전환되는 페이지) 태그 뒤에 들어갈 코드를 입력해주세요.</small>
            </div>
        </div>
        <div style="margin-top:20px;">
            <input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey="s">
        </div>
    </form>

    <!--     B사이트      -->
    <form name="config_form" id="config_form_b" method="post" action="./ajax/site_modify.php" style="margin-top: 30px">
        <input type="hidden" name="type" value="2">
        <div class="row">
            <div class="col-md-5 pr-1">
                <div class="form-group">
                    <label>사이트명*</label>
                    <input type="text" name="site_title" value="<?=$row2[1]?>" id="site_title" required class="required frm_input form-control" size="30">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">사이트명은 Browser Title 로 보이게 됩니다.</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-5 pr-1">
                <div class="form-group">
                    <label>사이트 소개*</label>
                    <input type="text" name="site_description" value="<?=$row2[2]?>" id="site_description" required class="required frm_input form-control" size="30">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">meta 태그의 og:description 에 해당됩니다.</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-8 pr-1">
                <div class="form-group">
                    <label>head 코드 삽입</label>
                    <textarea name="head_script" id="head_script" class="frm_input form-control"><?=$row2['head_script']?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">'head' 태그 안에 들어갈 코드를 입력해주세요. (ex. <strong>gtag, 구글태그매니저, 메타픽셀 등</strong>)</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-8 pr-1">
                <div class="form-group">
                    <label>body 코드 삽입</label>
                    <textarea name="body_script" id="body_script" class="frm_input form-control body"><?=$row2['body_script']?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">'body' 태그 뒤에 들어갈 코드를 입력해주세요. (ex. <strong>구글태그매니저</strong>)</small>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-8 pr-1">
                <div class="form-group">
                    <label>전환페이지 코드 삽입</label>
                    <textarea name="conversion_script" id="conversion_script" class="frm_input form-control"><?=$row2['conversion_script']?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <small class="description text-xs">전환페이지(문의 완료 후 전환되는 페이지) 태그 뒤에 들어갈 코드를 입력해주세요.</small>
            </div>
        </div>
        <div style="margin-top:20px;">
            <input type="submit" value="확인" class="btn_submit btn btn-primary" accesskey="s">
        </div>
    </form>
</div>
<!-- page-header end -->
</div>
<!-- box end -->

</div>
<!-- content-box-wrap end -->

<script>
    $(".tab1").click(function (){
        $(".tab1").addClass("active");
        $(".tab2").removeClass("active");
        $("#config_form_a").show();
        $("#config_form_b").hide();
    });
    $(".tab2").click(function (){
        $(".tab2").addClass("active");
        $(".tab1").removeClass("active");
        $("#config_form_b").show();
        $("#config_form_a").hide();
    });
</script>
