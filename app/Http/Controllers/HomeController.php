<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class HomeController extends Controller
{
	public function index(){

		$products = DB::table('products')->get();
		//dd($products);
	// $data = array('name'=>'Saiful',
	// 	'email' => 'saiful@hotmail.com',
	// 	 'message' => 'Saiful is GPA 5.');
		
		return view('home',compact('products'));

	}
    //
}
