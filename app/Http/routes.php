<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::any('/wechat', 'WechatController@serve');
Route::any('/menu', 'WechatController@menu');

Route::group(['prefix' => 'home'], function () {
    Route::get('/error', 'HomeController@error');
    Route::get('/unavailable', 'HomeController@unavailable');
});

/* menu wdf */
Route::get('/wdf', 'WDFController@index');
/* menu activity */
Route::get('/activity', 'ActivityController@index');
Route::get('/activity/view', 'ActivityController@loadView');
Route::get('/activity/none', 'ActivityController@noneView');
/* menu personal */
//Route::group(['prefix' => 'volunteer'], function () {
//    Route::get('create-self', 'VolunteerController@createSelf');
//    Route::post('store-self', 'VolunteerController@storeSelf');
//    Route::get('show-self', 'VolunteerController@showSelf');
//    Route::get('edit-self', 'VolunteerController@editSelf');
//});

/*
 * xsm, add, 20151123.
 * for volunteer register, volunteer info.
 * */
Route::group(['prefix' => 'volunteer'], function () {
    Route::get('/information', 'VolunteerController@information'); //个人中心 - 我的消息
    Route::get('/beans', 'VolunteerController@beans'); //个人中心 - 迈豆积分

    Route::get('create-self', 'VolunteerController@indexSelf'); //登录页面
    Route::post('login-self', 'VolunteerController@loginSelf'); //登录操作
    Route::get('sms', 'VolunteerController@sms'); //发送验证码

    Route::get('register-self', 'VolunteerController@createSelf');//注册页面
    Route::post('store-self', 'VolunteerController@storeSelf');
    Route::get('qr_code', 'VolunteerController@qr_code');
    Route::get('edit-self', 'VolunteerController@editSelf');//个人中心 - 编辑信息。区别于资源管理。使用该路由只能管理自己的信息。
    Route::get('show-self', 'VolunteerController@showSelf');//个人中心 - 个人信息。区别于资源管理。使用该路由只能查看自己的信息。
    Route::post('update-self', 'VolunteerController@updateSelf');
    Route::get('remove-self', 'VolunteerController@removeBind');
    Route::post('unbind-self', 'VolunteerController@unbindSelf');

    Route::get('beans', 'VolunteerController@beans');
    Route::get('shop', 'VolunteerController@shop');
    Route::get('about', 'VolunteerController@about');
    Route::get('success', 'VolunteerController@success');
    Route::get('check', 'VolunteerController@check');
    Route::get('import', 'VolunteerController@import'); // 批量生产二维码
});


Route::group(['prefix' => 'kzkt'], function () {
    Route::get('/index', 'KZKTController@index');
    Route::post('/get_hospital', 'KZKTController@getHospital');
    Route::get('/department', 'KZKTController@getDepartment');
    Route::post('/addClassroom', 'KZKTController@addClassroom');
    Route::post('/updateClassroom', 'KZKTController@updateClassroom');
    Route::post('/checkIn', 'KZKTController@checkIn');
    Route::get('/findPreRegister', 'KZKTController@findPreRegister');
    Route::get('/findSingleRegister', 'KZKTController@findSingleRegister');
    Route::get('/findAllRegister', 'KZKTController@findAllRegister');
    Route::get('/signup', 'KZKTController@signup');
    Route::get('/study_progress', 'KZKTController@study_progress');
    Route::get('/editClassroom', 'KZKTController@editClassroom');
    Route::get('/viewCard', 'KZKTController@viewCard');
    Route::get('/classdetail', 'KZKTController@classdetail');
    Route::get('/classdetail2', 'KZKTController@classdetail2');
    Route::get('/classdetail3', 'KZKTController@classdetail3');
    Route::get('/viewHospital', 'KZKTController@viewHospital');
    Route::post('/addHospital', 'KZKTController@addHospital');
    Route::get('/showflow', 'KZKTController@showflow');
    Route::get('/checkuser', 'KZKTController@checkuser');
    Route::get('/showfail', 'KZKTController@showfail');
    Route::post('/yxzyz_send_code', 'KZKTController@yxzyz_send_code');
});

