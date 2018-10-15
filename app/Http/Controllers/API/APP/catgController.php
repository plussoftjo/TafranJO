<?php

namespace App\Http\Controllers\API\APP;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\catg;
use App\subCatg;
use App\offer;
class catgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catg = new catg;
        return response()->json($catg->get());
    }

    public function checkhassub($id) {
        $subCatg = new subCatg;
        $catg = new catg;

        $value = $catg::where('id',$id)->value('title');

        $subCount = $subCatg::where('catgID',$value)->count();

        if($subCount == 0)
        {
            return response()->json(['hasSub' => false]);
        }else {
            return response()->json(['hasSub' => true, 'subCatg' => $subCatg::where('catgID',$value)->get()]);
        }
    }
    

    public function getOffer($id) 
    {
        $catg = new catg;
        $title = $catg::where('id',$id)->value('title');
        $offer = new offer;
        return response()->json($offer::where('catg',$title)->get());
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $catg = new catg;
        $catgTitle = $catg::where('id',$id)->value('title');
        $offer = new offer;
        return response()->json($offer::where('catg',$catgTitle)->orderBy('id','desc')->get());
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
