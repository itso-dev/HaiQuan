<?php
include_once('../../db/dbconfig.php');

// 차단 아이피
$ip_sql = "SELECT * FROM ip_block_tbl";
$ip_stt = $db_conn->prepare($ip_sql);
$ip_stt->execute();
?>
<div class="head-wrap">
    <span>차단 아이피 관리</span>
    <i class="fas fa-times modal-close"></i>
</div>
<div class="ip-list">
    <div class="ip-table">
        <table>
            <thead>
            <tr>
                <th width="30%">아이피</th>
                <th width="10%">문의 수</th>
                <th width="15%">차단 후 접속 수</th>
                <th width="25%">차단일</th>
                <th width="15%"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while($ip=$ip_stt->fetch()){
                $contact_sql = "SELECT COUNT(*) AS total_count FROM contact_tbl where writer_ip = '". $ip['ip']."'";
                $contact_stt = $db_conn->prepare($contact_sql);
                $contact_stt->execute();
                $contact = $contact_stt -> fetch();
                ?>
                <tr>
                    <td><?= $ip['ip'] ?></td>
                    <td><?= $contact[0] ?></td>
                    <td><?= $ip['view'] ?></td>
                    <td><?= $ip['regdate'] ?></td>
                    <td>
                        <input type="hidden" name="ip" value="<?= $ip['id'] ?>" />
                        <span class="ip-del">차단해제</span>
                    </td>
                </tr>
            <? } ?>
            </tbody>
        </table>
    </div>
</div>



<script>
    $(".modal-close").click(function (){
        $(".ip-modal-container").fadeOut("300")
        $(".modal-bg").hide();
    });

    $(".ip-del").click(function (){
        var id = $(this).siblings('input[type=hidden]').val();
        var row = $(this).closest('tr'); // 현재 클릭된 버튼의 tr 요소를 저장

        $.ajax({
            type:'post',
            url:'./ajax/ip_del_ajax.php',
            data:{id:id},
            success:function(data){
                row.remove();
            }
        });

    });
</script>
