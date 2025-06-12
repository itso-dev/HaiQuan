<?php
include_once('../../head.php');

$category_type = $_POST['category_type'];

if( $category_type == "all"){
    $ad_sql1 = "select * from ad_link_tbl order by id desc";
    $ad_stt1=$db_conn->prepare($ad_sql1);
    $ad_stt1->execute();
} else {
    $ad_sql1 = "select * from ad_link_tbl where type = $category_type order by id desc";
    $ad_stt1=$db_conn->prepare($ad_sql1);
    $ad_stt1->execute();
}

$ad_category_sql = "select * from ad_type_tbl order by regdate desc";
$ad_category_stt = $db_conn->prepare($ad_category_sql);
$ad_category_stt->execute();
$ad_categories = $ad_category_stt->fetchAll();

$engNameArr = [];
foreach ($ad_categories as $category) {
    $engNameArr[$category['id']] = $category['eng_name'];
}
?>


<table class="a-table table border-bottom tab-content" style="min-width: 800px;">
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" rowspan="2" width="5%">
            <input type="checkbox" name="chkall" value="1" class="check_all_a checkbox-list" id="check_all_a">
            <label for="check_all_a"></label>
        </th>
        <!-- <th scope="col" id="mb_list_id" width="10%" class="text-center">구분</th> -->
        <th scope="col" id="mb_list_join" width="20%" class="text-center">코드명</th>
        <th scope="col" id="mb_list_join" width="10%" class="text-center">유입 방문자 수</th>
        <th scope="col" id="mb_list_join" width="10%" class="text-center">문의 수</th>
        <th scope="col" id="mb_list_join" width="10%" class="text-center">등록일</th>
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
            <td headers="mb_list_mng" class="td_mng td_mng_s text-center" onclick="copyToClipboard('<?= $root_url ?>?<?= $engNameArr[$ad1['type']]; ?>=<?= $ad1['link'] ?>')" style="cursor: pointer; color: blue; text-decoration: underline;"
            ><?= $root_url ?>?<?= $engNameArr[$ad1['type']]; ?>=<?= $ad1['link'] ?></td>
            <td class="text-center">
                                    <span class="memo" onclick="memoModal(<?= $ad1['id'] ?>)"><i class="far fa-edit"></i>
                                    </span>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>


<script>

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
        $(".category-modal-container").fadeOut("300")
        $(".memo-modal-container").fadeOut("300")
        $(".modal-bg").hide();
    });

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
</script>
