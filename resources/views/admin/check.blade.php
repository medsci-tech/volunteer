@extends("layouts.admin")

@section("content")
    <section class="content-header">
        <h1>
            注册审核管理
            <small></small>
        </h1>
    </section>
    <section class="content">
        <div query-panel style="line-height: 24px;margin: 5px 0 15px 0;">
            <div style="float: left;width: 215px;"><span>姓名</span><input type="text" name="name" placeholder="请输入姓名"/></div>
            <div style="float: left;width: 215px;"><span>电话</span><input type="text" name="phone" placeholder="请输入电话"></div>
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
            <button class="btn btn-default" onclick="exportCheck();">导出</button>
            <button class="btn btn-default" onclick="queryRepresent();">查询</button>
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
            @foreach($volunteers as $volunteer)
                <tr>
                    <td>{{$volunteer->id}}</td>
                    <td field="name">{{$volunteer->name}}</td>
                    <td field="phone">{{$volunteer->phone}}</td>
                    <td field="initial">
                        {{$volunteer->number}}
                    </td>
                    <td field="belong_area">
                        @if(!empty($volunteer->represent))
                            {{$volunteer->represent->belong_area}}
                        @endif
                    </td>
                    <td field="belong_dbm">
                        @if(!empty($volunteer->represent))
                            {{$volunteer->represent->belong_dbm}}
                        @endif
                    </td>
                    <td field="belong_project">
                        @if(!empty($volunteer->represent))
                            {{$volunteer->represent->belong_project}}
                        @endif
                    </td>
                    <td field="belong_company">
                        @if(!empty($volunteer->unit))
                            {{$volunteer->unit->full_name}}
                        @endif
                    </td>
                    <td style="padding: 2px 8px;">
                        <button class="btn btn-default btn-sm" onclick="editVolunteer({{$volunteer->id}},this)">编辑</button>
                        <button class="btn btn-default btn-sm" onclick="showCheckModal({{$volunteer->id}},this)">审核</button>
                        <button class="btn btn-default btn-sm" onclick="deleteVolunteer({{$volunteer->id}})">删除</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center;">{!! $volunteers->appends($query_arr)->render() !!}</div>
    </section>
    <div id="edit-volunteer-modal" style="display: none;">
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
                    <button class="btn btn-primary" style="margin-right: 10px;" onclick="editPostVolunteer();">确定</button>
                    <button onclick="removeModal();" class="btn btn-default">取消</button>
                </div>
            </form>
        </div>
    </div>
    <div id="check-volunteer-modal" style="display:none;">
        <div style="width: 400px;" list-info>
            <table class="table table-bordered table-condensed">
                <tbody>
                </tbody>
            </table>
        </div>
        <div tool-button style="text-align: center;margin: 5px 0;"><button class="btn btn-primary" style="margin-right: 5px;">审核通过</button><button class="btn btn-danger">审核不通过</button></div>
    </div>
