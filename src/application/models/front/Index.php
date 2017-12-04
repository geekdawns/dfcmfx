<?php
class Front_IndexModel{
    public $db = null;

    public function __construct($db = null){
        //实例化数据库
       $this->db = Yaf_Registry::get('db');
    }

    public function mysql(){
        $datas = $this->db->select("df_a_users","*",["id[>]"=> 0]);

        return $datas;
    }
}
