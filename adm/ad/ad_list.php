<?php
include_once('../head.php');
include_once('../default.php');

// 리스트에 출력하기 위한 sql문
$ad_sql1 = "select * from ad_link_tbl where type = 1 order by id desc";
$ad_stt1=$db_conn->prepare($ad_sql1);
$ad_stt1->execute();

$ad_sql2 = "select * from ad_link_tbl where type = 2 order by id desc";
$ad_stt2=$db_conn->prepare($ad_sql2);
$ad_stt2->execute();

?>
<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/board_list.css" rel="stylesheet" />

<div class="page-header">
    <h4 class="page-title">
        광고 링크 관리
    </h4>
</div>

<form name="fmemberlist" id="fmemberlist" action="/setting/ad_delete.php" method="post">
    <input type="hidden" name="link" value="portfolio_list.php"/>
    <input type="hidden" name="type" value="all"/>
    <div class="btn_fixed_top">
        <div class="tab-wrap">
            <span class="ad-tab1 tab-item active" href="a_config.php?menu=77">A 페이지</span>
            <span class="ad-tab2 tab-item" href="b_config.php?menu=77">B 페이지</span>
        </div>
        <div class="top_btn-wrap">
            <span onclick="delData()" class="btn btn-danger">선택삭제</span>
            <a href="ad_form.php?menu=77&mode=insert" id="member_add" class="btn btn-primary">고유코드 추가</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">

            <!--     A사이트      -->
            <table class="a-table table border-bottom" style="min-width: 800px;">
                <thead>
                <tr>
                    <th scope="col" id="mb_list_chk" rowspan="2" width="5%">
                        <input type="checkbox" name="chkall" value="1" class="check_all_a checkbox-list" id="check_all_a">
                        <label for="check_all_a"></label>
                    </th>
                    <!-- <th scope="col" id="mb_list_id" width="10%" class="text-center">구분</th> -->
                    <th scope="col" id="mb_list_join" width="20%" class="text-center">고유코드</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">유입 방문자 수</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">문의 수</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">작성일</th>
                    <th scope="col" id="mb_list_mng" width="40%" class="text-center">링크</th>
                    <th scope="col"  id="mb_list_mng" width="20%" class="text-center">메모</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while($ad1=$ad_stt1->fetch()){
                    $count_sql = "SELECT COUNT(*) AS total_count FROM contact_tbl WHERE ad_code = '" .$ad1['link'] ."'";
                    $count_stt = $db_conn->prepare($count_sql);
                    $count_stt->execute();
                    $count = $count_stt->fetch();
                    ?>
                    <tr class="bg0">
                        <td headers="mb_list_chk" class="td_chk">
                            <!-- <input type="hidden" name="mb_id[<?=$ad1['id']?>]" value="admin" id="mb_id_<?=$ad1['id']?>"> -->
                            <input type="checkbox" name="chk[]" class="m_chk checkbox-list" value="<?=$ad1['id']?>" id="chk_<?=$ad1['id']?>">
                            <label for="chk_<?= $ad1['id'] ?>"></label>
                        </td>
                        <td headers="mb_list_join" class="td_date text-center"><?=$ad1['link']?></td>
                        <td headers="mb_list_join" class="td_date text-center"><?= number_format($ad1['view'])?></td>
                        <td headers="mb_list_join" class="td_date text-center"><?= number_format($count['total_count'])?></td>
                        <td headers="mb_list_name" class="td_mbname text-center"><?= substr($ad1['regdate'], 0, 10) ?></td>
                        <td headers="mb_list_mng" class="td_mng td_mng_s text-center" onclick="copyToClipboard('<?= $root_url ?>?adCode=<?= $ad1['link'] ?>')" style="cursor: pointer; color: blue; text-decoration: underline;"
                        ><?= $root_url ?>?adCode=<?= $ad1['link'] ?></td>
                        <td class="text-center">
                                    <span class="memo" onclick="memoModal(<?= $ad1['id'] ?>)"><i class="far fa-edit"></i>
                                    </span>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <!--      B사이트       -->
            <table class="b-table table border-bottom" style="min-width: 800px;">
                <thead>
                <tr>
                    <th scope="col" id="mb_list_chk" rowspan="2" width="5%">
                        <input type="checkbox" name="chkall" value="1" class="check_all_b checkbox-list" id="check_all_b">
                        <label for="check_all_b"></label>
                    </th>
                    <!-- <th scope="col" id="mb_list_id" width="10%" class="text-center">구분</th> -->
                    <th scope="col" id="mb_list_join" width="20%" class="text-center">고유코드</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">유입 방문자 수</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">문의 수</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">작성일</th>
                    <th scope="col" id="mb_list_mng" width="40%" class="text-center">링크</th>
                    <th scope="col"  id="mb_list_mng" width="20%" class="text-center">메모</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while($ad2=$ad_stt2->fetch()){
                    $count_sql = "SELECT COUNT(*) AS total_count FROM contact_tbl WHERE ad_code = '" .$ad2['link'] ."'";
                    $count_stt = $db_conn->prepare($count_sql);
                    $count_stt->execute();
                    $count = $count_stt->fetch();
                    ?>
                    <tr class="bg0">
                        <td headers="mb_list_chk" class="td_chk">
                            <input type="hidden" name="mb_id[<?=$ad2['id']?>]" value="admin" id="mb_id_<?=$ad2['id']?>">
                            <input type="checkbox" name="chk[]" class="m_chk checkbox-list" value="<?=$ad2['id']?>" id="chk_<?=$ad2['id']?>">
                            <label for="chk_<?= $ad2['id'] ?>"></label>
                        </td>
                        <td headers="mb_list_join" class="td_date text-center"><?=$ad2['link']?></td>
                        <td headers="mb_list_join" class="td_date text-center"><?= number_format($ad2['view'])?></td>
                        <td headers="mb_list_join" class="td_date text-center"><?= number_format($count['total_count'])?></td>
                        <td headers="mb_list_name" class="td_mbname text-center"><?= substr($ad2['regdate'], 0, 10) ?></td>
                        <td headers="mb_list_mng" class="td_mng td_mng_s text-center" onclick="copyToClipboard('<?= $root_url ?>/B/index.php?adCode=<?= $ad2['link'] ?>')" style="cursor: pointer; color: blue; text-decoration: underline;"
                        ><?= $root_url ?>/B/index.php?adCode=<?= $ad2['link'] ?></td>
                        <td class="text-center">
                                    <span class="memo" onclick="memoModal(<?= $ad2['id'] ?>)">메모 <i class="far fa-edit"></i>
                                    </span>
                        </td>
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
<div class="memo-modal-container modal-public">
</div>

</div>
<!-- content-box-wrap end -->
<style>
    .list-thumb{
        width: 200px;
    }
</style>

<script type="text/javascript">


    $(".check_all_a").click(function (){
        if($(this).prop("checked") == true) {
            $(".a-table").find("input[type=checkbox]").prop("checked", true);
        } else {
            $(".a-table").find("input[type=checkbox]").prop("checked", false);
        }
    });
    $(".check_all_b").click(function (){
        if($(this).prop("checked") == true) {
            $(".b-table").find("input[type=checkbox]").prop("checked", true);
        } else {
            $(".b-table").find("input[type=checkbox]").prop("checked", false);
        }
    });

    function delData(){
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
            if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
            $("#fmemberlist").attr("action", "./setting/ad_delete.php");
            $("#fmemberlist").submit();
            return true;
        }
    }
    function copyToClipboard(text) {
        // Temporary input to hold the text to copy
        var tempInput = document.createElement("input");
        document.body.appendChild(tempInput);
        tempInput.value = text;
        tempInput.select();
        tempInput.setSelectionRange(0, 99999); // For mobile devices

        // Execute copy command
        document.execCommand("copy");

        // Remove temporary input
        document.body.removeChild(tempInput);

        // Optional: Alert or other feedback for successful copy
        alert("해당 링크가 복사되었습니다. \n" + text);
    }

    $(".ad-tab1").click(function (){
        $(".ad-tab1").addClass("active");
        $(".ad-tab2").removeClass("active");
        $(".a-table").show();
        $(".b-table").hide();
        $("#fmemberlist").find("input[type=checkbox]").prop("checked", false);

    });
    $(".ad-tab2").click(function (){
        $(".ad-tab2").addClass("active");
        $(".ad-tab1").removeClass("active");
        $(".b-table").show();
        $(".a-table").hide();
        $("#fmemberlist").find("input[type=checkbox]").prop("checked", false);
    });

    // 모달 닫기 (배경 클릭 포함)
    $(document).on('click', '.modal-bg, .close-modal', function (e) {
        $('.memo-modal-container').hide();
        $('.memo-modal-container').attr('aria-hidden', 'true');
        $('.modal-bg').fadeOut(300);
    });
    function memoModal(id){
        $.ajax({
            type:'post',
            dataType: 'html',
            data: { id: id},
            url:'./setting/memo_modal.php',
            success:function(html){
                $('.memo-modal-container').empty();
                $('.memo-modal-container').html(html);
                $(".modal-bg").show();
                $(".memo-modal-container").fadeIn("300")
            }
        });
    }
    $(".modal-close").click(function (){
        $(".memo-modal-container").fadeOut("300")
        $(".modal-bg").hide();
    });
</script>
