<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Fascades\Redirect;


class CartController extends Controller{

	public function __construct(){


	}

	public function addToCart(Request $request) {

       //dd(session()->all());
       if (! session()->has('tracking_number')) {
            session()->put('tracking_number', Session::getId());
        }
       $tracking_number = Session::get('tracking_number');

       $data['product_info'] = DB::table('products')->where('product_row_id', $request->product_row_id)->first();

       $order_exists= DB::table('temp_orders')->where('product_row_id',$data['product_info']->product_row_id)->where('tracking_number',$tracking_number)->first();

       if($order_exists)
       {
         $product_qty= $order_exists->product_qty+$request->qty;
         DB::table('temp_orders')->where('temp_order_row_id', $order_exists->temp_order_row_id)->update([
          'product_qty'=> $product_qty,
          'product_total_price'=> ($order_exists->product_price * $product_qty)
          ]);
       }
       else
       {
            
        DB::table('temp_orders')->insert([
        'product_row_id'=> $data['product_info']->product_row_id, 
        'tracking_number'=> $tracking_number,
        'product_price'=> $data['product_info']->product_price, 
        'product_qty'=> $request->qty,
        'product_total_price'=> $data['product_info']->product_price*$request->qty,  
        'created_at'=> date('Y-m-d H:i:s'),        
        ]);  
       }
            
        return redirect('/mycart');   
    }//end addcart fucntion

	public function mycart() {
        if (! session()->has('tracking_number')) {
            session()->put('tracking_number', Session::getId());
        }
        $tracking_number = session()->get('tracking_number');
        $data['temp_orders'] = DB::table('temp_orders As To')
                               ->join('products As p', 'To.product_row_id', '=', 'p.product_row_id')
                               ->where('To.tracking_number', $tracking_number)
                               ->select('p.*', 'To.*')
                               ->get();
                               
        $data['total_price'] = DB::table('temp_orders')
                               ->where('tracking_number', $tracking_number)
                               ->sum('product_total_price');
                               
        return view('cart', ['data'=>$data]);
    }//end mycart 


    public function updateCart(Request $request) {

      if (! session()->has('tracking_number')) {
            session()->put('tracking_number', Session::getId());
        }
           
        $product_info = DB::table('temp_orders')->where('temp_order_row_id', $request->temp_order_row_id)->first();
        $product_price = DB::table('products')->where('product_row_id', $product_info->product_row_id)->first()->product_price;                       
        $product_qty = $request->qty_textbox;
        
        DB::table('temp_orders')->where('temp_order_row_id', $request->temp_order_row_id)->update([
          'product_qty'=> $product_qty,
          'product_total_price'=> ($product_price * $product_qty)
          ]);

       return redirect('/mycart');                         
    }//update cart function 


     public function cartItemDelete($temp_order_row_id) {
        if (! session()->has('tracking_number')) {
            session()->put('tracking_number', Session::getId());
        }
        DB::table('temp_orders')->where('tracking_number', session()->get('tracking_number'))->where('temp_order_row_id',$temp_order_row_id)->delete();

        echo 1;
    } //delete cart item

     public function cartItemDeleteAll() {
        if (! session()->has('tracking_number')) {
            session()->put('tracking_number', Session::getId());
        }
        DB::table('temp_orders')->where('tracking_number', session()->get('tracking_number'))->delete();
    
    }//delete all



    public function checkoutItems() {

        if (! session()->has('tracking_number')) {
            session()->put('tracking_number', Session::getId());
        }
        
        $data = []; 

        return view ('checkout_Without_login',['data'=>$data]);
    
    }//checkoutItems



