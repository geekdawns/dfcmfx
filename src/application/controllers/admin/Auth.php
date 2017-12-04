<?php
/**
 * 后台登录入口
 */
class Admin_AuthController extends Yaf_Controller_Abstract {
        public function init(){
            session_start();
            $this->auth = new AuthModel();
        }

        /**
         * [后台登录界面]
         * @return [type] [description]
         */
        public function loginAction(){
            // 设置登录标识
            if(!isset($_SESSION['BACK_ADMIN_KEY'])){
                $_SESSION['BACK_ADMIN_KEY'] = '';
            }

            $is_login = $_SESSION['BACK_ADMIN_KEY'];

            if(empty($is_login)) {
                $params = [];
                $this->getView()->assign('title', '后台登录');
                $this->getView()->make("admin.auth.login", $params);
            }else {
                return $this->redirect("/admin_index/index");
            }
        }

        /**
         * [后台登录处理]
         * @return [type] [description]
         */
        public function loginHandleAction(){
            $request = $this->getRequest();

            $user = $request->getPost('user');
            $pass = $request->getPost('password');

            //验证是否封号
            $is_ban = $this->auth->isBan($user,'a');

            if($is_ban){
                $info['types'] = 'fail';
                $info['msg'] = '账号被封，请联系管理员！';
            }else{
                $is_pass = $this->auth->checkUserPass($user,$pass,'a');

                if($is_pass){
                    $_SESSION['BACK_ADMIN_KEY'] = $is_pass;
                    $info['types'] = 'success';
                    $info['msg'] = '登录成功';
                }else{
                    $info['types'] = 'fail';
                    $info['msg'] = '登录失败，请检查账号或密码！';
                }
            }
            echo json_encode($info,true);
        }

         /**
         * [后台注册页面]
         * @return [type] [description]
         */
        public function registerAction(){
            $request = $this->getRequest();


        }

         /**
         * [后台注册处理]
         * @return [type] [description]
         */
        public function registerHandleAction(){
            $request = $this->getRequest();


        }

         /**
         * [后台登出]
         * @return [type] [description]
         */
        public function loginOutAction(){
            unset($_SESSION['BACK_ADMIN_KEY']);

            return $this->redirect("/admin_auth/login");
        }
}
