<?php

namespace App\Http\Middleware;

use App\Model\Volunteer;
use Carbon\Carbon;
use Closure;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Session::get('logged_user');
        if (!$user) { // 修复此处禁止出现404等页面提示
            \Log::info('logged_user is null');
            $appId  = env('WX_APPID');
            $secret = env('WX_SECRET');
            $auth = new Auth($appId, $secret);
            $user = $auth->authorize(url($request->fullUrl()));
        } /*if>*/

        \Log::info('AccessMiddleware.openid:'.$user['openid']);
        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer || !$volunteer->phone) {
            \Log::info('AccessMiddleware.$volunteer: no data');
            return redirect('/volunteer/register-self'); // 直接进入注册
        } /*if>*/

        \Log::info('AccessMiddleware.diffInMinutes');
        if (Carbon::now()->diffInMinutes($volunteer->updated_at) > 30) {
            \Log::info('AccessMiddleware.diffInMinutes > 30');
            $volunteer->openid      = $user['openid'];
            $volunteer->headimgurl  = $user['headimgurl'];
            $volunteer->nickname    = $user['nickname'];
            $volunteer->save();
        } /*if>*/
        \Log::info('AccessMiddleware.next');
        return $next($request);
    }

} /*class*/
