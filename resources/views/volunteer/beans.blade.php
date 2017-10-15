<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的迈豆</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">

    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/user_point.css">

</head>


<body>

<div class="container-fluid">
    <div class="row userBlock">
        <img class="userPic" src="{{$volunteer->headimgurl}}" alt=""/>
        <ul class="list-unstyled">
            <li class="userPoint">{{$volunteer->beans_total}}</li>
            <li>迈豆积分</li>
        </ul>
    </div>

    @foreach($volunteer->beans->sortByDesc('updated_at') as $bean)
        <div class="row project-x ">
            <div class="col-xs-6 ">
                <ul class="list-unstyled ">
                    <li>项目名称：{{$bean->activityBeanRate->activity_name}}</li>
                    <li>奖励来源：{{$bean->activityBeanRate->action_chs}}</li>
                    <li class="date">{{$bean->valid_time->format('Y-m-d')}}</li>
                </ul>
        </div>
            <div class="col-xs-6 projPoint">
                <span>500</span><span class="md">&nbsp;迈豆</span>
        </div>
    </div>
    @endforeach

</div>


<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>
</html>