<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rule' => new RuleResource($this->rule),
            'system' => $this->system,
            'event' => $this->event,
            'severity' => $this->severity,
            'ip' => $this->ip,
            'user_id' => $this->user_id,
            'form_id' => $this->form_id,
            'question_id' => $this->question_id,
            'time to detected' => $this->created_at->format('Y M d - H:i:s')
        ];
    }
}
