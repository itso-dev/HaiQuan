<?php
include_once('../head.php');
include_once('../default.php');

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


$role_sql = "select * from admin_role_tbl";
$role_stt=$db_conn->prepare($role_sql);
$role_stt->execute();

?>

<link rel="stylesheet" type="text/css" href="../css/board_form.css" rel="stylesheet" />

<div class="page-header">
    <h4 class="page-title">담당자 설정</h4>
    <form name="manager_form" id="manager_form" method="post" action="./setting/manager_setting.php">
        <input type="hidden" name="id" value="<?= $id ?>" />
        <input type="hidden" name="type" value="<?= $type ?>" />
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

            <select name="role">
                <?php
                while($role_val=$role_stt->fetch()){
                ?>
                <option value="<?= $role_val['id'] ?>" <? if($role == $role_val['id']) echo 'selected'; ?>><?= $role_val['name'] ?></option>
                <? } ?>
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
            <a href="./manager_list.php?menu=4" class="go-back">목록</a>
            <input type="submit" class="submit" value="확인" />
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
