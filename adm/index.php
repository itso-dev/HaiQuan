<?php
include_once('./head.php');
include_once('./default.php');



// 전체 조회수
$total_view_sql = "SELECT COUNT(view_cnt) FROM view_log_tbl";
$total_view_stt=$db_conn->prepare($total_view_sql);
$total_view_stt->execute();
$total_view = $total_view_stt -> fetch();
// 전체 문의건수
$total_contact_sql = "SELECT COUNT(contact_cnt) FROM contact_log_tbl";
$total_contact_stt=$db_conn->prepare($total_contact_sql);
$total_contact_stt->execute();
$total_contact = $total_contact_stt -> fetch();
// 전체 대기자 수
$total_wait_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%대기%'";
$total_wait_stt=$db_conn->prepare($total_wait_sql);
$total_wait_stt->execute();
$total_wait = $total_wait_stt -> fetch();
// 전체 진행자 수
$total_processing_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%진행%'";
$total_processing_stt=$db_conn->prepare($total_processing_sql);
$total_processing_stt->execute();
$total_processing = $total_processing_stt -> fetch();
// 전체 완료자 수
$total_finish_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%완료%'";
$total_finish_stt=$db_conn->prepare($total_finish_sql);
$total_finish_stt->execute();
$total_finish = $total_finish_stt -> fetch();
// 오늘 조회수
$today_view_sql = "SELECT COUNT(view_cnt) FROM view_log_tbl WHERE DATE(reg_date) = DATE(NOW());";
$today_view_stt=$db_conn->prepare($today_view_sql);
$today_view_stt->execute();
$today_view = $today_view_stt -> fetch();
// 오늘 문의건수
$today_contact_sql = "SELECT COUNT(contact_cnt) FROM contact_log_tbl WHERE DATE(reg_date) = DATE(NOW())";
$today_contact_stt=$db_conn->prepare($today_contact_sql);
$today_contact_stt->execute();
$today_contact = $today_contact_stt -> fetch();
// 오늘 대기자 수
$today_wait_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%대기%' AND DATE(write_date) = DATE(NOW())";
$today_wait_stt=$db_conn->prepare($today_wait_sql);
$today_wait_stt->execute();
$today_wait = $today_wait_stt -> fetch();
// 오늘 진행자 수
$today_processing_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%진행%' AND DATE(write_date) = DATE(NOW())";
$today_processing_stt=$db_conn->prepare($today_processing_sql);
$today_processing_stt->execute();
$today_processing = $today_processing_stt -> fetch();
// 오늘 완료자 수
$today_finish_sql = "SELECT COUNT(id) FROM contact_tbl where result_status like '%완료%' AND DATE(write_date) = DATE(NOW())";
$today_finish_stt=$db_conn->prepare($today_finish_sql);
$today_finish_stt->execute();
$today_finish = $today_finish_stt -> fetch();


// 차트 관련
$stChart = date('Y-m-d', strtotime('-5 days'));
$edChart = date('Y-m-d');
// A, B페이지 결과 배열 초기화
$result = [];
foreach (['A', 'B'] as $page) {
    foreach (range(strtotime($stChart), strtotime($edChart), 86400) as $timestamp) {
        $date = date('Y-m-d', $timestamp);
        $result[$page][$date] = [
            'contact_count' => 0,
            'view_count' => 0
        ];
    }
}

// 총 문의 SQL
$search_contact_sql = "
    SELECT ab_test, DATE(write_date) AS log_date, COUNT(id) AS contact_count
    FROM contact_tbl
    WHERE write_date BETWEEN ? AND ? AND ab_test IN ('A', 'B')
    GROUP BY ab_test, log_date
    ORDER BY log_date";
$search_contact_stt = $db_conn->prepare($search_contact_sql);
$search_contact_stt->execute([$stChart, $edChart]);
$contact_data = $search_contact_stt->fetchAll(PDO::FETCH_ASSOC);

