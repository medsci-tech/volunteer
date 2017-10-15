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
<div style="width: 400px;height:400px;margin: 100px auto 0 auto;">
    <!-- resources/views/auth/register.blade.php -->
    <form method="POST" action="{{url('/admin/register')}}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label class="form-label">用户名</label>
            <input class="form-control" type="text" name="name" value="{{ old('name') }}"/>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('email') }}"/>
        </div >

        <div class="form-group">
            <label class="form-label">密码</label>
            <input class="form-control" type="password" name="password"/>
        </div>

        <div class="form-group">
            <label class="form-label">确认密码</label>
            <input class="form-control" type="password" name="password_confirmation"/>
        </div>

        <div style="text-align: center;">
            <button style="width: 100%;" class="btn btn-primary" type="submit">注册</button>
        </div>
        <div style="text-align: center;margin-top:10px;"><a style="width:100%;"  class="btn btn-default"  href="{{url('/admin/login')}}">已注册,请登录</a></div>
    </form>
</div>
</body>
</html>