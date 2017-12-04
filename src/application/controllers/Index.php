<?php
class IndexController extends Yaf_Controller_Abstract {
    public function init(){
        $this->model = new Front_IndexModel();
        $this->auth = new AuthModel();
    }

   public function indexAction() {//默认Action
        $content = 'Hello World!';

        $params['content'] = $content;

        $this->getView()->assign('test','1');
        $this->getView()->assign('a','a');
        $this->getView()->make("index.index", $params);
   }

   public function mysqlAction(){
        $datas = $this->model->mysql();

        var_dump($datas);
   }

   public function testAction(){

         $user = 'admin@bropeak.com';
         $pass = '111111';
         $from = 'a';

         $id = $this->auth->createUser($user,$pass,$from);

       echo $id;
//       $hash = password_hash($password, PASSWORD_BCRYPT);
//       $password1 = 'asdfa';
//       if (password_verify($password1, $hash)) {
//           /* Valid */
//           echo '1';
//       } else {
//           /* Invalid */
//           echo '2';
//       }
//
//
//       var_dump($hash);
//       exit;
   }
}
?>
