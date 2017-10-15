<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Model\RepresentDetail;
use App\Model\RepresentInfo;
use App\Model\Unit;
use App\Model\Volunteer;
use App\Model\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Constants\AppConstant;
use Overtrue\Wechat\Js;
use Cache;
use DB;
class VolunteerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.wechat',[
            'except'=>['import'
            ]
        ]);
        $except = [
            'indexSelf',
            'createSelf',
            'storeSelf',
            'loginSelf',
            'remvoeBind',
            'sms',
            'import'
        ];
        if(isset($_REQUEST['referrer_id']))
            $except[7] = 'qr_code';// 医生报名开放
        $this->middleware('auth.access', [
            'except' => $except
        ]);
    }

    public function indexSelf(Request $request){
        return view('volunteer.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSelf(Request $request)
    {
        $user = \Session::get('logged_user');
        //if ($user)
            //return redirect(url('/volunteer/qr_code?role=volunteer'));
        $units = Unit::all();
        return view('volunteer.create')->with(['units' => $units]);
    }


    public function loginSelf(Request $request){
        $user = \Session::get('logged_user');
        if (!$user) {
            //return redirect('home/error');
            return response()->json(['result' => '-1','message'=>'用户信息不存在']);
        } /*if>*/
        if($request->password == substr($request->phone,-6)){

        }
        else{
            return response()->json(['result' => '-1','message'=>'密码不正确']);
        }

        //判断手机号是否绑定其他的微信帐号
        $volunteer = Volunteer::where('phone', $request->phone)->first();
        if($volunteer && !empty($volunteer['openid'])){
            return response()->json(['result' => '-1','message'=>'手机号已绑定其他微信号，请核对后绑定！']);
        }
        if(!$volunteer){
            $volunteer = new Volunteer();
        }
        //
        $volunteer->phone   = $request->phone;
        $volunteer->password= $request->password;

        //判断是否注册成功
        $represent = RepresentInfo::where('phone',$request->phone)->first();
        if(!$represent){
            return response()->json(['result' => '-1','message'=>'手机号未注册，请先注册！']);
        }

        $volunteer->name    = $represent['name'];
        $volunteer->number  = $represent['initial'];
        $volunteer->status = '1';
        $volunteer->represent_id = $represent['id'];

        $unit_name = $represent['belong_company'];
        $unit = Unit::where('full_name', $unit_name)->first();
//        $volunteer->password   = $request->password;
        $volunteer->unit_id = $unit['id'];

        $email = $represent['initial'] . '@novonordisk.com';
        $volunteer->email = $email;

        $volunteer->headimgurl  = $user['headimgurl'];
        $volunteer->nickname    = $user['nickname'];
        $volunteer->openid      = $user['openid'];
//        $volunteer->headimgurl  = '';
//        $volunteer->nickname    = '';
//        $volunteer->openid      = '10';
        $volunteer->save();

        return response()->json(['result' => '1']);
    }
    public function storeSelf(Request $request)
    {
        $user = \Session::get('logged_user'); // 获取授权用户信息
        $initial = strtoupper(trim($request->number)); // 将initial转成大写
        $checkuser = RepresentInfo::where(array('initial'=>$initial))->orderBy('id', 'desc')->first(); // 验证用户合法性
        if (!$checkuser) {
            return response()->json(['result' => '-1','message'=>'员工编号不存在']);
        }
        $auth_code = Cache::get($request->phone);
        if ($request->code != $auth_code || $request->input('code') == '000000') {
            return response()->json(['result' => '-1','message'=>'验证码不匹配']);
        }

        /* 工号仅限本人使用,如果他人使用非本人工号注册系统无法限制，线下人工处理 */
        $volunteer_info = Volunteer::where(array('number'=>$initial))->orWhere(array('openid'=>$user['openid']))->first(); // 注册用户工号验证唯一性
        if($volunteer_info){
            \Log::info('存在的账号:工号:'.$request->number.' openid:'.$user['openid'].' 手机号:'.$request->phone);
            return response()->json(['result' => '-1','message'=>'该账号已注册!']);
        }

        DB::beginTransaction(); //开启事务
        try{
            /* 注册用户 */
            $unit_name = $checkuser['belong_company']; //所属单位
            $unit = Unit::where('full_name', $unit_name)->first(); //所属单位id

            $volunteer = new Volunteer();
            $volunteer->name    = $request->name;//姓名
            $volunteer->phone   = $request->phone;//电话
            $volunteer->unit_id = $unit['id'] ? $unit['id'] : 1;//单位id
            $volunteer->email = $request->email; //邮箱
            $volunteer->number = $initial;//工号
            $volunteer->headimgurl  = $user['headimgurl'];//微信图像
            $volunteer->nickname    = $user['nickname'];//微信昵称
            $volunteer->openid      = $user['openid'];//微信openid
            $volunteer->credit      =100;//积分
            $volunteer->save();
            /* 生成专属报名二维码 */
            $insertedId = $volunteer->id;
            $url = url('kzkt/signup?name='.$volunteer->name.'&phone='.$volunteer->phone.'&referrer_id='.$insertedId.'&site_id='.$request->site_id);
            \QrCode::encoding('UTF-8')->format('png')->size(30)->margin(0)->generate($url,public_path("qrcodes/qrcode_s_$insertedId.png")); // 生成缩略图
            \QrCode::encoding('UTF-8')->format('png')->size(300)->margin(0)->generate($url,public_path("qrcodes/qrcode_$insertedId.png")); // 生成大图
            RepresentInfo::where('initial', $initial)->update(['phone' => $request->phone]);     /* 更新志愿者excel info */
//            try {
//                /* 同步注册用户中心 */
//                $post_data = array(
//                    "name" => $request->name,
//                    "phone" => $request->phone,
//                    'email'=> $request->email,
//                    'role'=>'volunteer',
//                    'remark'=>'空中课堂',
//                );
//                $res = \Helper::tocurl(env('API_URL'). '/register', $post_data,1);
//                if(array_key_exists('status',$res))// 服务器返回响应状态码
//                {
//                    try {
//                        //赠送迈豆积分
//                        $post_data = array('phone'=> $request->phone,'bean'=>100);
//                        $res = \Helper::tocurl(env('API_URL2'). '/modify-bean', $post_data,1);
//                    } catch (\Exception $e) {
//                        return response()->json(['result' => '-1','message'=>'操作失败!'.$e->getMessage()]);
//                    }
//                }
//            }
//            catch (\Exception $e) {
//                return response()->json(['result' => '-1','message'=>'注册失败!'.$e->getMessage()]);
//            }
            DB::commit();
        }
        catch (\Exception $e){
            DB::rollback();//事务回滚
            return response()->json(['result' => '-1','message'=>'注册失败!'.$e->getMessage()]);
        }

        /* 发送注册信息到数据分析平台 */

        return response()->json(['result' => '1']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showSelf(Request $request)
    {
        \Log::info('showSelf');
        $user = \Session::get('logged_user');
        if (!$user) {
            \Log::info('showSelf no session');
            return redirect('home/error');
        }

        $volunteer = Volunteer::where('openid', $user['openid'])->first();
//        try{
//            $response = \Helper::tocurl(env('API_URL2'). '/query-user-information?phone='.$volunteer->phone, null,0);
//            if($response['httpCode']==200)// 服务器返回响应状态码,当电话存在时
//                $bean = $response['result']['bean']['number'];
//            else
//                $bean=0;
//        }
//        catch (\Exception $e) {
//            $bean = 0;
//        }

        if (!$volunteer) {
            \Log::info('showSelf no volunteer');
            return redirect('home/error');
        } /*if>*/

        \Log::info('name'.$volunteer->name);

        //return view('volunteer.show')->with(['volunteer' => $volunteer, 'represent' => $represent]);
        return view('volunteer.show')->with(['volunteer' => $volunteer,'bean'=>$volunteer->credit]);
    }

    public function qr_code(Request $request){
        $referrer_id = $request->input('referrer_id');
        if($referrer_id){
            $volunteer = Volunteer::where('id', $referrer_id)->first();
        }else{
            $user = \Session::get('logged_user');
//            $user['openid'] = '';
            $volunteer = Volunteer::where('openid', $user['openid'])->first();
        }
        if (!$volunteer) {
            return redirect('home/error');
        }
        $appId  = env('WX_APPID');
        $secret = env('WX_SECRET');
        $js = new Js($appId, $secret);

        return view('volunteer.qr_code')->with(['js' => $js,'volunteer' => $volunteer]);
    }

    public function editSelf(Request $request)
    {
        $user = \Session::get('logged_user');
        if (!$user) {
            return redirect('home/error?message='.urldecode('用户信息不存在'));
        } /*if>*/

        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer) {
            return redirect('home/error?message='.urldecode('代理信息不存在'));

        } /*if>*/

        return view('volunteer.edit')->with(['volunteer' => $volunteer]);
    }

    public function updateSelf(Request $request)
    {
        $user = \Session::get('logged_user');
        if (!$user) {
            return redirect('home/error?message='.urldecode('用户信息不存在'));
        } /*if>*/

        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer) {
            return redirect('home/error?message='.urldecode('代理信息不存在'));
        } /*if>*/

        $validator = \Validator::make($request->all(), [
            'phone' => 'required|digits:11|unique:volunteers,phone,' . $volunteer->id,
            'email' => 'required|email|unique:volunteers,email,' . $volunteer->id,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } /*if>*/

        $volunteer->phone = $request->phone;
        $volunteer->email = $request->email;
        $volunteer->number = $request->number;
        $volunteer->save();

        $reprensentInfo = RepresentInfo::where('id',$volunteer->represent_id)->first();
        $reprensentInfo->phone = $request->phone;
        $reprensentInfo->initial = $request->number;
        $reprensentInfo->save();

        return redirect('/volunteer/show-self');
    }

    /*
     * get all beans by volunteer
     * */
    public function beans(Request $request)
    {
        $user = \Session::get('logged_user');
        if (!$user) {
            return redirect('home/error?message='.urldecode('用户信息不存在'));
        }

//        $user['openid'] = '';
        //当前用户
        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer) {
            return redirect('home/error?message='.urldecode('代理信息不存在'));
        }

        return view('volunteer.beans')->with(['volunteer' => $volunteer]);
    }

    public function removeBind(Request $request)
    {
        $user = \Session::get('logged_user');
        if (!$user) {
            return redirect('home/error?message='.urldecode('用户信息不存在'));
        } /*if>*/

        //当前用户
        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer) {
            return redirect('home/error?message='.urldecode('代理信息不存在'));
        } /*if>*/

        if($volunteer->status=='0'){
            return view('volunteer.check');
        }
        $volunteer->openid = '';
        $volunteer->save();
        return view('volunteer.unbind');
    }

    //解除绑定
    public function unbindSelf(Request $request)
    {
        $user = \Session::get('logged_user');
        if (!$user) {
            return redirect('home/error?message='.urldecode('用户信息不存在'));
        }

        //当前用户
        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer) {
            return redirect('home/error?message='.urldecode('代理信息不存在'));
        }

        $phone_volunteer = Volunteer::where('phone', $request->phone)->first();
        if(!$phone_volunteer){
            return redirect('/volunteer/creat-self');
        }
        if(empty($phone_volunteer->openid)){
            $volunteer->openid = '';
            $phone_volunteer->openid = $user['openid'];
            $volunteer->save();
            $phone_volunteer->save();
            return redirect('/volunteer/show-self');
        }
        else{
            return redirect('/home/error?message='.urldecode('手机号已经绑定，请先解绑后，再绑定！'));
        }

    }

    public function shop(Request $request)
    {
        return view('volunteer.shop');
    }

    public function about(Request $request)
    {
        return view('volunteer.about');
    }

    public function success(Request $request)
    {
        return view('volunteer.success');
    }

    public function check(Request $request)
    {
        return view('volunteer.check');
    }

    /**
     * 短信发送
     * @author      lxhui<772932587@qq.com>
     * @since 1.0
     * @return array
     */
    public function sms(Request $request) {
        $validator = \Validator::make($request->all(), [
            //'phone'   => 'required|digits:11|exists:volunteers,phone'
             'phone'   => 'required|digits:11'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error_message' => $validator->errors()->getMessages()
            ]);
        }
        $phone  = $request->phone;
        $code   = \MessageSender::generateMessageVerify();
        \MessageSender::sendMessageVerify($phone, $code);
        try {
            Cache::put($phone, $code,1);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => true,'code'=>$code]);
    }

    /**
     * 批量生成二维码
     * @author      lxhui<772932587@qq.com>
     * @since 1.0
     * @return array
     */
    public function import(Request $request) {
        $list = RepresentInfo::all();
        foreach($list as $val)
        {
            $res = Volunteer::where(['number'=>$val->initial])->first();
            if($res)
            {
                $insertedId = $res->id;
                $url = url('kzkt/signup?name='.$res->name.'&phone='.$res->phone.'&referrer_id='.$insertedId.'&site_id=2');
                \QrCode::encoding('UTF-8')->format('png')->size(30)->margin(0)->generate($url,public_path("qrcodes/qrcode_s_$insertedId.png")); // 生成缩略图
                \QrCode::encoding('UTF-8')->format('png')->size(300)->margin(0)->generate($url,public_path("qrcodes/qrcode_$insertedId.png")); // 生成大图
                echo('用户:'.$val->id.'手机号:'.$val->phone.' 二维码生成成功!').'<br>';
            }
        }

exit;
        $list = Volunteer::whereIn('phone', ['15927086090', '13871000454'])->get();
        foreach($list as $val)
        {
            $insertedId = $val->id;
            $url = url('kzkt/signup?referrer_id='.$insertedId);
            \QrCode::format('png')->size(30)->margin(0)->generate($url,public_path("qrcodes/qrcode_s_$insertedId.png")); // 生成缩略图
            \QrCode::format('png')->size(300)->margin(0)->generate($url,public_path("qrcodes/qrcode_$insertedId.png")); // 生成大图
            echo('用户:'.$val->id.'手机号:'.$val->phone.' 二维码生成成功!').'<br>';
        }
    }

} /*class*/
