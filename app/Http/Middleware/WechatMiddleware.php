<?php

namespace App\Http\Middleware;

use Closure;
use Overtrue\Wechat\Auth;

class WechatMiddleware
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
        if (\Session::has('logged_user')) {
            \Log::info('WechatMiddleware:has logged_user');
            return $next($request);
        } /*if>*/

        $appId  = env('WX_APPID');
        $secret = env('WX_SECRET');
        $auth = new Auth($appId, $secret);
        $user = $auth->authorize(url($request->fullUrl()));
        if ($user) {
            \Log::info('get user put logged_user');
            \Session::put('logged_user', $user->all());
        } else {
            \Log::info('put null into logged_user');
            \Session::put('logged_user', null);
        } /*else>*/
        return $next($request);
    }

} /*class*/
