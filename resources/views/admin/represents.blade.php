@extends("layouts.admin")

@section("content")
    <section class="content-header">
        <h1>
            代表资料信息管理
            <small></small>
        </h1>
    </section>
    <section class="content">
        <div query-panel style="line-height: 24px;margin: 5px 0 15px 0;">
            <div style="float: left;width: 215px;"><span>姓名&nbsp;</span><input type="text" name="name" placeholder="请输入姓名"/></div>
            <div style="float: left;width: 215px;"><span>电话&nbsp;</span><input type="text" name="phone" placeholder="请输入电话"></div>
            <div style="float: left;width: 215px;"><span>initial&nbsp;</span><input type="text" name="initial" placeholder="请输入initial"></div>
            <div style="float: left;width: 215px;"><span>所属大区&nbsp;</span>
                <select style="width:100px;height: 30px;" name="belong_area">
                    <option value="">不限</option>
                    @foreach($areas as $area)
                        <option value="{{$area->province}}">{{$area->province}}</option>
                    @endforeach
                </select>
            </div>
            <div style="float: left;width: 215px;"><span>所属DBM&nbsp;</span>
                <select style="width:100px;height: 30px;" name="belong_dbm">
                    <option value="">不限</option>
                    @foreach($dbms as $dbm)
                        <option value="{{$dbm->belong_dbm}}">{{$dbm->belong_dbm}}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <div operate-represent-buttons>
            <button class="btn btn-default">添加</button>
            <a class="btn btn-default" href="{{url('/admin/represent/import')}}">导入</a>
            <button class="btn btn-default" onclick="exportRepresent();">导出</button>
            <button class="btn btn-default" onclick="queryRepresent();">查询</button>
            <button class="btn btn-default" onclick="InitRepresent();" style="display:none;">同步</button>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td>
                    编号
                </td>
                <td>
                    姓名
                </td>
                <td>
                    电话
                </td>
                <td>
                    Initial
                </td>
                <td>
                    所属区域
                </td>
                <td>
                    所属DBM
                </td>
                <td>
                    所属项目
                </td>
                <td>
                    所属公司
                </td>
                <td>
                    操作
                </td>
            </tr>
            </thead>
            <tbody>
            @foreach($represents as $represent)
                <tr>
                    <td>{{$represent->id}}</td>
                    <td field="name">{{$represent->name}}</td>
                    <td field="phone">{{$represent->phone}}</td>
                    <td field="initial">{{$represent->initial}}</td>
                    <td field="belong_area">{{$represent->belong_area}}</td>
                    <td field="belong_dbm">{{$represent->belong_dbm}}</td>
                    <td field="belong_project">{{$represent->belong_project}}</td>
                    <td field="belong_company">{{$represent->belong_company}}</td>
                    <td style="padding: 2px 8px;">
                        <button class="btn btn-default btn-sm" onclick="editRepresent({{$represent->id}},this)">编辑</button>
                        <button class="btn btn-default btn-sm" onclick="deleteRepresent({{$represent->id}})">删除</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center;">{!! $represents->appends($query_arr)->render() !!}</div>
    </section>
    <div id="add-represent-modal" style="display: none;">
        <div style="width: 600px;">
            <form class="form-horizontal" onsubmit="return false;">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-sm-3 control-label">姓名</label>
                    <div class="col-sm-9">
                        <input name="name" type="text" class="form-control" placeholder="输入姓名">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">手机号</label>
                    <div class="col-sm-9">
                        <input name="phone" type="text" class="form-control" placeholder="输入手机号">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Initial</label>
                    <div class="col-sm-9">
                        <input name="initial" type="text" class="form-control" placeholder="输入Initial">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所属大区</label>
                    <div class="col-sm-9">
                        <select  name="belong_area"  class="form-control">
                            @foreach($areas as $area)
                            <option value="{{$area->province}}">{{$area->province}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所属DBM</label>
                    <div class="col-sm-9">
                        <input type="text" name="belong_dbm" class="form-control" placeholder="输入所属DBM">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所属公司</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="belong_company">
                            @foreach($units as $unit)
                            <option value="{{$unit->full_name}}">{{$unit->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所属项目</label>
                    <div class="col-sm-9">
                        @foreach($activities as $activity)
                        <label class="control-label"><input belong_project  value="{{$activity->title}}" name="belong_project[]" type="checkbox">{{$activity->title}}</label>
                        @endforeach
                    </div>
                </div>
                <div style="text-align: center;" button-control>
                    <button class="btn btn-primary" style="margin-right: 10px;">添加</button>
                    <button onclick="removeModal();" class="btn btn-default">取消</button>
                </div>

            </form>
        </div>
    </div>
@endsection
@section("js")
    <script type="text/javascript">
        var $bottons = $('div[operate-represent-buttons] button');
        $bottons.eq(0).click(function(){
            var d = dialog({
                id:'add-represent-modal',
                title:'添加代表',
                content:$('#add-represent-modal').html()
            });
            d.showModal();
            var $modal = $(d.node).find('form');
            $modal.find('div[button-control] button:eq(0)').click(function(){
                addRepresent(true);
            });
        });
        function addRepresent(isAdd){
            var d = dialog.get(isAdd ? 'add-represent-modal':'edit-represent-modal');
            var $modal = $(d.node);
            if(!$modal.find('form input[name="name"]').val()){
                alert('姓名不能为空');
                return;
            }
//            if(!$modal.find('form input[name="phone"]').val()){
//                alert('电话不能为空');
//                return;
//            }
            if(!$modal.find('form input[name="initial"]').val()){
                alert('initial不能为空');
                return;
            }
//            if($modal.find('form input[belong_project]:checked').size() == 0){
//                alert('至少选择一个项目');
//                return;
//            }
            $modal.block({message:"正在添加代表信息，请稍候..."});
            $modal.find('form').ajaxSubmit({
                url:"{{url('/admin/postRepresent')}}",
                type:'POST',
                dataType:'json',
                success:function(res){
                    $modal.unblock();
                    d.remove();
                    if(res && res.success){
                        window.location.reload();
                        alert(isAdd ? '添加成功':'编辑成功');
                        return;
                    }
                    alert( isAdd ? '添加失败' : '编辑失败');
                },
                error:function(){
                    $.unblockUI();
                }
            });
        }
        function removeModal(){
            dialog.getCurrent().remove();
        }
        function deleteRepresent(id){
            $.ajax({
                url:'{{url("/admin/deleteRepresent")}}' + "/" + id,
                type:'post',
                data:{'_token':'{{csrf_token()}}'},
                dataType:'json',
                success:function(res){
                    if(res && res.success){
                        alert('删除成功');
                        window.location.href = "{{url('/admin')}}";
                        return;
                    }
                    alert('删除失败');
                },
                error:function(){}
            })
        }

        function InitRepresent(){
                    $.ajax({
                        url:'{{url("/admin/Initrepresent")}}',
                        type:'get',
                        dataType:'json',
                        success:function(res){
                            if(res && res.success){
                                alert(res.msg);
                                window.location.href = "{{url('/admin')}}";
                                return;
                            }
                            alert(res.msg);
                        },
                        error:function($res){
                        //alert($res.message)
                        }
                    })
                }

        function editRepresent(id,element){
            var $tr = $(element).closest('tr');
            var d = dialog({
                id:'edit-represent-modal',
                title:'编辑代表',
                content:$('#add-represent-modal').html()
            });
            d.showModal();
            var $modal = $(d.node).find('form');
            $modal.append('<input type="hidden" name="id" value="' + id + '">');
            $modal.find('input[name="name"]').val($tr.find('td[field="name"]').text().trim());
            $modal.find('input[name="phone"]').val($tr.find('td[field="phone"]').text().trim());
            $modal.find('input[name="initial"]').val($tr.find('td[field="initial"]').text().trim());
            $modal.find('select[name="belong_area"]').val($tr.find('td[field="belong_area"]').text().trim());
            $modal.find('input[name="belong_dbm"]').val($tr.find('td[field="belong_dbm"]').text().trim());
            var projects = $tr.find('td[field="belong_project"]').text().trim().split(',');
            if(projects.length>0){
                $modal.find('input[belong_project]').each(function(){
                    for(var i=0;i<projects.length;i++){
                        if($(this).val() == projects[i]){
                            $(this).attr('checked','checked');
                            break;
                        }
                    }
                });
            }
            $modal.find('select[name="belong_company"]').val($tr.find('td[field="belong_company"]').text().trim());
            $modal.find('div[button-control] button:eq(0)').text("确定").click(function(){
                addRepresent(false);
            });
        }

        function queryRepresent(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('input[name="name"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var initial = $panel.find('input[name="initial"]').val();
            var belong_area = $panel.find('select[name="belong_area"]').val();
            var belong_dbm = $panel.find('select[name="belong_dbm"]').val();
            window.location.href = "{{url('/admin')}}" + "?name=" + name + "&phone=" + phone + "&initial=" + initial + "&belong_area=" + belong_area + "&belong_dbm=" + belong_dbm;
        }
        function exportRepresent(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('input[name="name"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var initial = $panel.find('input[name="initial"]').val();
            var belong_area = $panel.find('select[name="belong_area"]').val();
            var belong_dbm = $panel.find('select[name="belong_dbm"]').val();
            window.open("{{url('/admin/represent/export')}}" + "?name=" + name + "&phone=" + phone + "&initial=" + initial + "&belong_area=" + belong_area + "&belong_dbm=" + belong_dbm);
        }

        //取当前请求url中指定参数的值
        function getParameter(key) {
            if (key && location.search && location.search.indexOf("?") === 0) {
                var search_string = location.search.substr(1);
                var pairs = search_string.split("&");
                for (var i = 0; i < pairs.length; i++) {
                    var key_value = pairs[i].split('=');
                    if (key_value[0].toUpperCase() === key.toLocaleUpperCase() && key_value.length > 1) {
                        return decodeURIComponent(key_value[1]);
                    }
                }
            }
        }
        $(function(){
            var $panel = $('div[query-panel]');
            $panel.find('input[name="name"]').val(getParameter("name"));
            $panel.find('input[name="phone"]').val(getParameter("phone"));
            $panel.find('input[name="initial"]').val(getParameter("initial"));
            $panel.find('select[name="belong_area"]').val(getParameter("belong_area"));
            $panel.find('select[name="belong_dbm"]').val(getParameter("belong_dbm"));
        });
    </script>
@endsection
