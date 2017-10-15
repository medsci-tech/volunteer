@extends("layouts.admin")

@section("content")
    <section class="content-header">
        <h1>
            医生信息管理
            <small></small>
        </h1>
    </section>
    <section class="content">
        <div query-panel style="line-height: 24px;margin: 5px 0 15px 0;">
            <div style="float: left;width: 215px;"><span>姓名&nbsp;</span><input type="text" name="name" placeholder="请输入姓名"/></div>
            <div style="float: left;width: 215px;"><span>电话&nbsp;</span><input type="text" name="phone" placeholder="请输入电话"></div>
            <div style="float: left;width: 235px;"><span>所在医院&nbsp;</span><input type="text" name="hospital" placeholder="请输入所在医院"></div>
            <div class="clearfix"></div>
            <div style="width:610px;margin: 5px 0;">
                <span>所在地</span>
                <div class="row" id="city-select">
                    <div class="col-sm-3">
                        <select class="form-control" name="province">
                            <option value="" selected="selected">-选择省-</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" name="city">
                            <option value="" selected="selected">-选择市-</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" name="area">
                            <option value="" selected="selected">-选择区-</option>
                        </select>
                    </div>
                    <input type="hidden" id="save-province" name="save-province" value="{{$query_arr['province']}}">
                    <input type="hidden" id="save-city" name="save-city" value="{{$query_arr['city']}}">
                    <input type="hidden" id="save-area" name="save-area" value="{{$query_arr['area']}}">
                </div>
            </div>
        </div>
        <div operate-doctor-buttons>
            {{--<button onclick="addDoctor();" class="btn btn-default">添加</button>--}}
            <a class="btn btn-default" href="{{url('/admin/doctor/import')}}">导入</a>
            <button class="btn btn-default" onclick="exportDoctor();">导出</button>
            <button class="btn btn-default" onclick="queryDoctor();">查询</button>
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
                    邮箱
                </td>
                <td>
                    所在地
                </td>
                <td>
                    所属医院
                </td>
                <td>
                    所属科室
                </td>
                <td>
                    操作
                </td>
            </tr>
            </thead>
            <tbody>
            @foreach($doctors as $doctor)
                <tr>
                    <td>{{$doctor->id}}</td>
                    <td field="name">{{$doctor->name}}</td>
                    <td field="phone">{{$doctor->phone}}</td>
                    <td field="email">{{$doctor->email}}</td>
                    <td field="area" data-province="{{$doctor->hospital->province ?? ''}}" data-province="{{$doctor->hospital->city ?? ''}}" data-province="{{$doctor->hospital->country ?? ''}}">{{isset($doctor->hospital)?($doctor->hospital->province).($doctor->hospital->city).($doctor->hospital->country):""}}</td>
                    <td field="hospital" hospital-id="{{$doctor->hospital_id}}">{{isset($doctor->hospital)?$doctor->hospital->hospital:""}}</td>
                    <td field="office" office-id="{{$doctor->office}}">{{isset($doctor->office)?$doctor->office:""}}</td>
                    <td style="padding: 2px 8px;">
                        {{--<button class="btn btn-default btn-sm" onclick="editDoctor('{{$doctor->id}}',this)">编辑</button>--}}
                        {{--<button class="btn btn-default btn-sm" onclick="deleteDoctor('{{$doctor->id}}')">删除</button>--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center;">{!! $doctors->appends($query_arr)->render() !!}</div>
    </section>
    <div id="add-doctor-modal" style="display: none;">
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
                    <label class="col-sm-3 control-label">email</label>
                    <div class="col-sm-9">
                        <input name="email" type="email" class="form-control" placeholder="输入email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所在医院</label>
                    <div class="col-sm-9">
                        <select id="form-hospital" name="hospital" class="form-control">
                            <option value="" selected="selected">-选择医院-</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所在科室</label>
                    <div class="col-sm-9">
                        <select  name="office"  class="form-control">
                            @foreach($offices as $office)
                                <option value="{{$office->office_id}}">{{$office->office_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="text-align: center;" button-control>
                    <button onclick="postDoctor();" class="btn btn-primary" style="margin-right: 10px;">添加</button>
                    <button onclick="removeModal();" class="btn btn-default">取消</button>
                </div>

            </form>
        </div>
    </div>
@endsection
@section("js")
    <link rel="stylesheet" href="{{asset("/AdminLTE/plugins/select2/select2.min.css")}}"/>
    <script type="text/javascript" src="{{url('/AdminLTE/plugins/select2/select2.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/area-select2/jquery.area.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.get_hospital.js')}}"></script>
    <script type="text/javascript">

        var select_area = function (area) {
            $('#city-select').citys({
                required:false,
                nodata:'',
                province:area.province,city:area.city,area:area.area,
                onChange:function(data){
                    var lists = {};
                    if(data['direct']){
                        lists.province = data.province;
                        lists.city = data.province;
                        lists.area = data.city;
                    }else {
                        lists.province = data.province;
                        lists.city = data.city;
                        lists.area = data.area;
                    }
                    $('#save-province').val(lists.province);
                    $('#save-city').val(lists.city);
                    $('#save-area').val(lists.area);
                }
            });
        };
//        function addDoctor(){
//            var d = dialog({
//                id:'add-doctor-modal',
//                title:'添加医生',
//                content:$('#add-doctor-modal').html()
//            });
//            d.showModal();
//            d.type = "add";
//            bindSelect2($(d.node).find('select[name="hospital"]'));
//        }

        function bindSelect2($select){
            $select.select2({
                ajax: {
                    url: "{{url('/admin/doctor/searchHospital')}}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data.items
                        };
                    }
                },
                minimumInputLength: 1
            });
        }

        function postDoctor(){
            var d = dialog.getCurrent();
            var isAdd = d.type == "add";
            var $modal = $(d.node);
            if(!$modal.find('form input[name="name"]').val()){
                alert('姓名不能为空');
                return;
            }
            if(!$modal.find('form input[name="phone"]').val()){
                alert('电话不能为空');
                return;
            }
            if(!$modal.find('form select[name="hospital"]').val()){
                alert('所在医院不能为空');
                return;
            }
            if(!$modal.find('form select[name="office"]').val()){
                alert('所在科室不能为空');
                return;
            }
            $modal.block({message:"正在添加医生信息，请稍候..."});
            $modal.find('form').ajaxSubmit({
                url:"{{url('/admin/doctor/post')}}",
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
        function deleteDoctor(id){
            $.ajax({
                url:'{{url("/admin/doctor/delete")}}' + "/" + id,
                type:'post',
                data:{'_token':'{{csrf_token()}}'},
                dataType:'json',
                success:function(res){
                    if(res && res.success){
                        alert('删除成功');
                        window.location.reload();
                        return;
                    }
                    alert('删除失败');
                },
                error:function(){}
            })
        }

        function editDoctor(id,element){
            var $tr = $(element).closest('tr');
            var d = dialog({
                id:'edit-doctor-modal',
                title:'编辑医生',
                content:$('#add-doctor-modal').html()
            });
            d.type = 'edit';
            d.showModal();

            var $modal = $(d.node).find('form');
            $modal.append('<input type="hidden" name="id" value="' + id + '">');
            $modal.find('input[name="name"]').val($tr.find('td[field="name"]').text().trim());
            $modal.find('input[name="phone"]').val($tr.find('td[field="phone"]').text().trim());
            $modal.find('input[name="email"]').val($tr.find('td[field="email"]').text().trim());
            var hospital_id = $tr.find('td[field="hospital"]').attr('hospital-id');
            var hospital = $tr.find('td[field="hospital"]').text();
            $modal.find('select[name="office"]').val($tr.find('td[field="office"]').attr('office-id'));
            $modal.find('div[button-control] button:eq(0)').text("确定");
        }

        function queryDoctor(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('input[name="name"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var hospital = $panel.find('input[name="hospital"]').val();
            var province = $('#save-province').val();
            var city = $('#save-city').val();
            var area = $('#save-area').val();
            console.log(province);
            window.location.href = "{{url('/admin/doctor')}}" + "?name=" + name + "&phone=" + phone + "&hospital=" +
                    hospital + "&province=" + province + "&city=" + city + "&area=" + area;
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






        function exportDoctor(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('input[name="name"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var hospital = $panel.find('input[name="hospital"]').val();
            var province = $panel.find('select[name="province"]').val()|| "";
            var city = $panel.find('select[name="city"]').val();
            var country = $panel.find('select[name="country"]').val();
            window.open("{{url('/admin/doctor/export')}}" + "?name=" + name + "&phone=" + phone + "&hospital=" +
                    hospital + "&province=" + province + "&city=" + city + "&country=" + country)
        }
        $(function(){
            var $panel = $('div[query-panel]');
            $panel.find('input[name="name"]').val(getParameter("name"));
            $panel.find('input[name="phone"]').val(getParameter("phone"));
            $panel.find('input[name="hospital"]').val(getParameter("hospital"));
            var area_data = {
                province: $('#save-province').val(),
                city: $('#save-city').val(),
                area: $('#save-area').val()
            };
            select_area(area_data);
        });
    </script>
@endsection