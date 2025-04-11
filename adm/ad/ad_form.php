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

?>

<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/board_form.css" rel="stylesheet" />

<div class="page-header">
    <div class="title-div">
        <h4 class="page-title">광고 링크 관리</h4>
    </div>
    <form name="popup_form" id="popup_form" method="post" enctype="multipart/form-data"
          action="setting/ad_setting.php">
        <div>
            <div class="input-wrap">
                <p class="label-name">구분</p>
                <select name="type">
                    <option value="1">A 페이지</option>
                    <option value="2">B 페이지</option>
                </select>

            </div>
            <div class="input-wrap">
                <p class="label-name">고유 코드</p>
                <input type="text" name="link" id="link" class="form-control"
                       placeholder="고유 코드를 입력해주세요" required>
                <small>* 고유코드는 중복값을 작성 할 수 없습니다.</small>
            </div>
        </div>

        <div class="btn-wrap">
            <a href="./ad_list.php?menu=77" class="go-back">목록</a>
            <input type="submit" class="submit" id="submit" value="확인" />
        </div>
    </form>
</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->
