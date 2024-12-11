<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'created_user_id',
        'winner_user_id',
        'type',
        'status',
        'began_at',
        'ended_at',
        'total_time',
        'board_id',
        'total_turns_winner',
    ];

    public $timestamps = true;

    public function created_user_id(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function winner_user_id(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }
}
