<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <title>空中课堂介绍</title>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/kzkt_index.css">
    <style>
        .color_red{
            color: #8c1d23;
        }
        .content-box .list_p{
            padding-left: 2em;
        }
        /*.content-box .list_p span{*/
            /*position: absolute;*/
            /*left: 15px;*/
        /*}*/
    </style>
</head>
<body>

<!--
	作者：a_xiao_yu@126.com
	时间：2017-01-05
	描述：大背景
-->
<div class="big-bg_img"><img src="/image/kzkt/big_backend_image.jpg"></div>

<div id="container" class="container clearfix">

    <!--
    	作者：a_xiao_yu@126.com
    	时间：2017-01-05
    	描述：头部
    -->
    <div id="top-box1" class="top-box clearfix">
        <div class="top-logo"><img src="/image/kzkt/kzkt_logo.png"></div>
        <div class="top-head clearfix">
            <h3>项目介绍</h3>
            <a class="btn btn-white" href="/kzkt/signup">报名</a>
        </div>
    </div>


    <div id="top-box2" class="top-box2">
        <a class="btn btn-white" href="/kzkt/signup">报名</a>
    </div>

    <!--
    	作者：a_xiao_yu@126.com
    	时间：2017-01-05
    	描述：内容
    -->
    <div class="content-box">
        <p>2017空中课堂是国内大型内分泌在线教育课程，由全国数名内分泌代谢领域的知名专家共同参与制作，聚焦基层医生必备的理论知识、诊疗规范、临床热点和疑难困惑，将专家授课与学员互动结合起来，帮助广大基层医生提升临床技能、规范基层糖尿病及其他疾病的管理。</p>
        <p class="color_red">2017年4月，空中课堂已正式上线，期待您的加入</p>
    </div>

    <ul class="content-box">
        <li><span>课程主办：</span>蓝海联盟（北京）医学研究院</li>
        <li><span>报名时间：</span>2017年04月01日-2017年10月31日</li>
        <li><span>开课时间：</span>2017年04月01日-2017年11月30日</li>
    </ul>

    <ul class="content-box">
        {{--<li><span>课程主办</span></li>--}}
        <li class="list_p">
            <span>课时设置：</span>
            总课时：87节。共分为6个专题：糖尿病基础知识、糖尿病的药物治疗、糖尿病管理、糖尿病并发症管理、特殊类型/人群糖尿病和其他内分泌疾病。
        </li>
        <li class="list_p">
            <span>学习权限：</span>
            学员学习采取等级晋升制。第一等级（必修课18节+答疑课）；第二等级（必修18节+选修19节+答疑课）；第三等级：37节公开课+答疑课+私教课
        </li>
    </ul>

    <div class="content-box content-box-last">
        <img src="/image/kzkt/kzkt_introduction.jpg">
    </div>
</div>

<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script>

    $(function () {
        // 背景平铺
        var con_img = $('.container').height();
        var bg_img = $('.big-bg_img').height();
        if(con_img > bg_img){
            $('.big-bg_img').height(con_img)
        }

        // 报名按钮定位
        var top1 = $('#top-box1');
        var top2 = $('#top-box2');
        var container = $('#container');
        window.onscroll = function (e) {
            var time = 10;
            var roll  = $(document).scrollTop();
            if(roll > 50){
                top1.hide();
                top2.show();
                container.css({'padding-top': '100px'});
            }else {
                top2.hide();
                top1.show();
                container.css({'padding-top': '0'});

            }

            console.log(roll);
        };
    })
</script>
</body>
</html>