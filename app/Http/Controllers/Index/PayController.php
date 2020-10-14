<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\OrderinfoModel;
use App\Model\OrdergoodsModel;
class PayController extends Controller
{
    public function pay($order_id){
        $orderinfo = OrderinfoModel::find($order_id);
        // dd($orderinfo);
         require_once app_path('libs/alipay/wappay/service/AlipayTradeService.php');
        require_once app_path('libs/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        $config = config('alipay');
if (!empty($order_id)&& trim($order_id)!=""){
    //商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no = date('YmdHis').rand(1000,9999);
    //订单名称，必填
    $subject = '2001商城测试';

    //付款金额，必填
    $total_amount = rand(1000,9999);

    //商品描述，可空
    $body = '';

    //超时时间
    $timeout_express="1m";

    $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new \AlipayTradeService($config);
    $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

    return ;
    }
}
//同步跳转
    public function return_url(){
            $config = config('alipay');
            require_once app_path('libs/alipay/wappay/service/AlipayTradeService.php');


            $arr=$_GET;
            // dd($arr);
            $alipaySevice = new \AlipayTradeService($config); 
            $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
    if($result) {//验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代码
        
        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        $jyl = OrderinfoModel::where(['order_sn'=>$arr['out_trade_no'],'deal_price'=>$arr['total_amount']])->count();
        if(!$jyl){
            
        }
        dd($jyl);
        //判断有没有此订单

        //商户订单号
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
        //支付宝交易号
        $trade_no = htmlspecialchars($_GET['trade_no']);
            
        echo "验证成功<br />外部订单号：".$trade_no;

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
            else {
                echo "验证失败";
            }
        } 
    //异步通知
    public function notify_url(){

    }
}
