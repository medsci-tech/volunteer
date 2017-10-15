<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title>空中课堂报名</title>

    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/plugins/area-select/style.css">
    <link rel="stylesheet" href="/css/volunteer.css">
    <style>
        .weui-cell__bd{
            font-size: 12px;
        }
    </style>
</head>
<body ontouchstart>
<div class="container">
    <div class="page">
        <form style="padding-bottom: 2em" action="" method="post" id="form-add_class">
            <div class="weui-cells__title">请填写学员报名信息</div>

            <div class="weui-cells weui-cells_form ">
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">姓名</label>
                    </div>
                    <div class="weui-cell__bd ">
                        <input name="name" type="text" class="weui-input" required placeholder="请输入姓名">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">手机号</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <input class="weui-input" name="phone" id="phone" type="tel" required  tips="请填写正确的11位手机号" placeholder="请输入手机号">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>
                <div class="weui-cell weui-cell_vcode">
                    <div class="weui-cell__hd weui-cell__hd">
                        <label class="weui-label">验证码</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" name="verify_code" required placeholder="验证码">
                    </div>
                    <div class="weui-cell__ft">
                        <button type="button" class="weui-vcode-btn" id="get_code">获取验证码</button>
                    </div>
                </div>

            </div>

            <div class="weui-cells weui-cells_form">
                <div class="weui-cell weui-cell_select showActionSheet">
                    <div class="weui-cell__hd">
                        <label class="weui-label">地区</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <a class="weui-input" id="expressArea" href="javascript:void(0)" style="display: inline-block"> 省 &gt; 市 &gt; 区/县</a>
                    </div>
                    <div class="weui-cell__ft"></div>
                </div>

                <!--选择地区弹层-->
                <section id="areaLayer" class="express-area-box" style="bottom: -100%;">
                    <header>
                        <h3>选择地区</h3>
                        <a id="closeArea" class="close" href="javascript:void(0)" title="关闭"></a>
                    </header>
                    <article id="areaBox">
                        <ul id="areaList" class="area-list">
                        </ul>
                    </article>
                </section>
                <!--遮罩层-->
                <div id="areaMask" class="mask" style="display: none;"></div>

                <div class="weui-cell showActionSheet">
                    <div class="weui-cell__hd">
                        <label class="weui-label">医院</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary" style="z-index: 9">
                        <input id="hospital" name="hospital" type="text" class="weui-input" required placeholder="请输入医院">
                        <div id="query-select" class="query-select">
                            <ul class="query-select-list"></ul>
                        </div>
                    </div>
                    <div id="query-select-bg" class="query-select-bg"></div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>



                <div class="weui-cell_select weui-cell showActionSheet">
                    <div class="weui-cell__hd">
                        <label class="weui-label">医院等级</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <select class="weui-input" id="hospital_level" name="hospital_level" required tips="请选择等级">
                            <option value="">请选择等级</option>
                            @foreach(config('params')['hospital_level'] as $ol)
                                <option value="{{$ol}}">{{$ol}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="weui-cell__ft"></div>
                </div>

                <div class="weui-cell weui-cell_select showActionSheet">
                    <div class="weui-cell__hd">
                        <label class="weui-label">科室</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                    <select class="weui-input" name="department" required tips="请选择科室">
                        <option value="">请选择科室</option>
                        @foreach($offices as $office)
                        <option value="{{$office->office_name}}">{{$office->office_name}}</option>
                            @endforeach
                    </select>
                    </div>
                    <div class="weui-cell__ft"></div>
                </div>

                <div class="weui-cell weui-cell_select showActionSheet">
                    <div class="weui-cell__hd">
                        <label class="weui-label">职称</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <select class="weui-input" name="doctor_title" required tips="请选择职称">
                            <option value="">请选择职称</option>
                            @foreach(config('params')['doctor_title'] as $v)
                                <option value="{{$v}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="weui-cell__ft"></div>
                </div>

            </div>

            <div class="weui-cells weui-cells_form">

                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">邮箱</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <input name="mail" type="email" required pattern="REG_EMAIL" tips="请填写正确格式的邮箱" class="weui-input" placeholder="请输入邮箱">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">QQ（选填）</label>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <input name="qq" type="number" class="weui-input" placeholder="请填写QQ">
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>
                <div id="checkbox_validate_parant" class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">学习方式</label>
                    </div>
                    <div class="weui-cell__bd ">
                        <div class="page__bd">
                            <input type="hidden" id="checkbox_validate" value="true" required tips="请选择学习方式">
                            <div class="weui-flex">
                                <div class="weui-flex__item">
                                    <div class="weui-cells_checkbox">
                                        <label class="weui-cell weui-check__label" style="padding: 0">
                                            <div class="weui-cell__hd">
                                                <input type="checkbox" class="weui-check" name="style[]" data-click="style" value="web" checked="checked">
                                                <i class="weui-icon-checked"></i>
                                            </div>
                                            <div class="weui-cell__bd">
                                                <p>网络</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="weui-flex__item">
                                    <div class="weui-cells_checkbox">
                                        <label class="weui-cell weui-check__label" style="padding: 0">
                                            <div class="weui-cell__hd">
                                                <input type="checkbox" class="weui-check" name="style[]" data-click="style" value="phone">
                                                <i class="weui-icon-checked"></i>
                                            </div>
                                            <div class="weui-cell__bd">
                                                <p>电话</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="weui-cell__ft"> <i class="weui-icon-warn"></i> </div>
                </div>
            </div>
            <p class="tips">为保证及时收到课程通知和课件信息，建议填写QQ号！</p>
            <label class="weui-agree">
                <input id="weuiAgree" type="checkbox" class="weui-agree__checkbox" checked>
                <span class="weui-agree__text">
                    阅读并同意<a href="javascript:void(0);">《相关条款》</a>
                </span>
            </label>
            <button id="btn_save" type="button" class="weui-btn weui-btn_primary" style="width: 90%;">确认</button>
            <input type="hidden" name="province_id" id="province_id">
            <input type="hidden" name="city_id" id="city_id">
            <input type="hidden" name="country_id" id="country_id">
            <input type="hidden" name="province" id="province">
            <input type="hidden" name="city" id="city">
            <input type="hidden" name="country" id="country">
            <input type="hidden" name="referrer_id" value="{{$referrer_id}}" id="referrer_id">
            <input type="hidden" name="site_id" value="{{$site_id}}" id="site_id">
        </form>
    </div>
</div>

<script src="/js/jquery-1.11.1.min.js"></script>
<script src="/js/weui.min.js"></script>
<script src="/plugins/area-select/area.data.js"></script>
<script src="/plugins/area-select/jquery.area.js"></script>
<script src="/js/jquery.common.mime.js"></script>
<script>
    var referrer_id = $('#referrer_id').val();
    var site_id = $('#site_id').val();
    // 查询医院数据
    var get_hospital = function (data) {
        data.province = $('#province').val();
        data.city = $('#city').val();
        data.country = $('#country').val();
        data.referrer_id = referrer_id;
        if(data.country){
            $.ajax({
                type: 'post',
                url: '/kzkt/get_hospital',
                data: data,
                success: function(res){
                    console.log(res.data);
                    if(res.code == 200){
                        show_hospiatal_list(res.data);
                    }else {
                        close_hospiatal_list();
                    }
                },
                error:function () {
                    close_hospiatal_list();
                }
            });
        }else {
            close_hospiatal_list();
        }
    };
    var show_hospiatal_list = function (list) {
        var html = '';
        for(var i in list){
            if(list[i]['hospital']) {
                html += '<li onclick="select_hospital(' + '\'' + list[i]['hospital'] + '\',\'' + list[i]['hospital_level'] + '\'' + ')">' + list[i]['hospital'] + '</li>'
            }
        }
        $('#query-select').show().find('ul').html(html);
        $('#query-select-bg').show();
    };
    var close_hospiatal_list = function () {
        $('#query-select').hide();
        $('#query-select-bg').hide();
    };

    var select_hospital = function (hospital, hospital_level) {
        $('#hospital').val(hospital);
        $('#hospital_level').val(hospital_level);
        close_hospiatal_list();
    };

    $(function () {
        var pattern = {
            EMAIL: /^[\w-]+@[\w-]+(\.[\w-]+)+$/,
            PHONE: /^1[35789]\d{9}$/
        };
        weui.form.checkIfBlur('#form-add_class', {
            regexp: pattern
        });

        // 表单提交
        var btn_save = $('#btn_save');
        btn_save.click(function () {
            weui.form.validate('#form-add_class', function (error) {
                if (!error) {
                    var form_url = '/kzkt/addClassroom';
                    var jump_url = '/kzkt/viewCard?role={{$role}}&referrer_id='+referrer_id+'&site_id='+site_id+'&phone='+$('#phone').val();
                    var data = $('#form-add_class').serialize();
                    subActionAjaxForMime(form_url, data, jump_url);
                }
            }, {
                regexp: pattern
            });
        });
        // 获取验证码
        $('#get_code').click(function () {
            var that = $(this);
            var code_url = '/kzkt/yxzyz_send_code?referrer_id='+referrer_id+'&site_id='+site_id;
            var phone = $('#phone');
            var phone_val = phone.val();
            console.log(phone_val);
            if(!phone.parent().parent().hasClass('weui-cell_warn') && phone_val){
                var int_time = 60;
                var html = int_time + 's后重新获取';
                that.attr('disabled',true).addClass('disabled-color').html(html);
                var get_time = setInterval(function () {
                    int_time--;
                    var html = int_time + 's后重新获取';
                    if(int_time >= 1){
                        that.attr('disabled',true).addClass('disabled-color').html(html);
                    }else {
                        clearInterval(get_time);
                        that.attr('disabled',false).removeClass('disabled-color').html('获取验证码');
                    }
                }, 1000);
                var data = {};
                data.phone = phone_val;
                data.type = 'doctor';
                subActionAjaxForMime(code_url, data);
            }
        });

        // 医院
        var hospital_dom = $('#hospital');
        var bind_name = 'input';
        if (navigator.userAgent.indexOf("MSIE") != -1){
            bind_name = 'propertychange';
        }
        hospital_dom.bind(bind_name, function(){
            var hospital = $(this).val();
            var data = {name:hospital};
            get_hospital(data);
        });

        hospital_dom.focus( function() {
            var hospital = hospital_dom.val();
            var data = {name:hospital};
            console.log(data);
            get_hospital({});
        });
        $('#query-select-bg').click( function() {
            close_hospiatal_list();
        });

        //学习方式
        var style_btn = $('[data-click="style"]');
        style_btn.click(function () {
            var check = verifyCheckedForMime(style_btn);
            var checkbox_validate = $('#checkbox_validate');
            $('#checkbox_validate_parant').removeClass('weui-cell_warn');
            if(check){
                checkbox_validate.val(check);
            }else {
                checkbox_validate.val('');
            }
            console.log(check);
        });

        //相关条款
        $('#weuiAgree').click(function () {
            var agree = $(this);
            var check = verifyCheckedForMime(agree);
            if(check){
                btn_save.attr('disabled',false).removeClass('weui-btn_disabled');
            }else {
                btn_save.attr('disabled',true).addClass('weui-btn_disabled');
            }
            console.log(check);
        })
    });

</script>
</body>
</html>