<?php

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
use Illuminate\http\Request;
/*Route::get('/', function () {
 
    return view('welcome');
});*/

Route::get('/','HomeController@index');

Route::resource('/product','ProductController');


Route::view('/about', 'about');

Route::view('/contact_us', 'contact');

Route::post('/submit-contact', function (Request $request){
//	dd($request);
	$data = array('name'=>$request->username,'email' => $request->email, 'message' => $request->message);

	return view('/show_details',['data'=>$data]);
});
