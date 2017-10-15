<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Overtrue\Wechat\Message;
use Overtrue\Wechat\Server;
use Overtrue\Wechat\Menu;
use Overtrue\Wechat\MenuItem;
use Overtrue\Wechat\AccessToken;
class WechatController extends Controller
{
    public function serve(Request $request)
    {
        $appId  = env('WX_APPID');
        $secret = env('WX_SECRET');
        $token  = env('WX_TOKEN');
        $encodingASEKey = env('WX_ENCODING_ASEKEY');
	
        $server = new Server($appId, $token, $encodingASEKey);
        $server->on('message', function ($message) {
            $words = array("空课", "空中课堂", "报名");
            if (in_array($message->Content, $words)) {
                return Message::make('text')->content("空中课堂，属于基层医生的内分泌代谢病网络课堂，4月1日正式开启。\n\n当“在岗学习”从一个奢侈品，变成了医生职业生涯的必需品
空中课堂，更像是一所学校，陪伴你的临床成长之旅具有匠人精神的各位老师，为你打磨最好的课程热心的助教，积极的同学，带动你一起快速成长\n\n报名方式：登录网站airclass.mime.org.cn，联系诺和诺德代表协助报名\n\n报名成功后，你可直接在上面的网站学习，或者接听语音电话\n\n请关注官方微信号“空中课堂云课堂”");
            }
            return Message::make('text')->content('您好！');
        });
        $server->on('event', 'CLICK', self::clickEventCallback());

        $result = $server->serve();
        return $result;
    }

    /**
     * @return \Closure
     */
    public function clickEventCallback()
    {
        return function ($event) {
            $http = new AccessToken(env('WX_APPID'),env('WX_SECRET'));
            $token = $http->getToken();
            //初始化
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token='.$token);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($data,true);

            if(empty($res['kf_online_list']))
                return Message::make('text')->content('欢迎使用迈德客服，我们的服务时间是:工作日9:00-18:00');
            
            $openId = $event['FromUserName'];
            $eventKey = $event['EventKey'];

            $fromUsername =$event['FromUserName'];
            $toUsername = $event['ToUserName'];
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                        </xml>";
            $resstr = sprintf($textTpl, $fromUsername, $toUsername, time());
            echo  $resstr;

        };
    }
    /**
     * @return \Closure 扫码回事件
     */
    public function scanEventCallback()
    {
        return function ($event) {
            \Log::info('SCAN' . $event);
            $openId = $event['FromUserName'];
            $eventKey = $event['EventKey'];

            $customers = Customer::where('openid', '=', $openId)->where('referrer_id', '=', $eventKey)->get();
            $customers_id = Customer::where('openid', '=', $openId)->first();
            if ($customers->isEmpty()) {
                \Log::info('SCAN111' . $customers);
                $customer = new Customer();
                $customer->openid = $openId;
                $customer->type_id = $customers_id->type_id;
                $customer->referrer_id = $eventKey;
                $customer->save();
                // Customer::where('openid', $openId)->update(['referrer_id' =>$eventKey ]);
            }
            \Log::info('SCAN222' . $customers);

        };
    }

    public function menu() {
        $menuService        = new Menu(env('WX_APPID'), env('WX_SECRET'));
        $buttonActivity     = new MenuItem("空中课堂");
        $buttonPersonal     = new MenuItem("我的");

        $menus = [
            $buttonActivity->buttons([
                new MenuItem('项目介绍', 'view', url('/kzkt/index')),
                new MenuItem('课程表', 'view', url('/kzkt/classdetail')),
                new MenuItem('报名', 'view', url('/kzkt/signup')),
                new MenuItem('微商城', 'view', 'http://airclass.mime.org.cn/shop'),
                neW MenuItem('联系客服', 'click','V1002_Custom'),
            ]),
            $buttonPersonal->buttons([
                new MenuItem('个人信息', 'view', url('/volunteer/show-self')),
                new MenuItem('我的二维码', 'view', url('/volunteer/qr_code?role=volunteer')),
                new MenuItem('我的学员', 'view', url('/kzkt/findAllRegister')),
            ]),
        ];
        $menuService->set($menus);

        try {
            $menuService->set($menus);
            echo '设置成功!';
        } catch (\Exception $e) {
            echo '设置失败!' . $e->getMessage();
        }
    }


} /*class*/
