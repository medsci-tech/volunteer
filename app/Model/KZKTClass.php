<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KZKTClass extends Model
{
    protected $table = 'mime_kzkt_classes';

    public  function volunteer()
    {
        return $this->belongsTo('App\Model\Volunteer', 'volunteer_id');
    }

    //医生详细信息
    public  function doctor()
    {
        return $this->belongsTo('App\Model\Doctor', 'doctor_id');
    }
}
