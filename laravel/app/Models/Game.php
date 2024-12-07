<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'created_user_id',
        'type',
        'status',
        'began_at',
        'ended_at',
        'total_time',
        'board_id',
        'total_turns_winner',
    ];

    public $timestamps = true;
}
