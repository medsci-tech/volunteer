<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App\Model\Doctor;
use \App\Model\Hospital;
use \App\Model\Address;
use Hash;
use DB;
use PhpParser\Comment\Doc;

class ApiController extends Controller
{
    /**
     * 空课PC端注册接口
     * @author      lxhui<772932587@qq.com>
     * @since 1.0
     * @return array
     */
    public function register(Request $request)
    {
        $rules = [
            'phone' => 'required|digits:11|unique:doctors,phone',
            'password'=>'required|between:6,20',
        ];
        $message = [
            'phone.required'=>'电话号码不能为空!',
            'phone.unique'=>'该手机号已经存在！',
            'password.required'=>'密码不能为空！'
        ];
        $validator = \Validator::make($request->all(),$rules,$message);
        $messages = $validator->errors();
        /* 输出错误消息 */
        foreach ($messages->get('phone') as $message) {
            return ['status_code' => 0,'message' =>$message];
        }
        foreach ($messages->get('password') as $message) {
            return ['status_code' => 0,'message' =>$message];
        }

        $password = $request->password; //密码
        $req_request = $request->hospital; //文字
        $req_country = $request->country;
        $req_phone = $request->phone;

        $doctor = Doctor::where('phone', $req_phone)->first();
        DB::beginTransaction();
        try{
            /* 同步注册用户中心 */
            $response = \Helper::tocurl(env('API_URL2'). '/query-user-information?phone='.$request->phone, null,0);
            if($response['httpCode']==422)// 服务器返回响应状态码,当电话不存在时
            {
                if(isset($response['phone'])) //电话不存在则同步注册
                {
                    // 医院
                    $hospital_where = [
                        'hospital' => $req_request,
                        'country' => $req_country,
                    ];
                    $hospital = Hospital::where($hospital_where)->first();
                    if($hospital)
                    {
                        $hospital_id = $hospital->id;
                        $province = $hospital->province;
                        $city = $hospital->city;
                        $hospital_name = $hospital->hospital;
                    }
                    else{
                        $add_request = [
                            'country' => $req_country,
                            'hospital' => $req_request,
                        ];
                        $hospital = $this->addHospital($add_request);
                        if($hospital['status_code'] == 200)
                        {
                            $hospital_id = $hospital['data']['id'];
                            $province = $hospital['data']['province'];
                            $city = $hospital['data']['city'];
                            $hospital_name = $hospital['data']['hospital'];
                        }
                        else
                            return $this->return_data_format(0, $hospital['message']);
                    }
                    try{
                        $post_data = array(
                            "phone" => $req_phone,
                            'role'=>'医生',
                            'remark'=>'空中课堂',
                            'password'=>$password,
                            'province'=>$province,//省
                            'city'=>$city,//城市
                            'hospital_name'=>$hospital_name, //医院名称
                        );

                        $res = \Helper::tocurl(env('API_URL'). '/register', $post_data,1); // 同步用户中心注册
                        if($res['httpCode']==200) // 服务器返回响应状态码
                        {
                            /* 本地保存医生信息 */
                            if(!$doctor)
                            {
                                $result =  self::saveDoctor(['phone'=>$req_phone,'password'=>Hash::make($password),'hospital_id'=>$hospital_id]);
                                $insertedId = isset($result['data']['doctor_id']) ? $result['data']['doctor_id']: 0;
                            }
                        }

                    }catch (\Exception $e) {
                        return ['status_code' => 0,'message' =>'服务器异常,注册失败!'.$e->getMessage()];
                    }
                }
            }
            elseif($response['httpCode']==200) // 用户已经存在
            {
                /* 处理特殊异常数据同步 */
                if(!$doctor)
                    $result =  self::saveDoctor(['phone'=>$req_phone,'password'=>Hash::make($password)]);

                return ['status_code' => 0,'message' =>'该用户已经注册过'];
            }

            else
                return ['status_code' => 0,'message' =>'服务器异常哦,注册失败!'];

            DB::commit();
        } catch (\Exception $e){
            DB::rollback();//事务回滚
            return ['status_code' => 0,'message' =>'注册失败!'.$e->getMessage()];
        }
        /* 发送注册信息到数据分析平台 */
        return ['status_code' => 200,'message' =>'注册成功!','data'=>array('doctor_id' =>isset($insertedId) ? $insertedId : 0)];

    }
    /**
     * 添加医院信息
     * @author      lxhui<772932587@qq.com>
     * @since 1.0
     * @return array
     */

