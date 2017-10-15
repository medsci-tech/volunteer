<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HPXTClass extends Model
{
    protected $table = 'hpxt_classes';

    protected function volunteer()
    {
        return $this->belongsTo('App\Model\Volunteer', 'volunteer_id');
    }
}
