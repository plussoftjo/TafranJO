<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('user/login', ['as' => 'login', 'uses'=>'API\userController@login']);
Route::post('user/register', 'API\userController@register');
Route::get('user/showite', 'API\userController@showit');

Route::group(['middleware' => 'auth:api'], function(){
Route::get('user/details', 'API\userController@details');
Route::post('user/logout', 'API\userController@logout');



/// Route for catgoray
Route::get('admin/catg/index', 'API\catgController@index');
Route::post('admin/catg/store', 'API\catgController@store');
Route::get('admin/catg/show/{id}', 'API\catgController@show');
Route::post('admin/catg/update/{id}', 'API\catgController@update');
Route::get('admin/catg/destroy/{id}', 'API\catgController@destroy');
Route::get('admin/catg/subcatg/index/{id}', 'API\catgController@subCatgShow');


/// Route for partner Admin
Route::get('admin/partner/index','API\ADMIN\partnerController@index');
Route::post('admin/partner/store','API\ADMIN\partnerController@store');
Route::post('admin/partner/showwithcatg','API\ADMIN\partnerController@showwithcatg');
Route::get('admin/partner/show/{id}','API\ADMIN\partnerController@show');
Route::post('admin/partner/setting/update/{id}','API\ADMIN\partnerController@update');
Route::post('admin/partner/update/image/{id}','API\ADMIN\partnerController@updateImage');

///// ADMIN PARTNER OFFERS CONTROLLERS & API
Route::get('admin/offer/withpartner/{id}','API\ADMIN\offerController@index');
Route::post('admin/offer/store','API\ADMIN\offerController@store');
Route::get('admin/offer/show/{id}', 'API\ADMIN\offerController@show');
Route::post('admin/offer/update/{id}','API\ADMIN\offerController@update');
Route::post('admin/offer/update/image/{id}','API\ADMIN\offerController@updateImage');


Route::get('admin/offer/vip/withpartner/{id}','API\ADMIN\vipOfferController@index');
Route::post('admin/offer/vip/store','API\ADMIN\vipOfferController@store');
Route::get('admin/offer/vip/show/{id}', 'API\ADMIN\vipOfferController@show');
Route::post('admin/offer/vip/update/{id}','API\ADMIN\vipOfferController@update');
Route::post('admin/offer/vip/update/image/{id}','API\ADMIN\vipOfferController@updateImage');


//////////// SPInFUNCTION 
Route::post('admin/spin/index/{catg}','API\ADMIN\spinController@index');
Route::post('admin/spin/store','API\ADMIN\spinController@store');
Route::get('admin/spin/show/{id}/type/{type}','API\ADMIN\spinController@show');
Route::post('admin/spin/showspinid/{id}/type/{type}','API\ADMIN\spinController@showSpinID');
Route::get('admin/wallet/show/{id}','API\ADMIN\spinController@showWallet');
Route::get('admin/spin/offer/showwithid/{id}','API\ADMIN\spinController@showOffer');
Route::get('admin/wallet/approve/{id}','API\ADMIN\spinController@destroyWallet');


// polace 
Route::post('admin/place/store', 'API\ADMIN\placeController@store');
Route::get('admin/place/index', 'API\ADMIN\placeController@index');
Route::get('admin/place/destroy/{id}', 'API\ADMIN\placeController@destroy');


////////////////
///// API ROUTE APP 
/////////
Route::get('catg/index', 'API\APP\catgController@index');
Route::get('catg/checkhassub/{id}', 'API\APP\catgController@checkhassub');
Route::get('catg/getOffer/{id}', 'API\APP\catgController@getOffer');
Route::get('offer/getten','API\APP\offerController@indexten');
Route::get('offer/maxid','API\APP\offerController@maxID');
Route::get('offer/show/{id}','API\APP\offerController@show');
Route::post('offer/search','API\APP\offerController@search');
Route::get('offer/subCatg/{id}','API\APP\subCatgController@show');
Route::get('offer/catg/{id}','API\APP\catgController@show');

//// PARTNER ROUTE 
Route::get('partner/show/{id}','API\APP\partnerController@show');
Route::get('partner/show/offer/{id}','API\APP\partnerController@getOffer');
Route::get('offer/show/condition/{id}','API\ADMIN\offerController@showCondition');

Route::get('test/session/index','test\sessiontest@index');
Route::get('test/session/store','test\sessiontest@store');





});



////// SESSION ROUUTE 
Route::group(['middleware' => 'session'],function () {

});