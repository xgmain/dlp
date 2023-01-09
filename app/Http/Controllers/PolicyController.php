<?php

namespace App\Http\Controllers;

use App\Http\Resources\PolicyCollection;
use App\Http\Resources\PolicyResource;
use App\Http\Requests\PolicyStoreRequest;
use App\Http\Requests\PolicyUpdateRequest;
use App\Models\Policy;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $policies = Policy::all();

            if ($policies->isEmpty()) { // policies is null
                throw new \Exception('404 not found');
            }

            return (new PolicyCollection($policies));

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
    public function store(PolicyStoreRequest $request)
    {
        return (new PolicyResource(Policy::create($request->post())));
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
            $policy = Policy::find($id);

            if (!$policy) { // policy is null
                throw new \Exception('404 not found');
            }

            return (new PolicyResource($policy));

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
    public function update(PolicyUpdateRequest $request, int $id)
    {
        try {
            $policy = Policy::find($id);

            if (!$policy) { // policy is null
                throw new \Exception('404 not found');
            }

            $policy->update($request->toArray());

            return (new PolicyResource($policy));

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
            $policy = Policy::find($id);

            if (!$policy) { // policy is null
                throw new \Exception('404 not found');
            }

            $policy->delete();

            return (new PolicyResource($policy));

        } catch (\Exception $e) {

            return response([
                'message' => $e->getMessage(),
            ], 404); 
        }
    }
}
