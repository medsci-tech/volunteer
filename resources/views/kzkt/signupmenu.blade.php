<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <title>我的学员</title>
    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/css/volunteer.css">
</head>

<body>
<div class="container">
    <div class="page">
        <div class="page__bd">
            <div class="weui-panel__hd weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label" style="text-align-last: auto">{{$count}}人</label>
                    <span class="weui-form-preview__value">
                        <div class="weui-cell weui-cell_select">
                            <div class="weui-cell__bd">
                                <select id="select-suffice" class="weui-select" style="height: auto;line-height: normal;width: auto">
                                    @foreach(config('params')['study_situation'] as $key => $value)
                                        <option value="{{$key}}" @if($suffice == $key) selected @endif>{{$value}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="weui-panel__bd">
                @foreach($data as $list)
                <div class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">{{$list['name']}}</h4>
                        <p class="weui-media-box__desc">{{$list['hospital']}}</p>
                        <p class="weui-media-box__desc">{{$list['time']}}</p>
                    </div>
                    <div class="weui-media-box__bd text-right">
                        <a href="/kzkt/viewCard?id={{$list['id']}}" class="weui-btn weui-btn_mini weui-btn_primary my-students-btn">听课证</a>
                        <div style="height: 0;padding: 6px 0"></div>
                        <a href="/kzkt/study_progress?id={{$list['id']}}" class="weui-btn weui-btn_mini weui-btn_primary my-students-btn">学习进度</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="/js/weui.min.js"></script>
<script>
    $(function () {
        $('#select-suffice').on('change',function () {
            var val = $(this).val();
            window.location.href = '/kzkt/findAllRegister?suffice=' + val;
        })
    })
</script>
</body>
</html>