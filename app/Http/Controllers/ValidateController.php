<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use App\Http\Requests\RuleTestRequest;
use App\Http\Requests\RulePostRequest;
use App\Http\Resources\RuleCollection;
use App\Models\Policy;
use Core\Rule\Context;
use Core\Rule\Keywords;
use Core\Rule\Regex;

class ValidateController extends Controller
{
    /**
     * get rules of policies which belong to client
     * 
     * @param client_id
     * @param text
     * 
     * @return Json
     */
    public function post(RulePostRequest $request)
    {
        $policies = Policy::where('client_id', $request->client_id)->get();
        $rules = Policy::rules($policies);
        
        return $this->check($request, $rules);
    }

    /**
     * test created rule
     * there are 2 types keywords(string)|regex
     * 
     * @return boolean
     */
    public function test(RuleTestRequest $request)
    {
        if ($request->type === 'keywords') {
            $context = new Context(new Keywords($request));
        }

        if ($request->type === 'regex') {
            $context = new Context(new Regex($request));
        }

        return $context->test();
    }

    /**
     * validate text against each rules
     * 
     * @param rule
     * @param text
     * @param reference
     * 
     * @return Json
     */
    private function check(RulePostRequest $request, Collection $rules)
    {
        $invalid = collect();

        $rules->each(function($rule) use($request, $invalid) {

            if ($rule->type === 'keywords') {
                $keywords = new Keywords($request);
                $keywords->setRule($rule);
                $context = new Context($keywords);
            }

            if ($rule->type === 'regex') {
                $regex = new Regex($request);
                $regex->setRule($rule);
                $context = new Context($regex);
            }

            $results = $context->passed();

            if ($results !== false) {
                $results->each(function($result) use($invalid) {
                    $invalid->push($result); // put invalid instance to collection
                });
            }
            
        });

        if ($invalid->isEmpty()) {
            return response([
                'passed' => 'true',
                'message' => 'everything is ok',
            ], 200);
        }

        return (new RuleCollection($invalid))->response()->setStatusCode(200);
    }
}
