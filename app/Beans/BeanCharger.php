<?php

namespace App\Beans;

use App\Model\Activity;
use App\Model\Volunteer;
use App\Model\BeanRate;
use App\Model\VolunteerBean;

/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2016/2/2
 * Time: 12:55
 */
trait BeanCharger
{
    private function recharge($user, $beanRate) {
        $bean = new VolunteerBean();
        $bean->volunteer_id = $user;
        $bean->bean_rate_id = $beanRate->id;
        $bean->is_valid     = false;
        $ret = $bean->save();

        return ($ret);
    }

    public function hpxtCharger($user)
    {
        if (!$user) {
            return false;
        } /*if>*/

        $beanRate = BeanRate::where('action_en', 'hpxt_apply_class')->first();
        if (!$beanRate) {
            return false;
        } /*if>*/

        $ret = recharge($user, $beanRate);
        return $ret;
    }

    public function kzktCharger($user)
    {
        if (!$user) {
            return false;
        } /*if>*/

        $beanRate = BeanRate::where('action_en', 'kzkt_apply_doctor')->first();
        if (!$beanRate) {
            return false;
        } /*if>*/

        $ret = recharge($user, $beanRate);
        return $ret;
    }

}