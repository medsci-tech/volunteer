
var action= '/admin/hospital/getHospital';
var get_hospital = function (data, hospital) {
    $.ajax({
        type: 'post',
        url: action,
        data: data,
        success: function(res){
            if(res.code == 200){
                show_hospital_list(res.data, hospital);
            }
        }
    });
};

var show_hospital_list = function (list, hospital) {
    var html = '<option value="">-选择医院-</option>';
    for(var i in list){
        if(list[i]['hospital']){
            html += '<option value="'+list[i]['id']+'" ';
            if(list[i]['id'] == hospital){
                html += 'selected ';
            }
            html += ' >'+list[i]['hospital']+'</option>';
        }
    }
    $('#form-hospital').html(html);
};
