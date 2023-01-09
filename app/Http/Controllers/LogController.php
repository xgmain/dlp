<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Requests\LogStoreRequest;
use App\Http\Requests\LogUpdateRequest;
use App\Http\Resources\LogCollection;
use App\Http\Resources\LogResource;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $logs = Log::all();

            if ($logs->isEmpty()) { // logs is null
                throw new \Exception('404 not found');
            }

            return (new LogCollection($logs));

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
    public function store(LogStoreRequest $request)
    {
        return (new LogResource(Log::create($request->post())));
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
            $log = Log::find($id);

            if (!$log) { // log is null
                throw new \Exception('404 not found');
            }

            return (new LogResource($log));

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
    public function update(LogUpdateRequest $request, $id)
    {
        try {
            $log = Log::find($id);

            if (!$log) { // log is null
                throw new \Exception('404 not found');
            }

            $log->update($request->toArray());

            return (new LogResource($log));

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
            $log = Log::find($id);

            if (!$log) { // log is null
                throw new \Exception('404 not found');
            }

            $log->delete();

            return (new LogResource($log));

        } catch (\Exception $e) {

            return response([
                'message' => $e->getMessage(),
            ], 404); 
        }
    }

    public function paginate(Request $request)
    {
        try {
            $logs = new Log;

            if ( $request->has('start') && $request->has('end') ) {
                $logs = $logs->whereBetween('created_at', [ $request->get('start'), $request->get('end') ]);
            }

            if ( $request->has('severity') ) {
                $logs = $logs->where('severity', $request->get('severity'));
            }
            
            if ( $request->has('limit') ) {
                $logs = $logs->orderBy('created_at', 'DESC')->paginate( $request->get('limit') );
            }

            if ($logs->isEmpty()) { // logs is null
                throw new \Exception('404 not found');
            }

            return (new LogCollection($logs));

        } catch (\Exception $e) {
            
            return response([
                'message' => $e->getMessage(),
            ], 404); 
        }
    }
}
