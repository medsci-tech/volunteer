<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>空中课堂课程表</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/css/volunteer.css">
</head>

<body>

    <div class="page">
        <div class="page__bd">
            <div class="weui-panel weui-panel_access">
                {{--<div class="weui-panel__hd">课程表</div>--}}
                <div class="weui-panel__bd">
                    <div class="weui-media-box weui-media-box_appmsg">
                        <div class="weui-media-box__hd">
                            <img class="weui-media-box__thumb" style="vertical-align: middle;" src="/image/kzkt/airclass.png">
                        </div>
                        <div class="weui-media-box__bd">
                            <p class="weui-media-box__p">报名即可学习空课全部课程</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page__bd">
        @if($lists)
            @foreach($lists as $list)
            <h4 class="text-center" style="color: #921B23;margin: 20px 0 2px 0;">{{$list['unit_name']}}</h4>
            <table class="table striped">
                <thead>
                <tr>
                    <th width="15%" class="text-center">序号</th>
                    <th width="60%" class="text-center">课程</th>
                    <th width="20%" class="text-center">讲师</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list['unit_list'] as $key => $value)
                <tr>
                    <td class="text-center">{{$key+1}}</td>
                    <td>{{$value['name']}}</td>
                    <td class="text-center">{{$value['lecturer']}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endforeach
            @else
        @endif
        </div>
    </div>
<!-- 引入 jQuery 库 -->
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="/js/weui.min.js"></script>
<script type="application/javascript">

</script>
</body>
</html>