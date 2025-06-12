<?php
include_once('../../../db/dbconfig.php');

$id = $_POST['id'];

// 차단 아이피
$ad_sql1 = "select * from ad_link_tbl where id = $id";
$ad_stt1=$db_conn->prepare($ad_sql1);
$ad_stt1->execute();
$memo = $ad_stt1 -> fetch();

?>
<div class="head-wrap">
    <span>메모</span>
    <i class="fas fa-times modal-close"></i>
</div>
<div class="body">
    <textarea name="memo" placeholder="메모를 입력해주세요."><?= $memo['memo'] ?></textarea>
    <span class="submit" type="submit">저장</span>
    <span class="tip">저장되었습니다.</span>
</div>
<script>
    $(".modal-close").click(function (){
        $(".memo-modal-container").fadeOut("300")
        $(".modal-bg").hide();
    });

    $(".submit").click(function (){

        var id = <?= $id ?>;
        var data = $("textarea[name=memo]").val();

        $.ajax({
            type:'post',
            url:'./setting/memo_edit.php',
            data:{id:id, data: data},
            success:function(data){
                $(".tip").fadeIn("300")
                setTimeout(function() {
                    $(".tip").fadeOut("300")
                }, 2000);
            }
        });

    });
</script>
