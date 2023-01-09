<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuleStoreRequest;
use App\Http\Requests\RuleUpdateRequest;
use App\Http\Resources\RuleCollection;
use App\Http\Resources\RuleResource;
use App\Models\Rule;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $rules = Rule::all();

            if ($rules->isEmpty()) { // rules is null
                throw new \Exception('404 not found');
            }

            return (new RuleCollection($rules));

        } catch (\Exception $e) {
            
            return response([
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RuleStoreRequest $request)
    {   
        return (new RuleResource(Rule::create($request->post())));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $rule = Rule::find($id);

            if (!$rule) { // rule is null
                throw new \Exception('404 not found');
            }

            return (new RuleResource($rule));

        } catch (\Exception $e) {
            
            return response([
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RuleUpdateRequest $request, $id)
    {
        try {
            $rule = Rule::find($id);

            if (!$rule) { // rule is null
                throw new \Exception('404 not found');
            }

            $rule->update($request->toArray());

            return (new RuleResource($rule));

        } catch (\Exception $e) {

            return response([
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rule = Rule::find($id);

            if (!$rule) { // rule is null
                throw new \Exception('404 not found');
            }

            $rule->delete();

            return (new RuleResource($rule));

        } catch (\Exception $e) {

            return response([
                'message' => $e->getMessage(),
            ], 404); 
        }
    }
}
