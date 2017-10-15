<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BeanRate extends Model
{
    protected $table = 'bean_rates';

    protected function beans()
    {
        return $this->hasMany('\App\Model\VolunteerBean', 'bean_rate_id');
    }
}
