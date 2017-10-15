<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>医学志愿者后台管理</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{asset("/AdminLTE/bootstrap/css/bootstrap.min.css")}}">
</head>
<body>
<!-- resources/views/auth/login.blade.php -->
<div style="width: 400px;height:400px;margin: 100px auto 0 auto;">
    <form method="POST" action="{{url('/admin/login')}}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label class="control-label">Email</label>
            <input class="form-control"  type="email" name="email" value="{{ old('email') }}"/>
        </div>
        <div class="form-group">
            <label class="control-label">密码</label>
            <input class="form-control"  type="password" name="password"/>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember"/>记住我</label>
        </div>
        <div style="text-align: center;">
            <button style="width:100%;" class="btn btn-primary" type="submit">登录</button>
        </div>
        <div style="text-align: center;margin-top:10px;"><a style="width:100%;"  class="btn btn-default"  href="{{url('/admin/register')}}">新用户,请注册</a></div>
    </form>
</div>
</body>
</html>