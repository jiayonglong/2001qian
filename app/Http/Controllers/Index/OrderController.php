<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AddressModel;
use App\Model\OrderModel;
use App\Model\CartModel;
use App\Model\Goodsattr;
use App\Model\OrdergoodsModel;
use App\Model\OrderinfoModel;
use DB;
class OrderController extends Controller
{
    public function confrimorder(Request $request){
        $cart_id  = $request->cart_id;
        // dd($cart_id);
    	$address  = $request->address;
    	$user = session('user_id');

    	if(!$user){
    		return redirect('login');
    	}
		$address = AddressModel::where('user_id',$user)->get();
		$address = $address?$address->toArray():[];
		$region = OrderModel::where('parent_id',0)->get();

        $jyl = AddressModel::where('user_id',$user)->get();
        $reg = new OrderModel;
        foreach($jyl as $k=>$v){
            $jyl[$k]['country'] = $reg->where('region_id',$v->country)->value('region_name'); 
            $jyl[$k]['province'] = $reg->where('region_id',$v->province)->value('region_name');
            $jyl[$k]['city'] = $reg->where('region_id',$v->city)->value('region_name');
            $jyl[$k]['district'] = $reg->where('region_id',$v->district)->value('region_name');
            $jyl[$k]['tel'] = substr($v->tel,0,3)."****".substr($v->tel,7,4);
        }
        $cart_id = explode(',',$request->cart_id);
         $cart = CartModel::select('ecs_cart.*','ecs_goods.goods_thumb')->leftjoin('ecs_goods','ecs_cart.goods_id','=','ecs_goods.goods_id')->whereIn('cart_id',$cart_id)->get();
        // dd($cart);
        foreach($cart as $k=>$v){
            if($v->goods_attr_id){
                $goods_attr_id = explode('|',$v->goods_attr_id);
                $Goods_attr = Goodsattr::select('attr_name','attr_value')->leftjoin('attribute','ecs_goods_attr.attr_id','=','attribute.attr_id')->whereIn('goods_attr_id',$goods_attr_id)->get();
                $cart[$k]['goods_attr'] = $Goods_attr?$Goods_attr->toArray():[];
            }
        }
        
        $cartData = CartModel::select('ecs_goods.goods_id','ecs_goods.goods_name','ecs_goods.shop_price','ecs_goods.goods_thumb','ecs_cart.buy_number')
                    ->leftjoin('ecs_goods','ecs_cart.goods_id','=','ecs_goods.goods_id')
                    ->where(['user_id'=>$user,'is_on_sale'=>1])
                    ->whereIn('ecs_cart.cart_id',$cart_id)
                    ->get();
         $price = DB::select("select SUM(shop_price*buy_number) as total FROM ecs_cart");
        //   var_dump($price);exit;
          $cartadd = implode(',',$cart_id);
        //   dd($cartadd);
         $count = count($cartData);
    	return view('index.order.confrimorder',['address'=>$address,'region'=>$region,'jyl'=>$jyl,'cart'=>$cart,'count'=>$count,'price'=>$price,'cartadd'=>$cartadd]);
    }

    public function getsondata(Request $request){
    	$region_id  = $request->region_id; 
    	$region_son = OrderModel::where('parent_id',$region_id)->get();
    	return json_encode(['code'=>0,'msg'=>'OK','data'=>$region_son]);
    }
    public function store(Request $request){
        // echo "111";
        $user = session('user_id');
        // dd($user);

        $post = $request->except('_token');
        // dd($post);
        $res = AddressModel::insert($post);
        if($res){
            return redirect('/confrimorder');
        }
    }
    public function order(Request $request){
        
        $datas = $request->all();
    	// dd($datas);
        $cartadd = $datas['cartadd']?explode(',',$datas['cartadd']):[];
        // dd($cartadd);
  
    	$user_id = session()->get('user_id');

    	if(!$user_id){
    		return redirect('login');die;
    	}
    	if($datas['pay_type']==1){
    		$datas['pay_name'] = "微信支付";
    	}else if($datas['pay_type']==2){
    		$datas['pay_name'] = "银行卡支付";
    	}else if($datas['pay_type']==3){
    		$datas['pay_name'] = "支付宝支付";
    	}else if($datas['pay_type']==4){
    		$datas['pay_name'] = "货到付款";
    	}
    	// dd($datas);
    	DB::beginTransaction();
    	try {
	    		//生成订单号
	    	$order_sn = $this->getOrdersn($user_id);
	    	//获取订单的收货地址消息
	    	$addressdata = AddressModel::where('address_id',$datas['address_id'])->first();
	    	$data = [
		    		'order_sn' => $order_sn,
		    		'user_id' => $user_id,
		    		'email' => $addressdata->email,
		    		'country' => $addressdata->country,
		    		'province' => $addressdata->province,
		    		'city' => $addressdata->city,
		    		'district' => $addressdata->district,
		    		'address' => $addressdata->address,
		    		'zipcode' => $addressdata->zipcode,
		    		'tel' => $addressdata->tel,
		    		'mobile' => $addressdata->mobile,
		    		'sign_building' => $addressdata->sign_building,
		    		'best_time' => $addressdata->best_time,
		    		'pay_type' => $datas['pay_type'],
		    		'pay_name' => $datas['pay_name'],
		    		'total_price' => $datas['allprice'],
		    		'deal_price' => $datas['deal_price'],
		    		'addtime' => time(),
    			];
		   	//添加订单入库，返回ID
	    	$order_id = OrderinfoModel::insertGetId($data);

	    	//查询订单商品数据
	    	$cart = CartModel::whereIn('cart_id',$cartadd)->get();
                    // dd($cart);
                //组合订单商品数据
                $data = [];
		    	foreach ($cart as $k => $v) {
		    		$data[$k]['order_id'] = $order_id;
		    		$data[$k]['goods_id'] = $v->goods_id;
		    		$data[$k]['goods_sn'] = $v->goods_sn;
		    		$data[$k]['product_id'] = $v->product_id;
		    		$data[$k]['goods_name'] = $v->goods_name;
		    		$data[$k]['shop_price'] = $v->shop_price;
		    		$data[$k]['buy_number'] = $v->buy_number;
		    		$data[$k]['goods_attr_id'] = $v->goods_attr_id?$v->goods_attr_id:'';
		    		//订单商品入库
		    		$ret = OrdergoodsModel::insert($data);
		    	}

		    	DB::commit();
		    		
				return redirect('/pay/'.$order_id);
		    	} catch (Exception $e) {
	    		DB::rollBack();
	    		echo "<script>alert('生成订单失败');location.href='/'</script>";
		    		return redirect('/pay');
	    		}
    	}
	    public function getOrdersn($user){
	    	$order_sn = time().rand(10000,99999).$user;
	    	return $order_sn;
	    }
        

    }

