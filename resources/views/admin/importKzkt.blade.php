
@extends("layouts.admin")

@section("content")
    <style type="text/css">
        input[type="file"]{
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            opacity: 0;
            -ms-filter: 'alpha(opacity=0)';
            font-size: 200px !important;
            direction: ltr;
            cursor: pointer;
        }
    </style>
    <div file-upload-module style="padding-left: 20px;padding-top: 20px;">
        <div style="line-height: 20px;" error></div>
        <div style="padding: 5px;">
            <button class="btn btn-default" style="position: relative;overflow: hidden;display: inline-block;">选择文件
                <input name="excel" type="file" id="fileupload" data-url="{{url('/admin/baoming/import')}}" data-form-data='{"_token": "{{csrf_token()}}"}'>
            </button>
            <span file-name style="padding-left:10px;line-height: 20px"></span>
        </div>
        <div style="padding: 5px;"><button class="btn btn-default" onclick="importFile();">导入</button>
            <a href="{{url('/admin/baoming/sampleExcel')}}">下载表格模板</a>
        </div>
        <div class="prom_message"></div>
    </div>
@endsection

@section("js")
    <script src="{{asset("/jquery-upload/jquery.ui.widget.js")}}"></script>
    <script src="{{asset("/jquery-upload/jquery.iframe-transport.js")}}"></script>
    <script src="{{asset("/jquery-upload/jquery.fileupload.js")}}"></script>
    <script type="text/javascript">
        window.fileUploadData = null;
        $(function () {
            $('#fileupload').fileupload({
                dataType: 'json',
                add: function (e, data) {
                    window.fileUploadData = data;
                    var file = data.files[0].name;
                    $('div[file-upload-module] span[file-name]').text(file);
                },
                success:function (result, textStatus, jqXHR){
                    if(result &&result.success){
                        $('div[file-upload-module] span[file-name]').text('共'+result.total_count+'条,成功导入'
                        + result.count +"条记录，错误数据"+result.error_count+"条，重复数据"+result.repeat+"条");

                        $('.prom_message').html(result.msg);
                    }
                },
                error:function(){
                    $('div[file-upload-module] span[file-name]').text('导入失败');
                },
                complete:function(){
                    $.unblockUI();
                    window.fileUploadData = null
                }
            });
        });
        function importFile(){
            var data = window.fileUploadData;
            if(data && data.files && data.files.length>0){
                $.blockUI({message:'正在导入文件，请耐心等待...'});
                data.submit();
            }
        }
    </script>
@endsection