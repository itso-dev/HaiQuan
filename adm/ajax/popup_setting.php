<?
include_once('../head.php');

$reg_date = date("Y-m-d H:i:s");

$id = $_POST['id'];
$type = $_POST['type'];
$popup_name = $_POST['popup_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$width = $_POST['width'];
$height = $_POST['height'];
$width2 = $_POST['width2'];
$height2 = $_POST['height2'];
$link = $_POST['link'];

//입력
if ($type == 'insert') {
     if (isset($_FILES['img_upload1']) && $_FILES['img_upload1']['name'] != "") {
          $file1 = $_FILES['img_upload1'];
          $upload_directory = $_SERVER["DOCUMENT_ROOT"] . '/data/popup/';
          $ext_str = "jpg,gif,png";
          $allowed_extensions = explode(',', $ext_str);
          $max_file_size = 5242880;
          $ext1 = substr($file1['name'], strrpos($file1['name'], '.') + 1);

          // 확장자 체크
          if (!in_array($ext1, $allowed_extensions)) {
               echo "<script type='text/javascript'>";
               echo "alert('이미지 파일만 업로드 하실 수 있습니다.'); history.back()";
               echo "</script>";
          }
          // 파일 크기 체크
          if ($file1['size'] >= $max_file_size) {
               echo "<script type='text/javascript'>";
               echo "alert('5MB 이하의 파일만 업로드 가능합니다.'); history.back()";
               echo "</script>";
          }

          //파일명 변경
          $chg_file1 = date("Y-m-d H:i:s:u") .$file1['name'];
          $chg_file1 = str_replace(' ', '', $chg_file1);

          // 모바일 이미지가 있을 경우
          if (isset($_FILES['img_upload2']) && $_FILES['img_upload2']['name'] != "") {
               $file2 = $_FILES['img_upload2'];
               $ext2 = substr($file2['name'], strrpos($file2['name'], '.') + 1);
               // 확장자 체크
               if (!in_array($ext2, $allowed_extensions)) {
                    echo "<script type='text/javascript'>";
                    echo "alert('이미지 파일만 업로드 하실 수 있습니다.'); history.back()";
                    echo "</script>";
               }
               // 파일 크기 체크
               if ($file2['size'] >= $max_file_size) {
                    echo "<script type='text/javascript'>";
                    echo "alert('5MB 이하의 파일만 업로드 가능합니다.'); history.back()";
                    echo "</script>";
               }
               //파일명 변경
               $chg_file2 = date("Y-m-d H:i:s:u") .$file2['name'];
               $chg_file2 = str_replace(' ', '', $chg_file2);
          } else {
               $file2 = $_FILES['img_upload1'];
               $chg_file2 = $chg_file1;
          }

          if (move_uploaded_file($file1['tmp_name'], $upload_directory . $chg_file1)) {
               move_uploaded_file($file2['tmp_name'], $upload_directory . $chg_file2);

               $insert_sql = "insert into popup_tbl
                                        (popup_name, link, start_date, end_date, width,
                                        height, file_name, reg_date, file_name_mobile, width_mobile, height_mobile)
                                   value
                                        (?, ?, ?, ?, ?,
                                        ?, ?, ?, ?, ?, ?)";

               $db_conn->prepare($insert_sql)->execute(
                   [
                       $popup_name,
                       $link,
                       $start_date,
                       $end_date,
                       $width,
                       $height,
                       $chg_file1,
                       $reg_date,
                       $chg_file2,
                       $width2,
                       $height2,
                   ]
               );

               echo "<script type='text/javascript'>";
               echo "alert('등록 되었습니다.'); location.href='../popup_list.php?menu=3&'";
               echo "</script>";
          }
     }
}

//수정
if ($type == 'modify') {

     $file1 = "";
     $file2 = "";
     $upload_directory = $_SERVER["DOCUMENT_ROOT"] . '/data/popup/';
     $ext_str = "jpg,gif,png";
     $allowed_extensions = explode(',', $ext_str);
     $max_file_size = 5242880;
     $file1_sql = "";
     $file2_sql = "";
     if (isset($_FILES['img_upload1']) && $_FILES['img_upload1']['name'] != "") {
          $file1 = $_FILES['img_upload1'];
          $ext1 = substr($file1['name'], strrpos($file1['name'], '.') + 1);

          // 확장자 체크
          if (!in_array($ext1, $allowed_extensions)) {
               echo "<script type='text/javascript'>";
               echo "alert('이미지 파일만 업로드 하실 수 있습니다.'); history.back()";
               echo "</script>";
          }
          // 파일 크기 체크
          if ($file1['size'] >= $max_file_size) {
               echo "<script type='text/javascript'>";
               echo "alert('5MB 이하의 파일만 업로드 가능합니다.'); history.back()";
               echo "</script>";
          }
          //파일명 변경
          $chg_file1 = date("Y-m-d H:i:s:u") .$file1['name'];
          $chg_file1 = str_replace(' ', '', $chg_file1);
          if (move_uploaded_file($file1['tmp_name'], $upload_directory . $chg_file1)) {
               $file1_sql = " file_name = '$chg_file1',";
          }
     } else {
          $file1_sql = "";
     }

     // 모바일 이미지가 있을 경우
     if (isset($_FILES['img_upload2']) && $_FILES['img_upload2']['name'] != "") {
          $file2 = $_FILES['img_upload2'];
          $ext2 = substr($file2['name'], strrpos($file2['name'], '.') + 1);
          // 확장자 체크
          if (!in_array($ext2, $allowed_extensions)) {
               echo "<script type='text/javascript'>";
               echo "alert('이미지 파일만 업로드 하실 수 있습니다.'); history.back()";
               echo "</script>";
          }
          // 파일 크기 체크
          if ($file2['size'] >= $max_file_size) {
               echo "<script type='text/javascript'>";
               echo "alert('5MB 이하의 파일만 업로드 가능합니다.'); history.back()";
               echo "</script>";
          }
          //파일명 변경
          $chg_file2 = date("Y-m-d H:i:s:u") .$file2['name'];
          $chg_file2 = str_replace(' ', '', $chg_file2);
          if (move_uploaded_file($file2['tmp_name'], $upload_directory . $chg_file2)){
               $file2_sql = " file_name_mobile = '$chg_file2',";
          }
     } else {
          $file2_sql = "";
     }

     $modify_sql = "update popup_tbl
               set 
               popup_name = '$popup_name',
               link = '$link',
               start_date = '$start_date',
               end_date = '$end_date',
               width = '$width',
               height = '$height',
               ".$file1_sql."
               ".$file2_sql."
               width_mobile = '$width2',
               height_mobile = '$height2'
                    where
               id = $id";

     $updateStmt = $db_conn->prepare($modify_sql);
     $updateStmt->execute();

     $count = $updateStmt->rowCount();


     echo "<script type='text/javascript'>";
     echo "alert('수정을 완료했습니다.'); location.href='../popup_form.php?menu=4&type=modify&id=$id'";
     echo "</script>";
}
?>
