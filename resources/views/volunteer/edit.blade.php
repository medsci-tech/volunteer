<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>修改信息</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">

    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">


    <link rel="stylesheet" href="/css/id_card_edit.css">

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

<div class="container-fluid">
    <div class="row">
        <form class="form-horizontal" action="{{url('/volunteer/update-self')}}" method="post">
            {{csrf_field()}}
            <div class="form-group">
                <div class="formDiv">
                    <input name="phone" value="{{$volunteer->phone}}" type="" class="form-control" id="inputPhone" placeholder="请输入新的手机号">
                    <label for="inputPassword3" class="formIcon login-field-icon"><span class="glyphicon glyphicon-phone"></span></label>
                </div>
                <div class="formDiv">
                    <input name="email" value="" type="email" class="form-control" id="inputEmail" placeholder="请输入新的邮箱">
                    <label for="inputPassword3" class="formIcon login-field-icon"><span class="glyphicon glyphicon-envelope"></span></label>
                </div>
                <div class="formDiv">
                    <input name="number" value="" type="text" class="form-control" id="inputNumber" placeholder="请输入新的工号">
                    <label for="inputPassword3" class="formIcon login-field-icon"><span class="glyphicon glyphicon-user"></span></label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success" role="button">确认修改</button>
            </div>
        </form>
    </div>
</div>


<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>
</html>