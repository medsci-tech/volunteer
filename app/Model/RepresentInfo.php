<?php
/**
 * Created by PhpStorm.
 * User: Chelsea
 * Date: 2016/4/6
 * Time: 21:11
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RepresentInfo extends Model
{
    //
    protected $table = 'mime_represent_info';

    protected $fillable = [
        'name','phone','initial','belong_area','belong_dbm','belong_company','belong_project',
    ];
}
