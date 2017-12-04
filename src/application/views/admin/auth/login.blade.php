@extends('admin.auth.common.common')

@section('title','后台登录')

@section('extend-css')
<link href="/css/admin.login.css" rel="stylesheet">
@stop

@section('content')
    <div class="container wrap1" style="height:450px;">
            <h2 class="mg-b20 text-center">DFCMFX内容管理框架</h2>
            <div class="col-sm-8 col-md-5 center-auto pd-sm-50 pd-xs-20 main_content">
                <p class="text-center font16">管理员登录</p>
                <form>
                    <div class="form-group mg-t20">
                        <i class="icon-user icon_font"></i>
                        <input type="email" class="login_input" id="Email1" name="user" placeholder="请输入用户名" data-rule="requied"/>
                    </div>
                    <div class="form-group mg-t20">
                        <i class="icon-lock icon_font"></i>
                        <input type="password" class="login_input" id="Password1" name="password" placeholder="请输入密码" />
                    </div>
                    <div class="checkbox mg-b25">
                        <label>
                            <input type="checkbox"  class="ck_rmb"/>记住我的登录信息
                        </label>
                    </div>
                    <button type="button" style="submit" class="login_btn">登 录</button>
               </form>
        </div>
    </div>
@stop

@section('footer_js')
<script type="text/javascript">
    //登录
    $(".login_btn").click(function(event) {
        var user = $("#Email1").val();
        var password = $("#Password1").val();

        //验证
        if(!user|| !/^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/.test(user)){
          return layer.alert('请填写用户名或邮箱格式不正确！', {
                icon: 0,
                skin: 'layer-ext-moon',
            });
        }
        if(!password){
            return layer.alert('请填写密码！', {
                icon: 0,
                skin: 'layer-ext-moon',
            });
        }

        $.ajax({
            type: 'post',
            url: '/admin_auth/loginHandle',
            dataType: 'json',
            data: {user: user, password: password},
            success: function(res){
              if(res.types == 'success'){
                layer.alert(res.msg, {
                  icon: 1,
                  skin: 'layer-ext-moon'
                });
                location.href = "/admin_index/index";
              }else{
                  layer.alert(res.msg, {
                      icon: 2,
                      skin: 'layer-ext-moon'
                  })
              }
            }

        });

        return false;
    });
</script>
@stop
