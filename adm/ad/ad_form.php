<?php
include_once('../head.php');
include_once('../default.php');

$mode = $_GET['mode'];

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = '';
}

$today = date("Y-m-d");

$ad_category_sql = "select * from ad_type_tbl order by regdate desc";
$ad_category_stt=$db_conn->prepare($ad_category_sql);
$ad_category_stt->execute();


?>

<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/board_form.css" rel="stylesheet" />

<div class="page-header">
    <div class="title-div">
        <h4 class="page-title">광고 코드 등록</h4>
    </div>
    <form name="popup_form" id="popup_form" method="post" enctype="multipart/form-data"
          action="setting/ad_setting.php">
        <div>
            <div class="input-wrap">
                <p class="label-name">구분</p>
                <select name="type">
                    <option value="1">기타</option>
                    <? while($category=$ad_category_stt->fetch()){ ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <? } ?>
                </select>

            </div>
            <div class="input-wrap">
                <p class="label-name">광고 코드</p>
                <input type="text" name="link" id="link" class="form-control"
                       placeholder="광고 코드로 사용할 이름을 입력해주세요." required>
                <small>* 광고 코드명은 영어로 작성해주세요.</small>
                <small>* 동일한 광고 코드명을 중복으로 사용하실 수 없습니다.</small>
                <small>* 생성된 광고 코드는 링크를 클릭하면 복사하여 사용하실 수 있습니다.</small>
            </div>
        </div>

        <div class="btn-wrap">
            <a href="./ad_list.php?menu=77" class="go-back">목록</a>
            <input type="submit" class="submit" id="submit" value="등록" />
        </div>
    </form>
</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->
