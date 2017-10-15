<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>学员资料</title>

    <link rel="stylesheet" href="/css/weui.min.css">
    <link rel="stylesheet" href="/css/volunteer.css">

    <!-- 引入 jQuery 库 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>

    <script type="application/javascript">

        var request = function (paras) {
            var url = location.href;
            var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
            var paraObj = {}
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
        }

        var province,city,country,hospital,department;
        var getData = function (id){
            var requestUrl = '/kzkt/findSingleRegister';
            $.ajax({
                type : "get",
                data: {
                    id: id
                },
                dataType : "json",
                url : requestUrl,
                success: function (json) {
                    if(json.result == '1') {
                        $("#name").val(json.data.name);
                        $("#phone").val(json.data.phone);
                        $("#mail").val(json.data.mail);
                        $("#text_qq").val(json.data.oicq);
                        $("#select_class").val(json.className);
                        $("#text_class").val(json.kzktType);
//                        $("#select_title").val(json.data.title);
                        $("#text_province").val(json.province);
                        $("#text_city").val(json.city);
                        $("#text_country").val(json.country);
                        $("#text_location").val(json.address);
                        $("#text_location2").val(json.hospital);
                        $("#text_hospital").val(json.hosp);
//                        province = json.data.province;
//                        city = json.data.city;
//                        country = json.data.country;
                        department = json.data.office;
//                        hospital = json.data.hospital;
                        getProvince(province);
//                        getCity(city, province);
//                        getCountry(country, city);
//                        getHospital(hospital, country);
                        getDepartment(department);
                    }
                },
                error: function (xhr, status, errorThrown) {
                    alert("Sorry, there was a problem!");
                    console.log("Error: " + errorThrown);
                    console.log("Status: " + status);
                    console.dir(xhr);
                },
                complete: function (xhr, status) {
//                    alert("The request is complete!");
                }
            });
        }

        var getProvince = function(id) {
            var requestUrl = '/kzkt/province';
            $.ajax({
                type : "get",
                dataType : "json",
                url : requestUrl,
                success: function (json) {
                    $("#select_province").empty();
//                    var strHtml="<option value='-1' selected=true>"+"请选择"+"</option>";
//
//                    $(json.list).each(function () {
//                        strHtml+="<option value='"+this.SA_PRIOVINCE_ID+"'>"+this.SA_PROVINCE+"</option>";
//                    });
                    var strHtml = "";
                    $(json.list).each(function () {
                        var id = "ss_" + this.province_id;
                        strHtml += "<div id='ss_" + this.province_id + "' class='weui_actionsheet_cell nextActionSheet' " +
                        "value='" + this.province_id + "' onclick='changeCity(\"" + id + "\")'>" + this.province + "</div>";
                    });
                    $("#select_province").html(strHtml);
//                    if (id != null)
//                        $("#select_province").val(id);

                },
                error: function (xhr, status, errorThrown) {
                    alert("Sorry, there was a problem!");
                    console.log("Error: " + errorThrown);
                    console.log("Status: " + status);
                    console.dir(xhr);
                },
                complete: function (xhr, status) {
//                    alert("The request is complete!");
                }
            });
        }

        var getDepartment = function (id) {
            switch (id) {
                case '1':
                    $('#select_department').val('内分泌科');
                    break;
                case '2':
                    $('#select_department').val('综合内科');
                    break;
                case '3':
                    $('#select_department').val('全科');
                    break;
                case '4':
                    $('#select_department').val('神经内科');
                    break;
                case '5':
                    $('#select_department').val('心血管科');
                    break;
                case '6':
                    $('#select_department').val('老年科');
                    break;
            }
            $('#text_department').val(id);
        }

