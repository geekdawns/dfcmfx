@extends('admin.common.add')

@section('extend-css')
@stop

@section('content')
    <article class="cl pd-20">
        <form action="" method="post" class="form form-horizontal" id="form-admin-role-add">
            <input type="hidden" name="handle" value="edit">
            <input type="hidden" name="id" value="{{$info['id']}}">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$info['title']}}" placeholder="" id="roleName" name="roleName" datatype="*4-16" nullmsg="用户账户不能为空">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">备注：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$info['description']}}" placeholder="" id="" name="description">
                </div>
            </div>
            @if($info['rules'] != '*')
                <div class="row cl">
                    <label class="form-label col-xs-4 col-sm-3">网站角色：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        @if($list)
                            @foreach($list as $v)
                                <dl class="permission-list">
                                    <dt>
                                        <label>
                                            <input type="checkbox" value="{{$v['fa']['suf_code']}}" name="permission[{{$v['fa']['pre_code']}}][]" id="user-Character-0" @if($v['fa']['checked']) checked @endif>
                                            {{$v['fa']['title']}}</label>
                                    </dt>
                                    <dd>
                                        <dl class="cl permission-list2">
                                            <dd>
                                                @if(@$v['ch'])
                                                    @foreach($v['ch'] as $cv)
                                                        <label class="">
                                                            <input type="checkbox" value="{{$cv['suf_code']}}" name="permission[{{$v['fa']['pre_code']}}][]" id="user-Character-0-0-0" @if($cv['checked']) checked @endif>
                                                            {{$cv['title']}}</label>
                                                    @endforeach
                                                @endif
                                            </dd>
                                        </dl>
                                    </dd>
                                </dl>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <button type="submit" class="btn btn-success radius" id="admin-role-save"><i class="icon-ok"></i> 确定</button>
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
            $(".permission-list dt input:checkbox").click(function(){
                $(this).closest("dl").find("dd input:checkbox").prop("checked",$(this).prop("checked"));
            });
            $(".permission-list2 dd input:checkbox").click(function(){
                var l =$(this).parent().parent().find("input:checked").length;
                var l2=$(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
                if($(this).prop("checked")){
                    $(this).closest("dl").find("dt input:checkbox").prop("checked",true);
                    $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",true);
                }
                else{
                    if(l==0){
                        $(this).closest("dl").find("dt input:checkbox").prop("checked",false);
                    }
                    if(l2==0){
                        $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",false);
                    }
                }
            });

            //提交
            $("#admin-role-save").click(function(){
                $("#form-admin-role-add").validate({
                    rules:{
                        roleName:{
                            required:true,
                        }
                    },
                    onkeyup:false,
                    focusCleanup:true,
                    success:"valid",
                    submitHandler:function(form){
                        $.ajax({
                            type: 'post',
                            url: '/admin_index/roleHandle',
                            dataType: 'json',
                            data: $("#form-admin-role-add").serialize(),
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
