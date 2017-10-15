<?php

namespace App\Http\Controllers;

use App\Model\KZKTClass;
use App\Model\Office;
use Illuminate\Http\Request;

use App\Http\Requests;
use \App\Model\Doctor;
use \App\Model\Hospital;
use \App\Model\ClassDetails;
use \App\Model\Volunteer;
use App\Model\StudyLog;
use App\Model\ThyroidClassCourse;
use Carbon\Carbon;
use \App\Model\InviteNumber;
use \App\Model\Address;
use DB;
use Hash;
use Overtrue\Wechat\Js;

class KZKTController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.wechat');
        $except = [
            'index',
            'classdetail',
            'showflow',
            'viewCard',
        ];
        if(isset($_REQUEST['referrer_id']))
        {
            // 医生报名开放
            $except[] = 'signup';
            $except[] = 'addClassroom';
            $except[] = 'yxzyz_send_code';
            $except[] = 'getHospital';
            $except[] = 'findSingleRegister';
        }
        $this->middleware('auth.access', [
            'except' => $except
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('kzkt.signupindex');
    }

    function getHospital(Request $request)
    {
        $province = $request->input('province');
        $find   = '省';
        $pos = strpos($province, $find);
        if ($pos !== false)
            $province =str_replace($find,"",$province);

        $city = $request->input('city');
        $find   = '市';
        $pos = strpos($city, $find);
        if ($pos !== false)
            $city =str_replace($find,"",$city);

        $country = $request->input('country');
        $find   = '区';
        $pos = strpos($country, $find);
        if ($pos !== false)
            $country =str_replace($find,"",$country);

        $name = $request->input('name');
        $where = [];
        if($province){
            $where['province'] = $province;
            if($city){
                $where['city'] = $city;
                if($country){
                    $where['country'] = $country;
                }
            }
            if($name){
                $list = Hospital::where('province','like', "%".$province."%")->where('city','like', "%".$city."%")->where('country','like', "%".$country."%")->where('hospital', 'like', '%' . $name . '%')->get();
            }else{
                $list = Hospital::where('province','like', "%".$province."%")->where('city','like', "%".$city."%")->where('country','like', "%".$country."%")->get();
                //$list = Hospital::where($where)->get();
            }
        }else{
            $list = null;
        }
        if($list){
            return $this->return_data_format(200, 'success', $list);
        }else{
            return $this->return_data_format(201, 'success');
        }
    }

    function getDepartment(Request $request)
    {
        $list = DB::select('select DEPT_ID,DEPT_NAME from dict_dept');
        return response()->json(['list'=>$list]);
    }

    function addClassroom(Request $request)
    {
        $volunteer_referrer_id = $request->input('referrer_id');//志愿者id
        $site_id = $request->input('site_id') ? $request->input('site_id') :2 ; // 项目id
        $req_hospital = $request->input('hospital'); //文字
        $req_province = $request->input('province');
        $req_city = $request->input('city');
        $req_country = $request->input('country');
        $req_hospital_level = $request->input('hospital_level');
        $req_phone = $request->input('phone');
        $req_style = $request->input('style');
        $req_style = implode($req_style,',');

        $check_code_result = $this->verify_sms($req_phone, $request->input('verify_code'), 'doctor');
        if($check_code_result['code'] != 200){
            return $this->return_data_format(403, $check_code_result['msg']);
        }
        if(empty($req_country)){
            return $this->return_data_format(500, '地区不能为空');
        }
        $volunteer = Volunteer::where('phone', $req_phone)->first(); // 查找是否属于注册志愿者
        if ($volunteer)
            return $this->return_data_format(500, '志愿者手机号无法参加该项目');

        //获取志愿者id
        if($volunteer_referrer_id){ // 扫码提交
            $volunteer_info = Volunteer::find($volunteer_referrer_id);
        }else{
            $user = \Session::get('logged_user');
            $volunteer_info = Volunteer::where('openid', $user['openid'])->first();
        }
        if(empty($volunteer_info)){
            return $this->return_data_format(500, '找不到对应志愿者');
        }
        $password_default = sprintf('%06d',random_int(000000, 999999));
        $phone = $request->input('phone');
        $doctor_title = $request->input('doctor_title');

        $doctor = Doctor::where('phone', $request->input('phone'))->first();
        if ($doctor)
        {
            $has_password = 1; // 从pc端注册已经存在密码
            $kzktData = KZKTClass::where(array('doctor_id'=>$doctor->id,'site_id'=>$site_id))->where('status', true)->first();
            if ($kzktData) {
                return $this->return_data_format(200, '该项目已报名');   //空中课堂报名数据存在时
            }
        }
        else
        {
            $doctor = new Doctor();
            $has_password = 0; // 新用户使用默认密码
        }

        DB::beginTransaction();
        try{
            // 医院
            $hospital_where = [
                'hospital' => $req_hospital,
                'country' => $req_country,
            ];
            $hospital = Hospital::where($hospital_where)->first();
            if($hospital)
                $hospital_id = $hospital->id;
            else
            {
                $add_request = [
                    'province' => $req_province,
                    'city' => $req_city,
                    'country' => $req_country,
                    'hospital_level' => $req_hospital_level,
                    'hospital' => $req_hospital,
                ];
                $hospital = $this->addHospital($add_request);
                if($hospital['code'] == 200){
                    $hospital_id = $hospital['data']['id'];
                }else{
                    return $this->return_data_format(404, $hospital['msg']);
                }
            }

            /* 保存医生信息 */
            $doctor->name = $request->input('name');
            $doctor->phone = $phone;
            $doctor->hospital_id = isset($hospital_id) ? $hospital_id : 0;
            $doctor->office = $request->input('department');
            $doctor->email = $request->input('mail');
            $doctor->qq = $request->input('qq');
            $doctor->title = $doctor_title; //职称
            if(!$has_password)
                $doctor->password = Hash::make($password_default);

            $res_doctor = $doctor->save();

//            if($res_doctor)
//            {
//                /* 同步注册用户中心 */
//                $response = \Helper::tocurl(env('API_URL2'). '/query-user-information?phone='.$phone, null,0); // 查询用户信息
//                if($response['httpCode']==422)// 服务器返回响应状态码,,当用户手机号不存在于用户中心
//                {
//                    if(isset($response['phone'])) //电话不存在则同步注册
//                    {
//                        $hospital_info = Hospital::where(array('id'=>$hospital_id))->first();//医院信息
//                        $post_data = array(
//                            "name" => $request->input('name'),
//                            "phone" => $phone,
//                            'email'=> $request->input('mail'),
//                            'role'=>'doctor',
//                            'remark'=>'空中课堂',
//                            'password'=>$password_default,
//                            'title'=>$request->doctor_title, //职称
//                            'office'=>$request->department,//科室
//                            'province'=>$hospital_info['province'],//省
//                            'city'=>$hospital_info['city'],//城市
//                            'hospital_name'=>$hospital_info['hospital'], //医院名称
//                            'upper_user_phone'=>$volunteer_info['phone'], //用户的上级用户电话
//                            'upper_user_remark'=>$volunteer_info['phone'],//用户的上级用户备注
//                        );
//                        try
//                        {
//                            $res = \Helper::tocurl(env('API_URL'). '/register', $post_data,1);// 同步注册
//                            if($res['httpCode']==200)// 服务器返回响应状态码
//                            {
//                                if(isset($res['status']))
//                                {
//                                    $msg = '您注册账号是：'.$phone.'，默认密码是：'.$password_default.'。感谢关注';
//                                    \MessageSender::sendMessage($phone, $msg, '医学志愿者');
//                                }
//                            }
//                        }
//                        catch (\Exception $e) {
//                            //\Log::info($request->all());
//                            return $this->return_data_format(404, '服务器异常!');
//                        }
//                    }
//                }
//            }
//            else
//            {
//                //\Log::info($request->all());
//                return $this->return_data_format(404, '添加医生信息失败');
//            }


            /* 保存报名信息 */
            $kzktData = new KZKTClass();
            $kzktData->volunteer_id = $volunteer_info->id;
            $kzktData->site_id = $site_id;// 项目id
            $kzktData->doctor_id = $doctor->id;
            //$kzktData->login_number = substr($request->input('phone'), 5);
            //$invite_number = InviteNumber::where('status', false)->first();
            $kzktData->status = true;
            $kzktData->style = $req_style;
            //$invite_number->status = true;
            $result = $kzktData->save();
            //$invite_number->save();
            /* 报名添加 */
            if($result)
            {
                try {
                    $rank = $doctor->rank;
                    if(!$rank) // 默认报名一级
                        Doctor::where(['phone'=>$doctor->phone])->update(['rank' => 1]);

                    //赠送迈豆积分
                    //$postdata = array('phone'=> $volunteer_info->phone,'bean'=>300);
                    //$res = \Helper::tocurl(env('API_URL2'). '/modify-bean', $postdata,1);
                    $volunteer_info->credit+=300;
                    $volunteer_info->save();
                    if(!$has_password)
                    {
                        $msg = '您注册账号是：'.$phone.'，默认密码是：'.$password_default.'。感谢关注';
                        \MessageSender::sendMessage($phone, $msg, '医学志愿者');
                    }

                } catch (\Exception $e) {
                    //\Log::info($request->all());
                    return $this->return_data_format(404, '服务器异常,操作失败!');
                }
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollback();//事务回滚
            //\Log::info($request->all());
            return $this->return_data_format(404, '服务器异常,报名失败!');
        }
        \Session::set('kzkt_signup_doctor_id', $doctor->id);

        /* 发送注册信息到数据分析平台 */
        return $this->return_data_format(200, 'success');
    }

    function postPage($url)
    {
        $response = "";
        // $rd=rand(1,4);
        //$proxy='http://192.168.81.213:808';
        if($url != "") {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_PROXY, $proxy);
            $response = curl_exec($ch);
            if(curl_errno($ch)) $response = "";
            curl_close($ch);
            //$response = @file_get_contents($url);
        }

        return $response;
    }

    function updateClassroom(Request $request)
    {
        $id = $request->input('id');
        $doctor = Doctor::where('id', $id)->first();
        $kzktData = KZKTClass::where('doctor_id', $doctor->id)->first();
        $hospital = Hospital::where('id', $request->input('hospital'))->first();
        if($doctor != null && $kzktData != null) {
            $doctor->name = $request->input('name');
            $doctor->phone = $request->input('phone');
            $doctor->hospital_id = $hospital->id;
            $doctor->office = $request->input('department');
            $doctor->email = $request->input('mail');
            $doctor->qq = $request->input('oicq');
            $doctor->save();

            $kzktData->login_number = substr($request->input('phone'), 5);
//            $kzktData->type = $request->input('classType');
            $invite_number = InviteNumber::where('status', false)->first();
            if ($doctor->email) {
                $kzktData->status = true;
                $kzktData->invite_number = $invite_number->number;
                $invite_number->status = true;
                $result = '1';
            } else {
                $kzktData->status = false;
                $result = '2';
            }
            $kzktData->save();
            $invite_number->save();

            return response()->json(['result' => $result, 'id' => $doctor->id, 'hospital'=>$hospital->hospital_id]);
        }
        else {
            return response()->json(['result' => '-1']);
        }
    }

    function checkIn(Request $request)
    {
        $openid = \Session::get('logged_user');
        $airClassrooms = AirClassroom::where('openid', $openid['openid'])->where('status', 1)->get();
        if (!$airClassrooms) {
            return response()->json(['result' => '-1']);
        } else {
            foreach ($airClassrooms as $airClassroom) {
                $airClassroom->status = 2;
                $airClassroom->save();
            }
            return response()->json(['result' => '1']);
        }
    }

    function viewCard(Request $request)
    {
        $appId  = env('WX_APPID');
        $secret = env('WX_SECRET');

        $js = new Js($appId, $secret);
//        \Session::set('kzkt_signup_doctor_id', null);
        $id = \Session::get('kzkt_signup_doctor_id');
        return view('kzkt.signupcard',['js' => $js, 'kzkt_signup_doctor_id' => $id,'referrer_id'=>$request->input('referrer_id')]);
    }

    function signup(Request $request)
    {
        $referrer_id = $request->input('referrer_id');
        $site_id = $request->input('site_id') ? $request->input('site_id') : 2;
        if(!$referrer_id){
            $role = 'volunteer';
        }else{
            $role = 'doctor';
        }
        $offices = Office::all();
        return view('kzkt.signup', [
            'referrer_id' => $referrer_id,
            'site_id' => $site_id,
            'role' => $role,
            'offices' => $offices,
        ]);
    }

    function editClassroom(Request $request)
    {
        return view('kzkt.signupedit');
    }

    function findSingleRegister(Request $request)
    {
        $id = $request->input('id');
        $phone = $request->input('phone');
        if($id){
            $doctor = Doctor::where('id', $id)->orWhere('phone', $phone)->first();
            if($doctor){
                $kzktData = KZKTClass::where('doctor_id', $doctor->id)->first();
                if($kzktData){
                    return $this->return_data_format(200, 'success', [
                        'doctor_name' => $doctor['name'],
                        'doctor_phone' => $doctor['phone']
                    ]);
                }else{
                    return $this->return_data_format(404, 'not found data');
                }
            }else{
                return $this->return_data_format(404, 'not found doctor');
            }
        }else{
            return $this->return_data_format(404, 'params error');
        }
    }

    function findAllRegister(Request $request)
    {
        $user = \Session::get('logged_user');
        $count = 0;
        $array = [];
        $kzktDatas = [];
        $suffice = $request->input('suffice', 0);
        // 测试使用113号
//        $user['openid'] = 'onweKwddHP4Dqh4vdURUZXKfdIYU';
        if ($user['openid']) {
            $volunteer = Volunteer::where('openid', $user['openid'])->first();
            $kzktWhere = [
                'volunteer_id' => $volunteer['id'],
                'status' => 1
            ];

            $kzktDatas = KZKTClass::where($kzktWhere)
                ->orderBy('id', 'desc')
                ->get();
            if($suffice > 0){
                foreach ($kzktDatas as $key => $kzktData){
                    $study_log = StudyLog::where([
                        'doctor_id' => $kzktData->doctor_id ,
                    ])->where('updated_at', '>',Carbon::now()->addMonth(-2)->toDateTimeString())->get();
//                    dd($study_log->count());
                    if($suffice == 1 && $study_log->count() == 0){
                        // 合格,近两个月学习过
                        unset($kzktDatas[$key]); // 没有学习记录清掉
                    }else if($suffice == 2 && $study_log->count() > 0){
                        //近两个月未学习过
                        unset($kzktDatas[$key]); // 没有学习记录清掉
                    }
                }
            }

        }
        if($kzktDatas){
            $count = $kzktDatas->count();
            foreach ($kzktDatas as $kzkt) {
                $doctor = Doctor::where('id', $kzkt['doctor_id'])->first();
                if($doctor){
                    $row = [
                        'name' => $doctor->name,
                        'id' => $doctor->id,
                        'phone' => $doctor->phone,
                        'time' => $kzkt->created_at,
                    ];
                    $hospital = Hospital::find($doctor->hospital_id);
                    if($hospital){
                        $row_hospital = $hospital['hospital'];
                    }else{
                        $row_hospital = '';
                    }
                    $row['hospital'] = $row_hospital;
                    array_push($array, $row);
                }

            }
        }

        return view('kzkt.signupmenu', [
            'count'=>$count,
            'data' => $array,
            'suffice' => $suffice,
        ]);
    }

    function addHospital($request)
    {
        $province = $request['province'];
        $city = $request['city'];
        $country = $request['country'];
        $name = $request['hospital'];
        $data = new Hospital();
        $data->province = $province;
        $data->city = $city;
        $data->country = $country;
        $data->hospital = $name;
        $data->hospital_level = $request['hospital_level'];
        $res = $data->save();
        if($res){
            $return_data = [
                'id' => $data->id,
            ];
            return ['code' => 200, 'msg'=>'success', 'data'=>$return_data];
        }else{
            return ['code' => 500, 'msg'=>'添加医院失败'];
        }
    }

    function viewHospital(Request $request)
    {
        return view('kzkt.addhospital');
    }

    function classdetail(Request $request)
    {
        $lists = [];
        foreach (config('params')['kzkt_class_unit'] as $key => $val){
            $units = ClassDetails::where('unit',$key)->get();
            $lists[] = [
                'unit_name' => $val,
                'unit_list' => $units,
            ];
        }
        return view('kzkt.classdetail',[
            'lists' => $lists,
        ]);
    }

    function classdetail2(Request $request)
    {
        return view('kzkt.classdetail2');
    }

    function classdetail3(Request $request)
    {
        return view('kzkt.classdetail3');
    }

    function showflow(Request $request)
    {
        return view('kzkt.showflow');
    }

    function showfail(Request $request)
    {
        $appId = env('WX_APPID');
        $secret = env('WX_SECRET');

        $js = new Js($appId, $secret);

        return view('kzkt.signupfailed', ['js' => $js]);
    }

    function checkuser(Request $request)
    {
        $openid = \Session::get('logged_user');
        $data = false;

        if ($openid) {
            $volunteer = Volunteer::where('openid', $openid['openid'])->first();

//            $data = RepresentDetail::where('represent_name', $volunteer->name)
//                ->where('represent_code',  strtoupper($volunteer->number))
//                ->first();
            $data = (strpos($volunteer['represent']['belong_project'],'空中课堂')===false) ? false:true;
        }

        if ($data) {
            return response()->json(['result' => '1']);
        } else {
            if($volunteer->number == '9999') {
                return response()->json(['result' => '1']);
            }
            else {
                return response()->json(['result' => '-1']);
            }
        }
    }

    public function yxzyz_send_code(Request $request) {
        $phone = $request->input('phone');
        $type = $request->input('type');
        if($phone && $type){
            $res = $this->send_sms($phone, $type, 0);
            if($res['code'] == 200){
                return $this->return_data_format(200, 'success');
            }else{
                return $this->return_data_format(500, $res['msg']);
            }
        }else{
            return $this->return_data_format(404, '参数错误');
        }
    }

    //学习进度
    public function study_progress(Request $request){
        $doctor_id = $request->input('id');
        $doctor = Doctor::find($doctor_id);
        $study_logs = [];
        if($doctor){
            $study_logs = StudyLog::select(DB::raw('sum(study_duration) as study_total, count(*) as study_count, course_id'))
                ->where(['site_id'=>$this->site_id, 'doctor_id'=>$doctor_id])
                ->groupBy('course_id')
                ->get(); // 学习记录
            foreach ($study_logs as &$study_log){
                $study_log->format_date = $this->formatDateForSeconds($study_log->study_total);
                $course = ThyroidClassCourse::find($study_log->course_id);
                if($course){
                    $study_log->title = $course->title;
                }else{
                    $study_log->title = '';
                }
            }
        }
//        dd($study_logs);
        return view('kzkt.study_progress',[
            'doctor' => $doctor,
            'study_logs' => $study_logs,
        ]);
    }

}
