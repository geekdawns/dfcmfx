<script type="text/javascript" src="/huiadmin/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/huiadmin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/huiadmin/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="/huiadmin/static/h-ui.admin/js/H-ui.admin.page.js"></script> 
 @yield('footer_js')
<script type="text/javascript">
 //返回上一页
 function delayer(){
   window.history.go(-1);
 }

 var allow = {{$is_allow}};
 if(!allow){
  layer.alert("权限不足！", {icon: 2,skin: "layer-ext-moon"});
  setTimeout('delayer()',1000);
}
</script>
</body>
</html>