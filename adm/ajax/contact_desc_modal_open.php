<?
include_once('../../db/dbconfig.php');

$wr_id = $_POST['wr_id'];

// 리스트에 출력하기 위한 sql문
$modal_sql = "select * from contact_tbl where id = $wr_id";
$modal_stt=$db_conn->prepare($modal_sql);
$modal_stt->execute();
$row = $modal_stt -> fetch();
?>


<div class="head-wrap">
    <span><?= $row['name'] ?>님 문의 내용</span>
    <i class="fas fa-times modal-close"></i>
</div>
<div class="body">
    <p class="desc"><?= $row['contact_desc'] ?></p>
</div>


<script>
    $(".modal-close").click(function (){
        $(".modal-public").fadeOut("300")
        $(".modal-bg").hide();
    });
</script>
