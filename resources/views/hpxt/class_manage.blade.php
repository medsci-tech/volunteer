<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>班级管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/huangpu_index.css">
</head>

<body class="huangpu-body">

<div class="container-fluid">
    <br>
    <a href="/hpxt/class-application" class="btn btn-primary btn-lg btn-block">申请班级</a>
    @if(0==$classes->count())
    @else
        <h3>已申请班级:</h3>
    @endif

    @foreach($classes as $class)
        <div class="row project-x">
            <img class=" littlepic" src="/image/svg/huangpu_class.svg" alt="图标">
            <h4 class="col-heading">{{$class->name}}<span class="float-right">{{$class->state}}</span></h4>
        </div>
    @endforeach

</div>
<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>