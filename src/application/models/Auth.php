<?php
/**
 * AuthMOdel
 * Created by PhpStorm.
 * User: Jhin
 * Date: 2017/11/23
 * Time: 11:01
 */
use PasswordCompat\binary;

class AuthModel {
    public $db = null;
    private $userFromMap = ['a','u','s'];
    private $tabelMap = ['a'=> 'df_a_users','u'=>'df_u_users','s'=>'df_s_users'];

    public function __construct($db = null){
        //实例化数据库
        $this->db = Yaf_Registry::get('db');
    }

    /**
     * 验证密码
     * @param $user 用户名
     * @param $pass 待验证密码
     * @param $from 来源
     * @return bool
     */
    public function checkUserPass($user,$pass,$from){
        if(!in_array($from,$this->userFromMap)){
            return false;
        }

        //验证用户名是否存在
        $count = $this->db->count($this->tabelMap[$from],["user_name" =>$user]);

        if(!$count){
            return false;
        }

        //获取密码哈希
        $data = $this->db->select($this->tabelMap[$from],["id","user_pwd"],["user_name"=> $user])[0];

        return $this->verifyPass($pass,$data['user_pwd'])? $data['id'] : false;
    }

    /**
     * 创建账号
     * @param $user 用户名
     * @param $pass 待加密密码
     * @param $from 账号来源
     * @return bool
     */
    public function createUser($user,$pass,$from){
        if(!in_array($from,$this->userFromMap)){
            return false;
        }

        //检测账号
        if($this->isExistAccount($user,$from)){
            return false;
        }

        $hash = $this->getHashPass($pass);

        $this->db->insert($this->tabelMap[$from],[
            "user_name" => $user,
            "user_pwd" => $hash,
            "create_at" => time(),
        ]);

        return $this->db->id();
    }

    /**
     * 判断是被封号
     * @param $user
     * @param $from
     * @return bool
     */
    public function isBan($user,$from){
        if(!in_array($from,$this->userFromMap)){
            return false;
        }

        //检测账号
        if(!$this->isExistAccount($user,$from)){
            return false;
        }

        return $this->db->select($this->tabelMap[$from],"status",["user_name" => $user])[0];
    }

    /**
     * 获取密码加密后的哈希值
     * @param $pass 待加密密码
     * @return 加密后的哈希值
     */
    private function getHashPass($pass){
        return password_hash($pass, PASSWORD_BCRYPT);
    }
    /**
     * 验证密码
     * @param $pass 密码
     * @param $hash 哈希值
     * @return bool
     */
    private function verifyPass($pass,$hash){
        if (password_verify($pass, $hash)) {
            /* Valid */
            return true;
        } else {
            /* Invalid */
            return false;
        }
    }

    /**
     * 检测是否存在账号
     * @param $user
     * @param $from
     * @return bool
     */
    private function isExistAccount($user,$from){
        if(!in_array($from,$this->userFromMap)){
            return false;
        }

        $count = $this->db->count($this->tabelMap[$from],["user_name" =>$user]);

        return $count? true : false;
    }
}