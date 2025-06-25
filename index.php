<?php
include_once('head.php');


//팝업 출력하기 위한 sql문
$popup_sql = "select * from popup_tbl where `end_date` > NOW() order by id ";
$popup_stt = $db_conn->prepare($popup_sql);
$popup_stt->execute();

$today = date("Y-m-d H:i:s");
$view_sql = "insert into view_log_tbl
                              (view_cnt,  reg_date)
                         value
                              (? ,?)";

$db_conn->prepare($view_sql)->execute(
    [1, $today]
);
?>

<link rel="stylesheet" type="text/css" href="css/index.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
      integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<script src='https://www.google.com/recaptcha/api.js'></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- recapture -->
<script src='https://www.google.com/recaptcha/api.js?render='></script>

<!-- GSAP 라이브러리 추가 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollSmoother.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>


<!-- layer popup -->
<?
$arr = array();
$left_count = 0;
$top = 10;
$top2 = 10;
$z_index = 9999;

if ($popup_stt->rowCount() > 0) {
    ?>
    <div class="popup-space">
        <?
        // 팝업 데이터 반복
        while ($popup = $popup_stt->fetch()) {
            $arr[] = $popup['id'];
            ?>
            <div class="layer-popup pc"
                 style="display: none; width: 80%; max-width: <?= $popup['width'] ?>px; height: <?= $popup['height'] ?>px; top: 10%; left: 5%; z-index: <?= $z_index ?>;">
                <div id="agreePopup<?= $popup['id'] ?>" class="agree-popup-frame">
                    <img src="data/popup/<?= $popup['file_name'] ?>" style="height:calc(<?= $popup['height'] ?>px - 36px);"
                         alt="<?= $popup['popup_name'] ?>" onclick="handleClick('<?= $popup['link'] ?>')">
                    <div class="show-chk-wrap">
                        <a href="javascript:AllClose()" class="all-close-btn">전체닫기</a>
                        <div class="show-chk-div">
                            <a href="javascript:todayClose('agreePopup<?= $popup['id'] ?>', 1);" class="today-x-btn">오늘하루닫기</a>
                            <a class="close-popup x-btn">닫기</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layer-popup mobile"
                 style="width: 80%; max-width: <?= $popup['width_mobile'] ?>px; height: <?= $popup['height_mobile'] ?>px; top: 10%; left: 10%; z-index: <?= $z_index ?>;">
                <div id="agreePopup_mo<?= $popup['id'] ?>" class="agree-popup-frame">
                    <img src="data/popup/<?= $popup['file_name_mobile'] ?>" style="height:calc(<?= $popup['height'] ?>px - 36px);"
                         alt="<?= $popup['popup_name'] ?>" onclick="handleClick('<?= $popup['link'] ?>')">
                    <div class="show-chk-wrap">
                        <a href="javascript:AllClose()" class="all-close-btn">전체닫기</a>
                        <div class="show-chk-div">
                            <a href="javascript:todayClose('agreePopup_mo<?= $popup['id'] ?>', 1);" class="today-x-btn">오늘하루닫기</a>
                            <a class="close-popup x-btn">닫기</a>
                        </div>
                    </div>
                </div>
            </div>
            <?
            $z_index -= 1;
            $top += 10;
            $top2 += 15;
        }
        ?>
    </div>
    <?
}
?>

<script>
    // * today popup close
    $(document).ready(function () {
        <?php for ($i = 0; $i < count($arr); $i++) { ?>
        todayOpen('agreePopup<?= $arr[$i] ?>'); // 항상 실행

        // 900px 이하일 때만 실행
        if (window.innerWidth <= 900) {
            todayOpen('agreePopup_mo<?= $arr[$i] ?>');
        }
        <?php } ?>
        $(".close-popup").click(function () {
            $(this).closest('.layer-popup').hide();
        });
    });

    // 창열기
    function todayOpen(winName) {
        var blnCookie = getCookie(winName);
        var obj = eval("window." + winName);
        console.log(blnCookie);
        if (blnCookie !== "expire") {
            $('#' + winName).closest('.layer-popup').show(); 
        } else {
            $('#' + winName).closest('.layer-popup').hide(); 
        }
    }
    // 창닫기
    function todayClose(winName, expiredays) {
        setCookie(winName, "expire", expiredays);
        var obj = eval("window." + winName);
        $('#' + winName).closest('.layer-popup').hide();
    }

    // 쿠키 가져오기
    function getCookie(name) {
        var nameOfCookie = name + "=";
        var x = 0;
        while (x <= document.cookie.length) {
            var y = (x + nameOfCookie.length);
            if (document.cookie.substring(x, y) == nameOfCookie) {
                if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                    endOfCookie = document.cookie.length;
                return unescape(document.cookie.substring(y, endOfCookie));
            }
            x = document.cookie.indexOf(" ", x) + 1;
            if (x == 0)
                break;
        }
        return "";
    }

    // 24시간 기준 쿠키 설정하기
    // 만료 후 클릭한 시간까지 쿠키 설정
    function setCookie(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }

    // 전체 팝업 닫기
    function AllClose() {
        $(".layer-popup").each(function () {
            $(this).animate({
                    opacity: 0      
                }, 200, function() { 
                    $(this).remove();  
            });
        });
    }
