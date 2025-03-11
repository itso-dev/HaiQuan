<?php
include_once('./head.php');
include_once('./default.php');

$type = $_GET['type'];
$id = "";
$login_id = "";
$role = "";
$name = "";
$phone = "";

if($type=='modify'){
    $id = $_GET['id'];
    // 리스트에 출력하기 위한 sql문
    $admin_sql = "select * from admin_tbl where id = $id";
    $admin_stt=$db_conn->prepare($admin_sql);
    $admin_stt->execute();
    $row = $admin_stt -> fetch();

    $login_id = $row['login_id'];
    $name = $row['login_name'];
    $phone = $row['phone'];
    $role = $row['role'];
}

?>

<link rel="stylesheet" type="text/css" href="./css/manager_form.css" rel="stylesheet" />

<div class="page-header">
    <h4 class="page-title">담당자 설정</h4>
    <form name="manager_form" id="manager_form" method="post" action="./ajax/manager_setting.php">
        <input type="hidden" name="id" value="<?= $id ?>" />
        <input type="hidden" name="type" value="<?= $type ?>" />
</div>
<div class="input-wrap">
    <p class="label-name">아이디*</p>
    <input type="text" autocomplete="off" name="login_id" class="form-control" value="<?= $login_id ?>">
</div>
<div class="input-wrap">
    <p class="label-name">비밀번호*</p>
    <input type="password" autocomplete="off" id="password" name="password" class="form-control" value="" required>
</div>
<div class="input-wrap">
    <p class="label-name">담당부서</p>
    <select name="role" style="font-size: 17px; padding: 10px;">
        <option value="영업" <? if($role == '영업') echo 'selected'; ?>>영업</option>
        <option value="내부 담당" <? if($role == '내부 담당') echo 'selected'; ?>>내부 담당</option>
        <option value="마케팅 대행사" <? if($role == '마케팅 대행사') echo 'selected'; ?>>마케팅 대행사</option>
    </select>
</div>
<div class="input-wrap">
    <p class="label-name">이름*</p>
    <input type="text" name="login_name" class="form-control" value="<?= $name ?>">
</div>
<div class="input-wrap">
    <p class="label-name">연락처</p>
    <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
</div>

</div>
<div class="btn-wrap">
    <input type="submit" class="submit" value="확인" />
    <a href="./manager_list.php?menu=4" class="go-back">목록</a>
</div>
</form>
</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->

<script type='text/javascript'>
    $( document ).ready(function() {
        $('#password').val('');
    });

</script>
