<?php

namespace Core\Rule;

use Illuminate\Support\Str;
use App\Models\Rule;

class Keywords implements Processor
{
    use Traits;

    private $request;
    private $rule;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function setRule(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function check()
    {
        return $this->check_call_back();
    }

    public function test()
    {
        return Str::contains($this->filter($this->request->text), $this->request->context);
    }

    public function filter(String $string)
    {
        $pattern = '^0-9a-zA-Z'; //allow those

        $text = preg_replace('/['. $pattern .']/', "", $string);
        // lowcase text
        $text = strtolower($text);

        return $text;
    }

    public function reference(String $string)
    {
        $allowSign = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        '0','1','2','3','4','5','6','7','8','9']; //allow those

        return $this->reference_call_back($string, $allowSign);
    }
}
