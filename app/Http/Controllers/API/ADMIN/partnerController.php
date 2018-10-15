<?php

namespace App\Http\Controllers\API\ADMIN;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\partner;
use App\user;
use Validator;
use Illuminate\Support\Facades\Auth; 
use Image;
use Carbon\Carbon;
class partnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partner = new partner;
        return response()->json($partner->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new user;
        $input = $request->all();
        $user->email = $input['username'];
        $user->name = $input['name'];
        $user->password = bcrypt($input['password']);
        $user->type =2;
        $user->save();
        $id = $user->id;

         $validator = Validator::make($request->all(), [
        'image' => 'required|image64:jpeg,jpg,png'
         ]);
         if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        } else {
            $imageData = $request->get('image');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/partner/').$fileName);
          
        }

        $input['image'] = $fileName;

        $partner = new partner;
        $partner->name = $input['name'];
        $partner->place = $input['place'];
        $partner->subPlace = $input['subPlace'];
        $partner->phone = $input['phone'];
        $partner->image = $input['image'];
        $partner->catg = $input['catg'];
        $partner->subCatg = $input['subCatg'];
        $partner->userID = $id;
        $partner->save();

        return response()->json(['msg' => 'success']);
    }


    public function showwithcatg(Request $req) 
    {
        $partner = new partner;
        return response()->json($partner::where('catg',$req->catg)->get());
    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $partner = new partner;
        return response()->json($partner::where('userID',$id)->firstOrFail());
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
        $partner = new partner;
        $partner::where('userID',$id)->update($req->all());
        return response()->json(['error' => 200]);
    }

    public function updateImage(Request $request,$id)
    {

          $validator = Validator::make($request->all(), [
        'image' => 'required|image64:jpeg,jpg,png'
         ]);
         if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        } else {
            $imageData = $request->get('image');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/partner/').$fileName);
          
        }
        $partner = new partner;
        $partner::where('userID',$id)->update(['image' => $fileName]);
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
