<?php

namespace App\Http\Controllers\API\APP;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\offer;

class offerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexten()
    {
        $offer = new offer;
        return response()->json($offer->inRandomOrder()->take(10)->get());
    }

    //// for book mark get max offfer id for make alloppp
    public function maxID() {
        $offer = new offer;
        return response()->json($offer->max('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    public function search(Request $req)
    {
        $offer = new offer;
        if($req->catg == 'All Catagoray')
        {
            return response()->json($offer::where('subPlace',$req->place)->get());
        }else {
            return response()->json($offer::where('subPlace',$req->place)->where('catg',$req->catg)->get());
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
    $offer = new offer;
    return response()->json($offer::where('id',$id)->firstOrFail());   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