Route::group(['prefix' => 'hpxt'], function () {
    Route::get('/index', 'HPXTController@index');
    Route::get('/introduction', 'HPXTController@introduction');
    Route::get('/procedure', 'HPXTController@procedure');
    Route::get('/document', 'HPXTController@document');
    Route::get('/document-ppt', 'HPXTController@documentPpt');
    Route::get('/document-agreement', 'HPXTController@documentAgreement');
    Route::get('/class-manage', 'HPXTController@classManage');
    Route::get('/class-application', 'HPXTController@classApplication');
    Route::get('/class-application-add-doctor', 'HPXTController@classApplicationAddDoctor');
    Route::get('/class-application-add-assistant', 'HPXTController@classApplicationAddAssistant');
    Route::get('/class-store', 'HPXTController@classStore');
});
/*  api部分 */
Route::group(['prefix' => 'api'], function () {
    Route::post('register', 'ApiController@register');//注册页面

});
Route::group(['prefix' => 'emy'], function () {
    Route::get('/emy/index', 'EMYController@index');
});
Route::group(['prefix'=>'admin'],function(){
    Route::get('/', "AdminController@index");
    Route::post('/postRepresent', "AdminController@postRepresent");
    Route::post('/deleteRepresent/{id}', "AdminController@deleteRepresent");
    Route::get('/represent/import', "AdminController@getImportRepresent");
    Route::post('/represent/import',"AdminController@importRepresent");
    Route::get('/represent/export',"AdminController@exportRepresent");
    Route::get('/represent/sampleExcel',"AdminController@downloadRepresentExcel");
    Route::get('/Initrepresent',"AdminController@dataInif");
    // Authentication routes...
    Route::get('/login', 'Auth\AuthController@getLogin');
    Route::post('/login', 'Auth\AuthController@postLogin');
    Route::get('/logout', 'Auth\AuthController@getLogout');

    // Registration routes...


    Route::get('/baoming', 'AdminController@getRegister');
    Route::get('/baoming/export',"AdminController@exportRegister");
    Route::post('/baoming/delete/{id}',"AdminController@deleteKzkt");
    Route::post('/baoming/post',"AdminController@postKzkt");
    Route::get('/baoming/import', "AdminController@getImportKzkt");
    Route::post('/baoming/import',"AdminController@importKzkt");
    Route::get('/baoming/sampleExcel',"AdminController@downloadKzktExcel");

    Route::get('/register', 'Auth\AuthController@getRegister');
    Route::post('/register', 'Auth\AuthController@postRegister');

    Route::get('/check', "AdminController@getVolunteerCheck");
    Route::get('/check/export', "AdminController@exportCheck");
    Route::post('/check/postVolunteer', "AdminController@postVolunteer");
    Route::post('/check/checkVolunteer/{id}', "AdminController@checkVolunteer");
    Route::post('/check/deleteVolunteer/{id}', "AdminController@deleteVolunteer");

    Route::get('/hospital',"AdminController@getHospitals");
    Route::get('/hospital/getCities',"AdminController@getCitiesByProvince");
    Route::get('/hospital/getCountries',"AdminController@getCountriesByCity");
    Route::post('/hospital/getHospital',"AdminController@getHospitalByProvince");
    Route::post('/hospital/post',"AdminController@postHospital");
    Route::post('/hospital/delete/{id}',"AdminController@deleteHospital");
    Route::get('/hospital/export',"AdminController@exportHospitals");
    Route::get('/hospital/import', "AdminController@getImportHospital");
    Route::post('/hospital/import',"AdminController@importHospital");
    Route::get('/hospital/sampleExcel',"AdminController@downloadHospitalExcel");

    Route::get('/doctor',"AdminController@doctors");
    Route::post('/doctor/delete/{id}',"AdminController@deleteDoctor");
    Route::post('/doctor/post',"AdminController@postDoctor");
    Route::get('/doctor/searchHospital',"AdminController@searchHospital");
    Route::get('/doctor/export',"AdminController@exportDoctor");
    Route::get('/doctor/import', "AdminController@getImportDoctor");
    Route::post('/doctor/import',"AdminController@importDoctor");
    Route::get('/doctor/sampleExcel',"AdminController@downloadDoctorExcel");
});






