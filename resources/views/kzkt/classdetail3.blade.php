<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2016内分泌代谢疾病 精品班课程表</title>
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
    <p class="tab_p">报名条件</p>
    <p class="tab_p1">精品班满足以下两个条件之一即可报名：</p>
    <div class="tab_tj">
        <p>1. 参加过2015年空中课堂高级班，并且是合格学员</p>
        <p>2. 二级及二级以上医院涵盖内分泌相关疾病的专科医生</p>
    </div>
    <div id="p_view" class="tab_bm" onclick="goSignUp();">
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
            <td style="text-align: center">2016/05/06</td>
            <td style="text-align: center">周五</td>
            <td>特殊类型糖尿病的诊断与治疗（LADA，MODY）</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/05/20</td>
            <td style="text-align: center">周五</td>
            <td>特殊类型糖尿病的诊断与治疗病例分析</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/06/03</td>
            <td style="text-align: center">周五</td>
            <td>继发糖尿病</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/06/17</td>
            <td style="text-align: center">周五</td>
            <td>继发糖尿病病例分析</td>
            <td style="text-align: center">杨文英教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/07/08</td>
            <td style="text-align: center">周五</td>
            <td>妊娠糖尿病的诊疗规范</td>
            <td style="text-align: center">窦京涛教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/07/22</td>
            <td style="text-align: center">周五</td>
            <td>糖尿病国际研究进展</td>
            <td style="text-align: center">窦京涛教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/08/05</td>
            <td style="text-align: center">周五</td>
            <td>腺垂体疾病</td>
            <td style="text-align: center">窦京涛教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/08/19</td>
            <td style="text-align: center">周五</td>
            <td>腺垂体疾病病例分析</td>
            <td style="text-align: center">窦京涛教授</td>
        </tr>

        <tr class="alt_1">
            <td style="text-align: center">2016/09/02</td>
            <td style="text-align: center">周五</td>
            <td>甲状腺疾病</td>
            <td style="text-align: center">吕朝晖教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/09/16</td>
            <td style="text-align: center">周五</td>
            <td>甲状腺疾病病例分析</td>
            <td style="text-align: center">吕朝晖教授</td>
        </tr>
        <tr class="alt_1">
            <td style="text-align: center">2016/10/14</td>
            <td style="text-align: center">周五</td>
            <td>肾上腺疾病</td>
            <td style="text-align: center">吕朝晖教授</td>
        </tr>

        <tr class="alt">
            <td style="text-align: center">2016/10/28</td>
            <td style="text-align: center">周五</td>
            <td>肾上腺疾病病例分析</td>
            <td style="text-align: center">吕朝晖教授</td>
        </tr>
    </table>
</div>

</body>
</html>