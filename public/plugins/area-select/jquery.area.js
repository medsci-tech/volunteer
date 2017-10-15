/**
 * jquery.area.js
 * 移动端省市区三级联动选择插件
 * author: 锐不可挡
 * date: 2016-06-17
**/


var areaList = $("#areaList"), areaTop = areaList.offset().top, expressArea = $('#expressArea');
var province = $('#province'), province_id = $('#province_id'), city = $('#city'), city_id = $('#city_id'), country = $('#country'), country_id = $('#country_id');
var level = 'national';
var parentCode = 86;
var ChineseDistricts = ChineseDistricts();
	// console.log(ChineseDistricts[710700]);
	var areaSelect = function (code, column) {
		code = parseInt(code);
		var areaData = ChineseDistricts[code];
		if(column == 'national'){
			areaHtml(areaData, 'province');
		}else if(column == 'province'){
			parentCode = 86;
			areaHtml(areaData, 'city');
		}else if(column == 'city'){
			parentCode = $('#province_id').val();
			areaHtml(areaData, 'country');
		}else if(column == 'country'){
			parentCode = $('#city_id').val();
		}
		level = column;
		parentCode = parseInt(parentCode);
		$('#' + column).val(ChineseDistricts[parentCode][code]);
		$('#' + column + '_id').val(code);
		if(column == 'country'){
			var html = province.val() + ' &gt; ' + city.val() + ' &gt; ' + country.val();
			expressArea.html(html);
			clockArea();
		}
	};
	// areaSelect(7100000,'province');
function areaHtml(area, column) {
	var areaCont = '';
	for (var i in area) {
		areaCont += '<li onClick="areaSelect('+ '\'' + i + '\',\'' + column + '\');">' + area[i] + '</li>';
	}
	areaList.html(areaCont);
	$("#areaBox").scrollTop(0);
}


/*关闭省市区选项*/
function clockArea() {
	if(level != 'national'){
		console.log(level);
		$('#hospital').val('');
	}
	$("#areaMask").fadeOut();
	$("#areaLayer").animate({"bottom": "-100%"});
}


$(function () {
	/*打开省市区选项*/
	expressArea.click(function() {
		areaSelect(86,'national');
		$("#areaMask").fadeIn();
		$("#areaLayer").animate({"bottom": 0});
	});
	/*关闭省市区选项*/
	$("#areaMask, #closeArea").click(function() {
		console.log(level);
		if (level != 'country' && level != 'national'){
			var val = '';
			province.val(val);
			province_id.val(val);
			city.val(val);
			city_id.val(val);
			country.val(val);
			country_id.val(val);
			expressArea.html('省 &gt; 市 &gt; 区/县');
		}
		clockArea();
	});
});