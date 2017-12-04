<?php
/**
 * InfoModel 账号资料管理
 * Created by PhpStorm.
 * User: Jhin
 * Date: 2017/11/24
 * Time: 16:27
 */
class InfoModel {
    public $db = null;

    public function __construct($db = null){
        //实例化数据库
        $this->db = Yaf_Registry::get('db');
    }

    /**
     * 创建个人资料
     * @param $data
     */
    public function createInfo($data){
        if($this->isExistInfo($data['uid'],$data['u_type'])){
            //存在
            return $this->editInfo($data);
        }else{
            //不存在，创建
            if($data['u_type'] == 0){
                $this->db->insert("df_infos",[
                    "uid" => $data['uid'],
                    "u_type" => $data['u_type'],
                    "sex" => $data['sex'],
                    "mobile" => $data['mobile'],
                    "email" => $data['email'],
                    "description" => $data['description'],
                    "create_at" => $data['create_at'],
                ]);
            }elseif($data['u_type'] == 1){
                $this->db->insert("df_infos",[
                    "uid" => $data['uid']?$data['uid']:"",
                    "u_type" => $data['u_type']?$data['u_type']:"",
                    "nick" => $data['nick'] ? $data['nick'] : "",
                    "real_name" => $data['real_name'] ? $data['real_name'] : "",
                    "sex" => $data['sex'] ? $data['sex'] : "",
                    "mobile" => $data['mobile']?$data['mobile']:"",
                    "email" => $data['email']?$data['email']:"",
                    "qq" => $data['qq']?$data['qq']:"",
                    "country_code" => $data['country_code']?$data['country_code']:"",
                    "provence_code" => $data['provence_code']?$data['provence_code']:"",
                    "city_code" => $data['city_code']?$data['city_code']:"",
                    "area_code" => $data['area_code']?$data['area_code']:"",
                    "address" => $data['address']?$data['address']:"",
                    "description" => $data['description']?$data['description']:"",
                    "create_at" => $data['create_at']?$data['create_at']:"",
                ]);
            }else{
                $this->db->insert("df_infos",[
                    "uid" => $data['uid']?$data['uid']:"",
                    "u_type" => $data['u_type']?$data['u_type']:"",
                    "nick" => $data['nick'] ? $data['nick'] : "",
                    "real_name" => $data['real_name'] ? $data['real_name'] : "",
                    "sex" => $data['sex'] ? $data['sex'] : "",
                    "mobile" => $data['mobile']?$data['mobile']:"",
                    "email" => $data['email']?$data['email']:"",
                    "qq" => $data['qq']?$data['qq']:"",
                    "country_code" => $data['country_code']?$data['country_code']:"",
                    "provence_code" => $data['provence_code']?$data['provence_code']:"",
                    "city_code" => $data['city_code']?$data['city_code']:"",
                    "area_code" => $data['area_code']?$data['area_code']:"",
                    "address" => $data['address']?$data['address']:"",
                    "description" => $data['description']?$data['description']:"",
                    "create_at" => $data['create_at']?$data['create_at']:"",
                ]);
            }


            return $this->db->id();
        }
    }

    /**
     * 修改个人资料
     * @param $data
     * @return mixed
     */
    public function editInfo($data){
        $id = $this->getInfoId($data['uid'],$data['u_type']);

        $this->db->update("df_infos",[
            "nick" => $data['nick'],
            "real_name" => $data['real_name'],
            "sex" => $data['sex'],
            "mobile" => $data['mobile'],
            "email" => $data['email'],
            "qq" => $data['qq'],
            "country_code" => $data['country_code'],
            "provence_code" => $data['provence_code'],
            "city_code" => $data['city_code'],
            "area_code" => $data['area_code'],
            "address" => $data['address'],
            "description" => $data['description'],
            "modified_at" => time(),
        ],[
            "id" => $id,
        ]);

        return $this->db->rowCount();

    }

    /**
     * 删除个人信息
     * @param $uid 用户id
     * @param $u_type  用户类型 0：admin 1: 前台用户 2：店铺用户
     * @return mixed
     */
    public function deleteInfo($uid,$u_type){
        $this->db->delete("df_infos",[
            "uid" => $uid,
            "u_type" => $u_type,
        ]);

        return $this->db->rowCount();
    }

    /**
     * 检测是否存在资料
     * @param $uid
     * @param $u_type
     * @return bool
     */
    private function isExistInfo($uid,$u_type){
        $count = $this->db->count("df_infos",[
            "uid" => $uid,
            "u_type" => $u_type,
        ]);

        return $count ? true : false;
    }

    /**
     * 获取资料id
     * @param $uid
     * @param $u_type
     * @return mixed
     */
    private function getInfoId($uid,$u_type){
        $id = $this->db->select("df_infos","id",[
            "uid" => $uid,
            "u_type" => $u_type,
        ])[0];

        return $id;
    }

}
