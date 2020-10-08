<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\BrandModel;
use App\Model\Goodsattr;
class IndexController extends Controller
{
    public function index(){
//        $goods = GoodsModel::get();
        $is_new = GoodsModel::where('is_new','=','1')->limit(4)->get();
//        dd($is_new);
        $goods = GoodsModel::orderBy('goods_id')->limit(12)->get()->toArray();
        //列表
        $key = GoodsModel::orderBy('goods_id')->limit(20)->get()->toArray();
        //轮播图
        $good = $this->luobotu();
        //获取分类数据
        $catedata = $this->putcate();
        //无限极分类
        $tree = $this->Treecate($catedata);
     
        return view('index.index.index',compact('goods','is_new','good','tree','key'));
    }   

    //推荐详情
    public function item($goods_id){
          //属性
        $attr = $this->putattr($goods_id);
        //简介
        $jianjie = $this->jianjie($goods_id);
        //规格
        $guige = $this->guige($goods_id);
        $goods=GoodsModel::where('goods_id',$goods_id)->get()->toArray();
        $good = GoodsModel::orderBy('goods_id','desc')->limit(5)->get()->toArray();
        $goo = GoodsModel::where('goods_id',$goods_id)->get()->toArray();
        return view('index.index.item',['goods'=>$goods,'good'=>$good,'goo'=>$goo,'attr'=>$attr,'jianjie'=>$jianjie,'guige'=>$guige]);
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
    //无限极分类
    public function Treecate($catedata,$parent_id=0,$level=0){
        $tree = [];
        foreach($catedata as $k=>$v){
            if($v['parent_id'] == $parent_id){
                $tree[$k] = $v;
                $tree[$k]['son'] =  $this->Treecate($catedata,$v['cat_id'],$level+1);
            }
        }
        return $tree;
    }
    //轮播图
    public function luobotu(){
        $goods = GoodsModel::select('goods_id','goods_img')
            ->where('is_show',1)
            ->orderBy('goods_id','desc')
            ->take(3)
            ->get();
        return $goods;
    }
    //获取分类数据
    public function putcate(){
        $catedata = Category::get();
        return $catedata;
    }
    //列表
    public function serch($cat_id){
        $query = request()->all();
        $where = [];
        if(isset($query['price'])){
            //去除价格中的元字
            $price_array = explode('块',$query['price']);
            //去除价格中的-
            $price_array = explode('-',$price_array[0]);
            $where[] =  [
                'shop_price','>',$price_array[0]
            ];
            if(isset($price_array[1])){
                //如果价格中的索引1存在说明有最大值
                $where[] =  [
                    'shop_price','<',$price_array[1]
                ];
            }
        }
        //根据品牌搜索
        if(isset($query['brand_id'])){
            $where[] = [
                'brand_id','=',$query['brand_id']
            ];
        }

        //查询分类
        $zbc = Category::where('parent_id',$cat_id)->pluck('cat_id');
        $zbc = $zbc?$zbc->toArray():[];
        array_push($zbc,$cat_id);
        //根据分类查商品
        $goods = GoodsModel::where($where)->where('is_on_sale',1)->whereIn('cat_id',$zbc)->paginate(10);
        //根据商品查询品牌
        $branda = GoodsModel::where('is_on_sale',1)->whereIn('cat_id',$zbc)->pluck('brand_id');
//        dd($branda);
        $branda = $branda?$branda->toArray():[];
        $branda = array_unique($branda);
        $brand = BrandModel::select('brand_id','brand_name','brand_logo')->whereIn('brand_id',$branda)->get();
        $shop_price = GoodsModel::where('is_on_sale',1)->whereIn('cat_id',$zbc)->max('shop_price');
//        dd($shop_price);
        $price = $this->getprice($shop_price);

        $url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        return view('index.goods.serch',compact('goods','brand','url','price'));
    }
    public function getprice($shop_price){
        $len = strlen($shop_price);
//        dd($len);
        $for = '1'.str_repeat(0,$len-4);
        $maxprice = substr($shop_price,0,1);
        $maxprice = $maxprice*$for;
//        dd($maxprice);
        //区间价格
        $price = [];
        $avga = $maxprice/5;
        for($i=0,$j=1;$i<$maxprice;$i++,$j++){
            $price[] = $i.'-'.$avga*$j.'块钱';
            $i = $avga*$j-1;
        }
        $price[] = $maxprice.'块钱以上';
        return $price;
    }
}
