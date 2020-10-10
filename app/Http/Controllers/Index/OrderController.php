<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AddressModel;
use App\Model\OrderModel;
class OrderController extends Controller
{
    public function confrimorder(Request $request){
    	$cart_id  = $request->cart_id;
    	$address  = $request->address;
    	$user = session('user_id');

    	if(!$user){
    		return redirect('login');die;
    	}
		$address = AddressModel::where('user_id',$user)->get();
		$address = $address?$address->toArray():[];
		$region = OrderModel::where('parent_id',0)->get();

    	return view('index.order.confrimorder',['address'=>$address,'region'=>$region]);
    }

    public function getsondata(Request $request){
    	$region_id  = $request->region_id; 
    	$region_son = OrderModel::where('parent_id',$region_id)->get();
    
    	return json_encode(['code'=>0,'msg'=>'OK','data'=>$region_son]);
    }
    public function store(Request $request){
        // echo "111";
        $user = session('user_name');
        $post = $request->except('_token');

        // dd($post);

        $res = AddressModel::insert($post);
        if($res){
            return redirect('/confrimorder');
        }
    }
}
