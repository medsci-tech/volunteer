<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>医学志愿者</title>

    <link rel="stylesheet" href="/css/medtech.css">
    <link rel="stylesheet" href="/css/vendor/swiper-3.3.0.min.css">

</head>
<body style="background-color: #f5f5f5">

<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img class="img-responsive" src="/image/activity/u6_1.jpg" onclick="goHuangpu()">
        </div>
        <div class="swiper-slide">
            <img class="img-responsive" src="/image/activity/u6_2.jpg" onclick="goAirClass()">
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>

<div class="row" id="index">
    <div id="kzkt" class="small-4 column" style="display:none;">
        <a href="/kzkt/index">
            <div >
                <img src="/image/activity/u11.png">
                <img src="/image/activity/u13.png" alt="">
                <p>空中课堂</p>
            </div>
        </a>
    </div>
    <div id="hpxt" class="small-4 column" style="display:none;">
        <a href="/hpxt/index">
            <div >
                <img src="/image/activity/u19.png">
                <img src="/image/activity/u21.png" alt="">
                <p>黄埔学堂</p>
            </div>
        </a>
    </div>
    <div id="yszs" class="small-4 column" style="display:none;">
        <a href="">
            <div >
                <img src="/image/activity/u27.png">
                <img src="/image/activity/u29.png" alt="">
                <p>医师助手</p>
            </div>
        </a>
    </div>
    <div id="jzxtl" class="small-4 column" style="display:none;">
        <a href="">
            <div >
                <img src="/image/activity/u35.png">
                <img src="/image/activity/u37.png" alt="">
                <p>甲状腺病例讨论</p>
            </div>
        </a>
    </div>
    <div id="doctor" class="small-4 column" style="display:none;">
        <a href="">
            <div >
                <img src="/image/activity/u43.png">
                <img src="/image/activity/u45.png" alt="">
                <p>E名医下基层</p>
            </div>
        </a>
    </div>
    <div id="jzxgkk" class="small-4 column end" style="display:none;">
        <a href="">
            <div >
                <img src="/image/activity/u51.png">
                <img src="/image/activity/u53.png" alt="">
                <p>甲状腺公开课</p>
            </div>
        </a>
    </div>
</div>


<script src="https://cdn.bootcss.com/Swiper/3.3.0/js/swiper.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 1,
        paginationClickable: true,
        loop: true,
        visiblilityFullfit: true,
        autoplay: 5000
    });

    function goAirClass() {
        window.location.href = '/kzkt/index';
    }

    function goHuangpu() {
        window.location.href = '/hpxt/index';
    }

    $(document).ready(function(){
        var requestUrl = '/activity/view';
        $.ajax({
            url: requestUrl,
            type: "get",
            dataType: "json",
            success: function (json) {
                if (json.result == '-1') {
                    window.location.href = '/home/error';
                }

                if (json.result == '-1') {
                    window.location.href = '/activity/none';
                }

                if (json.result == '1') {
                    for(var i = 0; i<json.activities.length; i++) {
                        var title = json.activities[i].title;
                        //var title = json.activities[i];
                        switch (title) {
                            case '空中课堂':
                                $("#kzkt").show();
                                break;
                            case '黄埔学堂':
                                $("#hpxt").show();
                                break;
                            case '医师助手':
                                $("#yszs").show();
                                break;
                            case '甲状腺病例讨论':
                                $("#jzxtl").show();
                                break;
                            case 'E名医下基层':
                                $("#doctor").show();
                                break;
                            case '甲状腺公开课':
                                $("#jzxgkk").show();
                                break;
                        }
                    }
                }
            },
            error: function (xhr, status, errorThrown) {
                alert("Sorry, there was a problem!");
            }
        });

    });
</script>
</body>
</html>