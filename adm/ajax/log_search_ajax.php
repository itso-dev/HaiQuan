<?php
include_once('../../db/dbconfig.php');

$stDate = $_POST['stDate'];
$edDate = $_POST['endDate'];

//문의 건수
$search_contact_sql = "SELECT COUNT(contact_cnt) FROM contact_log_tbl WHERE reg_date BETWEEN '$stDate' AND '$edDate';";
$search_contact_stt=$db_conn->prepare($search_contact_sql);
$search_contact_stt->execute();
$search_contact = $search_contact_stt -> fetch();
//조회수
$search_view_sql = "SELECT COUNT(view_cnt) FROM view_log_tbl WHERE reg_date BETWEEN '$stDate' AND '$edDate';";
$search_view_stt=$db_conn->prepare($search_view_sql);
$search_view_stt->execute();
$search_view = $search_view_stt -> fetch();
// 대기자 수
$search_wait_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%대기%' AND write_date BETWEEN '$stDate' AND '$edDate'";
$search_wait_stt=$db_conn->prepare($search_wait_sql);
$search_wait_stt->execute();
$search_wait = $search_wait_stt -> fetch();
// 진행자 수
$search_processing_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%진행%' AND write_date BETWEEN '$stDate' AND '$edDate'";
$search_processing_stt=$db_conn->prepare($search_processing_sql);
$search_processing_stt->execute();
$search_processing = $search_processing_stt -> fetch();
// 완료 수
$search_finish_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%완료%' AND write_date BETWEEN '$stDate' AND '$edDate'";
$search_finish_stt=$db_conn->prepare($search_finish_sql);
$search_finish_stt->execute();
$search_finish = $search_finish_stt -> fetch();


// 날짜별 데이터 초기화
$result = [];
foreach (range(strtotime($stDate), strtotime($edDate), 86400) as $timestamp) {
    $date = date('Y-m-d', $timestamp);
    $result[$date] = [
        'contact_count' => 0,
        'view_count' => 0,
        'wait_count' => 0,
        'processing_count' => 0,
        'finish_count' => 0,
    ];
}

// 문의건수 가져오기
$search_contact_sql = "
    SELECT DATE(reg_date) AS log_date, COUNT(contact_cnt) AS contact_count
    FROM contact_log_tbl
    WHERE reg_date BETWEEN ? AND ?
    GROUP BY log_date
    ORDER BY log_date";
$search_contact_stt = $db_conn->prepare($search_contact_sql);
$search_contact_stt->execute([$stDate, $edDate]);
foreach ($search_contact_stt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $result[$row['log_date']]['contact_count'] = $row['contact_count'];
}

// 조회수 가져오기
$search_view_sql = "
    SELECT DATE(reg_date) AS log_date, COUNT(view_cnt) AS view_count
    FROM view_log_tbl
    WHERE reg_date BETWEEN ? AND ?
    GROUP BY log_date
    ORDER BY log_date";
$search_view_stt = $db_conn->prepare($search_view_sql);
$search_view_stt->execute([$stDate, $edDate]);
foreach ($search_view_stt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $result[$row['log_date']]['view_count'] = $row['view_count'];
}

// 상담대기 수 가져오기
$search_wait_sql = "
    SELECT DATE(write_date) AS log_date, COUNT(id) AS wait_count
    FROM contact_tbl
    WHERE result_status LIKE '%대기%' AND write_date BETWEEN ? AND ?
    GROUP BY log_date
    ORDER BY log_date";
$search_wait_stt = $db_conn->prepare($search_wait_sql);
$search_wait_stt->execute([$stDate, $edDate]);
foreach ($search_wait_stt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $result[$row['log_date']]['wait_count'] = $row['wait_count'];
}

// 상담진행 수 가져오기
$search_processing_sql = "
    SELECT DATE(write_date) AS log_date, COUNT(id) AS processing_count
    FROM contact_tbl
    WHERE result_status LIKE '%진행%' AND write_date BETWEEN ? AND ?
    GROUP BY log_date
    ORDER BY log_date";
$search_processing_stt = $db_conn->prepare($search_processing_sql);
$search_processing_stt->execute([$stDate, $edDate]);
foreach ($search_processing_stt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $result[$row['log_date']]['processing_count'] = $row['processing_count'];
}

// 상담완료 수 가져오기
$search_finish_sql = "
    SELECT DATE(write_date) AS log_date, COUNT(id) AS finish_count
    FROM contact_tbl
    WHERE result_status LIKE '%완료%' AND write_date BETWEEN ? AND ?
    GROUP BY log_date
    ORDER BY log_date";
$search_finish_stt = $db_conn->prepare($search_finish_sql);
$search_finish_stt->execute([$stDate, $edDate]);
foreach ($search_finish_stt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $result[$row['log_date']]['finish_count'] = $row['finish_count'];
}
?>

<div class="result-wrap">
    <div class="content-wrap">
        <p class="tit">검색 결과</p>
        <div class="view-wrap">
            <div class="item">
                <p class="name">방문자 수</p>
                <p class="cnt"><?=number_format($search_view[0])?></p>
            </div>
            <div class="item">
                <p class="name">문의건 수</p>
                <p class="cnt"><?=number_format($search_contact[0])?></p>
            </div>
            <div class="item">
                <p class="name">상담 대기 수</p>
                <p class="cnt"><?=number_format($search_wait[0])?></p>
            </div>
            <div class="item">
                <p class="name">상담 진행 수</p>
                <p class="cnt"><?=number_format($search_processing[0])?></p>
            </div>
            <div class="item">
                <p class="name">상담 완료 수</p>
                <p class="cnt"><?=number_format($search_finish [0])?></p>
            </div>
        </div>
    </div>
    <div class="content-wrap">
        <p class="tit">일자별 검색 결과</p>
        <table class="result-table">
            <thead>
            <tr>
                <th>일자</th>
                <th>방문자 수</th>
                <th>문의건 수</th>
                <th>상담대기 수</th>
                <th>상담진행 수</th>
                <th>상담완료 수</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $date => $counts): ?>
            <tr>
                <td><?= $date ?></td>
                <td><?= $counts['view_count'] ?></td>
                <td><?= $counts['contact_count'] ?></td>
                <td><?= $counts['wait_count'] ?></td>
                <td><?= $counts['processing_count'] ?></td>
                <td><?= $counts['finish_count'] ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
    

