<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls; # Xls 파일로 다운받을경우
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx; # Xlsx 파일로 다운받을경우
use PhpOffice\PhpSpreadsheet\Cell\DataType;

include_once('PhpOffice/Psr/autoloader.php');
include_once('PhpOffice/PhpSpreadsheet/autoloader.php');
include_once('../../db/dbconfig.php');

$type = "";
$date = "";

if(isset($_GET['type'])){
    $type = $_GET['type'];
}
if($type == 'all'){
    if(isset($_POST['sch_startdate']) && $_POST['sch_startdate'] != "" &&  isset($_POST['sch_enddate']) && $_POST['sch_enddate'] != ""){
        $startDate = $_POST['sch_startdate'] . " 00:00:00";
        $endDate = $_POST['sch_enddate'] . " 23:59:59";

        $list_sql = "SELECT * FROM contact_tbl 
                     WHERE write_date BETWEEN :startDate AND :endDate 
                     ORDER BY id DESC";
    } else {
        $list_sql = "select * from contact_tbl order by id desc";
    }
    $list_stt=$db_conn->prepare($list_sql);
    if (strpos($list_sql, ':startDate') !== false && strpos($list_sql, ':endDate') !== false) {
        $list_stt->bindParam(':startDate', $startDate);
        $list_stt->bindParam(':endDate', $endDate);
    }
    $list_stt->execute();
} else if ($type == 'select' && isset($_GET['date'])) {
    $date = $_GET['date'];
    $list_sql = "select * from contact_tbl where DATE(write_date) = DATE(:date) order by id DESC";
    $list_stt = $db_conn->prepare($list_sql);
    $list_stt->bindParam(':date', $date);
    $list_stt->execute();
    if ($list_stt->rowCount() == 0) {
        echo "<script>alert('해당 날짜에 문의가 없습니다.'); window.location.href='../contact/contact_list.php';</script>";
        exit;
    }
} else {
    $chk =$_POST['chk'];
    $chk_count = count($_POST['chk']);


    $list_sql = "select * from contact_tbl
                            where
                    id IN (" . implode(',', array_map('intval', $chk)) .")
                            order by id";
    $list_stt=$db_conn->prepare($list_sql);
    $list_stt->execute();
}

// PhpSpreadsheet 객체를 생성합니다.
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet
    ->setCellValue("A1", "생성일")
    ->setCellValue("B1", "이름")
    ->setCellValue("C1", "연락처")
    ->setCellValue("D1", "창업희망지역")
    ->setCellValue("E1", "광고코드")
    ->setCellValue("F1", "문의 타입")
    ->setCellValue("G1", "유입경로")
    ->setCellValue("H1", "결과")
    ->setCellValue("I1", "아이피");

$sheet->getRowDimension('1')->setRowHeight(20);
$line = 2;
while ($list_row = $list_stt->fetch()) {
    $sheet
        ->setCellValueExplicit("A".$line, $list_row['write_date'], DataType::TYPE_STRING)
        ->setCellValue("B".$line, $list_row['name'])
        ->setCellValue("C".$line, $list_row['phone'])
        ->setCellValue("D".$line, $list_row['location'])
        ->setCellValue("E".$line, $list_row['ad_code'])
        ->setCellValue("F".$line, $list_row['contact_type'])
        ->setCellValue("G".$line, $list_row['flow'])
        ->setCellValue("H".$line, $list_row['result_status'])
        ->setCellValue("I".$line, $list_row['writer_ip']);

    $sheet->getRowDimension($line)->setRowHeight(20);
    $line++;
}

// Set column widths
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(30);
$sheet->getColumnDimension('G')->setWidth(10);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="문의_' . date('Y-m-d_H-i-s') . '.xls"'); // filename 수정
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');

?>
