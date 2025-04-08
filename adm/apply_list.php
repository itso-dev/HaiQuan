<?php
include_once('head.php');
include_once('default.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!isset($_GET['page']))
{
    $_GET['page']=1;
}


// 선택삭제 쿼리

$req = isset($_POST['req']) ? $_POST['req'] : '';

if($req != ""){
    if($req == 'delete'){
        $chk_count = count($_POST['chk']);
        for ($i = 0; $i < $chk_count; $i ++) {
            $id = $_POST['chk'][$i];
            $delete_sql = "delete form contact_tbl where id = $id";
            $deleteStmt = $db_conn->prepare($delete_sql);
            $deleteStmt->execute();
        }

        echo "<script type='text/javascript'>";
        echo "alert('삭제했습니다.'); location.href='/adm/apply_list.php";
        echo "</script>";
    }
}
$change = isset($_POST['change']) ? $_POST['change'] : '';
$wr_id = isset($_POST['wr_id']) ? $_POST['wr_id'] : '';
$result = isset($_POST['result']) ? $_POST['result'] : '';
$sch_startdate = isset($_POST['sch_startdate']) ? $_POST['sch_startdate'] : '';
$sch_enddate = isset($_POST['sch_enddate']) ? $_POST['sch_enddate'] : '';
$stx = isset($_POST['stx']) ? $_POST['stx'] : '';


// 상담결과 동적 변경
if($change == 'resultStatus' && $wr_id != '' && $result != ''){

    $modify_sql = "update contact_tbl
        set 
        result_status = '$result'
        where
        id = $wr_id";

    $updateStmt = $db_conn->prepare($modify_sql);
    $updateStmt->execute();

    $count = $updateStmt->rowCount();
}
// 상담사 동적 변경
else if($change == 'manager' && $wr_id != '' && $result != ''){

    $modify_sql = "update contact_tbl
            set 
            manager_fk = '$result'
            where
            id = $wr_id";

    $updateStmt = $db_conn->prepare($modify_sql);
    $updateStmt->execute();

    $count = $updateStmt->rowCount();

//        $admin_modify_sql = "update admin_tbl
//            set
//            consult_cnt = consult_cnt + 1
//            where
//            id = $result";
//
//        $adminUpdateStmt = $db_conn->prepare($admin_modify_sql);
//        $adminUpdateStmt->execute();
//
//        $admin_count = $adminUpdateStmt->rowCount();
}

//검색 쿼리용
$sch_manager = isset($_POST['sch_manager']) ? $_POST['sch_manager'] : '';
$sch_c_result = isset($_POST['sch_c_result']) ? $_POST['sch_c_result'] : '';
$sch_date = isset($_POST['sch_date']) ? $_POST['sch_date'] : '';
$stx = isset($_POST['stx']) ? $_POST['stx'] : '';


//상담 결과 검색
if($sch_c_result == ""){
    $sch_c_result_key = "";
}else{
    $sch_c_result_key = " and result_status like '%$sch_c_result%'";
}


// 담당자 검색
if ($sch_manager == "") {
    $sch_manager_key = " where manager_fk is not NULL";
} else {
    $sch_manager_key = " where manager_fk = $sch_manager";
}

// 날짜 검색
if ($sch_startdate == "" && $sch_enddate == "") {
    $sch_date_key = "";
} elseif ($sch_startdate != "" && $sch_enddate == "") {
    $sch_date_key = " and write_date between '$sch_startdate 00:00:00' and '$sch_startdate 23:59:59'";
} elseif ($sch_startdate == "" && $sch_enddate != "") {
    $sch_date_key = " and write_date between '$sch_enddate 00:00:00' and '$sch_enddate 23:59:59'";
}
else {
    $sch_date_key = " and write_date between '$sch_startdate 00:00:00' and '$sch_enddate 23:59:59'";
}

// 통합 검색
if($stx == ""){
    $stx_key = "";
}else{
    $stx_key = " AND ( name like '%$stx%' OR email like '%$stx%' OR phone like '%$stx%' OR  writer_ip like '%$stx%' )";
}


// 리스트에 출력하기 위한 sql문
$list_size = 10;
$page_size = 10;
$first = ($_GET['page']*$list_size)-$list_size;


$list_sql = "select * from contact_tbl "
    .$sch_manager_key
    .$sch_c_result_key
    .$sch_date_key
    .$stx_key
    ." order by id desc limit $first, $list_size";

$list_stt=$db_conn->prepare($list_sql);
$list_stt->execute();

//총 페이지를 구하기 위한 sql문
$total_sql = "select count(*) from contact_tbl "
     .$sch_manager_key
    .$sch_c_result_key
    .$sch_date_key
    .$stx_key;


$total_stt=$db_conn->prepare($total_sql);
$total_stt->execute();
$total_row=$total_stt->fetch();

$total_list = $total_row['count(*)'];
$total_page = ceil($total_list/$list_size);
$row = ceil($_GET['page']/$page_size);

$start_page=(($row-1)*$page_size)+1;

//담당자 리스트
$admin_sql = "select * from admin_tbl order by id";
$admin_stt=$db_conn->prepare($admin_sql);
$admin_stt->execute();



?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="./css/apply_list.css" rel="stylesheet" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<form name="fboardlist" id="fboardlist" method="post" onsubmit="return fboardlist_submit(this);">
    <input type="hidden" name="sst" value="a.wr_id">
    <input type="hidden" name="sod" value="desc">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="sch_pay" value="">
    <input type="hidden" name="sort_name" id="sort_name" value="">
    <input type="hidden" name="sort_date" id="sort_date" value="">
    <div class="page">
        <div class="page-header">
            <h4 class="page-title">전체보기
                <small class="text-muted text-xs">(<?= $total_list ?>)</small>
            </h4>
            <div class="btn_fixed_top">
                <div class="d-none">
                    <button type="submit" value="검색" onclick="document.pressed = this.value">삭제방지</button>
                </div>
                <button type="submit" name="act_button" id="delete_btn" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger btn-sm shadow">선택삭제</button>
                <button type="submit" id="export_chks" class="btn btn-primary btn-sm float-right shadow" onclick="document.pressed = '다운로드'" data-href="./ajax/apply_list_export.php">선택 엑셀 다운로드</button>
                <a id="export_all" href="./ajax/contact_list_export.php?type=all" target="_self" class="btn btn-sm float-right shadow">액셀 다운로드</a>
                <a id="export_chks" class="btn btn-primary btn-sm float-right shadow" href="email_form.php?menu=1">발송 이메일 관리</a>
                <span id="export_chks" class="btn btn-primary btn-sm float-right shadow ip-modal-open">차단 아이피 관리</span>
            </div>
            <div class="btn_fixed_top justify-end">
<!--                <a href="./schedule.php?menu=55" class="btn btn-sm btn-danger shadow mt-0">교육/행사 일정 관리</a>-->
<!--                <a href="./calendar.php?menu=55" class="btn btn-sm btn-danger shadow mt-0">미팅 일정 관리</a>-->
            </div>
        </div>
        <div class="mx-3 my-2 p-3 page-header border">
            <div class="my-2">상세검색</div>
            <div class="d-none d-md-block">
                <div class="row mx-0">
                    <div class="col-md-2 my-1 my-md-0 px-1">
                        <label>담당자</label>
                    </div>
                    <div class="col-md-2 my-1 my-md-0 px-1">
                        <label>결과</label>
                    </div>
                    <div class="col-md-2 my-1 my-md-0 px-1" >
                        <label>검색 시작일</label>
                    </div>
                    <div class="col-md-2 my-1 my-md-0 px-1" >
                        <label>검색 종료일</label>
                    </div>
                    <div class="col-md-4 my-1 my-md-0 px-1">
                        <label>통합검색</label>
                    </div>
                </div>
            </div>
            <div class="row mx-0 mb-2">
                <div class="col-6 col-md-2 my-1 my-md-0 px-1">
                    <select class="custom-select custom-select-sm rounded-0" name="sch_manager">
                        <option value="">없음</option>
                        <?php
                        while($admin_row1=$admin_stt->fetch()){
                            ?>
                            <option value="<?= $admin_row1['id'] ?>"><?= $admin_row1['login_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-6 col-md-2 my-1 my-md-0 px-1">
                    <select class="custom-select custom-select-sm rounded-0" name="sch_c_result">
                        <option value="" <? if($sch_c_result == "" || $sch_c_result == "전체") echo "selected" ?> >전체</option>
                        <option value="대기" <? if($sch_c_result == "대기") echo "selected" ?> >대기</option>
                        <option value="진행" <? if($sch_c_result == "진행") echo "selected" ?> >진행</option>
                        <option value="부재" <? if($sch_c_result == "부재") echo "selected" ?> >부재</option>
                        <option value="재통화" <? if($sch_c_result == "재통화") echo "selected" ?> >재통화</option>
                        <option value="거절" <? if($sch_c_result == "거절") echo "selected" ?> >거절</option>
                        <option value="완료" <? if($sch_c_result == "완료") echo "selected" ?> >완료</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 py-md-0 my-1 my-md-0 px-1 position-relative">
                    <input type="text" class="form-control h-100 bg-white date-picker" value="<?= $sch_startdate ?>" name="sch_startdate" id="sch_startdate" autocomplete="off" placeholder="검색 시작일" style="border-radius: 0;border: 1px solid #ced4da;">
                    <a class="position-absolute" href="javascript:initSchDate();" style="top:23%; right:6%;"><img src="<?= $site_url ?>/img/close.png" class="w-75"></a>
                </div>
                <div class="col-12 col-md-2 py-md-0 my-1 my-md-0 px-1 position-relative">
                    <input type="text" class="form-control h-100 bg-white date-picker" value="<?= $sch_enddate ?>" name="sch_enddate" id="sch_enddate" autocomplete="off" placeholder="검색 종료일" style="border-radius: 0;border: 1px solid #ced4da;">
                    <a class="position-absolute" href="javascript:initSchEndDate();" style="top:23%; right:6%;"><img src="<?= $site_url ?>/img/close.png" class="w-75"></a>
                </div>
                <div class="col-12 col-md-2 my-1 my-md-0 px-1 position-relative">
                    <input type="text" class="form-control h-100 pr-5" value="<?= $stx ?>" name="stx" id="sch_str" placeholder="검색어 입력" style="background:#FFF; border-radius: 0;border: 1px solid #ced4da;">
                    <a class="position-absolute" href="javascript:initSchStr();" style="top:23%; right:2%;"><img src="<?= $site_url ?>/img/close.png" class="w-75"></a>
                </div>
                <div class="col-12 col-md-2 my-1 my-md-0 px-1">
                    <div class="w-100 py-1 py-md-1 my-md-auto row mx-0 rounded-0" style="background-color:#F66332; border-radius: 3px; color:white; border:none; cursor:pointer" onclick="search();">
                        <div class="m-auto text-center">검색</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="table-responsive">
                <table class="table border-bottom border-top" style="min-width: 2100px;">
                    <thead>
                    <tr>
                        <th scope="col" class="cursor: pointer; text-center" style="width: 30px;"><input type="checkbox" class="checkbox-controller" onclick="check_all(this)"></th>
                        <th scope="col" style="width: 165px;" class="text-center" onclick="sortColumn('sort_date');">등록일</th>
                        <th scope="col" style="width: 170px;" class="text-center">유입 경로</th>
                        <th scope="col" style="width: 170px;" class="text-center">A/B 테스트</th>
                        <th scope="col" style="width: 170px;" class="text-center">광고 코드</th>
                        <th scope="col" style="width: 150px; cursor: pointer;" class="text-center">성함</th>
                        <th scope="col" style="width: 150px;" class="text-center">연락처</th>
                        <th scope="col" style="width: 200px;" class="text-center">이메일</th>
                        <th scope="col" style="width: 250px;" class="text-center">창업희망지역</th>
                        <th scope="col" style="width: 200px;" class="text-center">창업예상비용</th>
                        <th scope="col" style="width: 150px;" class="text-center">점포보유여부</th>
                        <th scope="col" style="width: 150px;" class="text-center">아이피</th>
                        <th scope="col" class="d-none" style="width:500px;cursor: pointer;" onclick="sortColumn('sort_importance');">중요도<span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $is_data = 0;
                    while($list_row=$list_stt->fetch()){
                        $is_data = 1;
                        ?>
                        <tr class="bg0">
                            <td class="td_chk text-center">
                                <label for="chk_0" class="sound_only"></label>
                                <input type="checkbox" name="chk[]" class="checkbox-list" value="<?= $list_row['id'] ?>" id="chk_">
                            </td>
                            <td class="text-center"><?=$list_row['write_date']?></td>
                            <td class="text-center"><?=$list_row['flow']?></td>
                            <td class="text-center">
                                <?
                                if($list_row['ab_test'] != ''){
                                    echo $list_row['ab_test'] ."페이지";
                                }
                                ?>
                            </td>
                            <td class="text-center"><?=$list_row['ad_code']?></td>
                            <td class="text-center"><?=$list_row['name']?></td>
                            <td class="text-center"><?=$list_row['phone']?></td>
                            <td class="text-center"><?=$list_row['email']?></td>
                            <td class="text-center"><?=$list_row['location']?></td>
                            <td class="text-center"><?=$list_row['cost']?></td>
                            <td class="text-center"><?=$list_row['store']?></td>
                            <td class="text-center"><span class="writer-ip"><?=$list_row['writer_ip']?></span></td>
                            <td>
                                <button type="button" class="button button4" style="width: 100px;" onclick="openContactDescModal(<?= $list_row['id'] ?>);">문의 내용</button>
                            </td>
                            <td>
                                <button type="button" class="button button4" style="width: 90px;" onclick="openCounselModal(<?= $list_row['id'] ?>);">상담 내역</button>
                            </td>
                            <!-- <td>
                                <button type="button" class="button button4" style="width: 90px;" onclick="openCounselLogModal(2630);">기록보기</button>
                            </td> -->
                            <td>
                                <select class="custom-select custom-select-sm" style="width: 100px;" onchange="changeResultStatus('<?=$list_row['id']?>', this.value)">
                                    <option value="대기" <? if($list_row['result_status'] == "대기") echo "selected"?>>대기</option>
                                    <option value="진행" <? if($list_row['result_status'] == "진행") echo "selected"?>>진행</option>
                                    <option value="부재" <? if($list_row['result_status'] == "부재") echo "selected"?>>부재</option>
                                    <option value="거절" <? if($list_row['result_status'] == "거절") echo "selected"?>>거절</option>
                                    <option value="완료" <? if($list_row['result_status'] == "완료") echo "selected"?>>완료</option>
                                </select>
                            </td>
                            <td>
                                <select class="custom-select custom-select-sm" style="width: 100px;" onchange="changeManager('<?=$list_row['id']?>', this.value)">
                                    <option value="0" <? if($list_row['manager_fk'] == 0) echo "selected"?>>없음</option>
                                    <?php
                                    //담당자 리스트
                                    $admin_stt2=$db_conn->prepare($admin_sql);
                                    $admin_stt2->execute();
                                    while($manager_row=$admin_stt2->fetch()){
                                        ?>
                                        <option value="<?= $manager_row['id'] ?>" <? if($list_row['manager_fk'] == $manager_row['id']) echo "selected"?>><?= $manager_row['login_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td class="d-none">
                                <select class="custom-select" onchange="changeImportance('2630', this.value);">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if($is_data != 1) { ?>
                    <tr><td colspan="20" class="text-center text-dark bg-light">문의 사항이 없습니다.</td></tr> </tbody>
                    <?php } ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <?php
                        if($start_page<=0){
                            $start_page = 1;
                        }
                        $end_page=($start_page+$page_size)-1;
                        if($total_page<$end_page){
                            $end_page=$total_page;
                        }
                        if($_GET['page']!=1){
                            $back=$_GET['page']-$page_size;
                            if($back<=0){
                                $back=1;
                            }
                            echo "<li class='page-item'>";
                            echo "  <a class='page-link' href='$_SERVER[PHP_SELF]?page=$back'>처음</a>";
                            echo "</li>";
                        }
                        for($i=$start_page; $i<=$end_page; $i++){
                            if($_GET['page']!=$i){
                                echo "<li class='page-item'>";
                                echo "  <a class='page-link' href='$_SERVER[PHP_SELF]?page=$i'>";
                                echo "      $i ";
                                echo "  </a>";
                                echo "</li>";
                            }else{
                                echo "<li class='page-item'>";
                                echo "  <strong class='page-link active'>";
                                echo "      $i 페이지 ";
                                echo "  </strong>";
                                echo "</li>";
                            }
                        }
                        if($_GET['page']!=$total_page){
                            $next=$_GET['page']+$page_size;
                            if($total_page<$next){
                                $next=$total_page;
                            }
                            echo "<li class='page-item'>";
                            echo "<a class='page-link' href='$_SERVER[PHP_SELF]?page=$next'>맨끝</a>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</form>
</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->


<!-- Popup Modal -->
<div class="modal-bg"></div>
<div class="ip-modal-container">

</div>

<div class="modal-container">
</div>


<script type="text/javascript">

    // 상담 상태 동적 변경
    function changeResultStatus(index, result){
        $.ajax({
            type:'post',
            dataType:'json',
            url:'./apply_list.php',
            data:{wr_id:index, result:result, change:'resultStatus'},
            success:function(json){
            }
        });
    }

    // 담당자 동적 변경
    function changeManager(index, result){
        $.ajax({
            type:'post',
            dataType:'json',
            url:'./apply_list.php',
            data:{wr_id:index, result:result, change:'manager'},
            success:function(json){
            }
        });
    }

    function check_all(thisobj) {
        var $this = $(thisobj);

        if($this.prop("checked") == true) {
            $this.closest("#fboardlist").find("input[type=checkbox]").prop("checked", true);
        } else {
            $this.closest("#fboardlist").find("input[type=checkbox]").prop("checked", false);
        }
    }

    function fboardlist_submit(f) {
        var count = 0;
        var obj = document.getElementsByName("chk[]");

        for(var i=0 ; i < obj.length ; i++){
            if( obj[i].checked == true ){
                count++;
            }
        }
        if( count == 0) {
            alert("한 개 이상을 선택해주세요.");
            return false;
        } else {
            if(document.pressed == "선택삭제") {
                if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                    return false;
                }else{
                    document.fboardlist.action = "./ajax/contact_delete.php";
                }
            } else if(document.pressed == "다운로드"){
                document.fboardlist.action = "./ajax/contact_list_export.php";
            }
            return true;
        }
    }
    function search(){
        document.getElementById('fboardlist').submit();
    }
    // 검색 input text 지움
    function initSchDate(){
        $('#sch_startdate').val('');
    }
    function initSchEndDate(){
        $('#sch_enddate').val('');
    }
    function initSchStr(){
        $('#sch_str').val('');
    }
    //datepicker
    $( function() {
        $( "#sch_startdate" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
        $( "#sch_enddate" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
    //문의 내용 팝업
    function openCounselModal(index){

        $.ajax({
            type:'post',
            url:'./ajax/counsel_modal_open.php',
            data:{wr_id:index},
            success:function(html){

                $('.modal-container').empty();
                $('.modal-container').html(html);
                $('#contactModal').modal('show');
                $('.modal-bg').show();
                $('#contactModal').attr('aria-hidden', 'false');

            }
        });
    }
    //상담 내역 팝업
    function openContactDescModal(index){

        $.ajax({
            type:'post',
            url:'./ajax/contact_desc_modal_open.php',
            data:{wr_id:index},
            success:function(html){

                $('.modal-container').empty();
                $('.modal-container').html(html);
                $('#contactModal').modal('show');
                $('.modal-bg').show();
                $('#contactModal').attr('aria-hidden', 'false');
            }
        });
    }

    // 모달 닫기 (배경 클릭 포함)
    $(document).on('click', '.modal-bg, .close-modal', function (e) {
        $('#contactModal').modal('hide');
        $('#contactModal').attr('aria-hidden', 'true');
        $('.modal-bg').fadeOut(300);
    });

    $(".writer-ip").click(function (){
        var ip = $(this).text();

        var result = confirm('해당 아이피 접속을 차단하시겠습니까?');

        if(result) {
            $.ajax({
                type:'post',
                url:'./ajax/ip_block_ajax.php',
                data:{ip:ip},
                success:function(data){
                    alert(data)
                }
            });
        }
    });
    $(".ip-modal-open").click(function (){
        $.ajax({
            type:'post',
            url:'./ajax/ip_block_modal.php',
            success:function(html){
                $('.ip-modal-container').empty();
                $('.ip-modal-container').html(html);
                $(".modal-bg").show();
                $(".ip-modal-container").fadeIn("300")
            }
        });
    });
    $(".modal-close").click(function (){
        $(".ip-modal-container").fadeOut("300")
        $(".modal-bg").hide();
    });
</script>
