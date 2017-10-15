<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>登录账号</title>
    <link rel="stylesheet" href="/css/weui.min.css">
    <link rel="stylesheet" href="/css/volunteer.css">
    <!-- 引入 jQuery 库 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>

    <script type="application/javascript">

        var validateMobile = function() {
            var mobile = document.getElementById('phone').value;
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

            var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if (!myreg.test(mobile)) {
                document.getElementById('txt_warn').innerText = '请输入有效的手机号码！';
                document.getElementById('phone').focus();
                return false;
            }
            return true;
        };

        var verifyPassword = function() {
            var password = document.getElementById('password').value;
            //var repassword = document.getElementById('repassword').value;

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



            return true;
        };


        $(document).ready(function(){




            $("#btn_save").on('click',function () {
                var result = true;


                if (!validateMobile()) {
                    result = false;
                    return result;
                }

                if (!verifyPassword()) {
                    result = false;
                    return result;
                }



                if(result == true) {
                    //var name = $("#name").val();
                    var phone = $("#phone").val();
                    var password = $("#password").val();
                    //var unit_id = $("#unit").val();
                    //var number = "";
                    //var email = "";
                    //if(unit_id == '1') {
                    //    number = $("#number").val();
                    //    email = number + '@novonordisk.com';
                    //}
                    document.getElementById('txt_warn').innerText = '正在登录！';
                    var requestUrl = '/volunteer/login-self';
                    $.ajax({
                        url: requestUrl,
                        data: {

                            phone: phone,
                            password:password
                        },
                        type: "post",
                        dataType: "json",
                        success: function (json) {
                            if (json.result == '1') {
                                document.getElementById('txt_warn').innerText = '登录成功！';
                                window.location.href = '/volunteer/success';
                            }

                            if (json.result == '-1') {
                                document.getElementById('txt_warn').innerText = '登录失败！原因：'+json.message;
                                //window.location.href = '/home/error?message='+json.message;
                            }
                        },
                        error: function (xhr, status, errorThrown) {
                            alert("Sorry, there was a problem!");
                        }
                    });
                }

            });
        });
    </script>
</head>

<body class="body-gray" ontouchstart>
<div class="container" style="margin-top:15%">
    <div id="sign_up">

        <form action="" method="post">

            {{--<div class="weui_cells_title">请填写您的注册信息</div>--}}

            <div class="weui_cells weui_cells_form weui_cells_access">

                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">用户名</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input id="phone" type="text" class="weui_input" placeholder="请输入手机号">
                    </div>
                </div>

                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">密&emsp;码</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input id="password" type="password" class="weui_input" placeholder="密码">
                    </div>
                </div>


            </div>
            <p id="txt_warn" style="text-align:center;color:red"></p>
            <a id="btn_save" class="weui_btn weui_btn_primary" style="cursor: pointer;">登&emsp;录</a>
            <a href="/volunteer/register-self" class="weui_btn weui_btn_primary">注&emsp;册</a>

            <input type="hidden" id="unit" value="-1">
        </form>
    </div>
</div>

</body>

</html>
