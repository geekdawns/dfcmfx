<?php
/**
 * 用户管理
 * Created by PhpStorm.
 * User: Jhin
 * Date: 2017/11/28
 * Time: 9:45
 */
class UserModel{
    public $db = null;
    private $userFromMap = ['a','u','s'];
    private $tabelMap = ['a'=> 'df_a_users','u'=>'df_u_users','s'=>'df_s_users'];
    private $u_type = ['a' => 0, 'u' => 1, 's' => 2];

    public function __construct($db = null){
        //实例化数据库
        $this->db = Yaf_Registry::get('db');
    }

    public function getUserInfo($uid,$from){
        $fields = " a.user_name,
                       i.nick, i.real_name, i.sex, i.mobile, i.email, i.qq, i.country_code, i.city_code, i.area_code, i.address, i.description,
                       r.title, r.description ";
        $joins = " LEFT JOIN `df_infos` i ON a.id = i.uid
                   LEFT JOIN `df_auth_role_access` ra ON a.id = ra.aid
                   LEFT JOIN `df_auth_role` r ON r.id = ra.role_id ";
        $where = " WHERE i.u_type = {$this->u_type[$from]} AND a.id = {$uid}";
        $sql = "SELECT {$fields} FROM `df_a_users` a {$joins} {$where}";

        return $this->db->query($sql)->fetchAll();
    }


}