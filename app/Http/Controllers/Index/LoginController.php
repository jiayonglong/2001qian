<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
class LoginController extends Controller
{
    //注册
    public function reg(){
        return view('index.login.reg');
    }
    //执行
    public function regdo(Request $request)
    {
        $user_plone = $request->post('user_plone');
//        dd($user_plone);
        $user_name = $request->post('user_name');
        $user_pwd = $request->post('user_pwd');
        $user_pwds = $request->post('user_pwds');
        $len = strlen($user_pwd);
        $t = UserModel::where(['user_plone'=>$user_plone])->first();
        $a = UserModel::where(['user_name'=>$user_name])->first();
        if($t){
            return json_encode(['code'=>'00001','msg'=>'手机号已存在']);
        }
        if($a){
            return json_encode(['code'=>'00002','msg'=>'用户名已存在']);
        }
        if($len<6){
            return json_encode(['code'=>'00003','msg'=>'密码长度不能小于六位']);
        }
        if($user_pwds != $user_pwd){
            return json_encode(['code'=>'00004','msg'=>'确认密码与密码不一致']);
        }
        $user_pwd = password_hash($user_pwd,PASSWORD_BCRYPT);
        $data = [
            'user_name' => $user_name,
            'user_plone' => $user_plone,
            'user_pwd'=>$user_pwd
        ];
        $res = UserModel::insert($data);
        if(!$res){
            return json_encode(['code'=>'00005','msg'=>'注册失败']);
        }else{
            return json_encode(['code'=>'00000','msg'=>'注册成功']);
        }
    }
    //手机验证码验证
    public function sendSMS()
    {
        $name = request()->name;
//        dd($name);
        $reg = '/^1[3|5|6|7|8|9]\d{9}$/';
        if(!preg_match($reg,$name)){
            return json_encode(['code'=>'00001','msg'=>'请输入正确的手机号']);
        }
        $code = rand(10000,999999);
        $result = $this->send($name,$code);
        if($result['Message']=='OK'){
            return json_encode(['code'=>'00000','msg'=>'发送成功']);
        }else{
            return json_encode(['code'=>'000002','msg'=>'发送失败']);
        }

    }
    //短信验证
    public function send($name,$code){

// Download：https://github.com/aliyun/openapi-sdk-php
// Usage：https://github.com/aliyun/openapi-sdk-php/blob/master/README.md
        AlibabaCloud::accessKeyClient('LTAI4GFccq2jJ5vjx9C1XNir', 'V97fmw5pHOmq5J0ij8RUZtQgdXDSko')
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $name,
                        'SignName' => "龙龙小草",
                        'TemplateCode' => "SMS_182665157",
                        'TemplateParam' => "{code:$code}",
                    ],
                ])
                ->request();
            print_r($result->toArray());
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
    //登录
    public function login(){
        return view('index.login.login');
    }
    //执行
    public function logindo(Request $request){
        $user_plone = $request->post('user_plone');
        $user_pwd = $request->post('user_pwd');

        $u = UserModel::where(['user_plone'=>$user_plone])->first();

        if(!$u){
            return json_encode(['code'=>'00002','msg'=>'账号错误']);
        }else{
            $res = password_verify($user_pwd,$u->user_pwd);
            if(!$res){
                return json_encode(['code'=>'00001','msg'=>'密码错误']);
            }else{

                session(['user_plone' => $u['user_plone']]);
                session(['user_id' => $u['user_id']]);
                session(['user_name' => $u['user_name']]);
                $request->session()->save();
                return json_encode(['code'=>'00000','msg'=>'登录成功']);
            }
        }
    }
    //退出
    public function logout(){
        $user_id=request()->session()->put('user_id',null);
        $id=request()->session()->get('user_id');
//         print_r($id);exit;
//        dd($id);
        if($id==null){
            return redirect('/login');
        }
    }
}
