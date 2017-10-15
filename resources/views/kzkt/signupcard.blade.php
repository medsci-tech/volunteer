<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>完成报名</title>

    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/css/volunteer.css">
</head>
<body >
<div class="container">
    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__hd text_blue" style="text-align: center;font-weight: bold;">听课证</div>
            <div class="weui-panel__bd">
                <div class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__hd">
                        <img class="weui-media-box__thumb" style="vertical-align: middle;" src="/image/kzkt/class.png">
                    </div>
                    <div class="weui-media-box__bd">
                        <p class="weui-media-box__p">
                            欢迎参加2017空中课堂，为方便接收课前通知和课后学习资料，请关注微信公众号。
                        </p>
                    </div>
                </div>
            </div>
            <div class="weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label text_blue">学员姓名</label>
                    <span class="weui-form-preview__value text_blue" id="name"></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label text_blue">学员账号</label>
                    <span class="weui-form-preview__value text_blue" id="phone"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="page__bd page__bd_spacing">
        <div class="weui-flex">
            <div class="weui-flex__item">
                扫码关注空中课堂云课堂公众号
            </div>
        </div>
        <div class="weui-flex">
            <div class="weui-flex__item">
                <img width="100%" src="/image/kzkt/airclass_qrcode.jpg">
            </div>
        </div>
    </div>

    <!--弹窗提示分享--虽然这个功能我是拒绝的-->
    <div class="js_dialog" id="iosDialog2">
    <div class="weui-mask"></div>
    <div class="weui-dialog" style="top:11%;background-color: transparent;color: #FFFFFF;max-width: none;width: 100%">
            <img style="position: absolute;right: 20px" width="100" src="/image/up-arrow22_03.png">
        <p style="margin-top: 40px;font-size: larger;padding-right: 60px;font-weight: 800">转发此听课证给学员</p>
        <p style="font-size: larger;padding-right: 60px;font-weight: 800">即可在线学习</p>

    </div>
    </div>

</div>

<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">
    var referrer_id = "{{$referrer_id}}";
    var request = function (paras) {
        var url = location.href;
        var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
        var paraObj = {};
        for (i = 0; j = paraString[i]; i++) {
            paraObj[j.substring(0, j.indexOf("=")).
            toLowerCase()] = j
                    .substring(j.indexOf("=") + 1, j.length);

        }
        var returnValue = paraObj[paras.toLowerCase()];
        if (typeof(returnValue) == "undefined") {
            return "";
        } else {
            return returnValue;
        }
    };

    var getData = function (id){
        var requestUrl = '/kzkt/findSingleRegister';
        $.ajax({
            type : "get",
            data: {
                'id':id,
                'referrer_id':referrer_id
            },
            dataType : "json",
            url : requestUrl,
            success: function (json) {
                if(json.code == 200) {
                    $('#name').html(json.data.doctor_name);
                    $('#phone').html(json.data.doctor_phone);
                }
            }
        });
    };

    $(function(){
        var id = request("id");
        if(!id) {
            id = '{{$kzkt_signup_doctor_id}}';
        }
        if(id){
            getData(id);
        }

    wx.config(<?php echo $js->config(array('checkJsApi','onMenuShareAppMessage'), false, false) ?>);

    wx.ready(function () {

        wx.checkJsApi({
            jsApiList: [
                'onMenuShareAppMessage'
            ],
            success: function (res) {
//                alert(JSON.stringify(res));
            }
        });

        wx.onMenuShareAppMessage({
            title: '空中课堂', // 分享标题
            desc: '已报名信息', // 分享描述
            link: 'http://volunteers.mime.org.cn/kzkt/viewCard?id='+id, // 分享链接
            imgUrl: 'http://volunteers.mime.org.cn/image/kzkt/airclass.png', // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
//                    alert('已分享');
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
//                    alert('已取消');
            }
        });

    });

    wx.error(function (res) {
        alert("error:" + res.errMsg);
    });

    });

    $(function(){
        var role = request("role");
        var  $iosDialog2 = $('#iosDialog2');
        if(role != 'volunteer'){
            $iosDialog2.hide();
        }
        $iosDialog2.on('click', '.weui-dialog__btn,.weui-mask', function(){
            $(this).parents('.js_dialog').fadeOut(200);
        });
    });
</script>
</body>
</html>