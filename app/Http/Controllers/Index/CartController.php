<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\Category;
use App\Model\BrandModel;
use App\Model\Goodsattr;
use App\Model\ProductModel;
class CartController extends Controller
{
    public function addcart(Request $request){
        //判断是否登录
        $user = session('user_id');
        // dd($user);
        // $user = '1';
        if(!$user){
            return json_encode(['code'=>1,'msg'=>'没有登录']);
        }
        //判断商品ID和购买数量
               $goods_id = $request->goods_id;
		    	$buy_number = $request->buy_number;
                $goods_attr_id = $request->goods_attr_id;
		    	if(!$goods_id || !$buy_number){
                     return json_encode(['code'=>20000,'msg'=>'缺失参数']);
                }
        //根据商品的ID判断商品是否上下架
        $goods = GoodsModel::select('goods_id','goods_name','goods_sn','shop_price','is_on_sale','goods_number')->find($goods_id);
        if(!$goods->is_on_sale){
            return json_encode(['code'=>30000,'msg'=>'商品已经下架']);
        }
        //判断规格 再根据规格判断库存
        if($goods_attr_id){
            $goods_attr_id = implode('|',$goods_attr_id);
            //查询规格
             $product = ProductModel::select('product_id','product_number')->where(['goods_id'=>$goods_id,'goods_attr'=>$goods_attr_id])->first();
            //  echo $product_number;exit;
            if($product->product_number<$buy_number){
                return json_encode(['code'=>40000,'msg'=>'商品库存不足1']);
            }
        }else{
            if($goods->goods_number<$buy_number){
                return json_encode(['code'=>50000,'msg'=>'商品库存不足']);
            }
        }
        //根据当前用户的ID判断购物车是否有此商品,没有的话商品添加数据库，有的话更改数据库数量
        $cart = CartModel::where(['user_id'=>$user,'goods_id'=>$goods_id,'goods_attr_id'=>$goods_attr_id])->first();
         // dd($cart);
        if($cart){
            //更新数据库
            $buy_number = $cart->buy_number+$buy_number;
            // dd($buy_number);
             if($goods_attr_id){
           //  echo $product_number;exit;
            if($product->product_number<$buy_number){
                // echo '111';
                $buy_number = $product->product_number;
            }
        }else{
            if($goods->goods_number<$buy_number){
                $buy_number = $goods->product_number;
            }
        }
        $res = CartModel::where(['user_id'=>$user,'goods_id'=>$goods_id,'goods_attr_id'=>$goods_attr_id])->update(['buy_number'=>$buy_number]);
        if($res){
            return json_encode(['code'=>60000,'msg'=>'加入购物车成功']);
        }
        }else{
             $data = [
            'user_id'=>$user,
            'product_id'=>$product->product_id??0,
            'buy_number'=>$buy_number,
            'goods_attr_id'=>$goods_attr_id??''
        ];
        $goods = $goods?$goods->toArray():[];
        unset($goods['is_on_sale']);
        unset($goods['goods_number']);
        // dd($goods);
        $data = array_merge($data,$goods);
        $res = CartModel::insert($data);
        }
        if($res){
            return json_encode(['code'=>70000,'msg'=>'成功加入购物车']);
        }
    }
    //购物车列表
    public function cart(){
        $user = session('user_id');
        // dd($user);
        if(!$user){
            return redirect('/login');
        }
        $cart = CartModel::select('ecs_cart.*','ecs_goods.goods_thumb')->leftjoin('ecs_goods','ecs_cart.goods_id','=','ecs_goods.goods_id')->where('user_id',$user)->get();
        // dd($cart);
        foreach($cart as $k=>$v){
            if($v->goods_attr_id){
                $goods_attr_id = explode('|',$v->goods_attr_id);
                $Goods_attr = Goodsattr::select('attr_name','attr_value')->leftjoin('attribute','ecs_goods_attr.attr_id','=','attribute.attr_id')->whereIn('goods_attr_id',$goods_attr_id)->get();
                $cart[$k]['goods_attr'] = $Goods_attr?$Goods_attr->toArray():[];
            }
        }
        // dd($cart);
        return view('index.cart.cart',['cart'=>$cart]);
    }
}
