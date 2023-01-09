<?php

namespace Core\Rule;

use Illuminate\Support\Collection;
use App\Models\Log;
use Core\Log\Main;

trait Util
{
    public function tokenize(String $string)
    {
        return md5($string);
    }

    public function compare(Int $start, Int $end, String $content, String $formID, Log $f)
    {
        return strcmp($this->tokenize($start.$end.$content.$formID), $f->token) === 0;
    }

    public function formID()
    {
        return $this->tokenize($this->request->client_id.$this->request->form_id.
        $this->request->user_id.$this->request->ip.$this->request->question_id);
    }

    public function merge(Collection $a, Collection $b)
    {
        return $a->merge($b)->sortBy([
            ['start', 'asc'],
        ]);
    }

    public function extraAttribute(Int $start, Int $end, String $content, String $token)
    {   
        $rule = clone $this->rule;
        
        $rule->setAttribute('start', $start);
        $rule->setAttribute('end', $end);
        $rule->setAttribute('content', $content);
        $rule->setAttribute('token', $this->tokenize($start.$end.$content.$token));

        return $rule;
    }

    public function setAvailFalse(Log $log)
    {
        $log->available = false;
        $log->save();
    }

    public function saveLog(Collection $collect)
    {
        $ids = collect();
        $log =  new Main();

        $collect->each(function($rule) use($log, $ids) {
            $log->set($this->request);
            $log->set($rule);
            $ids->push($log->create()->id); // each save, all log keep siblings relationship
        });

        $log->sibling($ids); //save siblings relationship
    }

    /**
     * filter out denied char in given string | lowcase all
     * 
     * @param String pattern
     * @return Collection
     */
    public function arrange()
    {
        $str = $this->request->text;
        $return = collect();

        /* convert Latin characters to ASCII */
        $translitRules = 'Any-Latin; Latin-ASCII;';
        $str = transliterator_transliterate($translitRules, $str);

        $str = collect(preg_split("/\r\n|\n|\r/", $str))->filter(function($value) {
            return $value !== '';
        });
   
        $str->each(function($item) use(&$return){
            $foundEmail = $this->emailParts($item);
            $foundNoEmail = $this->nonEmailParts($item, $foundEmail);
            $return->push($this->merge(collect($foundEmail), $foundNoEmail));
        });
        dd($return);
        return collect($return->collapse()->all());
    }
}