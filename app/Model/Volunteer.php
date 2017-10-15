<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Volunteer
 * @package App\Model
 * @mixin \Eloquent
 */
class Volunteer extends Model
{
    protected $table = 'mime_volunteers';

    public function unit()
    {
        return $this->belongsTo('App\Model\Unit', 'unit_id');
    }

    public function beans()
    {
        return $this->hasMany('App\Model\VolunteerBean', 'volunteer_id');
    }

    public function represent()
    {
        return $this->belongsTo('App\Model\RepresentInfo', 'number', 'initial');
    }
}
