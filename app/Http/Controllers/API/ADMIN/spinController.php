<?php

namespace App\Http\Controllers\API\ADMIN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\spinoffer;
use Validator;
use Illuminate\Support\Facades\Auth; 
use Image;
use Carbon\Carbon;
use App\spinofferdetails;
use App\userWallet;
use App\userSpin;
class spinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($catg,Request $req)
    {

        $userSpin = new userSpin;
        $count = $userSpin::where('userID',$req->id)->where('type', $catg)->count();


        if($count == 0) {
            $userSpin->userID = $req->id;
            $userSpin->type = $catg;
            $userSpin->lastSpin = '0';
            $userSpin->save();
        }else {
            $lastSpin = $userSpin::where('userID',$req->id)->where('type', $catg)->value('lastSpin');
            if($lastSpin == '0') {
                $spin = true;
            }else {
                     $dtArray = explode('-', $lastSpin);
            $dt = Carbon::create($dtArray[2],$dtArray[1],$dtArray[0]);
            $dt->toDateTimeString();
            $dt->addDay(2);
            $toSpin = $dt->format('d-m-Y');

            if($toSpin == Carbon::now()->format('d-m-Y')) {
            $spin = true;

            }else {
            $spin = false;
            }
            }
       
        }


        $spinoffer = new spinoffer;
        $fetch = $spinoffer::where('spintype',$catg)->get();
        return response()->json(['fetch' => $fetch,'spin' => $spin]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'image' => 'required|image64:jpeg,jpg,png'
         ]);
         if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        } else {
            $imageData = $request->get('image');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/spin/').$fileName);
          
        }

        $spinofferdetails = new spinofferdetails;
        $spinofferdetails->title = $request->title;
        $spinofferdetails->partnerID = $request->market;
        $spinofferdetails->image = $fileName;
        $spinofferdetails->save();

        $offerID = $spinofferdetails->id;

        $spinoffer = new spinoffer;
        $spinoffer::where('id',$request->spinid)->update(['empty' => '1', 'offerID' => $offerID]);

        return response()->json(['success' => 200]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$type)
    {
        $spinoffer = new spinoffer;
        $offerid = $spinoffer::where('id',$id)->where('spintype',$type)->value('offerid');
        $spinofferdetails = new spinofferdetails;
        return response()->json($spinofferdetails::where('id',$offerid)->firstOrFail());
    }

     public function showSpinID($id,$type,Request $req)
    {
        $spinoffer = new spinoffer;
        $offerid = $spinoffer::where('spinid',$id)->where('spintype',$type)->value('offerid');
        $spinofferdetails = new spinofferdetails;
        if($spinofferdetails::where('id',$offerid)->count() == 0)
        {

        } else {
            $userWallet = new userWallet;
            $userWallet->userID = $req->id;
            $userWallet->offerID = $offerid;
            $userWallet->vaild = '0';
            $userWallet->save();
            $userSpin = new userSpin;
            $userSpin = $userSpin::where('userID',$req->id)->where('type',$type)->update(['lastSpin' => Carbon::now()->format('d-m-Y')]);
        }

        return response()->json($spinofferdetails::where('id',$offerid)->firstOrFail());

        // return response()->json(['msg' => $id[]]);
    }


    public function showWallet($id) 
    {
        $userWallet = new userWallet;
        return response()->json($userWallet::where('userID', $id)->get());
    }

    public function showOffer($id)
    {
        $spinofferdetails = new spinofferdetails;
        return response()->json($spinofferdetails::where('id',$id)->firstOrFail());
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
    public function destroyWallet($id)
    {
        $userWallet = new userWallet;
        $userWallet::where('id', $id)->delete();
        return response()->json(['msg' => 200]);
    }
}
