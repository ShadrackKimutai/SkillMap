<?php
//use Auth;
use App\Http\Controllers\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$data=DB::table('users')->get();
    return view('index')->withData($data);
});
Route::get('/entrepreneurs', function () {
 $data=DB::table('users')->get();
 

//dd($graduate);
//dd($data);
return view('entrepreneurs')->withData($data);
});

Route::get('/map', function() {
	return view('mapscript');
});
Auth::routes();

Route::get('home', function(){ 
	$data=DB::table('users')->get();
	return view('home')->withData($data);
});
Route::get('profile', 'UserController@CurrentProfile');
Route::post('edit-profile', 'UserController@update_avatar');
Route::get('edit-profile','UserController@profile');

