<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StatusWorker;

class StatusWorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nSWorker = new StatusWorker;

        $nSWorker->emri = $request->emri;
        $nSWorker->toRes= $request->toRes;

        $nSWorker->save();

        if(isset($request->isWaiter)){
            return redirect()->route('admWoMng.adminWoStatusWorkerWaiter');
        }else{
            return redirect()->route('dash.statusWorker');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $swUpdate = StatusWorker::find($request->updateWorId);

        $swUpdate->emri = $request->emri;
        $swUpdate->save();

        if(isset($request->isWaiter)){
            return redirect()->route('admWoMng.adminWoStatusWorkerWaiter');
        }else{
            return redirect()->route('dash.statusWorker');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        StatusWorker::find($req->delId)->delete();
    }
}
