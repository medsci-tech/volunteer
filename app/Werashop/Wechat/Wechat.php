<?php


namespace App\Werashop\Wechat;

use App\Constants\AppConstant;
use App\Model\Customer;
use Overtrue\Wechat\AccessToken;
use Overtrue\Wechat\Auth;
use Overtrue\Wechat\Http;
use Overtrue\Wechat\Js;
use Overtrue\Wechat\Menu;
use Overtrue\Wechat\MenuItem;
use Overtrue\Wechat\Message;
use Overtrue\Wechat\Payment;
use Overtrue\Wechat\Payment\Notify;
use Overtrue\Wechat\Payment\Business;
use Overtrue\Wechat\Payment\UnifiedOrder;
use Overtrue\Wechat\QRCode;
use Overtrue\Wechat\Server;
use Overtrue\Wechat\Staff;

/**
 * Class Wechat
 * @package App\Werashop\Wechat
 */
class Wechat
{

    /**
     * @var mixed
     */
    private $_appId;

    /**
     * @var mixed
     */
    private $_secret;

    /**
     * @var mixed
     */
    private $_aesKey;

    /**
     * @var mixed
     */
    private $_token;

    /**
     * @var mixed
     */
    private $_mchId;

    /**
     * @var mixed
     */
    private $_mchSecret;

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->_appId;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->_secret;
    }

    /**
     * @return mixed
     */
    public function getAesKey()
    {
        return $this->_aesKey;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Wechat constructor.
     */
    public function __construct()
    {
        $this->_appId = env('WX_APPID');
        $this->_secret = env('WX_SECRET');
        $this->_aesKey = env('WX_ENCODING_AESKEY');
        $this->_token = env('WX_TOKEN');
    }


    /**
     * @return boolean
     */
    public function generateMenu()
    {
        $menuService = new Menu($this->_appId, $this->_secret);
        $menus = $this->generateMenuItems();

        try {
            $menuService->set($menus);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return \Overtrue\Wechat\Server
     */
    public function getServer()
    {
        return new Server($this->_appId, $this->_token, $this->_aesKey);
    }

    /**
     * @return \Closure
     */
    public function locationEventCallback()
    {
        return function ($event) {
            \Log::info('location' . $event);
            $openId = $event['FromUserName'];

            $customer = Customer::where('openid', $openId)->first();
            if ((!$customer) || (!$customer->is_registered)) {
                return;
            }

            $customerLocation = CustomerLocation::where('customer_id', $customer->id)->first();
            if (!$customerLocation) {
                $customerLocation = new CustomerLocation();
                $customerLocation->customer_id = $customer->id;
            }

            $customerLocation->latitude = $event['Latitude'];
            $customerLocation->longitude = $event['Longitude'];
            $customerLocation->precision = $event['Precision'];
            $customerLocation->save();
        };
    }

    /**
     * @return \Closure
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
    /**
     * @return \Closure
     */
    public function clickEventCallback()
    {
        return function ($event) {
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
            echo $resstr;

        };
    }

    /**
     * @return \Closure
     */
    public function subscribeEventCallback()
    {
        return function ($event) {
            // \Log::info('yijian:0831::---' . $event);
            \Log::info('subscribe' . $event);
            $openId = $event['FromUserName'];

            $eventKey = $event['EventKey'];


           // $customer = Customer::where('openid', $openId)->first();

            $content = '二维码参数id:'.$eventKey;
            return Message::make('text')->content($content);
        };
    }

    /**
     * @return \Closure
     */
    public function messageEventCallback()
    {
        return function ($message) {

            return Message::make('text')->content('您好！欢迎来到医学志愿者!');

        };

    }

    /**
     * @param string $jump_url
     * @return null|\Overtrue\Wechat\Utils\Bag
     */
    public function authorizeUser($jump_url)
    {
        $appId = $this->_appId;
        $secret = $this->_secret;
        $auth = new Auth($appId, $secret);
        $result = $auth->authorize(url($jump_url), 'snsapi_base,snsapi_userinfo');

        \Session::put('web_token', $result->get('access_token'));
        return $auth->getUser($result->get('openid'), $auth->access_token);
    }

    /**
     * @param $scene_id
     * @return string
     */
    public function getForeverQrCodeUrl($scene_id)
    {
        $qrCode = new QRCode($this->_appId, $this->_secret);
        $result = $qrCode->forever($scene_id);

        return $qrCode->show($result->ticket);
    }

    /**
     * @param \App\Models\Order    $order
     * @param \App\Models\Customer $customer
     * @return array|string
     */
    public function generatePaymentConfig(Order $order, Customer $customer)
    {
        $business = new Business($this->_appId, $this->_secret, $this->_mchId, $this->_mchSecret);

        $wechat_order = new WechatOrder();
        $wechat_order->body = $this->generatePaymentBody($order);
        $wechat_order->out_trade_no = $order->wx_out_trade_no;
        $wechat_order->total_fee = '' . floor(strval($order->cash_payment_calculated * 100));
        $wechat_order->openid = $customer->openid;
        $wechat_order->notify_url = url('/wechat/payment/notify');

        $unified_order = new UnifiedOrder($business, $wechat_order);
        $payment = new Payment($unified_order);

        return $payment->getConfig();
    }

    /**
     * @return string
     */
    public function paymentNotify()
    {
        $notify = new Notify($this->_appId, $this->_secret, $this->_mchId, $this->_mchSecret);

        $transaction = $notify->verify();

        if (!$transaction) {
            return $notify->reply('FAIL', 'verify transaction error');
        }

        return $notify->reply();
    }

    /**
     * @param Order $order
     * @return string
     */
    protected function generatePaymentBody(Order $order)
    {
        return '' . $order->commodities()->first()->name . '等' . $order->commodities()->get()->count() . '件商品';
    }

    /**
     * @return string
     */
    public function getWebAuthAccessToken()
    {
        return \Session::get('web_token');
    }


    /**
     * @param $array
     * @return array|string
     */
    public function getJssdkConfig($array)
    {
        $js = new Js($this->_appId, $this->_secret);
        return $js->config($array);
    }

    /**
     * @param $url
     * @return bool
     */
    public function urlHasAuthParameters($url)
    {
        if (!strstr($url, 'code=')) {
            return false;
        }

        $back = substr($url, strpos($url, 'code=') + 5);
        $code = substr($back, 0, strpos($back, '&'));

        if (strlen($code) == 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $url
     * @return string
     */
    public function urlRemoveAuthParameters($url)
    {
        return preg_replace('/code=.*(&|\s)/U', '', $url);
    }

    public function sendMessage($message, $openId)
    {
        $staff = new Staff($this->_appId, $this->_secret);
        $staff->send(Message::make('text')->content($message))->to($openId);
        return true;
    }

    public function moveUserToGroup($userid, $to_groupid)
    {
        \Log::info('testttt' . 'okokok');
        $staff = new Staff($this->_appId, $this->_secret);
        $_accesstoken = $this->GetAccessToken();
        \Log::info('testttt' . $_accesstoken);
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=" . $_accesstoken;

        $data = "{\"openid\":\"" . $userid . "\",\"to_groupid\":" . $to_groupid . "}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $err = curl_error($ch);
        \Log::info($err);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function GetAccessToken()
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("/home/wwwroot/www.ohmate.cn/ohmate-shop/access_token.json"));
        if ($data->expire_time < time()) {
            // 如果是企业号用以下URL获取access_token
            //$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->_appId . "&secret=" . $this->_secret;
            $res = json_decode($this->httpGet($url));
            // print_r($res);
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("/home/wwwroot/www.ohmate.cn/ohmate-shop/access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

}
