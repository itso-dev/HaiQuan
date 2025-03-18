<?php
    include_once('../head.php');
    include_once('../default.php');

    // 리스트에 출력하기 위한 sql문
    $admin_sql = "select * from admin_tbl order by id";
    $admin_stt=$db_conn->prepare($admin_sql);
    $admin_stt->execute();

    $total_sql = "select count(*) from admin_tbl";
    $total_stt=$db_conn->prepare($total_sql);
    $total_stt->execute();
    $total=$total_stt->fetch();

?>
<link rel="stylesheet" type="text/css" href="../css/manager_list.css" rel="stylesheet" />

<form name="fmemberlist" id="fmemberlist" action="setting/manager_delete.php" onsubmit="return fmemberlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="mb_datetime">
    <input type="hidden" name="sod" value="desc">
    <input type="hidden" name="sfl" value="">
    <input type="hidden" name="stx" value="">
    <input type="hidden" name="page" value="1">
    <div class="page-header">
        <h4 class="page-title">담당자
        <small class="text-muted text-xs">(총 <?= $total[0] ?> 명)</small>
        </h4>
        <div class="btn_fixed_top">
           <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
            <a href="./access_list.php?menu=111&type=insert" id="member_add" class="btn2 btn_01">담당 부서 설정</a>
            <a href="./manager_form.php?menu=111&type=insert" id="member_add" class="btn2 btn_01">담당자 추가</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border-bottom" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th scope="col" id="mb_list_chk" rowspan="2">
                            <label for="chkall" class="d-none">회원 전체</label>
                            <input type="checkbox" name="chkall" value="1" onclick="check_all(this)">
                        </th>
                        <th scope="col" id="mb_list_id" colspan="2">아이디</th>
                        <th scope="col" id="mb_list_name" class="text-center">이름</th>
                        <th scope="col" id="mb_list_mobile" class="text-center">휴대폰</th>
                        <th scope="col" id="mb_list_join" class="text-center">가입일</th>
                        <th scope="col" rowspan="2" id="mb_list_mng" class="text-center">관리</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php
                            while($list_row=$admin_stt->fetch()){
                        ?>
                        <tr class="bg0">
                            <td headers="mb_list_chk" class="td_chk">
                                <input type="hidden" name="mb_id[<?=$list_row['id']?>]" value="admin" id="mb_id_<?=$list_row['id']?>">
                                <input type="checkbox" name="chk[]" class="m_chk" value="<?=$list_row['id']?>" id="chk_<?=$list_row['id']?>">
                            </td>
                            <td headers="mb_list_id" colspan="2" class="td_name sv_use"> <?=$list_row['login_id']?></td>
                            <td headers="mb_list_name" class="td_mbname text-center"><?=$list_row['login_name']?></td>
                            <td headers="mb_list_mobile" class="td_tel text-center"><?=$list_row['phone']?></td>
                            <td headers="mb_list_join" class="td_date text-center"><?=$list_row['reg_date']?></td>
                            <td headers="mb_list_mng" class="td_mng td_mng_s text-center"><a href="./manager_form.php?menu=4&type=modify&id=<?=$list_row['id']?>" class="btn btn_03">수정</a></td>
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

</script>
