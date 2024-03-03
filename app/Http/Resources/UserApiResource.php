<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserApiResource extends JsonResource
{
    /**
     * toArray
     *
     * @param  mixed $request
     * @return void
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'role' => $this->role,
            'access_token' => $this->access_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
