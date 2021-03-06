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

Route::any('/uploadImage', "Api\EditerController@upload_img");
Route::post('/submitMd', "Api\EditerController@submit");

Route::get("/info", function (Request $request) {
	return phpinfo();
});

Route::get("/news/{page}/{limit}", "Api\IndexController@news");
Route::get("/addNewsView", "Api\IndexController@addNewsView");
Route::get("/test", "Api\IndexController@test");
Route::get('/hot', "Api\IndexController@hot");
Route::get('/login', "Api\IndexController@login");
Route::post('/publish', "Api\IndexController@msg");
Route::get('/all_msg/{page}/{limit}', "Api\IndexController@all_msg");
Route::get('/up/{type}/{id}', "Api\IndexController@up");
Route::post('/reply', "Api\IndexController@reply");

Route::get("/get_tags", "Api\ManagerController@get_tags1");
Route::post("/upload_news", "Api\ManagerController@upload_news1");

Route::get("/cartoons", "Api\ManagerController@cartoons1");
Route::post("/add_cartoon", "Api\ManagerController@add_cartoon1");
Route::get("/delete_cartoon", "Api\ManagerController@delete_cartoon1");
Route::get("/delete_cartoons", "Api\ManagerController@delete_cartoons1");
Route::get("/update_cartoon", "Api\ManagerController@update_cartoon1");
Route::get("/all_news", "Api\ManagerController@all_news");
Route::get("/news_id/{id}", "Api\ManagerController@news_id");