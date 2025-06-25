<?php
    include_once('../head.php');
    include_once('../default.php');

    // 리스트에 출력하기 위한 sql문
    $admin_sql = "select * from admin_role_tbl order by id";
    $admin_stt=$db_conn->prepare($admin_sql);
    $admin_stt->execute();
?>
<link rel="stylesheet" type="text/css" href="../css/manager_list.css" rel="stylesheet" />

<form name="fmemberlist" id="fmemberlist" action="setting/role_delete.php" onsubmit="return fmemberlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="mb_datetime">
    <input type="hidden" name="sod" value="desc">
    <input type="hidden" name="sfl" value="">
    <input type="hidden" name="stx" value="">
    <input type="hidden" name="page" value="1">
    <div class="page-header">
        <div class="btn_fixed_top">
           <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger">
            <span onclick="memoModal()" id="member_add" class="btn btn-primary">담당 부서 추가</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border-bottom" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th scope="col" id="mb_list_chk" width="3%">
                            <label for="chkall" class="d-none">회원 전체</label>
                            <input type="checkbox" class="checkbox-list" name="chkall" id="chkall" value="1" onclick="check_all(this)"><label for="chkall" class="d-done">회원전체</label>
                        </th>
                        <th scope="col" id="mb_list_id" class="text-center" width="10%">부서명</th>
                        <th scope="col" id="mb_list_name" class="text-center" width="10%">홈</th>
                        <th scope="col" id="mb_list_mobile" class="text-center" width="10%">기본설정</th>
                        <th scope="col" id="mb_list_join" class="text-center" width="10%">광고관리</th>
                        <th scope="col" id="mb_list_join" class="text-center" width="10%">문의관리</th>
                        <th scope="col" id="mb_list_join" class="text-center" width="10%">팝업설정</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php
                            while($row=$admin_stt->fetch()){
                        ?>
                        <tr class="bg0">
                            <td headers="mb_list_chk" class="td_chk">
                                <input type="hidden" name="mb_id[<?=$row['id']?>]" value="admin" id="mb_id_<?=$row['id']?>">
                                <input type="checkbox" name="chk[]" class="m_chk checkbox-list" value="<?=$row['id']?>" id="chk_<?=$row['id']?>"><label for="chk_<?=$row['id']?>"></label>
                            </td>
                            <td headers="mb_list_id" class="td_name sv_use text-center"> <?=$row['name']?></td>
                            <?php foreach (json_decode($row['authority'], true) as $index => $value): ?>
                                <td headers="mb_list_id" class="td_name sv_use">
                                    <input type="hidden" name="sort[]" value="">
                                    <select class="access_select" name="authority[]" onchange="accessChg(this, <?=$row['id']?>, <?= $index ?>)">
                                        <option value="1" <?= ($value == 1) ? 'selected' : '' ?>>접근 허용</option>
                                        <option value="0" <?= ($value == 0) ? 'selected' : '' ?>>접근 제한</option>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
        <!-- page-header end -->
    </div>
 <!-- box end -->

<div class="modal-bg"></div>
<div class="modal-container modal-public">
    <div class="head-wrap">
        <span>담당 부서 추가</span>
        <i class="fas fa-times modal-close"></i>
    </div>
    <div class="body">
        <form name="manager_form" id="manager_form" method="post" action="./setting/role_setting.php">
            <input type="hidden" name="type" value="insert">
        <div class="input-wrap">
            <p class="label-name">부서명</p>
            <input type="text" name="name" class="form-control">
        </div>
            <input type="submit" class="submit" value="등록">
        </form>
    </div>
</div>


</div>
<!-- content-box-wrap end -->

<script type="text/javascript">

function check_all(thisobj) {
	var $this = $(thisobj);

	if($this.prop("checked") == true) {
		$this.closest("#fmemberlist").find("input[type=checkbox]").prop("checked", true);
	} else {
		$this.closest("#fmemberlist").find("input[type=checkbox]").prop("checked", false);
	}
}

function fmemberlist_submit(f){
    var count = 0;
    var obj = document.getElementsByName("chk[]");

    for(var i=0 ; i < obj.length ; i++){
        if( obj[i].checked == true ){
            count++;
        }
    }
    if( count == 0) {
        alert("삭제하실 대상을 선택해주세요.");
        return false;
    } else {
        if(document.pressed == "선택삭제") {
            if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }
        return true;
    }
}

function accessChg(selectElement, id, sort){
    let selectedValue = selectElement.value;

    $.ajax({
        type:'post',
        dataType: 'html',
        data: {val: selectedValue, id: id, sort: sort},
        url:'./setting/access_setting.php',
        success:function(html){
            console.log(html);
        }
    });

}

$(function() {
    $('.access_select').on('change', function() {
        if ($(this).val() == '1')
            $(this).css('background', '#fff');
        else
            $(this).css('background', '#ccc');
    });

    $('.access_select').each(function() {
        if ($(this).val() == '1')
            $(this).css('background', '#fff');
        else
            $(this).css('background', '#ccc');
    });
});

// 모달 닫기 (배경 클릭 포함)
$(document).on('click', '.modal-bg, .close-modal', function (e) {
    $('.modal-container').hide();
    $('.modal-container').attr('aria-hidden', 'true');
    $('.modal-bg').fadeOut(300);
});
function memoModal(){
    $(".modal-bg").show();
    $(".modal-container").fadeIn("300")
}
$(".modal-close").click(function (){
    $(".modal-container").fadeOut("300")
    $(".modal-bg").hide();
});

</script>
