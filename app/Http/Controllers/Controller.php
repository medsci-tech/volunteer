<?php

namespace App\Http\Controllers;

use App\Model\Doctor;
use App\Model\Volunteer;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Beans\BeanCharger;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /*
     * xsm, add.
     * */
    use BeanCharger;

    protected $site_id = 2;

    /**
     * 返回json格式到页面
     * @author zhaiyu
     * @param $code
     * @param string $msg
     * @param array $data
     * @return mixed
     */
    public function return_data_format($code, $msg='', $data=[]){
        return response()->json(['code' => $code, 'msg'=>$msg, 'data'=>$data]);
    }

    /**
     * 发送手机验证码
     * @author zhaiyu
     * @param $phone
     * @param $type
     * @param $required ：1，必须有；-1，必须无；0，随意
     * @return array
     */
    public function send_sms($phone, $type, $required = 0) {
        if($phone){
            $code   = \MessageSender::generateMessageVerify();
            // 要调用户中心接口
            $user = null;
            if($required == 0){
                \Session::set('phone_code_' . $phone, $code);
            }else{
                if($type == 'doctor'){
                    $user = Doctor::where('phone', $phone)->update(['phone_code' => $code]);
                }elseif($type == 'volunteer'){
                    $user = Volunteer::where('phone', $phone)->update(['phone_code' => $code]);
                }
            }
            if(($required == 1 && $user) || ($required == -1 && !$user) || $required == 0){
                $res = \MessageSender::sendMessageVerify($phone, $code);
                if(json_decode($res)->error == 0){
                    return ['code' => 200, 'msg'=>'success'];
                }else{
                    return ['code' => 500, 'msg'=>'发送失败'];
                }
            }elseif($user){
                return ['code' => 501, 'msg'=>'not found phone'];
            }else{
                return ['code' => 501, 'msg'=>'phone existed'];
            }
        }else{
            return ['code' => 400, 'msg'=>'params error'];
        }
    }

    /**
     * 检测手机验证码有效性
     * @author zhaiyu
     * @param $params
     * @return array
     */
    public function verify_sms($phone, $code, $type) {
        if($phone && $code){
            $check_code = \Session::get('phone_code_' . $phone);
            if(!$check_code){
                if($type == 'doctor'){
                    $user = Doctor::where('phone', $phone)->first();
                    if($user){
                        $check_code = $user['phone_code'];
                    }
                }elseif($type == 'volunteer'){
                    $user = Volunteer::where('phone', $phone)->first();
                    if($user){
                        $check_code = $user['phone_code'];
                    }
                }
            }
            if(strtolower($check_code) == strtolower($code)){
                return ['code' => 200, 'msg'=>'success'];
            }else{
                return ['code' => 404, 'msg'=>'验证码错误'];
            }
        }else{
            return ['code' => 500, 'msg'=>'验证码错误'];
        }
    }

    /**
     * 将秒数格式化时分秒
     * @param $seconds
     * @return array
     */
    public function formatDateForSeconds($seconds){
        $res = [
            'hours' => 0,
            'minutes' => 0,
        ];
        if($seconds > 60){
            $minutes = number_format(floor($seconds / 60)); // 秒转化成分
            $seconds_over = $seconds % 60; // 多余的秒
            if($minutes > 60){
                $hours = number_format(floor($minutes / 60)); // 分转化成时
                $minutes_over = $minutes % 60; // 多余的分
                $res['hours'] = $hours;
            }else{
                $minutes_over = $minutes;
            }
            $res['minutes'] = $minutes_over;
        }else{
            $seconds_over = $seconds;
        }
        $res['seconds'] = $seconds_over;
        return $res;
    }
}
