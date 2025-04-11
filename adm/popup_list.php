<?php
include_once('./head.php');
include_once('./default.php');

// 리스트에 출력하기 위한 sql문
$admin_sql = "select * from popup_tbl order by id";
$admin_stt = $db_conn->prepare($admin_sql);
$admin_stt->execute();
?>

<div class="page-header">
    <h4 class="page-title">팝업 설정</h4>
    <div class="btn_fixed_top fe">
        <button type="button" onclick="location.href='./popup_form.php?menu=3&type=insert'"
                class="btn btn-primary">새창관리추가</button>
    </div>
</div>
<!-- page-header end -->

<div class="table-responsive mt-3">
    <table class="table border-bottom" style="min-width: 800px;">
        <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">번호</th>
            <th scope="col">제목</th>
            <th scope="col">시작일시</th>
            <th scope="col">종료일시</th>
            <th scope="col" class="text-center">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $is_data = 0;
        while ($list_row = $admin_stt->fetch()) {
            $is_data = 1;
            ?>
            <tr class="text-center">
                <td>
                    <?= $list_row['id'] ?>
                </td>
                <td>
                    <?= $list_row['popup_name'] ?>
                </td>
                <td>
                    <?= $list_row['start_date'] ?>
                </td>
                <td>
                    <?= $list_row['end_date'] ?>
                </td>
                <td>
                    <a href="./popup_form.php?menu=4&type=modify&id=<?= $list_row['id'] ?>"
                       class="btn btn-default">수정</a>
                    <a href="./ajax/popup_delete.php?id=<?= $list_row['id'] ?>&file=<?= $list_row['file_name'] ?>"
                       onclick="return confirm('선택한 팝업을 삭제하시겠습니까?');" class="btn btn-danger">삭제</a>
                </td>
            </tr>
        <?php } ?>
        <?php if ($is_data != 1) { ?>
        <tr>
            <td colspan="20" class="text-center text-dark bg-light">새창이 없습니다.</td>
        </tr>
        </tbody>
        <?php } ?>
    </table>
</div>

</div>
<!-- box end -->
</div>
<!-- content-box-wrap end -->
