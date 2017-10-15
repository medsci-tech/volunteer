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
            <div style="float: left;width: 215px;"><span>来源&nbsp;</span><input type="text" name="phone" placeholder="请输入代理手机号"/></div>
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
        <div operate-doctor-buttons>
            <button onclick="addKzkt();" class="btn btn-default" style="">添加</button>
            <a class="btn btn-default" href="{{url('/admin/baoming/import')}}" style="">导入</a>

            <button class="btn btn-default" onclick="exportDoctor();">导出</button>
            <button class="btn btn-default" onclick="querybaoming();">查询</button>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td>
                    编号
                </td>
                <td>
                    数据来源
                </td>
                <td>
                    姓名
                </td>
                <td>
                    手机号
                </td>
                <td>
                    邮箱
                </td>
                <td>
                    医院
                </td>
                <td>
                    科室
                </td>
                <td>
                    所在地
                </td>
                <td>
                    报名项目
                </td>
                <td>
                    报名课程
                </td>
                <td>
                    报名状态
                </td>
                <td>
                    所属大区
                </td>
                <td>
                    所属DBM
                </td>
                <td>
                    操作
                </td>
            </tr>
            </thead>
            <tbody>
            @foreach($kzkts as $kzkt)
                <tr>
                    <td>{{$kzkt->id}}</td>
                    <td field="v_phone">{{isset($kzkt->volunteer->phone)?$kzkt->volunteer->phone:""}}</td>
                    <td field="name">{{isset($kzkt->doctor->name)?$kzkt->doctor->name:""}}</td>
                    <td field="phone">{{isset($kzkt->doctor->phone)?$kzkt->doctor->phone:""}}</td>
                    <td field="email">{{isset($kzkt->doctor->email)?$kzkt->doctor->email:""}}</td>
                    <td>
                        {{isset($kzkt->doctor->hospital)?$kzkt->doctor->hospital->hospital:""}}
                    </td>
                    <td>
                         {{isset($kzkt->doctor->office)?$kzkt->doctor->office:""}}
                    </td>

                    <td>
                         {{isset($kzkt->doctor->hospital)?($kzkt->doctor->hospital->province).($kzkt->doctor->hospital->city).($kzkt->doctor->hospital->country):""}}
                    </td>
                    <td field="project">空中课堂</td>
                    <td field="type" type-id="{{$kzkt->type}}">
                    @if($kzkt->type == '1')
                        基础班
                    @elseif($kzkt->type == '2')
                        高级班
                    @elseif($kzkt->type == '3')
                        精品班
                    @endif
                    </td>
                    <td field="status" status-id="{{$kzkt->status}}">
                        @if($kzkt->status == '1')
                           报名成功
                                            @elseif($kzkt->status == '0')
                                                报名失败

                                            @endif
                    </td>
                    <td field="">
                        {{isset($kzkt->volunteer->represent->belong_area)?$kzkt->volunteer->represent->belong_area:""}}
                    </td>
                    <td>
                        {{isset($kzkt->volunteer->represent->belong_dbm)?$kzkt->volunteer->represent->belong_dbm:""}}
                    </td>
                    <td style="padding: 2px 8px;">
                        <button class="btn btn-default btn-sm" onclick="editkzkt({{$kzkt->id}},this)">编辑</button>
                         <button class="btn btn-default btn-sm" onclick="deletekzkt({{$kzkt->id}})">删除</button>
                       </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center;">{!! $kzkts->appends($query_arr) !!}</div>
    </section>
    <div id="edit-kzkt-modal" style="display: none;">
            <div style="width: 600px;">
                <form class="form-horizontal" onsubmit="return false;">
                    {{csrf_field()}}
                    <div class="form-group" id="v_phone">
                                            <label class="col-sm-3 control-label">数据来源</label>
                                            <div class="col-sm-9">
                                                <input name="v_phone" type="text" class="form-control" placeholder="输入代理手机号">
                                            </div>
                                        </div>
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
                                        <label class="col-sm-2 control-label">所在地</label>
                                        <div class="col-sm-3">
                                            <select name="province" class="form-control">
                                                @foreach($provinces as $province)
                                                    <option value="{{$province->province}}">{{$province->province}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="city" class="form-control"></select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="country" class="form-control"></select>
                                        </div>
                                    </div>
                    <div class="form-group" id="hospital_div">
                                        <label class="col-sm-3 control-label">所在医院</label>
                                        <div class="col-sm-9">
                                            <select  name="hospital"  class="form-control">
                                                @foreach($hospitals as $hospital)
                                                    <option value="{{$hospital->id}}">{{$hospital->hospital}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group"  id="office_div">
                                        <label class="col-sm-3 control-label">所在科室</label>
                                        <div class="col-sm-9">
                                            <select  name="office"  class="form-control">
                                                @foreach($offices as $office)
                                                    <option value="{{$office->office_id}}">{{$office->office_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                    <div class="form-group">
                       <label class="col-sm-3 control-label">报名课程</label>
                       <div class="col-sm-9">
                          <select  name="type"  class="form-control">
                                                      @foreach($class as $item)
                                                          <option value="{{$item->id}}">{{$item->type_name}}</option>
                                                      @endforeach
                                                  </select>
                       </div>
                       </div>
                    <div class="form-group">
                                            <label class="col-sm-3 control-label">报名状态</label>
                                            <div class="col-sm-9">
                                                <select  name="status"  class="form-control">
                                                                           <option value="1">成功</option>
                                                                           <option value="0">失败</option>
                                                                        </select>
                                            </div>
                                        </div>
                    <div style="text-align: center;" button-control>
                        <button onclick="postKzkt();" class="btn btn-primary" style="margin-right: 10px;">添加</button>
                        <button onclick="removeModal();" class="btn btn-default">取消</button>
                    </div>

                </form>
            </div>
        </div>
@endsection
@section("js")
    <link rel="stylesheet" href="{{asset("/AdminLTE/plugins/select2/select2.min.css")}}"/>
    <script type="text/javascript" src="{{url('/AdminLTE/plugins/select2/select2.js')}}"></script>
    <script type="text/javascript">
        function addKzkt(){
            var d = dialog({
                id:'edit-kzkt-modal',
                title:'添加报名信息',
                content:$('#edit-kzkt-modal').html()
            });
            d.showModal();
            d.type = "add";
            //bindSelect2($(d.node).find('select[name="hospital"]'));
            var $modal = $(d.node).find('form');
                        bindSelectProvince($modal.find('select[name="province"]'),$modal.find('select[name="city"]'),$modal.find('select[name="country"]'),$modal.find('select[name="hospital"]'));
                        bindSelectCountry($modal.find('select[name="city"]'),$modal.find('select[name="country"]'),$modal.find('select[name="hospital"]'),$modal.find('select[name="province"]'));
                        selectAjax($modal.find('select[name="province"]').val(),$modal.find('select[name="city"]'),null,$modal.find('select[name="country"]'),null,$modal.find('select[name="hospital"]'));
            bindSelectCity($modal.find('select[name="city"]'),$modal.find('select[name="country"]'),$modal.find('select[name="hospital"]'),$modal.find('select[name="province"]'));
        }

        function ajaxCity(province,callback){
                    if(province){
                        $.ajax({
                            url:'{{url("/admin/hospital/getCities")}}' + "?province=" + encodeURIComponent(province),
                            type:'get',
                            dataType:'json',
                            success:function(res){
                                if(res && res.success){
                                    callback && callback(res.cities);
                                }
                            },
                            error:function(){
                            }
                        });
                    }else{
                        callback && callback([]);
                    }
                }

                function ajaxCountries(city,callback){
                    if(city){
                        $.ajax({
                            url:'{{url("/admin/hospital/getCountries")}}' + "?city=" + encodeURIComponent(city),
                            type:'get',
                            dataType:'json',
                            success:function(res){
                                if(res && res.success){
                                    callback && callback(res.countries);
                                }
                            },
                            error:function(){
                            }
                        });
                    }else{
                        callback && callback([]);
                    }
                }
        function ajaxHospitals(province,city,country,callback){
                            ///if(city){

                                $.ajax({
                                    url:'{{url("/admin/hospital/getHospital")}}' + "?city=" + encodeURIComponent(city)
                                        +"&province=" + encodeURIComponent(province)+"&country=" + encodeURIComponent(country),
                                    type:'get',
                                    dataType:'json',
                                    success:function(res){
                                        if(res && res.success){
                                            callback && callback(res.hospital);
                                        }
                                    },
                                    error:function(){
                                    }
                                });
        }


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

        function postKzkt(){
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

            $modal.block({message:"正在添加医生信息，请稍候..."});
            $modal.find('form').ajaxSubmit({
                url:"{{url('/admin/baoming/post')}}",
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
        function deletekzkt(id){
            $.ajax({
                url:'{{url("/admin/baoming/delete")}}' + "/" + id,
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

        function editkzkt(id,element){
            var $tr = $(element).closest('tr');
            var d = dialog({
                id:'edit-kzkt-modal',
                title:'编辑报名信息',
                content:$('#edit-kzkt-modal').html()
            });
            d.type = 'edit';
            d.showModal();

            var $modal = $(d.node).find('form');
            $modal.append('<input type="hidden" name="id" value="' + id + '">');
            $modal.find('input[name="name"]').val($tr.find('td[field="name"]').text().trim());
            $modal.find('input[name="phone"]').val($tr.find('td[field="phone"]').text().trim());
            $modal.find('input[name="email"]').val($tr.find('td[field="email"]').text().trim());
            $modal.find('div[id="v_phone"]').hide();
            $modal.find('input[name="v_phone"]').val($tr.find('td[field="v_phone"]').text().trim());
            $modal.find('div[id="hospital_div"]').hide();
            $modal.find('div[id="office_div"]').hide();
            //var hospital_id = $tr.find('td[field="type"]').attr('type-id');
            //var hospital = $tr.find('td[field="type"]').text();
            //$modal.find('select[name="type"]').append('<option value="'+ hospital_id +'">'+ hospital +'<option>');
            //$modal.find('select[name="type"]').val(hospital_id);
            //bindSelect2($(d.node).find('select[name="type"]'));
            $modal.find('select[name="type"]').val($tr.find('td[field="type"]').attr('type-id'));
            $modal.find('select[name="status"]').val($tr.find('td[field="status"]').attr('status-id'));
            $modal.find('div[button-control] button:eq(0)').text("确定");
        }

        function querybaoming(){
            var $panel = $('div[query-panel]');
            var name = $panel.find('select[name="belong_area"]').val();
            var phone = $panel.find('input[name="phone"]').val();
            var hospital = $panel.find('select[name="belong_dbm"]').val();
            window.location.href = "{{url('/admin/baoming')}}" + "?area=" + name + "&phone=" + phone + "&dbm=" + hospital;
        }
        function exportDoctor(){
                    var $panel = $('div[query-panel]');
                    var name = $panel.find('select[name="belong_area"]').val();
                    var phone = $panel.find('input[name="phone"]').val();
                    var hospital = $panel.find('select[name="belong_dbm"]').val();
                    window.open("{{url('/admin/baoming/export')}}" + "?area=" + name + "&phone=" + phone + "&dbm=" +
                            hospital );
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
            $panel.find('input[name="phone"]').val(getParameter("phone"));
            $panel.find('select[name="belong_area"]').val(getParameter("area"));
            $panel.find('select[name="belong_dbm"]').val(getParameter("dbm"));
        });


                function bindSelectProvince($select_province,$select_city,$select_country,$select_hospital){
                            $select_province.change(function(){
                                var province = $select_province.val();
                                ajaxCity(province,function(cities){
                                    insertArrForSelect(cities,$select_city,true);
                                    ajaxCountries($select_city.val(),function(countries){
                                        insertArrForSelect(countries,$select_country,true);
                                        ajaxHospitals(province,$select_city.val(),$select_country.val(),function(hospital){
                                                                            insertArrForSelect(hospital,$select_hospital,false);
                                                                        });
                                    });
                                    ajaxHospitals(province,$select_city.val(),'',function(hospital){
                                                                        insertArrForSelect(hospital,$select_hospital,false);
                                                                    });
                                });
                                ajaxHospitals(province,'','',function(hospital){
                                    insertArrForSelect(hospital,$select_hospital,false);
                                });
                            });
                        }

                        function bindSelectCountry($select_city,$select_country,$select_hospital,$select_province){

                            $select_city.change(function(){
                                var province = $select_province.val();
                                var city = $select_city.val();
                                ajaxCountries(city,function(countries){
                                    insertArrForSelect(countries,$select_country,true);
                                    ajaxHospitals(province,city,$select_country.val(),function(hospital){
                                                                        insertArrForSelect(hospital,$select_hospital,false);
                                                                    });
                                });
                                ajaxHospitals(province,city,'',function(hospital){
                                    insertArrForSelect(hospital,$select_hospital,false);
                                });
                            });
                        }
                        function bindSelectCity($select_city,$select_country,$select_hospital,$select_province){

                                                    $select_country.change(function(){
                                                        var province = $select_province.val();
                                                        var city = $select_city.val();

                                                        ajaxHospitals(province,city,$select_country.val(),function(hospital){
                                                            insertArrForSelect(hospital,$select_hospital,false);
                                                        });
                                                    });
                                                }

                        function selectAjax(province,$select_city,city,$select_country,country,$select_hospital){
                                    if(province)
                                        ajaxCity(province,function(cities){
                                            insertArrForSelect(cities,$select_city,true);
                                            $select_city.val(city);
                                            ajaxCountries(city,function(countries){
                                                insertArrForSelect(countries,$select_country,true);
                                                $select_country.val(country);
                                                ajaxHospitals(province,city,country,function(hospitals){
                                                    insertArrForSelect(hospitals,$select_hospital,false)
                                                })
                                            });
                                        });
                                }
        function insertArrForSelect(arr,$select,includeAll){
                    var htmlArr = [];
                    if(includeAll){
                        htmlArr.push('<option value="0">所有</option>');
                    }
                    for(var i =0;i<arr.length;i++){
                        htmlArr.push('<option value="' + arr[i] + '">' + arr[i] + '</option>');
                    }
                    $select.html(htmlArr.join(''));
                }
    </script>
@endsection