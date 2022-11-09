<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class phanThuong extends Model
{
    protected $fillable = [
        'id','name', 'image', 'value','ratio','used','amount'
    ];
    protected $table = 'phan-thuong';
}
