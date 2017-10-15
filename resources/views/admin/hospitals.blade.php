@extends("layouts.admin")

@section("content")
    <section class="content-header">
        <h1>
            医院管理
            <small></small>
        </h1>
    </section>
    <section class="content">
        <div query-panel style="width: 80%;">
            <div style="padding: 5px 0;">
                <div class="col-sm-2" style="line-height: 34px;">
                    <span>所在地</span>
                </div>
                <div class="col-sm-8">
                    <select style="display: inline-block;width: 33%" name="province" class="form-control">
                        <option value="">所有</option>
                        @foreach($provinces as $province)
                            <option value="{{$province->province}}">{{$province->province}}</option>
                        @endforeach
                    </select>
                    <select style="display: inline-block;width: 33%" name="city" class="form-control">
                        <option value="">所有</option>
                    </select>
                    <select style="display: inline-block;width: 32%" name="country" class="form-control">
                        <option value="">所有</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-default" onclick="queryRepresent();">查询</button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div style="padding: 5px 0;">
                <div class="col-sm-2" style="line-height: 34px;">
                    <span>医院名称</span>
                </div>
                <div class="col-sm-8">
                    <input name="name" type="text" class="form-control" placeholder="输入医院名称关键字">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div operate-represent-buttons style="margin-top:5px;">
            <button class="btn btn-default" onclick="addHospital();">添加</button>
            <a class="btn btn-default" href="{{url('/admin/hospital/import')}}">导入</a>
            <button class="btn btn-default" onclick="exportHospital();">导出</button>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td>
                    编号
                </td>
                <td>
                    医院名称
                </td>
                <td>
                    省
                </td>
                <td>
                    市
                </td>
                <td>
                    区/县
                </td>
                <td>
                    医院等级
                </td>
                <td>
                    操作
                </td>
            </tr>
            </thead>
            <tbody>
            @foreach($hospitals as $hospital)
                <tr>
                    <td>{{$hospital->id}}</td>
                    <td field="name">{{$hospital->hospital}}</td>
                    <td field="province">{{$hospital->province}}</td>
                    <td field="city">{{$hospital->city}}</td>
                    <td field="country">{{$hospital->country}}</td>
                    <td field="country">{{$hospital->hospital_level}}</td>
                    <td style="padding: 2px 8px;">
                        <button class="btn btn-default btn-sm" onclick="editHospital({{$hospital->id}},this)">编辑</button>
                        <button class="btn btn-default btn-sm" onclick="deleteHospital({{$hospital->id}})">删除</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center;">{!! $hospitals->appends($query_arr)->render() !!}</div>
    </section>
    <div id="edit-hospital-modal" style="display: none;">
        <div style="width: 600px;">
            <form class="form-horizontal" onsubmit="return false;">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-sm-2 control-label">医院名称</label>
                    <div class="col-sm-9">
                        <input name="name" type="text" class="form-control" placeholder="输入医院">
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
                <div style="text-align: center;" button-control>
                    <button class="btn btn-primary" style="margin-right: 10px;" onclick="editPostHospital();">确定</button>
                    <button onclick="removeModal();" class="btn btn-default">取消</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section("js")
    <script type="text/javascript">
        function removeModal(){
            dialog.getCurrent().remove();
        }
        function deleteHospital(id){
            $.ajax({
                url:'{{url("/admin/hospital/delete")}}' + "/" + id,
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

        function addHospital(){
            var d = dialog({
                id:'add-hospital-modal',
                title:'添加',
                content:$('#edit-hospital-modal').html()
            });
            d.type = "add";
            d.showModal();
            var $modal = $(d.node).find('form');
            bindSelectProvince($modal.find('select[name="province"]'),$modal.find('select[name="city"]'),$modal.find('select[name="country"]'));
            bindSelectCountry($modal.find('select[name="city"]'),$modal.find('select[name="country"]'));
            selectAjax($modal.find('select[name="province"]').val(),$modal.find('select[name="city"]'),null,$modal.find('select[name="country"]'),null);
        }

        function editHospital(id,element){
            var $tr = $(element).closest('tr');
            var d = dialog({
                id:'edit-hospital-modal',
                title:'编辑',
                content:$('#edit-hospital-modal').html()
            });
            d.type = "edit";
            d.showModal();
            var $modal = $(d.node).find('form');
            $modal.append('<input type="hidden" name="id" value="' + id + '">');
            $modal.find('input[name="name"]').val($tr.find('td[field="name"]').text().trim());
            var province = $tr.find('td[field="province"]').text().trim();
            var city = $tr.find('td[field="city"]').text().trim();
            var country = $tr.find('td[field="country"]').text().trim();
            var $province = $modal.find('select[name="province"]');
            var $city = $modal.find('select[name="city"]');
            var $country = $modal.find('select[name="country"]');
            $province.val(province);
            selectAjax(province,$city,city,$country,country);
            bindSelectProvince($province,$city,$country);
            bindSelectCountry($city,$country);
        }

        function selectAjax(province,$select_city,city,$select_country,country){
            if(province)
                ajaxCity(province,function(cities){
                    insertArrForSelect(cities,$select_city,true);
                    $select_city.val(city);
                    ajaxCountries(city,function(countries){
                        insertArrForSelect(countries,$select_country,true);
                        $select_country.val(country);
                    });
                });
        }

        function insertArrForSelect(arr,$select,includeAll){
            var htmlArr = [];
            if(includeAll){
                htmlArr.push('<option value="">所有</option>');
            }
            for(var i =0;i<arr.length;i++){
                htmlArr.push('<option value="' + arr[i] + '">' + arr[i] + '</option>');
            }
            $select.html(htmlArr.join(''));
        }

        function bindSelectProvince($select_province,$select_city,$select_country){
            $select_province.change(function(){
                var province = $select_province.val();
                ajaxCity(province,function(cities){
                    insertArrForSelect(cities,$select_city,true);
                    ajaxCountries($select_city.val(),function(countries){
                        insertArrForSelect(countries,$select_country,true);
                    });
                });
            });
        }

        function bindSelectCountry($select_city,$select_country){
            $select_city.change(function(){
                var city = $select_city.val();
                ajaxCountries(city,function(countries){
                    insertArrForSelect(countries,$select_country,true);
                });
            });
        }

        function editPostHospital(){
            var d = dialog.getCurrent();
            var $modal = $(d.node);
            var isEdit = d.type == "edit";
            if(!$modal.find('form input[name="name"]').val()){
                alert('医院名不能为空');
                return;
            }
            if(!$modal.find('form select[name="province"]').val()){
                alert('省不能为空');
                return;
            }
            if(!$modal.find('form select[name="city"]').val()){
                alert('市不能为空');
                return;
            }
            $modal.block({message:isEdit?"正在编辑信息，请稍候...":"正在新增信息，请稍候..."});
            $modal.find('form').ajaxSubmit({
                url:"{{url('/admin/hospital/post')}}",
                type:'POST',
                dataType:'json',
                success:function(res){
                    $modal.unblock();
                    d.remove();
                    if(res && res.success){
                        window.location.reload();
                        alert(isEdit?'编辑成功':"添加成功");
                        return;
                    }
                    alert( isEdit?'编辑失败':"添加失败");
                },
                error:function(){
                    $.unblockUI();
                }
            });
        }
        function queryRepresent(){
            var $panel = $('div[query-panel]');
            var province = $panel.find('select[name="province"]').val();
            var city = $panel.find('select[name="city"]').val();
            var country = $panel.find('select[name="country"]').val();
            var name = $panel.find('input[name="name"]').val();
            window.location.href = "{{url('/admin/hospital')}}" + "?province=" + province + "&city=" + city + "&country=" + country + "&name=" + name;
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

        function exportHospital(){
            var $panel = $('div[query-panel]');
            var province = $panel.find('select[name="province"]').val();
            var city = $panel.find('select[name="city"]').val();
            var country = $panel.find('select[name="country"]').val();
            var name = $panel.find('input[name="name"]').val();
            window.open("{{url('/admin/hospital/export')}}" + "?province=" + province + "&city=" + city + "&country=" + country + "&name=" + name);
        }

        $(function(){
            var $panel = $('div[query-panel]');
            var province = getParameter('province');
            var $province = $panel.find('select[name="province"]');
            var $city = $panel.find('select[name="city"]');
            var $country = $panel.find('select[name="country"]');
            $panel.find('input[name="name"]').val(getParameter('name'));
            $province.val(province);
            bindSelectProvince($province,$city,$country);
            bindSelectCountry($city,$country);
            selectAjax(province,$city,getParameter('city'),$country,getParameter('country'));
        });

    </script>
@endsection