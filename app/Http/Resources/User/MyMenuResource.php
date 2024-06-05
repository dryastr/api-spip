<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class MyMenuResource extends JsonResource
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
        if (isset($this['sectionTitle'])) {
            return [
                'sectionTitle' => $this['sectionTitle'],
            ];
        }

        $children = empty($this->children) ? [] : self::collection($this->children);
        $result = [
            'id' => $this->id,
            'title' => $this->title,
            'permissions' => $this->permissions,
        ];

        if (count($children) > 0) {
            $result['children'] = $children;
        }

        if ($this->icon) {
            $result['icon'] = $this->icon;
        }

        if ($this->url) {
            $result['path'] = $this->url;
        }

        return $result;
    }
}
