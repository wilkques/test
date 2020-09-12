<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fortune extends Model
{
    protected $table = 'fortune';

    protected $fillable = [
        'astro',
        'execute_day',
        'fortune',
        'fortune_comment',
        'love',
        'love_comment',
        'cause',
        'cause_comment',
        'money',
        'money_comment'
    ];
}