    function addHospital($request)
    {
        $country = $request['country'];
        $name = $request['hospital'];
        $address = Address::where('country', $country)->first();
        if($address) {
            $data = new Hospital();
            $data->province = $address->province;
            $data->province_id = $address->province_id;
            $data->city = $address->city;
            $data->city_id = $address->city_id;
            $data->country = $address->country;
            $data->country_id = $address->country_id;
            $data->hospital = $name;
            //$data->hospital_level = $request['hospital_level'];
            $res = $data->save();
            if($res){
                return [
                    'status_code' => 200,
                    'message'=>'success',
                    'data'=>
                    [
                        'id' => $data->id,
                        'province'=>$address->province,
                        'city'=>$address->city,
                        'hospital'=> $name
                    ]
                ];
            }else{
                return ['status_code' => 0, 'message'=>'添加医院失败'];
            }

        }else {
            return ['status_code' => 0, 'message'=>'匹配不到地区'];
        }

    }

    /**
     * 保存医生信息
     * param data  要保存的字段键值对
     * @author      lxhui<772932587@qq.com>
     * @since 1.0
     * @return array
     */
    private function saveDoctor($data)
    {
        if(empty($data))
            return ['status_code' => 0, 'message'=>'无效的数据'];
        if(isset($data['id'])) // 更新
        {
            $id = $data['id'];
            unset($data['id']);
            Doctor::where('id', $id)->update($data);
        }
        else // 添加
        {
            $doctor = Doctor::create($data);
            $id = $doctor->id;
        }
        return ['status_code' => 200, 'message'=>'保存成功!','data'=>array('doctor_id'=>$id)];

    }

    /**
     * 用户登录接口.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request) {
        $rules = [
            'openid'=>'required',
            // 'unionid'=>'required|between:6,20',
        ];
        $message = [
            'openid.required'=>'openid不能为空！',
        ];
        $validator = \Validator::make($request->all(),$rules,$message);
        $messages = $validator->messages()->first();
        $response = [
            'status_code' => 200,
            'message' =>  $messages.$request->openid,
        ];
        $user = \App\User::select(['id as uid','openid','nickname','headimgurl'])->where('openid', $request->openid)->first();
        if($user)
            $response = [
                'status_code' => 0,
                'message' =>  '用户已经注册!',
                'data' =>  $user,
            ];
        else
            $response = [
                'status_code' => 200,
                'message' =>  '用户尚未注册!',
            ];

        return $response;

    }










    /**
     * 短信发送
     * @author      lxhui<772932587@qq.com>
     * @since 1.0
     * @return array
     */
    public function send(Request $request)
    {
        $method=$request->method();
        if($request->isMethod('post')){
            $validator = \Validator::make($request->all(), [
                'phone'   => 'required|digits:11'
            ]);
            if ($validator->fails()) {
                return ['status_code' => 200, 'message' => $validator->errors()->first('phone')];
            }
            $phone  = $request->phone;
            $code   = \MessageSender::generateMessageVerify();
            \MessageSender::sendMessageVerify($phone, $code);
            try {
                Cache::put($phone, $code,1);
            } catch (\Exception $e) {
                return ['status_code' => 0, 'message' => $e->getMessage()];
            }
            return ['status_code' => 200, 'message' => '发送成功!','code'=> $code];
        }
        else{
            return ['status_code' => 0, 'message' => '发送失败!','code'=> null];
        }
    }
}