//        var getCity = function (id, branchId) {
//            var requestCity = '/city';
//            $.ajax({
//                url: requestCity,
//                data: {
//                    id: branchId
//                },
//                type: "get",
//                dataType: "json",
//                success: function (json) {
//                    $("#select_city").empty();
//                    var strHtml = "<option value='-1' selected=true>" + "请选择" + "</option>";
//                    $(json.list).each(function () {
//                        strHtml += "<option value='" + this.SA_CITY_ID + "'>" + this.SA_CITY + "</option>";
//                    });
//                    $("#select_city").html(strHtml);
//                    if (id != null)
//                        $("#select_city").val(id);
//
//                    return true;
//                },
//                error: function (xhr, status, errorThrown) {
////                    alert("Sorry, there was a problem!");
//                    return false;
//                }
//            });
//        }

//        var getCountry = function(id, branchId) {
//            var requestCountry = '/activity/kzkt/country';
//            $.ajax({
//                url : requestCountry,
//                data: {
//                    id: branchId
//                },
//                type : "get",
//                dataType : "json",
//                success: function (json) {
//                    $("#select_country").empty();
//                    var strHtml="<option value='-1' selected=true>"+"请选择"+"</option>";
//                    $(json.list).each(function () {
//                        strHtml+="<option value='"+this.SA_COUNTRY_ID+"'>"+this.SA_COUNTRY+"</option>";
//                    });
//                    $("#select_country").html(strHtml);
//
//                    if (id != null)
//                        $("#select_country").val(id);
//
//                    return true;
//                },
//                error: function (xhr, status, errorThrown) {
////                    alert("Sorry, there was a problem!");
//                    return false;
//                }
//            });
//        }

