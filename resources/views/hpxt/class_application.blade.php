<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>申请班级</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/huangpu_index.css">
</head>
<body>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>错误！</strong> 请检查您的输入。<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <br>

    <form action="hpxt/class-store" class="form-horizontal" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
            <label class="form-label col-xs-4">班级名称</label>

            <div class="col-xs-8">
                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="请输入班级名称">
            </div>
        </div>
        <hr style="">
        <div class="form-group">
            <label class="form-label col-xs-4">班级规模</label>

            <div class="col-xs-8">
                <select class="form-control" name="{{old('scale_id')}}">
                    <option selected hidden>请选择班级规模</option>
                    @foreach($scales as $scale)
                        <option value="{{$scale['id']}}">{{$scale['scale']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="form-label col-xs-4">班级形式</label>

            <div class="col-xs-8">
                <select class="form-control" name="{{old('mode_id')}}">
                    <option selected hidden>请选择班级形式</option>
                    @foreach($modes as $mode)
                        <option value="{{$mode['id']}}">{{$mode['mode']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="form-label col-xs-4">招募时间</label>

            <div class="col-xs-8">
                <input type="date" class="form-control inputdate" name="time" value="{{old('time')}}" placeholder="请输入招募时间">
            </div>
        </div>
        <hr>
        <div><label class="form-label">班级导师</label><a href="class-application-add-doctor"  class="float-right">点击添加导师</a></div>
        <div>
            <div class="media-left media-middle">
                <img class="docpic" src="/image/icons/private.png" alt="...">
            </div>
            <div class="media-body">
                <br>
                <h4 class="media-heading">某某某&nbsp;
                    <small>主治医师</small>
                </h4>
                <p>中南医院呼吸科</p>
            </div>
        </div>
        <div>
            <div class="media-left media-middle">
                <img class="docpic" src="/image/icons/private.png" alt="...">
            </div>
            <div class="media-body">
                <br>
                <h4 class="media-heading">某某某&nbsp;
                    <small>主治医师</small>
                </h4>
                <p>中南医院呼吸科</p>
            </div>
        </div>
        <hr>
        <div><label class="form-label">班级助教</label><a href="class-application-add-assistant" class="float-right">点击添加助教</a></div>
        <div>
            <div class="media-left media-middle">
                <img class="docpic" src="/image/icons/private.png" alt="...">
            </div>
            <div class="media-body">
                <br>
                <h4 class="media-heading">某某某&nbsp;
                    <small>主治医师</small>
                </h4>
                <p>中南医院呼吸科</p>
            </div>
        </div>
        <hr>
        <button class="btn btn-block btn-primary btn-lg" type="submit">提交申请</button>
    </form>

    <br>
</div>


<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>