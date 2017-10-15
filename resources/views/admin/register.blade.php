@extends("layouts.admin")

@section("content")
    <section class="content-header">
        <h1>
            报名信息管理
            <small></small>
        </h1>
    </section>
    <section class="content">
        <div query-panel style="line-height: 24px;margin: 5px 0 15px 0;">
            <div style="float: left;width: 215px;">
                <span>代表</span>
                <input type="text" name="v_phone" placeholder="请输入代理手机号" value="{{$query_arr['v_phone']}}">
            </div>
            <div style="float: left;width: 215px;">
                <span>医生</span>
                <input type="text" name="phone" placeholder="请输入医生手机号" value="{{$query_arr['phone']}}">
            </div>
            <div style="float: left;width: 215px;">
                <span>所属大区&nbsp;</span>
                <select style="width:100px;height: 30px;" name="belong_area">
                    <option value="">不限</option>
                    @foreach($areas as $area)
                        <option value="{{$area->province}}" @if($query_arr['area'] == $area->province) selected @endif>{{$area->province}}</option>
                    @endforeach
                </select>
            </div>
            <div style="float: left;width: 215px;">
                <span>所属DBM&nbsp;</span>
                <select style="width:100px;height: 30px;" name="belong_dbm">
                    <option value="">不限</option>
                    @foreach($dbms as $dbm)
                        <option value="{{$dbm->belong_dbm}}" @if($query_arr['dbm'] == $dbm->belong_dbm) selected @endif>{{$dbm->belong_dbm}}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <div operate-doctor-buttons>
            <button class="btn btn-default" data-form="add-model" data-toggle="modal" data-target="#formModal">添加</button>
            <a class="btn btn-default" href="{{url('/admin/baoming/import')}}" style="">导入</a>

            <button class="btn btn-default" onclick="exportDoctor();">导出</button>
            <button class="btn btn-default" onclick="querybaoming();">查询</button>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td>编号</td>
                <td>代表手机号</td>
                <td>姓名</td>
                <td>医生手机号</td>
                <td>邮箱</td>
                <td>QQ</td>
                <td>学习方式</td>
                <td>医院</td>
                <td>医院等级</td>
                <td>科室</td>
                <td>所在地</td>
                <td>报名项目</td>
                <td>报名状态</td>
                <td>所属大区</td>
                <td>所属DBM</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>
            @foreach($kzkts as $kzkt)
                <tr id="list-num-{{$kzkt->id}}">
                    <td>{{$kzkt->id}}</td>
                    <td data-field="volunteer_phone">{{isset($kzkt->volunteer->phone)?$kzkt->volunteer->phone:""}}</td>
                    <td data-field="doctor_name">{{isset($kzkt->doctor->name)?$kzkt->doctor->name:""}}</td>
                    <td data-field="doctor_phone">{{isset($kzkt->doctor->phone)?$kzkt->doctor->phone:""}}</td>
                    <td data-field="doctor_email">{{isset($kzkt->doctor->email)?$kzkt->doctor->email:""}}</td>
                    <td data-field="doctor_qq">{{isset($kzkt->doctor->qq)?$kzkt->doctor->qq:""}}</td>
                    <td data-field="doctor_style">@if($kzkt->style == 'phone') 电话 @elseif($kzkt->style == 'web') 网络 @else 网络，电话 @endif</td>
                    <td data-field="doctor_hospital">{{isset($kzkt->doctor->hospital)?$kzkt->doctor->hospital->hospital:""}}</td>
                    <td data-field="doctor_hospital_level">{{isset($kzkt->doctor->hospital)?$kzkt->doctor->hospital->hospital_level:""}}</td>
                    <td data-field="doctor_office">{{isset($kzkt->doctor->office)?$kzkt->doctor->office:""}}</td>
                    <td>{{isset($kzkt->doctor->hospital)?($kzkt->doctor->hospital->province).($kzkt->doctor->hospital->city).($kzkt->doctor->hospital->country):""}}</td>
                    <td data-field="project">空中课堂</td>
                    <td>@if($kzkt->status == '1') 报名成功 @elseif($kzkt->status == '0') 报名失败 @endif</td>
                    <td data-field="volunteer_belong_area">{{isset($kzkt->volunteer->represent->belong_area)?$kzkt->volunteer->represent->belong_area:""}}</td>
                    <td data-field="volunteer_belong_dbm">{{isset($kzkt->volunteer->represent->belong_dbm)?$kzkt->volunteer->represent->belong_dbm:""}}</td>
                    <td>
                        <button class="btn btn-default btn-sm" data-form="edit-model" data-toggle="modal" data-target="#formModal"
                                data-id="{{$kzkt->id}}"
                                data-status="{{$kzkt->status}}"
                                data-doctor_province="{{$kzkt->doctor->hospital->province ?? ''}}"
                                data-doctor_city="{{$kzkt->doctor->hospital->city ?? ''}}"
                                data-doctor_country="{{$kzkt->doctor->hospital->country ?? ''}}"
                                data-doctor_hospital_id="{{$kzkt->doctor->hospital->id ?? ''}}"
                        >修改</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center;">{!! $kzkts->appends($query_arr) !!}</div>
    </section>
    <div class="modal inmodal fade" id="formModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">编辑</h4>
                </div>
                <form method="post" id="form-validate-form" class="form-horizontal m-t">
                    <input type="hidden" name="id" id="form-id">
                    <div class="modal-body">
                        <form class="form-horizontal">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="col-sm-3 control-label">数据来源</label>
                                <div class="col-sm-9">
                                    <input id="form-v_phone" name="v_phone" type="text" class="form-control" placeholder="输入代理手机号">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">姓名</label>
                                <div class="col-sm-9">
                                    <input id="form-name" name="name" type="text" class="form-control" placeholder="输入姓名">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">手机号</label>
                                <div class="col-sm-9">
                                    <input id="form-phone" name="phone" type="text" class="form-control" placeholder="输入手机号">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">email</label>
                                <div class="col-sm-9">
                                    <input id="form-email" name="email" type="email" class="form-control" placeholder="输入email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">所在地</label>
                                <div class="row" id="city-select">
                                    <div class="col-sm-3">
                                        <select class="form-control" id="form-province" name="province">
                                            <option value="" selected="selected">-选择省-</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" id="form-city" name="city">
                                            <option value="" selected="selected">-选择市-</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" id="form-area" name="area">
                                            <option value="" selected="selected">-选择区-</option>
                                        </select>
                                    </div>
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
                                    <select id="form-office" name="office" class="form-control">
                                        <option value="" selected="selected">-选择科室-</option>
                                        @foreach($offices as $office)
                                            <option value="{{$office->office_name}}">{{$office->office_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">报名状态</label>
                                <div class="col-sm-9">
                                    <select id="form-status" name="status" class="form-control">
                                        <option value="1">成功</option>
                                        <option value="0">失败</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary btn-sm" id="form-validate-submit">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section("js")
    <script src="{{asset('plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/area-select2/jquery.area.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.get_hospital.js')}}"></script>
    <script>
        var subActionAjaxForMime = function (type, url, data, location) {
            $.ajax({
                type: type,
                url: url,
                data: data,
                success: function(res){
                    if(res.code == 200){
                        swal({
                            title: "成功",
                            type: "success",
                            confirmButtonColor: "#1ab394",
                            confirmButtonText: "确定",
                            closeOnConfirm: false
                        }, function () {
                            window.location.href = location;
                        });
                    }else {
                        swal({
                            title: "失败",
                            text: res.msg,
                            type: "warning",
                            confirmButtonColor: "#1ab394",
                            confirmButtonText: "确定",
                            closeOnConfirm: false
                        });
                    }
                },
                error:function (res) {
                    swal({
                        title: "失败",
                        text: res.responseText,
                        type: "warning",
                        confirmButtonColor: "#1ab394",
                        confirmButtonText: "确定",
                        closeOnConfirm: false
                    });
                }
            });
        };
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
                    get_hospital(lists);
                }
            });
        };

        function querybaoming(){
            var $panel = $('div[query-panel]');
            var area = $panel.find('select[name="belong_area"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var v_phone = $panel.find('input[name="v_phone"]').val();
            var belong_dbm = $panel.find('select[name="belong_dbm"]').val();
            window.location.href = "{{url('/admin/baoming')}}"
                    + "?area=" + area
                    + "&phone=" + phone
                    + "&v_phone=" + v_phone
                    + "&dbm=" + belong_dbm;
        }
        function exportDoctor(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('select[name="belong_area"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var hospital = $panel.find('select[name="belong_dbm"]').val();
            window.open("{{url('/admin/baoming/export')}}" + "?area=" + name + "&phone=" + phone + "&dbm=" +
                    hospital );
        }

        $(function () {
            var form_url = '{{url('/admin/baoming/post')}}';
            var index_url = window.location.href;
            $('#form-validate-submit').click(function () {
                var data = $('#form-validate-form').serialize();
                console.log(data);
                subActionAjaxForMime('post', form_url, data, index_url);
            });

            /**
             * 点击添加按钮触发的操作
             */
            $('[data-form="add-model"]').click(function () {
                var n = '';
                $('#form-id').val(n);
                $('#form-v_phone').val(n);
                $('#form-name').val(n);
                $('#form-email').val(n);
                $('#form-phone').val(n);
                $('#form-status').val(1);
                $('#form-province').val(n);
                $('#form-city').val(n);
                $('#form-area').val(n);
                $('#form-hospital').val(n);
                $('#form-office').val(n);
                var area_data = {
                    province: '',
                    city: '',
                    area: ''
                };
                select_area(area_data);
            });
            /**
             * 点击修改按钮触发的操作
             */
            $('[data-form="edit-model"]').click(function () {
                var id = $(this).attr('data-id');
                var parent = $('#list-num-' + id);

                var status = $(this).attr('data-status');
                var doctor_province = $(this).attr('data-doctor_province');
                var doctor_city = $(this).attr('data-doctor_city');
                var doctor_country = $(this).attr('data-doctor_country');
                var doctor_hospital_id = $(this).attr('data-doctor_hospital_id');

                var volunteer_phone = parent.find('td[data-field="volunteer_phone"]').text();
                var doctor_name = parent.find('td[data-field="doctor_name"]').text();
                var doctor_phone = parent.find('td[data-field="doctor_phone"]').text();
                var doctor_email = parent.find('td[data-field="doctor_email"]').text();
                var project = parent.find('td[data-field="project"]').text();
                var volunteer_belong_area = parent.find('td[data-field="volunteer_belong_area"]').text();
                var volunteer_belong_dbm = parent.find('td[data-field="volunteer_belong_dbm"]').text();
                var doctor_office = parent.find('td[data-field="doctor_office"]').text();

                $('#form-id').val(id);
                $('#form-v_phone').val(volunteer_phone);
                $('#form-name').val(doctor_name);
                $('#form-email').val(doctor_email);
                $('#form-phone').val(doctor_phone);
                $('#form-status').val(status);
                $('#form-province').val(doctor_province);
                $('#form-city').val(doctor_city);
                $('#form-area').val(doctor_country);
                $('#form-office').val(doctor_office);
                var area_data = {
                    province: doctor_province,
                    city: doctor_city,
                    area: doctor_country
                };
                select_area(area_data);
                get_hospital(area_data,doctor_hospital_id);
            });


        });

    </script>
@endsection