<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\SeckillModel;
use App\Model\GoodsModel;
use App\Model\Category;
use App\Model\BrandModel;
use App\Model\Goodsattr;
use DB;
use App\Model\AddressModel;
use App\Model\OrderModel;
use App\Model\CartModel;
use App\Model\OrdergoodsModel;
use App\Model\OrderinfoModel;
use Illuminate\Support\Facades\Redis;
class SeckillController extends Controller
{
    public function seckill(){
        $jyl = SeckillModel::where('start_time','<',time())->where('end_time','>',time())->get();
        return view('index.seckill.seckillindex',['jyl'=>$jyl]); 
    }
    public function miao($goods_id){
          //属性
        $attr = $this->putattr($goods_id);
        //简介
        $jianjie = $this->jianjie($goods_id);
        // if($shu = Redis::setnx('cnm_'.$goods_id,1)){
        //     $shu = Redis::set('cnm_',$goods_id);
        // }else{
        //     $shu = Redis::incr('cnm_',$goods_id);
        // }
        //访问量
        $hist = Redis::zincrby('cnm_',1,'cnm_'.$goods_id);
        //取最高的5条
        $attr = $this->putattr($goods_id);
        //简介
        $jianjie = $this->jianjie($goods_id);
        //规格
        $guige = $this->guige($goods_id);
        $goods=SeckillModel::where('goods_id',$goods_id)->get()->toArray();
        $good = SeckillModel::orderBy('goods_id','desc')->limit(5)->get()->toArray();
        $goo = SeckillModel::where('goods_id',$goods_id)->get()->toArray();
        // dd($goo);
      // $time = $good['end_time'];
      $time = time();
        return view('index.seckill.seckillitem',['goods'=>$goods,'good'=>$good,'goo'=>$goo,'attr'=>$attr,'jianjie'=>$jianjie,'guige'=>$guige,'hist'=>$hist,'time'=>$time]);
    }
      //属性

    public function putattr($goods_id){
        $goodsattr = Goodsattr::select('goods_attr_id','ecs_goods_attr.attr_id','attribute.attr_name','ecs_goods_attr.attr_value')
                     ->leftjoin('attribute','ecs_goods_attr.attr_id','=','attribute.attr_id')
                     ->where(['goods_id'=>$goods_id,'attribute.attr_type'=>0])
                     ->get();
        return $goodsattr;
     }
     //简介
     public function jianjie($goods_id){
        $janj = GoodsModel::select('goods_id','goods_desc')
                ->where('goods_id',$goods_id)
                ->first();
        return $janj;
     }
     //规格
     public function guige($goods_id){  
        $guige = Goodsattr::select('goods_attr_id','ecs_goods_attr.attr_id','attribute.attr_name','ecs_goods_attr.attr_value')
        ->leftjoin('attribute','ecs_goods_attr.attr_id','=','attribute.attr_id')
        ->where(['goods_id'=>$goods_id,'attribute.attr_type'=>1])
        ->get();

        $data = [];
        if( $guige ){
            foreach($guige as $k=>$v){
                $data[$v['attr_id']]['attr_name'] = $v['attr_name'];
               $data[$v['attr_id']]['attr_value'][$v['goods_attr_id']] =  $v['attr_value'];     
             }
             return $data;
        }
        return $guige;
     }
     public function conf(Request $request){
        $seckill_id  = $request->seckill_id;
        // dd($seckill_id);
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
}
