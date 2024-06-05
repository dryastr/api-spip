<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class MyMenu2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'icon' => $this->icon,
            'url' => $this->url,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
            'mode' => $this->mode,
            'sub' => empty($this->sub) ? [] : self::collection($this->sub),
            'permissions' => $this->permissions,
            'tab' => empty($this->tab) ? [] : self::collection($this->tab),
            'count' => $this->when(isset($this->parent_id), $this->count),
        ];
    }
}
