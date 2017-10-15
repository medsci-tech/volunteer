<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>2016空中课堂报名</title>

    <link rel="stylesheet" href="/css/weui.min.css">
    <link rel="stylesheet" href="/css/volunteer.css">

    <!-- 引入 jQuery 库 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>

    <script type="application/javascript">
        function goSignUp() {
            window.location.href = '/activity/kzkt/signup';
        }

        function goEdit(id) {
            window.location.href = '/activity/kzkt/editClassroom?id='+id;
        }

        $(document).ready(function(){

            $("#btn_save").on('click',function () {

                var requestUrl = '/activity/kzkt/checkIn';
                $.ajax({
                    url: requestUrl,
                    type: "post",
                    dataType: "json",
                    success: function (json) {
                        if (json.result == '-1') {
                            document.getElementById('txt_warn').innerText = '没有需要提交的人员';
                        }
                        if (json.result == '1') {
                            document.getElementById('txt_warn').innerText = '提交成功！';
                            window.location.href = '/activity/kzkt/findAllRegister';
                        }
                    },
                    error: function (xhr, status, errorThrown) {
                        alert("Sorry, there was a problem!");
                    }
                });

            });
        });
    </script>
</head>
<body class="body-gray">
<div class="container student_sign">
    <div class="weui_cells">

        @foreach($data as $index)
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <p>{{$index['name']}}</p>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <p class="text_center">{{$index['className']}}</p>
                </div>
                <div class="weui_cell_ft">
                    <img src="/image/kzkt/edit.png" alt="编辑" onclick="goEdit({{$index['id']}})">
                </div>
            </div>
        @endforeach

    </div>
    <img src="/image/kzkt/add.png" alt="继续添加新学员" onclick="goSignUp()">

    <p id="txt_warn" class="warning"></p>
    {{--<a class="weui_btn">确&emsp;认</a>--}}
    <button id="btn_save" type="button" class="weui_btn" style="width: 90%;">报&emsp;名</button>

</div>

</body>
</html>