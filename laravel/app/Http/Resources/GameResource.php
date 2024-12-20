<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_user_id' => $this->created_user_id,
            'status' => $this->status,
            'type' => $this->type,
            'began_at' => $this->began_at,
            'ended_at' => $this->ended_at,
            'total_time' => $this->total_time,
            'board_id' => $this->board_id,
            'total_turns_winner' => $this->total_turns_winner,
            'custom' => $this->custom,
        ];
    }
}
