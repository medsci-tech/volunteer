<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2016/1/13
 * Time: 15:32
 */

namespace App\Constants;


class AppConstant {

    /* session key */
    const SESSION_USER_KEY = 'logged_user';

    /* wechat expire interval */
    const WECHAT_EXPIRE_INTERVAL        = 30;
    const AUTH_CODE_EXPIRE_INTERVAL     = 30;

    /* bean translate to money */
    const MONEY_BEAN_RATE               = 100;

    /* education daily ceiling */
    const EDUCATION_DAILY_CEILING = 5;

    /* customer type */
    const CUSTOMER_COMMON       = 'common';
    const CUSTOMER_VOLUNTEER    = 'volunteer';
    const CUSTOMER_NURSE        = 'nurse';
    const CUSTOMER_DOCTOR       = 'doctor';
    const CUSTOMER_ENTERPRISE   = 'enterprise';

    /* Bean Actions */
    const BEAN_ACTION_REGISTER      = 'register';
    const BEAN_ACTION_SIGN_IN       = 'sign_in';
    const BEAN_ACTION_STUDY         = 'study';
    const BEAN_ACTION_SHARE         = 'share';
    const BEAN_ACTION_CONSUME       = 'consume';
    const BEAN_ACTION_INVITE        = 'invite';
    const BEAN_ACTION_CONSUME_FEEDBACK  = 'consume_feedback';
    const BEAN_ACTION_TRANSFER_CASH = 'transfer_cash';

    const BEAN_ACTION_EDUCATION_VOLUNTEER_FEEDBACK  = 'education_volunteer_feedback';
    const BEAN_ACTION_CONSUME_VOLUNTEER_FEEDBACK    = 'consume_volunteer_feedback';

    const BEAN_ACTION_DOCTOR_INVITE     = 'doctor_invite';
    const BEAN_ACTION_NURSE_INVITE      = 'nurse_invite';
    const BEAN_ACTION_VOLUNTEER_INVITE  = 'volunteer_invite';

    /*url*/
    const ATTENTION_URL = 'http://mp.weixin.qq.com/s?__biz=MzAxMTY0OTc1MQ==&mid=401938638&idx=1&sn=d4483f5087ca8ccd51f48b74a5747f9c&scene=1&srcid=0114rNGJEWOnEQdEGpnX7Y7i&from=groupmessage&isappinstalled=0&key=41ecb04b051110034d288743b0058d2ba205fe3a1f448de9349ded00996528b67c70fa5a829663e231fb2043c2d4952f&ascene=1&uin=MTE4MTQ5NTM4MA%3D%3D&devicetype=android-19&version=26030849&nettype=WIFI&pass_ticket=XQ1572Eciw7RzuFw3JN%2FcySvJpc4SLsZ5mrM2iG3sEn8Wu1VZF1qnz4K9S1%2Fe%2F7O';

} /*class*/