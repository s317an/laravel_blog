<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    //モデルに関連付けるテーブル
    protected $table = 'blogs';

    //複数代入可能な属性
    protected $fillable = 
    [
        'title',
        'content'
    ];

}
