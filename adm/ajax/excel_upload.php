<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once 'PhpOffice/Psr/autoloader.php';
require_once 'PhpOffice/PhpSpreadsheet/autoloader.php';

require_once '../../db/dbconfig.php';

$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 오류 catch를 위해 설정

function getUploadErrorMessage($errorCode) {
    $errors = [
        UPLOAD_ERR_OK => '정상 업로드',
        UPLOAD_ERR_INI_SIZE => 'php.ini의 upload_max_filesize 제한을 초과했습니다.',
        UPLOAD_ERR_FORM_SIZE => 'HTML 폼에서 지정한 MAX_FILE_SIZE를 초과했습니다.',
        UPLOAD_ERR_PARTIAL => '파일이 일부만 업로드되었습니다.',
        UPLOAD_ERR_NO_FILE => '파일이 업로드되지 않았습니다.',
        UPLOAD_ERR_NO_TMP_DIR => '임시 폴더가 없습니다.',
        UPLOAD_ERR_CANT_WRITE => '디스크에 파일을 쓸 수 없습니다.',
        UPLOAD_ERR_EXTENSION => 'PHP 확장 모듈에 의해 업로드가 중단되었습니다.',
    ];
    return $errors[$errorCode] ?? "알 수 없는 오류 (코드: $errorCode)";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];

    // 업로드 에러 검사
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("파일 업로드 중 오류 발생: " . getUploadErrorMessage($file['error']));
    }

    // 확장자 검사
    $allowed_extensions = ['xls', 'xlsx'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        die("엑셀 파일(xls 또는 xlsx)만 업로드 가능합니다. 현재 확장자: $file_ext");
    }

    try {

        // Excel 파일 읽기
        $spreadsheet = IOFactory::load($file['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        if (empty($data)) {
            die("엑셀에 데이터가 없습니다.");
        }


        $header = array_shift($data);

        // INSERT 준비 (모든 필드 포함)
        $stmt = $db_conn->prepare("
            INSERT INTO contact_tbl (
                write_date, flow, ad_code, name, location, manager_name, phone, writer_ip, sort,
                result_status, consult_fk, counsel_desc, contact_desc, manager_fk
            ) VALUES (
                :write_date, :flow, :ad_code, :name, :location, :manager_name, :phone, :writer_ip, :sort,
                :result_status, :consult_fk, :counsel_desc, :contact_desc, :manager_fk
            )
        ");

        // 한글 → DB 컬럼명 매핑
        $colMap = [
            "생성일"      => "write_date",
            "이름"        => "name",
            "연락처"      => "phone",
            "창업희망지역" => "location",
            "광고코드"    => "ad_code",
            "유입경로"    => "flow",
            "결과"        => "result_status",
            "아이피"      => "writer_ip",
            "업종변경"      => "sort",
            "문의내용"      => "contact_desc"
        ];
        $inserted = 0;
        foreach ($data as $index => $row) {
            $assoc = [];
            foreach ($header as $k => $hanCol) {
                if (isset($colMap[$hanCol])) {
                    $dbCol = $colMap[$hanCol];
                    $assoc[$dbCol] = $row[$k] ?? '';
                }
            }

            // 필수값 체크
            if (empty($assoc['write_date']) || empty($assoc['name'])) {
                echo "❗️ {$index}번째 행: 필수 데이터 없음. 건너뜁니다.<br>";
                continue;
            }

            $stmt->bindValue(':write_date', $assoc['write_date']);
            $stmt->bindValue(':flow', $assoc['flow'] ?? '');
            $stmt->bindValue(':ad_code', $assoc['ad_code'] ?? '');
            $stmt->bindValue(':name', $assoc['name'] ?? '');
            $stmt->bindValue(':location', $assoc['location'] ?? '');
            $stmt->bindValue(':manager_name', ''); // 없는 값은 기본값
            $stmt->bindValue(':phone', $assoc['phone'] ?? '');
            $stmt->bindValue(':writer_ip', $assoc['writer_ip'] ?? '');
            $stmt->bindValue(':result_status', $assoc['result_status'] ?? 'O');
            $stmt->bindValue(':consult_fk', 0);
            $stmt->bindValue(':counsel_desc', '');
            $stmt->bindValue(':contact_desc', $assoc['contact_desc'] ?? '');
            $stmt->bindValue(':manager_fk', 0);
            $stmt->bindValue(':sort', $assoc['sort'] ?? '');

            try {
                $stmt->execute();
                $inserted++;
            } catch (PDOException $e) {
                echo "❌ DB 저장 오류 (행 $index): " . $e->getMessage() . "<br>";
                $stmt->debugDumpParams();
            }
        }

        echo "<script>alert('총 {$inserted}건의 엑셀 데이터를 성공적으로 업로드했습니다.'); window.location.href='../apply_list.php';</script>";

    } catch (Exception $e) {
        die("엑셀 처리 중 오류: " . $e->getMessage());
    }
} else {
    echo "폼에서 파일이 전송되지 않았습니다.";
}
?>
