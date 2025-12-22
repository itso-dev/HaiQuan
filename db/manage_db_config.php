<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function mysqlConnectAdmin(){
    $db = null;
    $dsn = "mysql:host=175.126.123.155;port=3306;dbname=SolutionManagement;charset=utf8";

    try {
        $db = new PDO($dsn, "itso", "vldna@!1234");
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "DB 연결 실패: " . $e->getMessage();
    }
    return $db;
}

// 필요하면 여기서 바로 커넥션 생성
$db_conn_admin = mysqlConnectAdmin();

/**
 * DB 연결 테스트
 * - 성공: 서버/DB/시간 출력
 * - 실패: 에러 출력
 */
function testDbConnectionAdmin(PDO $db){
    try {
        $sql = "
            SELECT
                @@hostname AS host,
                DATABASE() AS dbname,
                NOW() AS server_time
        ";
        $stmt = $db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "DB 연결 성공<br>";
        echo "Host: " . htmlspecialchars($row['host'] ?? '') . "<br>";
        echo "DB: " . htmlspecialchars($row['dbname'] ?? '') . "<br>";
        echo "Server Time: " . htmlspecialchars($row['server_time'] ?? '') . "<br>";
    } catch (PDOException $e) {
        echo "DB 테스트 쿼리 실패: " . $e->getMessage();
    }
}

// 테스트 실행 (원할 때만 켜기)
if (isset($_GET['dbtest']) && $_GET['dbtest'] === '1') {
    if ($db_conn_admin instanceof PDO) {
        testDbConnectionAdmin($db_conn_admin);
    } else {
        echo "DB 객체 생성 실패";
    }
}
