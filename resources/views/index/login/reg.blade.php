<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <title>个人注册</title>


    <link rel="stylesheet" type="text/css" href="/static/css/webbase.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/pages-register.css" />
</head>

<body>
<div class="register py-container ">
    <!--head-->
    <div class="logoArea">
        <a href="" class="logo"></a>
    </div>
    <!--register-->
    <div class="registerArea">
        <h3>注册新用户<span class="go">我有账号，去<a href="{{url('/login')}}" target="_blank">登陆</a></span></h3>
        <div class="info">
            <form action=""  class="sui-form form-horizontal">
                @csrf
                <div class="control-group">
                    <label class="control-label">用户名：</label>
                    <div class="controls">
                        <input type="text" placeholder="请输入你的用户名" name="user_name" class="input-xfat input-xlarge">
                    </div>
                </div>
                <div class="control-group">
                    <label for="inputPassword" class="control-label">登录密码：</label>
                    <div class="controls">
                        <input type="password" placeholder="设置登录密码" name="user_pwd" class="input-xfat input-xlarge">
                    </div>
                </div>
                <div class="control-group">
                    <label for="inputPassword" class="control-label">确认密码：</label>
                    <div class="controls">
                        <input type="password" placeholder="再次确认密码" name="user_pwds" class="input-xfat input-xlarge">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">手机号：</label>
                    <div class="controls">
                        <input type="text" placeholder="请输入你的手机号" name="user_plone" class="input-xfat input-xlarge">
                    </div>
                </div>
                <div class="control-group">
                    <label for="inputPassword" class="control-label">短信验证码：</label>
                    <div class="controls">
                        <input type="text" placeholder="短信验证码" name="code" class="input-xfat input-xlarge">
                        <button type="button" class="btn btn-success">获取短信验证码</button>
                    </div>
                </div>

                <div class="control-group">
                    <label for="inputPassword" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <div class="controls">
                        <input name="m1" type="checkbox" value="2" checked=""><span>同意协议并注册《品优购用户协议》</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls btn-reg">
                        <input type="button" id="aww" class="sui-btn btn-block btn-xlarge btn-danger" value="完成注册">
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
    <!--foot-->
    <div class="py-container copyright">
        <ul>
            <li>关于我们</li>
            <li>联系我们</li>
            <li>联系客服</li>
            <li>商家入驻</li>
            <li>营销中心</li>
            <li>手机品优购</li>
            <li>销售联盟</li>
            <li>品优购社区</li>
        </ul>
        <div class="address">地址：北京市昌平区建材城西路金燕龙办公楼一层 邮编：100096 电话：400-618-4000 传真：010-82935100</div>
        <div class="beian">京ICP备08001421号京公网安备110108007702
        </div>
    </div>
</div>


<script type="text/javascript" src="/static/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/plugins/jquery.easing/jquery.easing.min.js"></script>
<script type="text/javascript" src="/static/js/plugins/sui/sui.min.js"></script>
<script type="text/javascript" src="/static/js/plugins/jquery-placeholder/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="/static/js/pages/register.js"></script>
</body>
</html>
<script scr="/static/jqery.min.js"></script>
<script>
    $(document).on('click','#aww',function (){
        // alert(111);
        var user_name = $('input[name="user_name"]').val();
        // dump(user_name);
        var user_pwd = $('input[name="user_pwd"]').val();
        var user_pwds = $('input[name="user_pwds"]').val();
        var user_plone = $('input[name="user_plone"]').val();
        var code = $('input[name="code"]').val();
        $.post('/regdo',{user_name:user_name,user_pwd:user_pwd,user_pwds:user_pwds,user_plone:user_plone,code:code},function (result) {
            if(result.code=='00001'){
                alert(result.msg);
            }
            if(result.code=='00002'){
                alert(result.msg);
            }
            if(result.code=='00003'){
                alert(result.msg);
            }
            if(result.code=='00004'){
                alert(result.msg);
            }
            if(result.code=='00005'){
                alert(result.msg);
            }
            if(result.code=='00000'){
                location.href = "/login"
            }else{
                alert(result.msg);
            }
        },'json')
    });
    $('button').click(function () {
        var name = $('input[name="user_plone"]').val();
        var mobilereg = /^1[3|5|6|7|8|9]\d{9}$/;
        if(mobilereg.test(name)){
            //发送手机号验证码
            $.get('/reg/sendSMS',{name:name},function (res) {
                if(res.code=='00001'){
                    alert(res.msg);
                }
                if(res.code=='00000'){
                    alert(res.msg);
                }
                if(res.code=='00002'){
                    alert(res.msg);
                }
            },'json');
            return;
        }
        alert('请输入正确的手机号');
        return;

    });
</script>
