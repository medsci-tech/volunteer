<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>我的信息</title>

    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/css/volunteer.css">
</head>

<body>
    <div class="page">
        <div class="page__hd">
            <div class="volunteer_thumb">
                <img src="{{$volunteer->headimgurl}}" alt="">
            </div>
        </div>

        <div class="page__bd">
        <div class="weui-form-preview">
            <div class="weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">姓名</label>
                    <span class="weui-form-preview__value">{{$volunteer->name}}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">手机号</label>
                    <span class="weui-form-preview__value">{{$volunteer->phone}}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">邮箱</label>
                    <span class="weui-form-preview__value">{{$volunteer->email}}</span>
                </div>
                {{--<div class="weui-form-preview__item">--}}
                    {{--<label class="weui-form-preview__label">所属大区</label>--}}
                    {{--<span class="weui-form-preview__value">{{isset($volunteer->represent->belong_area)?$volunteer->represent->belong_area:''}}</span>--}}
                {{--</div>--}}
                {{--<div class="weui-form-preview__item">--}}
                    {{--<label class="weui-form-preview__label">所属DBM</label>--}}
                    {{--<span class="weui-form-preview__value">{{isset($volunteer->represent->belong_dbm)?$volunteer->represent->belong_dbm:''}}</span>--}}
                {{--</div>--}}
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">initial</label>
                    <span class="weui-form-preview__value">{{$volunteer->number}}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">二维码</label>
                    <a href="/volunteer/qr_code?role=volunteer"><!--跳转到我的二维码页面-->
                    <span class="weui-form-preview__value">
                        <img width="24" height="24" src="/image/qr_code_icon.png">
                    </span>
                    </a>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">我的迈逗</label>
                    <a href="/volunteer/beans">
                        <span class="weui-form-preview__value">{{$bean}}</span>
                    </a>
                </div>
            </div>
        </div>
        </div>

    </div>
</body>
</html>