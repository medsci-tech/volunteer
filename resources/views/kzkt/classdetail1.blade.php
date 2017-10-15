<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2016内分泌代谢疾病 基础班课程表</title>
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
            <td style="text-align: center">2016/05/03</td>
            <td style="text-align: center">周二</td>
            <td>认识糖尿病</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/05/17</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病的控制目标和治疗路径</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/06/07</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病的控制目标和治疗路径</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/06/21</td>
            <td style="text-align: center">周二</td>
            <td>口服降糖药的降糖机制和合理选择</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/07/05</td>
            <td style="text-align: center">周二</td>
            <td>胰岛素的分类和发展史</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/07/19</td>
            <td style="text-align: center">周二</td>
            <td>理想的胰岛素治疗模式</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/08/02</td>
            <td style="text-align: center">周二</td>
            <td>胰岛素起始治疗方案</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/08/16</td>
            <td style="text-align: center">周二</td>
            <td>胰岛素优化治疗方案</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/09/06</td>
            <td style="text-align: center">周二</td>
            <td>胰岛素强化治疗方案</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/09/20</td>
            <td style="text-align: center">周二</td>
            <td>减少低血糖，安心达标</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>
        <tr class="alt_1">
            <td style="text-align: center">2016/10/18</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病急性并发症的管理</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/11/01</td>
            <td style="text-align: center">周二</td>
            <td>糖尿病慢性并发症的管理</td>
            <td style="text-align: center">许樟荣教授</td>
        </tr>
    </table>
</div>
</body>
</html>