<?php
include_once('head.php');
include_once('default.php');

    // 리스트에 출력하기 위한 sql문
    $email_sql = "SELECT * FROM email_tbl ORDER BY id ASC LIMIT 5";
    $email_stt=$db_conn->prepare($email_sql);
    $email_stt->execute();

    $count_sql = "SELECT COUNT(*) AS total_count FROM email_tbl";
    $count_stt=$db_conn->prepare($count_sql);
    $count_stt->execute();
    $count = $count_stt->fetch();

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

?>
<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/board_form.css" rel="stylesheet" />
        <div class="page-header">
            <h4 class="page-title">발송 이메일 관리</h4>
            <form name="config_form" id="config_form" method="post" action="./ajax/email_insert.php">
                <div class="mt-3">
                    <div class="col-md-5 pr-1">
                        <label class="label-name">이메일 추가</label>
                        <div class="form-group">
                            <input type="email" name="email" value="" id="email" required class="required frm_input form-control">
                            <?php if($count['total_count'] < 5){ ?>
                            <input type="submit" value="추가" class="btn btn-primary">
                            <?php } else { ?>
                            <span id="add_not" class="btn btn-primary">추가</span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
            <div class="mt-3">
                <div class="email-list-wrap">
                    <?php
                    while($email=$email_stt->fetch()){
                    ?>
                    <div class="item">
                        <input type="hidden" name="id" value="<?= $email['id'] ?>"
                        <span><?= $email['email'] ?></span><span class="del-email" onclick="delEmail(<?= $email['id'] ?>)">삭제</span>
                    </div>
                    <?php } ?>
                </div>
                <small class="small">이메일은 최대 5개까지 등록 가능합니다.</small>
            </div>
            <div class="btn-wrap">
                <a href="./apply_list.php?menu=55" class="go-back">뒤로가기</a>
            </div>
        </div>
        <!-- page-header end -->
    </div>
    <!-- box end -->

</div>

<style>
       .form-group {
        display: flex;
        flex-direction: row;
        gap: 4px;
        width: 400px;
    }
    .label-name{
        display: block;
        font-size: 14px;
        color: #213350;
        margin-bottom: 8px;
    }
    .btn-primary {
        border-radius: 4px !important;
    }
    input[type=email]{
        border-radius: 4px;
    }
    .email-list-wrap{
        border: 1px solid #ced4da;
        border-radius: 8px;
        height: 250px;
        overflow: hidden;
        width: 400px;
        padding: 0;
        background-color: #fff;
    }
    .email-list-wrap .item{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        width: 100%;
        background: #fff;
        padding: 8px 12px;
        height: 40px;
        box-sizing: border-box;
        font-size: 16px;
        border-bottom: 1px solid #ced4da;
        background-color: #fff;
    }
    .email-list-wrap .item span{
        color: #505050;
        font-size: 14px;
    }

    .email-list-wrap .item:hover{
        background-color: rgba(216, 227, 243, 0.2);
    }

    .email-list-wrap .item .del-email{
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        font-size: 12px;
        border: 1px solid #FFC1BA;
        padding: 4px 8px;
        border-radius: 4px;
        background-color: #fff;
        color: rgb(255, 109, 93, 0.85);
        font-weight: 500;
        margin-left: 6px;
    }

    .email-list-wrap .item .del-email:hover{
        background-color: #FFF2F0;
        border: 1px solid #FFC1BA;
    }
</style>

<script>
    function delEmail(index){
        $.ajax({
            type:'post',
            url:'./ajax/email_delete.php',
            data:{id:index},
            success: function(html) {
                alert("해당 문의 이메일이 삭제되었습니다.");
                location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error("이메일 삭제 실패:", error);
            }
        });
    }
</script>

<!-- content-box-wrap end -->