//        var getHospital = function(id, branchId) {
//            var requestHospital = '/kzkt/hospital';
//            $.ajax({
//                url : requestHospital,
//                data: {
//                    id: branchId
//                },
//                type : "get",
//                dataType : "json",
//                success: function (json) {
//                    $("#select_hospital").empty();
//                    var strHtml="<option value='-1' selected=true>"+"请选择"+"</option>";
//                    $(json.list).each(function () {
//                        strHtml+="<option value='"+this.SA_HOSPITAL_ID+"'>"+this.SA_HOSPITAL+"</option>";
//                    });
//
//                    $("#select_hospital").html(strHtml);
//                    console.log(id);
//                    if (id != null)
//                        $("#select_hospital").val(id);
//                },
//                error: function (xhr, status, errorThrown) {
////                    alert("Sorry, there was a problem!");
//                }
//            });
//        }

        var changeCity = function (id) {
            $(function () {
                var branchId = $('#' + id).attr('value');
                var location = document.getElementById(id).innerText;
                $('#text_location').val('');
                $('#text_location').val(location);
                $('#text_province').val(branchId);
                $('#text_province_view').val(location);
                console.log("ready" + branchId + location);
                var requestCity = '/kzkt/city';
                $.ajax({
                    url: requestCity,
                    data: {
                        id: branchId
                    },
                    type: "get",
                    dataType: "json",
                    success: function (json) {
                        $("#select_city").empty();
                        var strHtml = "";
                        $(json.list).each(function () {
                            var id = "ss_" + this.city_id;
                            strHtml += "<div id='ss_" + this.city_id + "' class='weui_actionsheet_cell nextActionSheet' " +
                            "value='" + this.city_id + "' onclick='changeCountry(\"" + id + "\")'>" + this.city + "</div>";
                        });
                        $("#select_city").html(strHtml);

                        $("#select_province").parents('.actionSheet_wrap').next().children(0).addClass('weui_fade_toggle');
                        $("#select_province").parents('.actionSheet_wrap').next().children(0).css("display", "block");
                        $("#select_province").parents('.actionSheet_wrap').next().children(1).addClass('weui_actionsheet_toggle');

                    },
                    error: function (xhr, status, errorThrown) {
                        alert("Sorry, there was a problem!");
                    }
                });

            });
        }

        var changeCountry = function (id) {
            $(function () {
                var branchId = $('#' + id).attr('value');
                var location = document.getElementById(id).innerText;
                $('#text_location').val('');
                $('#text_city').val(branchId);
                $('#text_city_view').val(location);
                var tmp = $('#text_province_view').val() + '-' + $('#text_city_view').val();
                $('#text_location').val(tmp);
                //alert(branchId);
                console.log("ready" + branchId);
                var requestCountry = '/kzkt/country';
                $.ajax({
                    url: requestCountry,
                    data: {
                        id: branchId
                    },
                    type: "get",
                    dataType: "json",
                    success: function (json) {
                        $("#select_country").empty();
                        var strHtml = "";
                        $(json.list).each(function () {
                            var id = "ss_" + this.country_id;
                            strHtml += "<div id='ss_" + this.country_id + "' class='weui_actionsheet_cell actionsheet_cancel' " +
                            "value='" + this.country_id + "' onclick='changeHospital(\"" + id + "\")'>" + this.country + "</div>";
                        });
                        $("#select_country").html(strHtml);

                        $("#select_city").parents('.actionSheet_wrap').next().children(0).addClass('weui_fade_toggle');
                        $("#select_city").parents('.actionSheet_wrap').next().children(0).css("display", "block");
                        $("#select_city").parents('.actionSheet_wrap').next().children(1).addClass('weui_actionsheet_toggle');

                        $("#text_location2").val('');
                        $("#select_hospital").empty();
                        $("#text_hospital").val("-1");
//                        var strHtml1 = "<option value='-1' selected=true>" + "请选择" + "</option>";
//                        $("#select_hospital").html(strHtml1);

                        $("#select_department").val('');
                        $("#text_department").val("-1");
//                        $("#select_title").val("-1");
                    },
                    error: function (xhr, status, errorThrown) {
                        alert("Sorry, there was a problem!");
                    }
                });
            });
        }

        var changeHospital= function (id) {
            $(function () {
                var branchId = $('#' + id).attr('value');
                var location = document.getElementById(id).innerText;
                var tmp = $('#text_location').val();
                $('#text_location').val('');
                $('#text_country').val(branchId);
                $('#text_country_view').val(location);
                var tmp = $('#text_province_view').val() + '-' + $('#text_city_view').val()
                        + '-' + $('#text_country_view').val();
                $('#text_location').val(tmp);
                var requestHospital = '/kzkt/hospital';
                $.ajax({
                    url : requestHospital,
                    data: {
                        id: branchId
                    },
                    type : "get",
                    dataType : "json",
                    success: function (json) {
                        $("#select_hospital").empty();
//                        var strHtml="<option value='-1' selected=true>"+"请选择"+"</option>";
//                        $(json.list).each(function () {
//                            strHtml+="<option value='"+this.SA_HOSPITAL_ID+"'>"+this.SA_HOSPITAL+"</option>";
//                        });

                        var strHtml = "";
                        $(json.list).each(function () {
                            var id = this.id;
                            var name = this.hospital;
                            if (strHtml.indexOf(this.hospital) == -1) {
                                strHtml += "<div id='ss_" + this.id + "' class='weui_actionsheet_cell actionsheet_cancel' " +
                                "value='" + this.hospital + "' onclick='onHospitalClick(\"" + id + "\",\"" + name + "\")'>" + this.hospital + "</div>";
                            }
                        });

                        if(strHtml.length == 0) {
                            var id =  '-1';
                            var name = '请选择医院';
                            strHtml += "<div id='ss_" + this.id + "' class='weui_actionsheet_cell actionsheet_cancel' " +
                            "value='" + this.hospital + "' onclick='onHospitalClick(\"" + id + "\",\"" + name + "\")'>" + this.hospital + "</div>";
                        }

                        $("#select_hospital").html(strHtml);

                        $("#select_department").val('');
                        $("#text_department").val("-1");

                        $('.mask').removeClass('weui_fade_toggle');
                        $('.mask').css("display","none");
                        $('.weui_actionsheet').removeClass('weui_actionsheet_toggle');

                    },
                    error: function (xhr, status, errorThrown) {
                        alert("Sorry, there was a problem!");
                    }
                });
            });
        }

        var addSelOption = function(jq) {
            //创建option对象，并设置文本为"请选择"，value值为-1
            var opt = $("<option/>").text("请选择").attr("value", "-1");
            //将option对象添加到select中
            jq.append(opt);
        }

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
        }

        var verifyAddress = function () {
            var email = document.getElementById('mail').value;
            if (email.length > 0) {
                if (email.length == 0) {
                    document.getElementById('txt_warn').innerText = '请输入邮箱！';
                    document.getElementById('mail').focus();
                    return false;
                }
                var pattern = /^[\w-]+@([\w-]+\.)+[\w-]+$/;
                flag = pattern.test(email);
                if (!flag) {
                    document.getElementById('txt_warn').innerText = '请输入正确邮箱！';
                    document.getElementById('mail').focus();
                    return false;
                }
            }
            return true;
        }

        var onClassClick = function (id, name) {
            document.getElementById('text_class').value = id;
            document.getElementById('select_class').value = name;
        }

        var onHospitalClick = function(id, name) {
            document.getElementById('text_hospital').value = id;
            document.getElementById('text_location2').value = name;
            $(function () {
                $('.mask').removeClass('weui_fade_toggle');
                $('.mask').css("display","none");
                $('.weui_actionsheet').removeClass('weui_actionsheet_toggle');
            });

        }

        var onDepartmentClick = function(id, name) {
            document.getElementById('text_department').value = id;
            document.getElementById('select_department').value = name;
        }

        var onEditClick = function() {
            console.log('onEditClick');
            var id = request('id');
            window.location.href = '/kzkt/viewHospital?id='+id;
        }

        $(document).ready(function(){

            var id = request("id");
            if(id != null) {
                getData(id);
            }

            $('.showActionSheet').click(function(){
                $(this).next().children(0).addClass('weui_fade_toggle');
                $(this).next().children(0).css("display","block");
                $(this).next().children(1).addClass('weui_actionsheet_toggle');
            });

            $('.actionsheet_cancel').click(function(){
                $('.mask').removeClass('weui_fade_toggle');
                $('.mask').css("display","none");
                $('.weui_actionsheet').removeClass('weui_actionsheet_toggle');
            });

            $('.nextActionSheet').click(function(){
                $(this).parents('.actionSheet_wrap').next().children(0).addClass('weui_fade_toggle');
                $(this).parents('.actionSheet_wrap').next().children(0).css("display","block");
                $(this).parents('.actionSheet_wrap').next().children(1).addClass('weui_actionsheet_toggle');
            })


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

                if($("#text_class").val() == '-1'){
                    document.getElementById('txt_warn').innerText = '请选择班级！';
                    result = false;
                    return result;
                }


                if(document.getElementById('text_province').value.length == 0){
                    document.getElementById('txt_warn').innerText = '请选择省市县！';
                    result = false;
                    return result;
                }

                if(document.getElementById('text_city').value.length == 0){
                    document.getElementById('txt_warn').innerText = '请选择省市县！';
                    result = false;
                    return result;
                }

                if(document.getElementById('text_country').value.length == 0){
                    document.getElementById('txt_warn').innerText = '请选择省市县！';
                    result = false;
                    return result;
                }

                if($("#text_hospital").val() == '-1'){
                    document.getElementById('txt_warn').innerText = '请选择医院！';
                    result = false;
                    return result;
                }

                if($("#text_department").val() == '-1'){
                    document.getElementById('txt_warn').innerText = '请选择科室！';
                    result = false;
                    return result;
                }

                if(!verifyAddress()){
                    result = false;
                    return result;
                }

                if(result == true) {
                    console.log('123');
                    var name = $("#name").val();
                    var phone = $("#phone").val();
                    var classType = $("#text_class").val();
                    var province = $("#text_province").val();
                    var city = $("#text_city").val();
                    var country = $("#text_country").val();
                    var hospital = $("#text_hospital").val();
//                    var hospital = $("#select_hospital option:selected").text();
                    var department = $("#text_department").val();
//                    var title = $("#select_title").val();
                    var mail = $("#mail").val();
                    var oicq = $("#text_qq").val();
                    document.getElementById('txt_warn').innerText = '正在提交！';
                    console.log('456');
                    var requestUrl = '/kzkt/updateClassroom';
                    $.ajax({
                        url: requestUrl,
                        data: {
                            id: id,
                            name: name,
                            phone: phone,
                            classType: classType,
                            province: province,
                            city: city,
                            country: country,
                            hospital: hospital,
                            department: department,
                            mail: mail,
                            oicq: oicq
                        },
                        type: "post",
                        dataType: "json",
                        success: function (json) {
                            if (json.result == '-1') {
                                document.getElementById('txt_warn').innerText = '提交失败！';
                                window.location.href = '/kzkt/index';
                            }

                            if (json.result == '1') {
                                document.getElementById('txt_warn').innerText = '提交成功！';
//                                var requestUrl = "http://docmate3.mime.org.cn:82/AppClient/RegHandler.ashx?" +
//                                        "action=wechatreg&appid=APP20150701101838";
//
//                                $.ajax({
//                                    type: "get",
//                                    async: false,
//                                    url : requestUrl,
//                                    dataType : "jsonp",
//                                    jsonp: "callbackparam",//服务端用于接收callback调用的function名的参数
//                                    jsonpCallback:"success_jsonpCallback",//callback的function名称
//                                    data: {
//                                        mobile: phone,
//                                        pwd: '',
//                                        hospital: json.hospital,
//                                        nickname: name
//                                    },
//                                    success: function (data) {
//                                        console.log(data);
//
//                                    },
//                                    error: function (xhr, status, errorThrown) {
////                                        alert("Sorry, there was a problem!");
//                                    },
//                                    complete: function (xhr, status) {
//
//                                    }
//                                });

                                window.location.href = '/kzkt/viewCard?id='+json.id;
                            }
                            if (json.result == '2') {
                                console.log('aaa');
                                document.getElementById('txt_warn').innerText = '邮箱未填写，需要完善；本次报名不成功，请在未完成报名页面进行修改';
                                window.location.href = '/kzkt/showfail';
                                console.log('bbb');
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
<div class="container">
    <div id="sign_up">

        <form action="" method="post">

            <div class="weui_cells_title">请为学员填写报名信息</div>

            <div class="weui_cells weui_cells_form weui_cells_access">


                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">姓&emsp;名</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input name="name" id="name" type="text" class="weui_input" placeholder="请输入姓名">
                    </div>
                </div>


                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">手机号</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input name="phone" id="phone" type="text" class="weui_input" placeholder="请输入手机号">
                    </div>
                </div>

                <div class="weui_cell showActionSheet">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">课程班</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary location_select">
                        <input name="select_class" id="select_class" type="text" class="weui_input" disabled placeholder="请选择班级">
                    </div>
                    <div class="weui_cell_ft"></div>
                </div>

                <div class="actionSheet_wrap">
                    <div class="mask"></div>
                    <div class="weui_actionsheet">
                        <div class="weui_actionsheet_menu">
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onClassClick('1','基础班')">基础班</div>
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onClassClick('2','高级班')">高级班</div>
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onClassClick('3','精品班')">精品班</div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="weui_cells weui_cells_form weui_cells_access">


                <div class="weui_cell showActionSheet">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">省市县</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary location_select">
                        <input name="text_location" id="text_location" type="text" class="weui_input" disabled placeholder="请选择省市县">
                    </div>
                    <div class="weui_cell_ft"></div>
                </div>

                <div class="actionSheet_wrap">
                    <div class="mask"></div>
                    <div class="weui_actionsheet">
                        <div id="select_province" class="weui_actionsheet_menu">
                            <div class="weui_actionsheet_cell nextActionSheet">示例菜单1</div>
                        </div>
                    </div>
                </div>

                <div class="actionSheet_wrap">
                    <div class="mask"></div>
                    <div class="weui_actionsheet">
                        <div id="select_city" class="weui_actionsheet_menu">
                            <div class="weui_actionsheet_cell nextActionSheet">示例菜单1-1</div>
                        </div>
                    </div>
                </div>

                <div class="actionSheet_wrap">
                    <div class="mask"></div>
                    <div class="weui_actionsheet">
                        <div id="select_country" class="weui_actionsheet_menu">
                            <div class="weui_actionsheet_cell actionsheet_cancel">示例菜单1-1-1</div>
                        </div>
                    </div>
                </div>

                <div class="weui_cell showActionSheet">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">医&emsp;院</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary location_select">
                        <input name="text_location2" id="text_location2" type="text" class="weui_input" disabled placeholder="请选择医院">
                    </div>
                    <div class="weui_cell_ft"></div>
                </div>

                <div class="actionSheet_wrap">
                    <div class="mask"></div>
                    <div class="weui_actionsheet">
                        <div id="select_hospital" class="weui_actionsheet_menu">
                            <div class="weui_actionsheet_cell actionsheet_cancel">请选择医院</div>
                        </div>
                    </div>
                </div>

                <div class="weui_cell" onclick="onEditClick();">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">&emsp;&emsp;</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input name="edit" id="edit" type="text" class="weui_input" disabled placeholder="无法选择，请点击此处新增医院">
                    </div>
                    <div class="weui_cell_ft"></div>

                </div>

                <div class="weui_cell showActionSheet">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">科&emsp;室</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary location_select">
                        <input name="select_department" id="select_department" type="text" class="weui_input" disabled placeholder="请选择科室">
                    </div>
                    <div class="weui_cell_ft"></div>

                </div>

                <div class="actionSheet_wrap">
                    <div class="mask"></div>
                    <div class="weui_actionsheet">
                        <div class="weui_actionsheet_menu">
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onDepartmentClick('1','内分泌科')">内分泌科</div>
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onDepartmentClick('2','综合内科')">综合内科</div>
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onDepartmentClick('3','全科')">全科</div>
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onDepartmentClick('4','神经内科')">神经内科</div>
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onDepartmentClick('5','心血管科')">心血管科</div>
                            <div class="weui_actionsheet_cell actionsheet_cancel" onclick="onDepartmentClick('6','老年科')">老年科</div>
                        </div>
                    </div>
                </div>



            </div>

            <div class="weui_cells weui_cells_form weui_cells_access">


                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">邮&emsp;箱</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input name="mail" id="mail" type="text" class="weui_input" placeholder="请输入邮箱">
                    </div>
                </div>


                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label id="inputQQ" for="" class="weui_label">QQ（选填）</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input name="text_qq" id="text_qq" type="text" class="weui_input" placeholder="请填写QQ">
                    </div>
                </div>
            </div>

            <p class="tips">为保证及时收到课程通知和课件信息，建议填写QQ号！</p>
            <p id="txt_warn" class="warning"></p>
            {{--<label id="label" class="warning">请输入。。。。</label>--}}
            {{--<a class="weui_btn">确&emsp;认</a>--}}
            <button id="btn_save" type="button" class="weui_btn weui_btn_primary" style="width: 90%;">确&emsp;认</button>
            <input type="hidden" id="text_province">
            <input type="hidden" id="text_city">
            <input type="hidden" id="text_country">
            <input type="hidden" id="text_province_view">
            <input type="hidden" id="text_city_view">
            <input type="hidden" id="text_country_view">
            <input type="hidden" id="text_class" value="-1">
            <input type="hidden" id="text_hospital" value="-1">
            <input type="hidden" id="text_department" value="-1">
        </form>
    </div>
</div>

</body>
</html>