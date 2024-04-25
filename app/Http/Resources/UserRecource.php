<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRecource extends JsonResource
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
            'email' => $this->Email,
            'namaLengkap' => $this->whenNotNull($this->NamaLengkap),
            'username' => $this->Username,
            'level' => $this->Level,
            'token' => $this->whenNotNull($this->token)
        ];
    }
}
