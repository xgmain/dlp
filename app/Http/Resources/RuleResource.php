<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PolicyResource;

class RuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'policy' => new PolicyResource($this->policy),
            'name' => $this->name,
            'context' => $this->context,
            'type' => $this->type,
            'status' => $this->status,
            'message' => $this->message,
        ];

        if(isset($this->start)) {
            $result['start'] = $this->start;
        }

        if(isset($this->end)) {
            $result['end'] = $this->end;
        }

        return $result;
    }
}
