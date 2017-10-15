<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2016内分泌代谢疾病 高级班课程表</title>
    <link rel="stylesheet" href="/css/kzkt.css">
    <link rel="stylesheet" href="/css/weui.css">
    <!-- 引入 jQuery 库 -->
    <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <script type="application/javascript">
        function goSignUp() {
            window.location.href = '/kzkt/signup';
        }

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

        $(document).ready(function () {

            var result = request('id');

            if (result == '-1') {
                $('#p_view').hide();
            }
            else {
                $('#p_view').show();
            }

        });

    </script>
</head>

<body>
<div class="weui_cells_title">
    <div class="tab_tj">
        <p>满足学员学习需求均可报名</p>
    </div>
    <div class="tab_bm" id="p_view" onclick="goSignUp();">
        <div>
            <p>报名</p>
        </div>
    </div>
</div>
<div style="padding: 3px;">
    <table id="customers">
        <tr>
            <th style="width: 25%">日期</th>
            <th style="width: 15%">星期</th>
            <th style="width: 35%">课程题目</th>
            <th style="width: 25%">授课专家</th>

        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/05/10</td>
            <td style="text-align: center">周二</td>
            <td>胰岛素的治疗</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/05/24</td>
            <td style="text-align: center">周二</td>
            <td>口服降糖药的选择和延展</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/06/14</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病的自我血糖监测</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/06/28</td>
            <td style="text-align: center">周二</td>
            <td>胰岛素泵的使用规范</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/07/12</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病急性并发症的诊断与治疗</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/07/26</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病的大血管病变</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/08/09</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病肾病</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/08/23</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病足病</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/09/13</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病神经病变</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/09/27</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病的综合管理</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>
        <tr class="alt_1">
            <td style="text-align: center">2016/10/11</td>
            <td style="text-align: center">周二</td>
            <td>妊娠糖尿病的筛查与管理</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/10/25</td>
            <td style="text-align: center">周二</td>
            <td>老年糖尿病</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>
    </table>
    </div>
</body>
</html>