// 조회수 SQL
$search_view_sql ="
    SELECT ab_test, DATE(reg_date) AS log_date, COUNT(view_cnt) AS view_count
    FROM view_log_tbl
    WHERE reg_date BETWEEN ? AND ? AND ab_test IN ('A', 'B')
    GROUP BY ab_test, log_date
    ORDER BY log_date";
$search_view_stt = $db_conn->prepare($search_view_sql);
$search_view_stt->execute([$stChart, $edChart]);
$view_data = $search_view_stt->fetchAll(PDO::FETCH_ASSOC);

// 데이터 병합
foreach ($contact_data as $row) {
    $result[$row['ab_test']][$row['log_date']]['contact_count'] = $row['contact_count'];
}
foreach ($view_data as $row) {
    $result[$row['ab_test']][$row['log_date']]['view_count'] = $row['view_count'];
}

// 차트에 사용할 데이터 준비
$dates = array_keys($result['A']); // 날짜 리스트 (공통일 경우 A나 B 둘 중 아무거나 가능)
$chart_data_A = [
    'dates' => $dates,
    'contact_counts' => array_column($result['A'], 'contact_count'),
    'view_counts' => array_column($result['A'], 'view_count')
];
$chart_data_B = [
    'dates' => $dates,
    'contact_counts' => array_column($result['B'], 'contact_count'),
    'view_counts' => array_column($result['B'], 'view_count')
];


// 담당자 쿼리
$admin_sql = "select * from admin_tbl order by id";
$admin_stt=$db_conn->prepare($admin_sql);
$admin_stt->execute();

$stDate = '';
$endDate = '';
?>

