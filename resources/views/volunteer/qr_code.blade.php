<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>我的二维码</title>

    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/css/volunteer.css">
</head>
<body >
<div class="container">
    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__bd">
                <div class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__hd">
                        <img class="weui-media-box__thumb circular_img" src="{{$volunteer['headimgurl']}}" alt="">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">{{$volunteer['name']}}</h4>
                        <p class="weui-media-box__desc">{{$volunteer['phone']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page__bd page__bd_spacing">
        <div class="weui-flex">
            <div class="weui-flex__item">
                学员扫此二维码即可报名学习
            </div>
        </div>
        <div class="weui-flex">
            <div class="weui-flex__item">
                <img width="100%" src="/qrcodes/qrcode_{{$volunteer['id']}}.png">
            </div>
        </div>
    </div>

</div>
<div class="js_dialog" id="iosDialog2">
    <div class="weui-mask"></div>
    <div class="weui-dialog" style="top:11%;background-color: transparent;color: #FFFFFF;max-width: none;width: 100%;">
        <img style="position: absolute;right: 20px" width="100" src="/image/up-arrow22_03.png">
        <p style="margin-top: 40px;font-size: larger;padding-right: 20px;font-weight: 800">转发此二维码</p>
        <p style="font-size: larger;padding-right: 20px;font-weight: 800">学员扫码即可报名</p>
    </div>
</div>

<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">

    $(function(){

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
                desc: '报名页面', // 分享描述
                link: 'http://volunteers.mime.org.cn/volunteer/qr_code?role=doctor&referrer_id={{$volunteer['id']}}', // 分享链接
                imgUrl: 'http://volunteers.mime.org.cn/qrcodes/qrcode_{{$volunteer['id']}}.png', // 分享图标
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