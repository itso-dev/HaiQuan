<?php
include_once('../head.php');
include_once('../default.php');

// 리스트에 출력하기 위한 sql문
$ad_category_sql = "select * from ad_type_tbl order by regdate desc";
$ad_category_stt=$db_conn->prepare($ad_category_sql);
$ad_category_stt->execute();
?>
<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/board_list.css" rel="stylesheet" />

<div class="page-header">
    <h4 class="page-title">
        매체 관리
    </h4>
</div>

<form name="fmemberlist" id="fmemberlist" action="/setting/ad_delete.php" method="post">
    <input type="hidden" name="del-id" id="del-id" value=""/>
    <input type="hidden" name="type" value="all"/>
    <div class="btn_fixed_top">
        <div class="top_btn-wrap">
            <!-- <span onclick="delData()" class="btn btn-danger">선택삭제</span> -->
            <a href="ad_list.php?menu=77" id="member_add" class="btn btn-primary">코드 관리</a>
        </div>
    </div>
    <div class="card-body">
        <div id="ad_list" class="table-responsive">
            <table class="a-table table border-bottom tab-content" style="min-width: 800px;">
                <thead>
                <tr>
                    <!-- <th scope="col" id="mb_list_id" width="10%" class="text-center">구분</th> -->
                    <th scope="col" id="mb_list_join" width="20%" class="text-center">매체 명</th>
                    <th scope="col" id="mb_list_join" width="20%" class="text-center">매체 영문명</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">설정 코드 수</th>
                    <th scope="col" id="mb_list_join" width="10%" class="text-center">관리</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while($category=$ad_category_stt->fetch()){
                    $count_sql = "SELECT COUNT(*) AS total_count FROM ad_link_tbl WHERE type = " .$category['id'];

                    $count_stt = $db_conn->prepare($count_sql);
                    $count_stt->execute();
                    $count = $count_stt->fetch();
                    ?>
                    <tr class="bg0">
                        <td headers="mb_list_join" class="td_date text-center"><?=$category['name']?></td>
                        <td headers="mb_list_join" class="td_date text-center"><?=$category['eng_name']?></td>
                        <td class="text-center"><?= $count['total_count'] ?></td>
                        <td class="text-center">
                            <? if($category['name'] != '기타') { ?>
                            <span class="btn btn-default" onclick="categoryModal(<?=$category['id']?>, '<?=$category['name']?>', '<?=$category['eng_name']?>')">수정</span>
                            <span onclick="delData(<?=$category['id']?>)" class="btn btn-danger">삭제</span>
                            <? } ?>
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
<div class="category-modal-container modal-public">
    <div class="head-wrap">
        <span>매체 수정</span>
        <i class="fas fa-times modal-close"></i>
    </div>
    <div class="body">
        <form action="setting/category_setting.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="type" value="modify" >
            <input type="hidden" id="category-id" name="id" value="">
            <input type="text" id="category-name" name="name" placeholder="매체 명" required style="margin-bottom: 10px">
            <input type="text" id="category-eng-name" name="eng_name" placeholder="매체 영문명" required>
            <button class="submit" type="submit">저장하기</button>
        </form>
    </div>
</div>


</div>
<!-- content-box-wrap end -->
<style>
    .list-thumb{
        width: 200px;
    }
</style>

<script type="text/javascript">


    function delData(id){
        if(!confirm("선택한 매체를 삭제하시겠습니까? 소속된 코드들은 기타로 분류됩니다")) {
            return false;
        }
        $("#del-id").val(id);

        $("#fmemberlist").attr("action", "./setting/category_delete.php");
        $("#fmemberlist").submit();
        return true;
    }

    // 모달 닫기 (배경 클릭 포함)
    $(document).on('click', '.modal-bg, .close-modal', function (e) {
        $('.memo-modal-container').hide();
        $('.category-modal-container').hide();
        $('.memo-modal-container').attr('aria-hidden', 'true');
        $('.modal-bg').fadeOut(300);
    });
    function categoryModal(id, name, eng_name){

        $("#category-id").val(id);
        $("#category-name").val(name);
        $("#category-eng-name").val(eng_name);
        $(".modal-bg").show();
        $(".category-modal-container").fadeIn("300")
    }
    $(".modal-close").click(function (){
        $(".category-modal-container").fadeOut("300")
        $(".memo-modal-container").fadeOut("300")
        $(".modal-bg").hide();
    });
</script>
