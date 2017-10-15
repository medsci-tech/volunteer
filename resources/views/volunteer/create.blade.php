<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>注册账号</title>
    <link rel="stylesheet" href="/css/weui.min.css">
    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/css/volunteer.css">
    <!-- 引入 jQuery 库 -->
    <script src="/js/jquery-1.11.1.min.js"></script>
</head>

<body class="body-gray" ontouchstart>
<div class="container">
    <div class="page">

        <form action="" method="post" style="margin-bottom: 2em">

            <div class="weui_cells_title">请填写您的注册信息</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">姓名</label>
                    </div>
                    <div class="weui-cell__bd ">
                        <input name="name" id="name"  type="text" class="weui-input" required placeholder="请输入姓名">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">手机号</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <input class="weui-input" name="phone" id="phone" type="tel" required placeholder="请输入手机号">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>
                <div class="weui-cell weui-cell_vcode">
                    <div class="weui-cell__hd weui-cell__hd">
                        <label class="weui-label">验证码</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" id="code" name="code" required placeholder="验证码">
                    </div>
                    <div class="weui-cell__ft">
                        <button type="button" class="weui-vcode-btn" onclick="turnTo();" id="get_code">获取验证码</button>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">邮箱</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <input name="mail" type="email" id="email" required class="weui-input" placeholder="请输入邮箱">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>

                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">initial</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <input name="number" type="text" id="number" required class="weui-input" placeholder="请输入员工编码">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>

            </div>

            <p id="txt_warn" style="text-align:center;color:red"></p>
            <button id="btn_save" type="button" class="weui-btn weui-btn_primary" style="width: 90%;margin-top: 15px">注册</button>
            <input type="hidden" id="unit" value="-1">
        </form>
    </div>
</div>
</body>
</html>

<script>
    var validateMobile = function() {
        var mobile = document.getElementById('phone').value;
        var code = document.getElementById('code').value;
        if (mobile.length == 0) {
            document.getElementById('txt_warn').innerText = '请输入手机号码！';
            document.getElementById('phone').focus();
            return false;
        }
        if (mobile.length != 11) {
            document.getElementById('txt_warn').innerText = '请输入有效的手机号码！';
            document.getElementById('phone').focus();
            return false;
        }

        var myreg = /^1[35789]\d{9}$/;
        if (!myreg.test(mobile)) {
            document.getElementById('txt_warn').innerText = '请输入有效的手机号码！';
            document.getElementById('phone').focus();
            return false;
        }
        return true;
    };

    var verifyPassword = function() {
        var password = document.getElementById('password').value;
        var repassword = document.getElementById('repassword').value;

        if (password.length == 0) {
            document.getElementById('txt_warn').innerText = '请输入密码！';
            document.getElementById('password').focus();
            return false;
        }

        if (password.length < 6) {
            document.getElementById('txt_warn').innerText = '密码不足6位！';
            document.getElementById('password').focus();
            return false;
        }

        if (repassword.length == 0) {
            document.getElementById('txt_warn').innerText = '请确认密码！';
            document.getElementById('repassword').focus();
            return false;
        }

        if (repassword.length < 6 || repassword.length >10) {
            document.getElementById('txt_warn').innerText = '密码介于6-10位！';
            document.getElementById('repassword').focus();
            return false;
        }

        if (password != repassword) {
            document.getElementById('txt_warn').innerText = '两次输入密码不一致！';
            document.getElementById('repassword').focus();
            return false;
        }

        return true;
    };

    $(document).ready(function(){

        $("#btn_save").on('click',function () {
            var result = true;
            var name = document.getElementById('name').value;
            if (name.length == 0) {
                document.getElementById('txt_warn').innerText = '请输入姓名！';
                document.getElementById('name').focus();
                result = false;
                return result;
            }

            if (!validateMobile()) {
                result = false;
                return result;
            }

//                if (!verifyPassword()) {
//                    result = false;
//                    return result;
//                }

            var code = $("#code").val();
            if (code.length == 0) {
                document.getElementById('txt_warn').innerText = '请输入验证码！';
                document.getElementById('code').focus();
                return false;
            }

            if (code.length != 6) {
                document.getElementById('txt_warn').innerText = '请输入有效的验证码！';
                document.getElementById('code').focus();
                return false;
            }

            if (isNaN(code)) {
                document.getElementById('txt_warn').innerText = '请输入有效的验证码！';
                document.getElementById('code').focus();
                return false;
            }


//            if($("#unit").val() == '-1'){
//                document.getElementById('txt_warn').innerText = '请选择公司！';
//                result = false;
//                return result;
//            }
            $("#unit").val(1); //  初始化公司
            if($("#unit").val() == '1'){
                var number = document.getElementById('number').value;
                if(number.length == 0) {
                    document.getElementById('txt_warn').innerText = '诺和员工请填写工号！';
                    result = false;
                    return result;
                }
            }

            if(result == true) {
                var name = $("#name").val();
                var phone = $("#phone").val();
                var code = $("#code").val();
                var unit_id = $("#unit").val();
                var number = "";
                var email = $("#email").val();
                if(unit_id == '1') {
                    number = $("#number").val();
                    //email = number + '@novonordisk.com';
                }
                document.getElementById('txt_warn').innerText = '正在提交！';
                var requestUrl = '/volunteer/store-self';
                $.ajax({
                    url: requestUrl,
                    data: {
                        name: name,
                        phone: phone,
                        code: code,
//                            password:password,
                        unit_id:unit_id,
                        number:number,
                        email:email
                    },
                    type: "post",
                    success: function (json) {
                        if (json.result == '1') {
                            document.getElementById('txt_warn').innerText = '提交成功！';
                            window.location.href = '/volunteer/success';
                        }

                        if (json.result == '-1') {
                            document.getElementById('txt_warn').innerText = '提交失败！原因：'+json.message;
                            //window.location.href = '/home/error?message='+json.message;
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        document.getElementById('txt_warn').innerText = '请求失败，请刷新后再试';
                    }
                });
            }

        });
    });

    //短信发送
    function turnTo() {
        if (validateMobile()) {
            if (!validateMobile()) {
                result = false;
                return result;
            }
            $('#get_code').attr("disabled", "disabled");
            $('#phone').attr("readonly", "readonly");
            var mobile = document.getElementById('phone').value;
            $.get(
                    '/volunteer/sms?phone=' + mobile,
                    function (data) {
                        if (data.success) {
                        } else {
                            alert(data.error_message.phone);
                        }
                    },
                    "json"
            );

            var i = 61;
            timer();
            function timer() {
                i--;
                $('#get_code').text(i + '秒后重发');
                if (i == 0) {
                    clearTimeout(timer);
                    $('#get_code').removeAttr("disabled");
                    $('#get_code').text('重新发送');
                } else {
                    setTimeout(timer, 1000);
                }
            }
        }
    }

</script>


