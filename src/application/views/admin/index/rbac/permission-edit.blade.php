@extends('admin.common.add')

@section('extend-css')
@stop

@section('content')
    <article class="cl pd-20">
        <form action="" method="post" class="form form-horizontal" id="form-admin-permission-add">
            <input type="hidden" name="handle" value="edit">
            <input type="hidden" name="id" value="{{$info['id']}}">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>权限名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$info['title']}}" placeholder="" id="permissionTitle" name="title" datatype="*4-16" nullmsg="权限名称不能为空">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>前置标识：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$info['pre_code']}}" placeholder="" id="preName" name="pre_code" datatype="*4-16" nullmsg=前置不能为空">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>后置标识：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$info['suf_code']}}" placeholder="" id="sufName" name="suf_code" datatype="*4-16" nullmsg=后置不能为空">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>状态：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <label class="radio-inline">
                        <input type="radio" name="status" id="" value="0" @if($info['status'] == 0) checked @endif> 启用
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" id="" value="1" @if($info['status'] == 1) checked @endif> 禁用
                    </label>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>权限规则：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$info['condition']}}" placeholder="" id="permissionCondition" name="condition">
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <button type="submit" class="btn btn-success radius" id="admin-permission-save"><i class="icon-ok"></i> 确定</button>
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
        $("#admin-permission-save").click(function(){

            $("#form-admin-permission-add").validate({
                rules:{
                    title:{
                        required:true
                    },
                    pre_code:{
                        required:true
                    },
                    suf_code: {
                        required: true
                    },
                    status:{
                        required:true
                    },
                    condition:{
                        required:true
                    }

                },
                onkeyup:false,
                focusCleanup:true,
                success:"valid",
                submitHandler:function(form){
                    $.ajax({
                        type: 'post',
                        url: '/admin_index/permissionHandle',
                        dataType: 'json',
                        data: $("#form-admin-permission-add").serialize(),
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
    </script>
@stop
