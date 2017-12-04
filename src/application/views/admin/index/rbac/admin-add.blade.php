@extends('admin.common.add')

@section('extend-css')
@stop

@section('content')
    <article class="cl pd-20">
        <form action="" method="post" class="form form-horizontal" id="form-admin-add">
            <input type="hidden" name="handle" value="add">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>管理员：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="" id="adminName" name="adminName">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>初始密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="password" class="input-text" autocomplete="off" value="" placeholder="密码" id="password" name="password">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>确认密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="password" class="input-text" autocomplete="off"  placeholder="确认新密码" id="password2" name="password2">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>性别：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="sex" type="radio" id="sex-1" value="1" checked>
                        <label for="sex-1">男</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" id="sex-2" value="0" name="sex">
                        <label for="sex-2">女</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>手机：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="" id="phone" name="phone">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>邮箱：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" placeholder="@" name="email" id="email">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">角色：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
				<select class="select" name="adminRole" size="1">
                    @if($roles)
                        @foreach($roles as $v)
                            <option value="{{$v['id']}}">{{mb_substr($v['title'],0,10)}}</option>
                        @endforeach
                    @endif
                </select>
				</span> </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">备注：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea name="" cols="" rows="" class="textarea"  placeholder="说点什么...100个字符以内" dragonfly="true" onKeyUp="textarealength(this,100)"></textarea>
                    <p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <button type="submit" class="btn btn-success radius" id="admin-add-save"><i class="icon-ok"></i> 提交</button>
                </div>
            </div>
        </form>
    </article>
@stop

@section('footer_js')
    <script type="text/javascript" src="/huiadmin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/huiadmin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/huiadmin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script type="text/javascript">
        $(function(){
            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });

            $("#admin-add-save").click(function(){
                $("#form-admin-add").validate({
                    rules:{
                        adminName:{
                            required:true,
                            minlength:4,
                            maxlength:16
                        },
                        password:{
                            required:true,
                        },
                        password2:{
                            required:true,
                            equalTo: "#password"
                        },
                        sex:{
                            required:true,
                        },
                        phone:{
                            required:true,
                            isPhone:true,
                        },
                        email:{
                            required:true,
                            email:true,
                        },
                        adminRole:{
                            required:true,
                        },
                    },
                    onkeyup:false,
                    focusCleanup:true,
                    success:"valid",
                    submitHandler:function(form){
                        $.ajax({
                            type: 'post',
                            url: '/admin_index/adminHandle',
                            dataType: 'json',
                            data: $("#form-admin-add").serialize(),
                            success: function(res){
                                layer.alert(res.msg, {
                                    icon: 1,
                                    skin: 'layer-ext-moon'
                                });

                                $(".layui-layer-btn").on('click',function(){
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.layer.close(index);
                                });
                            }
                        });

                        return false;
                    }
                });
            });
        });
    </script>
@stop