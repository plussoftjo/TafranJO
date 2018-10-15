<?php

namespace App\Http\Controllers\API\ADMIN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\offer;
use Image;
use Carbon\Carbon;
use Validator;
use App\partner;
use App\condition;
class offerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $offer = new offer;
        return response()->json($offer::where('partnerID',$id)->orderBy('id','desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {


         $validator = Validator::make($req->all(), [
        'image' => 'required|image64:jpeg,jpg,png'
         ]);
         if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        } else {
            $imageData = $req->get('image');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($req->get('image'))->save(public_path('images/offers/').$fileName);
          
        }


        $partner = new partner;
        $catgTitle = $partner::where('userID',$req->offer['partnerID'])->value('catg');
        $subPlace = $partner::where('userID',$req->offer['partnerID'])->value('subPlace');
        $subCatg = $partner::where('userID',$req->offer['partnerID'])->value('subCatg');

        $offer = new offer;
        $offer->title = $req->offer['title'];
        $offer->image = $fileName;
        $offer->perc = $req->offer['perc'];
        $offer->oldVal = $req->offer['oldVal'];
        $offer->newVal = $req->offer['newVal'];
        $offer->partnerID = $req->offer['partnerID'];
        $offer->subPlace = $subPlace;
        $offer->subCatg = $subCatg;
        $offer->catg = $catgTitle;
        $offer->save();

        $offerID = $offer->id;
        foreach ($req->condition as $key) {
            $condition = new condition;
            $condition->title = $key;
            $condition->offerID = $offerID;
            $condition->save();
        }



        // DELETE OLDEST ONE TO KEEP 3 OFFER JUST
        $count = $offer::where('partnerID',$req->offer['partnerID'])->count();
        if($count > 3) {
            $minID = $offer::where('partnerID',$req->offer['partnerID'])->min('id');
            $offer::where('id',$minID)->delete();
        }
      
        return response()->json(['success' => 200,'count' => $count]);
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

    public function showCondition($id)
    {
        // id is for offer
        $condition = new condition;
        return response()->json($condition::where('offerID',$id)->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $offer = new offer;
        $input = $req->all();
        $offer::where('id',$id)->update($input);
        return response()->json(['msg' => 200]);
    }

    public function updateImage(Request $req, $id)
    {
        $offer = new offer;
         $validator = Validator::make($req->all(), [
        'image' => 'required|image64:jpeg,jpg,png'
         ]);
         if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        } else {
            $imageData = $req->get('image');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($req->get('image'))->save(public_path('images/offers/').$fileName);
          
        }

        $offer::where('id',$id)->update(['image' => $fileName]);
        return response()->json(['error' => 200]);
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
