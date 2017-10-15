<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>空中课堂报名</title>

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

                        $("#text_location2").empty();
                        $("#select_hospital").empty();
                        $("#text_hospital").val("-1");

                    },
                    error: function (xhr, status, errorThrown) {
                        alert("Sorry, there was a problem!");
                    }
                });
            });
        }

        var changeHospital= function (id) {
            $('#closed').hide();
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

                $('.mask').removeClass('weui_fade_toggle');
                $('.mask').css("display","none");
                $('.weui_actionsheet').removeClass('weui_actionsheet_toggle');

            });
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



        $( window ).load(function() {
            console.log( "window loaded" );
        });


        $(document).ready(function(){
            console.log( "document loaded" );

            $('.showActionSheet').click(function(){
                $(this).next().children(0).addClass('weui_fade_toggle');
                $(this).next().children(0).css("display","block");
                $(this).next().children(1).addClass('weui_actionsheet_toggle');
                document.getElementById('text_province').value='';
                document.getElementById('text_city').value="";
                document.getElementById('text_country').value="";
                document.getElementById('text_location').value="";
                $("#closed").show();
            });

            $('.actionsheet_cancel').click(function(){
                $('.mask').removeClass('weui_fade_toggle');
                $('.mask').css("display","none");
                $('.weui_actionsheet').removeClass('weui_actionsheet_toggle');
                //$('#closed').hide();
            });

            $('.nextActionSheet').click(function(){
                $(this).parents('.actionSheet_wrap').next().children(0).addClass('weui_fade_toggle');
                $(this).parents('.actionSheet_wrap').next().children(0).css("display","block");
                $(this).parents('.actionSheet_wrap').next().children(1).addClass('weui_actionsheet_toggle');
            });
            $('#closed').on('click',function(){
                $('.mask').removeClass('weui_fade_toggle');
                $('.mask').css("display","none");
                $('.weui_actionsheet').removeClass('weui_actionsheet_toggle');
                $(this).hide();
            });

            var requestUrl = '/kzkt/province';
            $.ajax({
                type: "get",
                dataType: "json",
                url: requestUrl,
                success: function (json) {
                    $("#select_province").empty();
                    var strHtml = "";

                    $(json.list).each(function () {
                        var id = "ss_" + this.province_id;
                        strHtml += "<div id='ss_" + this.province_id + "' class='weui_actionsheet_cell nextActionSheet' " +
                        "value='" + this.province_id + "' onclick='changeCity(\"" + id + "\")'>" + this.province + "</div>";
                    });
                    $("#select_province").html(strHtml);
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

            $("#btn_save").on('click',function () {

                var result = true;
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

                if(document.getElementById('text_location2').value.length == 0){
                    document.getElementById('txt_warn').innerText = '请输入医院！';
                    result = false;
                    return result;
                }

                if(result == true) {

                    var province = $("#text_province").val();
                    var city = $("#text_city").val();
                    var country = $("#text_country").val();
                    var hospital = $("#text_location2").val();

                    document.getElementById('txt_warn').innerText = '正在提交！';
                    var requestUrl = '/kzkt/addHospital';
                    $.ajax({
                        url: requestUrl,
                        data: {
                            province: province,
                            city: city,
                            country: country,
                            hospital: hospital
                        },
                        type: "post",
                        dataType: "json",
                        success: function (json) {
                            console.log('1');
                            if (json.result == '-1') {
                                document.getElementById('txt_warn').innerText = '提交失败！';
                            }

                            if (json.result == '1') {
                                document.getElementById('txt_warn').innerText = '提交成功！';
                                var id = request("id");
                                if(id == '0') {
                                    window.location.href = '/kzkt/signup';
                                }
                                else{
                                    window.location.href = '/kzkt/editClassroom?id='+id;
                                }
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
            <div style="position: absolute;top:0;left:0; z-index: 99999;display: none;" id="closed">
                 关闭
            </div>
            <div class="weui_cells_title">请选择地区后填写医院名称</div>

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


                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">医&emsp;院</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input name="text_location2" id="text_location2" type="text" class="weui_input" placeholder="请输入医院">
                    </div>
                    <div class="weui_cell_ft"></div>
                </div>

                {{--<div class="actionSheet_wrap">--}}
                    {{--<div class="mask"></div>--}}
                    {{--<div class="weui_actionsheet">--}}
                        {{--<div id="select_hospital" class="weui_actionsheet_menu">--}}
                            {{--<div class="weui_actionsheet_cell actionsheet_cancel">请选择医院</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>

            <p class="tips"></p>
            <p id="txt_warn" class="warning"></p>
            {{--<label id="label" class="warning">请输入。。。。</label>--}}
            {{--<a class="weui_btn">确&emsp;认</a>--}}
            <button id="btn_save" type="button" class="weui_btn" style="width: 90%;">确&emsp;认</button>
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