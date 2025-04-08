<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls; # Xls 파일로 다운받을경우
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx; # Xlsx 파일로 다운받을경우

include_once('PhpOffice/Psr/autoloader.php');
include_once('PhpOffice/PhpSpreadsheet/autoloader.php');
include_once('../../db/dbconfig.php');

$type = "";
$date = "";

if(isset($_GET['type'])){
    $type = $_GET['type'];
}
if($type == 'all'){
    $list_sql = "select * from contact_tbl order by id desc";
    $list_stt=$db_conn->prepare($list_sql);
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
    ->setCellValue("A1", "등록일")
    ->setCellValue("B1", "유입 경로")
    ->setCellValue("C1", "A/B 테스트")
    ->setCellValue("D1", "광고 코드")
    ->setCellValue("E1", "이름")
    ->setCellValue("F1", "연락처")
    ->setCellValue("G1", "이메일")
    ->setCellValue("H1", "창업희망지역")
    ->setCellValue("I1", "창업예상비용")
    ->setCellValue("J1", "점포보유여부")
    ->setCellValue("K1", "담당자")
    ->setCellValue("L1", "문의내용")
    ->setCellValue("M1", "결과")
    ->setCellValue("N1", "아이피");

$sheet->getRowDimension('1')->setRowHeight(20);
$line = 2;
while ($list_row = $list_stt->fetch()) {
    $sheet
        ->setCellValue("A".$line, $list_row['write_date'])
        ->setCellValue("B".$line, $list_row['flow'])
        ->setCellValue("C".$line, $list_row['ab_test'])
        ->setCellValue("D".$line, $list_row['ad_code'])
        ->setCellValue("E".$line, $list_row['name'])
        ->setCellValue("F".$line, $list_row['phone'])
        ->setCellValue("G".$line, $list_row['email'])
        ->setCellValue("H".$line, $list_row['location'])
        ->setCellValue("I".$line, $list_row['cost'])
        ->setCellValue("J".$line, $list_row['store'])
        ->setCellValue("K".$line, $list_row['manager_name'])
        ->setCellValue("L".$line, $list_row['contact_desc'])
        ->setCellValue("M".$line, $list_row['result_status'])
        ->setCellValue("N".$line, $list_row['writer_ip']);

    $sheet->getRowDimension($line)->setRowHeight(20);
    $line++;
}

// Set column widths
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(50);
$sheet->getColumnDimension('N')->setWidth(15);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="문의_' . date('Y-m-d_H-i-s') . '.xls"'); // filename 수정
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
?>
