<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\catg;
use Validator;
use Image;
use App\subCatg;
use Carbon\Carbon;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
        'image' => 'required|image64:jpeg,jpg,png'
         ]);
         if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        } else {
            $imageData = $request->get('image');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($request->get('image'))->save(public_path('images/catg/').$fileName);
          
        }

        $input['image'] = $fileName;
         $catg = catg::create($input);
        return response()->json(['error' => 200]);
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
        $fetch = $catg::where('id',$id)->firstOrFail();
        return response()->json($fetch);
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
        $catg = new catg;
        $catg->where('id',$id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $catg = new catg;
        $catg->where('id',$id)->delete();
        return response()->json(['msg' => 200]);
    }

    public function subCatgShow($title)
    {
        $subCatg = new subCatg;
        return response()->json($subCatg::where('catgID',$title)->get());
    }
}
