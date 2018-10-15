<?php

namespace App\Http\Controllers\API\ADMIN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\vipOffer;
use Image;
use Carbon\Carbon;
use Validator;
use App\partner;
use App\condition;
class vipOfferController extends Controller
{
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $vipOffer = new vipOffer;
        return response()->json($vipOffer::where('partnerID',$id)->get());
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
        $catgTitle = $partner::where('userID',$req->partnerID)->value('catg');
        $subPlace = $partner::where('userID',$req->partnerID)->value('subPlace');

        $vipOffer = new vipOffer;
        $vipOffer->title = $req->title;
        $vipOffer->image = $fileName;
        $vipOffer->perc = $req->perc;
        $vipOffer->oldVal = $req->oldVal;
        $vipOffer->newVal = $req->newVal;
        $vipOffer->partnerID = $req->partnerID;
        $vipOffer->catg = $catgTitle;
        $vipOffer->subPlace = $subPlace;
        $vipOffer->save();

            // DELETE OLDEST ONE TO KEEP 3 OFFER JUST
        $count = $vipOffer::where('partnerID',$req->partnerID)->count();
        if($count > 1) {
            $minID = $vipOffer::where('partnerID',$req->partnerID)->min('id');
            $vipOffer::where('id',$minID)->delete();
        }
        return response()->json(['success' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vipOffer = new vipOffer;
        return response()->json($vipOffer::where('id',$id)->firstOrFail());
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
        $vipOffer = new vipOffer;
        $input = $req->all();
        $vipOffer::where('id',$id)->update($input);
        return response()->json(['msg' => 200]);
    }

    public function updateImage(Request $req, $id)
    {
        $vipOffer = new vipOffer;
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

        $vipOffer::where('id',$id)->update(['image' => $fileName]);
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
