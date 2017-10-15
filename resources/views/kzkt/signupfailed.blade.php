<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,user-scalable=no">
    <title>报名失败</title>

    <link rel="stylesheet" href="/css/weui.min.css">
</head>
<style type="text/css">
    .p{
        text-align: left;
        margin-left: 5%;
        margin-top: 5px;
        color: #5A5A5A
    }
</style>
<body>
<div class="weui_msg">
    <div class="weui_icon_area">
        <i class="weui_icon_msg weui_icon_info" style="color: #EF4F4F"></i>

    </div>
    <div style="margin-top: 25px">
        <p class="p">很抱歉，因为报名信息提交不完整，此次报名尚未成功。</p>
        <p class="p"><a href="/kzkt/index">请您返回2016空中课堂项目首页</a></p>
        <p class="p">进入“查看报名记录”--点击“报名未成功学员”</p>
        <p class="p">重新编辑补充学员信息--再次提交</p>
    </div>
        
    <div class="weui_opr_area" style="margin-top: 1px">
         <p class="weui_btn_area">
            <input type="button" class="weui_btn weui_btn_warn" id="closeWindow" value="确定">
        </p>    
    </div>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(array('checkJsApi','closeWindow'), false, false) ?>);


    wx.ready(function () {

        wx.checkJsApi({
            jsApiList: [
                'closeWindow'
            ],
            success: function (res) {
//                alert(JSON.stringify(res));
            }
        });

        document.querySelector('#closeWindow').onclick = function () {
            wx.closeWindow();
        };

    });

    wx.error(function (res) {
        alert("error:" + res.errMsg);
    });
</script>
</body>
</html>