<?php

use Illuminate\Http\Request ;
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

Route::middleware('auth:api')->get('/user',function (Request $request) {
	return $request->user();
});

Route::post('/uploadImage', "Api\EditerController@upload_img");
Route::post('/submitMd', "Api\EditerController@submit");

Route::get("/info", function (Request $request) {
	return phpinfo();
});
Route::get("/upload_news", "Api\EditerController@news");