</script>

<div id="contact">
    <form class="contact-form" name="contact_form" id="contact_form" method="post" action="contact_write.php" data-aos="fade-up" data-aos-duration="800">
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-1">    
        <input type="hidden" name="writer_ip" value="<?= get_client_ip() ?>" />
        <input type="hidden" name="adCode" value="<?= $adCode ?>" />
        <input type="hidden" name="flow" value="<?= $flow ?>" />

        <div class="contact-form-div">
            <div class="item">
                <div class="label">
                    <label for="name">성함 <span>*</span></label>
                </div>
                <div class="input">
                    <input type="text" id="name" name="name" placeholder="성함" required>
                </div>
            </div>
            <div class="item">
                <div class="label">
                    <label for="phone">연락처 <span>*</span></label>
                </div>
                <div class="input">
                    <input type="tel" name="phone" id="phone-input" placeholder="연락처" required maxlength="11">
                </div>
            </div>
            <div class="item">
                <div class="label">
                    <label for="location">창업 희망 지역 <span>*</span></label>
                </div>
                <div class="input">
                    <input type="text" id="location" name="location" placeholder="창업 희망 지역" required>
                </div>
            </div>
            <div class="item">
                <div class="s-btn-wrap">
                    <input type="hidden" name="sort" value="신규 창업">
                    <div class="form-tab s-tab have">
                        <span>신규 창업</span>
                    </div>
                    <div class="form-tab s-tab">
                        <span>업종 변경</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-wrap">
            <div class="agree-wrap">
                <label class="checkbox-label">
                    <input class="round-checkbox" type="checkbox" id="agree" name="agree" required>
                </label>
                <label for="agree" class="agree"><span class="agree-open">개인정보취급방침(보기)</span> 동의</label>
            </div>
            <input type="hidden" id="g-recaptcha" name="g-recaptcha">
            <input type="submit" value="문의하기" class="c-btn">
        </div>
    </form>
</div>

<script type="text/javascript">

    AOS.init();

    // 팝업 이동 - 새 창
    function handleClick(url) {
        if (url) {
            window.open(url, '_blank');
        }
    }

    document.querySelector("#contact_form").addEventListener("submit", function(e) {
        e.preventDefault(); // 기본 제출 방지

        grecaptcha.ready(function () {
            grecaptcha.execute('', {action: 'contact_form'}).then(function(token) {
                document.getElementById('g-recaptcha-response-1').value = token;
                e.target.submit(); 
            });
        });
    });

</script>

<!--문자 알림-->
<script type="text/javascript">
    function setPhoneNumber(val) {
        var numList = val.split("-");
        document.smsForm.sphone1.value = numList[0];
        document.smsForm.sphone2.value = numList[1];
        if (numList[2] != undefined) {
            document.smsForm.sphone3.value = numList[2];
        }
    }
    function loadJSON() {
        var data_file = "message_send2.php";
        var http_request = new XMLHttpRequest();
        try {
            // Opera 8.0+, Firefox, Chrome, Safari
            http_request = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");

            } catch (e) {

                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Eror
                    alert("지원하지 않는브라우저!");
                    return false;
                }

            }
        }
        http_request.onreadystatechange = function () {
            if (http_request.readyState == 4) {
                // Javascript function JSON.parse to parse JSON data
                var jsonObj = JSON.parse(http_request.responseText);
                if (jsonObj['result'] == "Success") {
                    var aList = jsonObj['list'];
                    var selectHtml = "<select name=\"sendPhone\" onchange=\"setPhoneNumber(this.value)\">";
                    selectHtml += "<option value='' selected>발신번호를 선택해주세요</option>";
                    for (var i = 0; i < aList.length; i++) {
                        selectHtml += "<option value=\"" + aList[i] + "\">";
                        selectHtml += aList[i];
                        selectHtml += "</option>";
                    }
                    selectHtml += "</select>";
                    document.getElementById("sendPhoneList").innerHTML = selectHtml;
                }
            }
        }

        http_request.open("GET", data_file, true);
        http_request.send();
    }

</script>
<?php
include_once('tale.php');
?>
