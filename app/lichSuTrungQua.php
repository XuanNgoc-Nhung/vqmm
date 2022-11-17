<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lichSuTrungQua extends Model
{
    //
    protected $fillable = [
        'id','phone', 'reward', 'status','created_at'
    ];
    protected $table = 'lich-su-trung-qua';
}
