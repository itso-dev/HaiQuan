<?php
    include_once('./head.php');
    include_once('./default.php');

    $sname = "";
    $sid = "";
    $sid = $_GET['sid'];

    switch ($sid){
        case 1:
            $sname =  "마케팅 신청";
            break;
        case 2:
            $sname =  "부가서비스 신청";
            break;
        case 3:
            $sname =  "기타 오류 문의";
            break;
        case 4:
            $sname =  "서비스 커스텀 신청";
            break;
    }
?>

<link rel="stylesheet" type="text/css" href="css/board_form.css" rel="stylesheet" />

    <div class="page-header">
        <h4 class="page-title"><?php echo $sname ?></h4>
        <form>
           <div class="form-container">
               <div class="input-wrap">
                   <div class="label-name">
                       <p>업체명</p>
                   </div>
                   <div class="input-wrap">
                       <input type="text" name="name" class="form-control" placeholder="업체명을 적어주세요." required>
                   </div>
               </div>
            <div class="input-wrap">
                   <div class="label-name">
                       <p>전화번호</p>
                   </div>
                   <div class="input-wrap">
                       <input type="text" name="phone" class="form-control" placeholder="ex) 02-1234-1234" required>
                   </div>
               </div>
               <div class="input-container">
                   <div class="label-name">
                       <p>문의사항</p>
                   </div>
                   <div class="input-wrap">
                        <textarea name="desc" class="form-control"></textarea>
                   </div>
               </div>
               <?php if($sid == 1){ ?>
               <div class="input-container">
                   <div class="label-name">
                       <p></p>
                   </div>
                   <div class="input-wrap chk-wrap">
                       <div class="chk-item">
                           <input type="checkbox" class="checkbox-list" name="type" id="chk1" required><label for="chk1">무료 컨설팅</label>
                           무료 컨설팅
                       </div>
                       <div class="chk-item">
                           <input type="checkbox" class="checkbox-list" name="type" id="chk2" required><label for="chk2">유료 컨설팅</label>
                           유료 컨설팅(가격 협의)
                       </div>
                   </div>
               </div>
                <?php } ?>
               <?php if($sid == 2){ ?>
                   <div class="input-container">
                       <div class="label-name">
                           <p></p>
                       </div>
                       <div class="input-wrap chk-wrap chk-wrap2">
                           <div class="chk-item">
                               <input type="checkbox" class="checkbox-list" name="type" id="chatbot" required><label for="chatbot">챗봇 서비스</label>
                               챗봇 서비스
                           </div>
                           <div class="chk-item">
                               <input type="checkbox" class="checkbox-list" name="type" id="textservice" required><label for="textservice">알림 문자 서비스</label>
                               알림 문자 서비스
                           </div>
                           <div class="chk-item">
                               <input type="checkbox" class="checkbox-list" name="type" id="lawedu" required><label for="lawedu">법정의무교육</label>
                               법정 의무 교육
                           </div>
                           <div class="chk-item">
                               <input type="checkbox" class="checkbox-list" name="type" id="consulting" required><label for="consulting">임직원 역량 강화 교육 및 컨설팅</label>
                               임직원 역량 강화 교육 및 컨설팅
                           </div>
                       </div>
                   </div>
               <?php } ?>
               <?php if($sid == 3 || $sid == 4){ ?>
               <div class="input-container">
                    <input type="file" name="file" class="form-control" value=""/>
               </div>
               <?php } ?>
               <div class="btn-wrap">
                   <a href="service_center.php?menu=10" class="btn go-back">이전</a>
                   <input type="submit" class="submit" value="상담신청">
               </div>
           </div>
        </form>
    </div>
    <!-- box end -->
</div>
<!-- content-box-wrap end -->

<script type='text/javascript'>


</script>
