<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VolunteerBean
 * @package App\Model
 * @mixin \Eloquent
 */
class VolunteerBean extends Model
{
    protected $table = 'volunteer_beans';

    protected function volunteer()
    {
        return $this->belongsTo('App\Model\Volunteer', 'volunteer_id');
    }

    protected function beanRate()
    {
        return $this->belongsTo('App\Model\BeanRate', 'bean_rate_id');
    }
}
