@extends('admin.common.add')

@section('extend-css')
@stop

@section('content')
    <article class="cl pd-20">
        <table class="table table-border table-bg">
            <caption>用户列表</caption>
            <thead>
            <tr>
                <th>#</th>
                <th>账号</th>
            </tr>
            </thead>
            <tbody>
            @if($admin_list)
                @foreach($admin_list as $k => $v)
                    <tr>
                        <th scope="row">{{$k+1}}</th>
                        <td>{{$v['user_name']}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </article>
@stop

@section('footer_js')
@stop
