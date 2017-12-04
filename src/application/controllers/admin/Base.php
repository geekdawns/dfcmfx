<?php
/**
 * 后台管理模块
 */
class Admin_BaseController extends Yaf_Controller_Abstract {
    protected $rbac;
    protected $aid;
    protected $is_allow;
    protected $user;
    protected $userInfo;
    public function init(){
        session_start();
        if(!$_SESSION['BACK_ADMIN_KEY']){
            return $this->redirect("/admin_auth/login");
        }

        $this->rbac = new Admin_RbacModel();
        $this->user = new UserModel();

        $this->aid = $_SESSION['BACK_ADMIN_KEY'];

        //获取来访路由
        $uri =  $_SERVER['REQUEST_URI'];
        $permission_uri = explode("&",$uri)[0];

        $this->is_allow = $this->rbac->checkAuth($this->aid, $permission_uri);

        // 获取用户信息
        $this->userInfo = $this->user->getUserInfo($this->aid,'a')[0];
    }

}
