<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lichSuTrungQua extends Model
{
    //
    protected $fillable = [
        'id','phone', 'reward', 'status'
    ];
    protected $table = 'lich-su-trung-qua';
}