@endsection
@section("js")
    <script type="text/javascript">
        function removeModal(){
            dialog.getCurrent().remove();
        }
        function deleteVolunteer(id){
            $.ajax({
                url:'{{url("/admin/check/deleteVolunteer")}}' + "/" + id,
                type:'post',
                data:{'_token':'{{csrf_token()}}'},
                dataType:'json',
                success:function(res){
                    if(res && res.success){
                        alert('删除成功');
                        window.location.href = "{{url('/admin/check')}}";
                        return;
                    }
                    alert('删除失败');
                },
                error:function(){}
            })
        }

        function checkVolunteer(id,status){
            $.ajax({
                url:'{{url("/admin/check/checkVolunteer")}}' + "/" + id,
                type:'post',
                data:{'_token':'{{csrf_token()}}','status':status},
                dataType:'json',
                success:function(res){
                    if(res && res.success){
                        alert('操作成功');
                        window.location.href = "{{url('/admin/check')}}";
                        return;
                    }
                    else{
                    alert('操作失败');
                    }

                },
                error:function(){}
            })
        }

        function showCheckModal(id,element){
            var $tr = $(element).closest('tr');
            var d = dialog({
                id:'check-volunteer-modal',
                title:'审核',
                content:$('#check-volunteer-modal').html()
            });
            d.showModal();
            var $modal = $(d.node);
            var html = '<tr><td>姓名:</td><td>' + $tr.find('td[field="name"]').text().trim() + '</td></tr>';
            html += '<tr><td>电话:</td><td>' + $tr.find('td[field="phone"]').text().trim() + '</td></tr>';
            html += '<tr><td>initial:</td><td>' + $tr.find('td[field="initial"]').text().trim() + '</td></tr>';
            html += '<tr><td>所属区域:</td><td>' + $tr.find('td[field="belong_area"]').text().trim() + '</td></tr>';
            html += '<tr><td>所属DBM:</td><td>' + $tr.find('td[field="belong_dbm"]').text().trim() + '</td></tr>';
            html += '<tr><td>所属项目:</td><td>' + $tr.find('td[field="belong_project"]').text().trim() + '</td></tr>';
            html += '<tr><td>所属公司:</td><td>' + $tr.find('td[field="belong_company"]').text().trim() + '</td></tr>';
            $modal.find('div[list-info] table tbody').html(html);
            $modal.find('div[tool-button] button:eq(0)').click(function(){
                checkVolunteer(id,1);
            }).next().click(function(){
                checkVolunteer(id,-1);
            });
        }

        function editVolunteer(id,element){
            var $tr = $(element).closest('tr');
            var d = dialog({
                id:'edit-volunteer-modal',
                title:'编辑',
                content:$('#edit-volunteer-modal').html()
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
        }
        function editPostVolunteer(){
            var d = dialog.get('edit-volunteer-modal');
            var $modal = $(d.node);
            if(!$modal.find('form input[name="name"]').val()){
                alert('姓名不能为空');
                return;
            }
            if(!$modal.find('form input[name="phone"]').val()){
                alert('电话不能为空');
                return;
            }
            if(!$modal.find('form input[name="initial"]').val()){
                alert('initial不能为空');
                return;
            }
            if(!$modal.find('form select[name="belong_area"]').val()){
                            alert('所属大区不能为空');
                            return;
                        }
                        if(!$modal.find('form input[name="belong_dbm"]').val()){
                                        alert('所属DBM不能为空');
                                        return;
                                    }
                                    if(!$modal.find('form select[name="belong_company"]').val()){
                                                                            alert('所属公司不能为空');
                                                                            return;
                                                                        }
            if($modal.find('form input[belong_project]:checked').size() == 0){
                alert('至少选择一个项目');
                return;
            }
            $modal.block({message:"正在编辑信息，请稍候..."});
            $modal.find('form').ajaxSubmit({
                url:"{{url('/admin/check/postVolunteer')}}",
                type:'POST',
                dataType:'json',
                success:function(res){
                    $modal.unblock();
                    d.remove();
                    if(res && res.success){
                        window.location.reload();
                        alert('编辑成功');
                        return;
                    }
                    alert( '编辑失败');
                },
                error:function(){
                    $.unblockUI();
                }
            });
        }
        function queryRepresent(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('input[name="name"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var belong_area = $panel.find('select[name="belong_area"]').val();
            var belong_dbm = $panel.find('select[name="belong_dbm"]').val();
            window.location.href = "{{url('/admin/check')}}" + "?name=" + name + "&phone=" + phone + "&belong_area=" + belong_area + "&belong_dbm=" + belong_dbm;
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

        function exportCheck(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('input[name="name"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var belong_area = $panel.find('select[name="belong_area"]').val();
            var belong_dbm = $panel.find('select[name="belong_dbm"]').val();
            window.open("{{url('/admin/check/export')}}" + "?name=" + name + "&phone=" + phone + "&belong_area=" + belong_area + "&belong_dbm=" + belong_dbm);
        }

        $(function(){
            var $panel = $('div[query-panel]');
            $panel.find('input[name="name"]').val(getParameter("name"));
            $panel.find('input[name="phone"]').val(getParameter("phone"));
            $panel.find('select[name="belong_area"]').val(getParameter("belong_area"));
            $panel.find('select[name="belong_dbm"]').val(getParameter("belong_dbm"));
        });
    </script>
@endsection