    public function confirmOrder(Request $request)
    {

         if (! session()->has('tracking_number')) {
            session()->put('tracking_number', Session::getId());
          }
                $tempOrders =DB::table('temp_orders')->where('tracking_number', session()->get('tracking_number'))->get();
                $payment_type_id=$request->payment_type_id;

                $shiping['name']=$request->customer_name;
                $shiping['email']=$request->customer_email;
                $shiping['mobile']=$request->customer_phone;
                $shiping['address']=$request->customer_address;

                $shiping_address=  json_encode($shiping);

                $payment_info='';
                $order_details= [];
                $product_codes='';
                //$payment_method_id=$request->payment_method;
               $payment_id= $request->get('payment_type');

                if($payment_id==1)
                {
                      $arr1["payment_method"]='bKash';
                      $arr1["txr_id"]=$request->trnxId;
                      $arr1["payment_id"]=$payment_id;
                      $payment_info=json_encode($arr1);
                }
                else if($payment_id==2)
                {
                     $arr1["payment_method"]='DBBL Mobile Banking';
                     $arr1["txr_id"]=$request->trnxId;
                      $arr1["payment_id"]=$payment_id;
                      $payment_info=json_encode($arr1);
                }
                else if($payment_id==3)
                {
                      $arr1["payment_method"]='Cash on Delivery';
                       $arr1["payment_id"]=$payment_id;
                      $payment_info=json_encode($arr1);
                }
                else if($payment_id==4){
                $arr1["payment_method"]='VISA Card';
                $arr1["payment_id"]=$payment_id;
                $arr1["card_no"]=$request->card_number;
                $arr1["card_name"]=$request->card_holder_contactname;
                $arr1["cw"]=$request->card_security_code;
                $arr1["exp_month"]=$request->card_exp_month;
                $arr1["exp_year"]=$request->card_exp_year;
                $payment_info=json_encode($arr1);
              
                }
                else if($payment_id==5){
                $arr1["payment_method"]='Master Card';
                $arr1["payment_id"]=$payment_id;
                $arr1["card_no"]=$request->card_number;
                $arr1["card_name"]=$request->card_holder_contactname;
                $arr1["cw"]=$request->card_security_code;
                $arr1["exp_month"]=$request->card_exp_month;
                $arr1["exp_year"]=$request->card_exp_year;
                $payment_info=json_encode($arr1);
              
                }
                else if($payment_id==6){
                $arr1["payment_method"]='American Express';
                $arr1["payment_id"]=$payment_id;
                $arr1["card_no"]=$request->card_number;
                $arr1["card_name"]=$request->card_holder_contactname;
                $arr1["cw"]=$request->card_security_code;
                $arr1["exp_month"]=$request->card_exp_month;
                $arr1["exp_year"]=$request->card_exp_year;
                $payment_info=json_encode($arr1);
              
                }


                    
                foreach ($tempOrders  as $order) {
                    
                        if($order !=null)
                        {
                         $order_details[] = [
                        'product_row_id' => $order->product_row_id,
                        'product_price' => $order->product_price,
                        'product_qty' => $order->product_qty,
                        'product_total_price' => $order->product_total_price,
                        'product_name'=>Product::find($order->product_row_id)->product_name,
                        'product_image'=>Product::find($order->product_row_id)->product_image,
                        ];

                       

                        }
                }

                $order_details_final =json_encode($order_details);


        if(Auth::check())
            {
                          $insert[]=
                                [
                                    'user_id'=>  Auth::user()->id,
                                    'total_price'=>$tempOrders->sum('product_total_price'),
                                    'order_details'=>$order_details_final,
                                    'shiping_address'=> $shiping_address,
                                    'payment_details'=>$payment_info
                                    
                                ];
                               if(!empty($insert)){
                    DB::table('orders')->insert($insert);   
                 
                           } 
            }
            else{
                $user=User::firstOrNew(array('email' => $request->customer_email));
                $user->name=$request->customer_name;
                $user->email=$request->customer_email;
                $user->address=$request->customer_address;
                $user->save();
                $insertedId = $user->id;

                             $insert[]=
                                [
                                    'user_id'=>  $insertedId,
                                    'total_price'=>$tempOrders->sum('product_total_price'),
                                    'order_details'=>$order_details_final,
                                    'shiping_address'=> $shiping_address,
                                    'payment_details'=>$payment_info
                                    
                                ];
                                if(!empty($insert)){
                    DB::table('orders')->insert($insert);   
                 
                           }  

            }
            $max_order_id= DB::table('orders')->max('order_row_id');
            session()->forget('tracking_number');
           // mail($request->customer_email, 'Order Confiration', 'Dear '.$request->customer_name.' Your Order Id is:'.sprintf('%06',$max_order_id));
            return Redirect::to('/thankyou');

    }
    



    public function checkout(Request $request)
    {
      
         $currentUser = app('Illuminate\Contracts\Auth\Guard')->user();

         $tracking_number = Session::getId(); 
         $total_price = DB::table('Temp_orders')->where('tracking_number',$tracking_number)->sum('product_total_price');
        
         $order_model = new order();

         $order_model->customer_name = $currentUser->name;
         $order_model->address = $request->customer_address;
         $order_model->phone = $request->customer_phone;
         $order_model->bikas_number = $request->bikash_number;
       
         $order_model->tracking_number = $tracking_number;
         $order_model->total_price = $total_price;

         $order_model->created_at = date('Y-m-d H:i:s');
         
         $order_model->save();



     return Redirect::to('/thankyou');
     
    }


    public function checkoutWithregistration(Request $request)
    {
     $tracking_number = Session::getId(); 
     $total_price = DB::table('Temp_orders')->where('tracking_number',$tracking_number)->sum('product_total_price');
    
     $order_model = new order();

     $order_model->customer_name = $request->customer_name;
     $order_model->address = $request->customer_address;
     $order_model->phone = $request->customer_phone;
   
     $order_model->tracking_number = $tracking_number;
     $order_model->total_price = $total_price;

     
     $order_model->save();


     return Redirect::to('/thankyou');
     
    }

    
    public function thankyou()
    {
        $max_order_id= DB::table('orders')->max('order_row_id');
        $data['order_no']= str_pad($max_order_id, 6, '0', STR_PAD_LEFT);
        return view('thankyou',['data'=>$data]);
    }
    

}