<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/css/home.css" rel="stylesheet" />

    <div class="page-header">
        <h4 class="page-title">방문자 통계</h4>
        <div class="content-container">
            <div class="content-wrap">
                <p class="tit">선택 조회</p>
                <div id="search_form">
                    <div class="choice-wrap">
                        <div class="item">
                            <p>시작 날짜</p>
                            <input type="date" id="stDate" name="stDate" class="date-input form-control" value=""/>
                        </div>
                        <div class="item">
                            <p>종료 날짜</p>
                            <input type="date" id="endDate" name="endDate" class="date-input form-control" value=""/>
                        </div>
                        <div class="item">
                            <input type="submit" id="search" class="btn btn-default search-btn" value="검색하기" />
                        </div>
                    </div>
                </div>
                <div id="search-result" class="search-result">
                    <?php if($stDate != ""){ ?>
                    <p class="tit">검색 결과</p>
                        <div class="view-wrap">
                            <div class="item">
                                <p class="name">방문자 수</p>
                                <p class="cnt">682</p>
                            </div>
                            <div class="item">
                                <p class="name">문의 건수</p>
                                <p class="cnt">1</p>
                            </div>
                            <div class="item">
                                <p class="name">상담 대기자 수</p>
                                <p class="cnt">1</p>
                            </div>
                            <div class="item">
                                <p class="name">상담 진행자 수</p>
                                <p class="cnt">0</p>
                            </div>
                                <div class="item">
                                    <p class="name">상담 완료 수<p>
                                    <p class="cnt">0</p>
                                </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="content-wrap mt-3">
                <p class="tit">방문자 현황</p>
                <div class="py-2"><div id="chart"></div></div>
            </div>
            <div class="content-wrap mt-3">
               <p class="tit">전체</p>
               <div class="view-wrap">
                   <div class="item">
                       <p class="name">방문자 수</p>
                       <p class="cnt"><?=number_format($total_view[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">문의 건수</p>
                       <p class="cnt"><?=number_format($total_contact[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">상담 대기자 수</p>
                       <p class="cnt"><?=number_format($total_wait[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">상담 진행 수</p>
                       <p class="cnt"><?=number_format($total_processing[0])?></p>
                   </div>
                   <div class="item">
                       <p class="name">상담 완료 수<p>
                       <p class="cnt"><?=number_format($total_finish[0])?></p>
                   </div>
               </div>
            </div>
            <div class="content-wrap mt-3">
                <p class="tit">오늘</p>
                <div class="view-wrap">
                    <div class="item">
                        <p class="name">방문자 수</p>
                        <p class="cnt"><?=number_format($today_view[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">문의 건수</p>
                        <p class="cnt"><?=number_format($today_contact[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">상담 대기자 수</p>
                        <p class="cnt"><?=number_format($today_wait[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">상담 진행자 수</p>
                        <p class="cnt"><?=number_format($today_processing[0])?></p>
                    </div>
                    <div class="item">
                        <p class="name">상담 완료 수<p>
                        <p class="cnt"><?=number_format($today_finish[0])?></p>
                    </div>
                </div>
            </div>
            <div class="content-wrap mt-3">
                <p class="tit">담당자 현황</p>

                <div class="admin-list">
                    <div class="item">
                        <p class="admin-name">아이디</p>
                        <p class="admin-name">이름</p>
                        <p class="admin-name">휴대폰번호</p>
                        <p class="admin-name">담당 건수</p>
                        <p class="admin-name">성사 건수</p>
                    </div>
                    <?php
                    while($list_row=$admin_stt->fetch()){

                        $admin_cnt_sql = "
                            SELECT COUNT(DISTINCT c.id) as manager, (
                            
                            SELECT COUNT(DISTINCT cc.id) FROM admin_tbl as aa, contact_tbl as cc WHERE  cc.manager_fk = " .$list_row['id']. " AND cc.result_status like '%완료%'
            
                            ) as result  FROM admin_tbl as a, contact_tbl as c where c.manager_fk = " .$list_row['id'] ;

                        $admin_cnt_stt=$db_conn->prepare($admin_cnt_sql);
                        $admin_cnt_stt->execute();
                        $admin_cnt = $admin_cnt_stt -> fetch();

                    ?>
                    <div class="item">
                        <p class="val"><?=$list_row['login_name']?></p>
                        <p class="val"><?=$list_row['login_id']?></p>
                        <p class="val"><?=$list_row['phone']?></p>
                        <p class="val"><?=$admin_cnt[0]?></p>
                        <p class="val"><?=$admin_cnt[1]?></p>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div id="search-result">

    </div>
</div>
<!-- page-header end -->

<!-- box end -->
</div>
<!-- content-box-wrap end -->
<style>
    .list-thumb{
        width: 200px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>

    // PHP에서 json_encode로 A/B 데이터를 모두 넘겨줌
    const chartData = <?= json_encode([
        'dates' => $dates,
        'A' => $chart_data_A,
        'B' => $chart_data_B
    ]) ?>;

    const categories = chartData.dates;

    // A/B 시리즈 데이터 설정
    const series = [
        {
            name: '총 문의',
            data: chartData.A.contact_counts
        },
        {
            name: '총 방문수(노출)',
            data: chartData.A.view_counts
        },
        // {
        //     name: 'B페이지 - 총 문의',
        //     data: chartData.B.contact_counts
        // },
        // {
        //     name: 'B페이지 - 총 방문수(노출)',
        //     data: chartData.B.view_counts
        // }
    ];

    // ApexCharts 옵션 설정
    var options = {
        chart: {
            type: 'area',
            height: 400,
            toolbar: { show: true }
        },
        series: series,
        xaxis: {
            categories: categories,
        },
        colors: ['#85A6FA', '#4347F0', '#FFB572', '#FF7C43'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "vertical",
                opacityFrom: 0.4,
                opacityTo: 0
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            shared: true,
            intersect: false
        },
        legend: {
            position: 'bottom'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    //선택조회
    $("#search").click(function (){
        var stDate = $("#stDate").val();
        var endDate = $("#endDate").val();
        $.ajax({
            type:'post',
            url:'./ajax/log_search_ajax.php',
            data:{stDate:stDate, endDate:endDate},
            success:function(html){
                $('#search-result').empty();
                $('#search-result').html(html);
            }
        });
    });
</script>

