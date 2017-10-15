<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学习进度</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/weui2.css">
    <link rel="stylesheet" href="/css/volunteer.css">
</head>

<body>

<div class="page">

    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div style="margin: 28px 15px 15px 15px">
                <div class="weui-flex" style="margin: 0 11px 0 24px">
                    <div class="weui-flex__item">
                        <div class="weui-slider__handler">1</div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width: @if($doctor->rank > 1 ) 100% @endif;"></div>
                        </div>
                    </div>
                    <div class="weui-flex__item">
                        <div class="weui-slider__handler">2</div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" style="width: @if($doctor->rank > 2 ) 100% @endif;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="weui-slider__handler">3</div>
                    </div>
                </div>
                <div class="weui-flex">
                    <div class="weui-flex__item text-left">
                        等级一
                    </div>
                    <div class="weui-flex__item text-center">
                        等级二
                    </div>
                    <div class="weui-flex__item text-right">
                        等级三
                    </div>
                </div>
            </div>
            <div class="weui-panel__hd">学习记录</div>
            <div class="weui-panel__bd">
                @if($study_logs)
                @foreach($study_logs as $study_log)
                <div class="weui-media-box weui-media-box_text">
                    <h4 class="weui-media-box__title">{{$study_log->title}}</h4>
                    <div class="weui-flex">
                        <div class="weui-flex__item">学习次数：{{$study_log->study_count}}</div>
                        <div class="weui-flex__item">学习时长：
                            @if($study_log->format_date['hours'] > 0){{$study_log->format_date['hours']}}时@endif{{$study_log->format_date['minutes']}}分
                        </div>
                    </div>
                </div>
                @endforeach
                    @endif
            </div>
        </div>
    </div>
</div>
<!-- 引入 jQuery 库 -->
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="/js/weui.min.js"></script>
<script type="application/javascript">

</script>
</body>
</html>