<?php

namespace Core\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Rule;
use App\Models\Log;

class Main
{
    private $request;
    private $rule;

    public function __construct(){}

    public function set($value)
    {
        if ($value instanceof Request) {
            $this->request = $value;
        }   
        
        if ($value instanceof Rule) {
            $this->rule = $value;
        }
    }

    public function format()
    {
        return [
            'rule_id' => $this->rule->id,
            'system' => 'dlp',
            'event' => 'log',
            'severity' => $this->rule->status,
            'content' => $this->rule->content,
            'token' => $this->rule->token,
            'ip' =>  $this->request->ip,
            'src' => $this->request->src,
            'user_id' => $this->request->user_id,
            'position' => json_encode([
                'start' => $this->rule->start,
                'end' => $this->rule->end
            ]),
            'form_id' => $this->request->form_id,
            'question_id' => $this->request->question_id,
        ];
    }

    public function create()
    {
        return Log::create($this->format());
    }

    public function sibling(Collection $ids)
    {
        // save sibling ids into each.
        $ids->each(function($id) use($ids) {
            $log = Log::find($id);
            $log->sibling = $ids->toJson();
            $log->save();
        });
        
    }
}
