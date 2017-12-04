<?php
/**
 * 后台权限管理
 * Created by PhpStorm.
 * User: Jhin
 * Date: 2017/11/23
 * Time: 12:52
 */
class Admin_RbacModel{
    public $db = null;

    public function __construct($db = null){
        //实例化数据库
        $this->db = Yaf_Registry::get('db');
        $this->auth = new AuthModel();
        $this->info = new InfoModel();
    }

    /**
     * 检测是否具备权限
     * @param $aid
     * @param $condition
     * @return bool|int  具备：不具备
     */
    public function checkAuth($aid, $condition){
        // 获取权限唯一id
        $count_pid = $this->db->count("df_auth_permission",["status"=> 0, "condition" => $condition]);

        if(!$count_pid){
            return true;
        }

        // 获取角色
        $role_id = $this->db->select("df_auth_role_access","role_id",["aid"=> $aid])[0];

        // 获取rules
        $rules = $this->db->select("df_auth_role","rules",["id" => $role_id])[0];

        if($rules == '*'){
            // 为超级管理员
            return true;
        }

        // 页面唯一id
        $pid = $this->db->select("df_auth_permission",["pre_code","suf_code"],["status"=> 0, "condition" => $condition])[0];
        $only_code = $pid['pre_code'] . $pid['suf_code'];

        $rules_array = explode(",",$rules);

        return in_array($only_code,$rules_array) ? true : 0;
    }

    /**
     * 获取角色列表
     * @return mixed
     */
    public function listRole(){
        return $this->db->select("df_auth_role","*","");
    }

    /**
     * 添加角色
     * @param $data
     */
    public function addRole($data){
        //获取role 基本信息
        $count = $this->db->count("df_auth_role",["title" => $data['title']]);

        if($count){
            $data['id'] = $this->db->select("df_auth_role","id",["title" => $data['title']])[0];

            return $this->editRole($data);
        }else{
            //写入
            $this->db->insert("df_auth_role",[
                "title" => $data['title'],
                "description" => $data['description'],
                "rules" => $data['rules']
            ]);

            return $this->db->id();
        }
    }

    /**
     * 获取角色信息
     * @param $id    角色id
     * @return mixed
     */
    public function getRoleInfo($id){
        $data = $this->db->select("df_auth_role","*",["id"=>$id]);

        return $info = $data[0];
    }

    /**
     * 编辑角色
     * @param $data
     * @return mixed
     */
    public function editRole($data){
        //修改
        $data =  $this->db->update("df_auth_role",[
            "title" => $data['title'],
            "description" => $data['description'],
            "rules" => $data['rules']
        ],[
            "id" => $data['id']
        ]);

        return $data->rowCount();
    }

    /**
     * 删除角色
     * @param $id   角色id
     * @return mixed
     */
    public function deleteRole($id){
        //删除角色
        $data = $this->db->delete("df_auth_role",[
            "id" => $id
        ]);

        return $data->rowCount();
    }

//*----------角色end-----------------

    /**
     * 获取权限列表
     * @return mixed
     */
    public function listPermission($search){
        $filter = array();

        //筛选条件
        if (isset($search['title']) && $search['title'] != '') {
            $filter[] = " LOCATE('{$search['title']}',title) > 0 ";
        }

        $where = " WHERE ";

        if (count($filter) >= 1) {
            $where .= implode(" AND ", $filter);
        }else{
            $where .= " 1 ";
        }

        $sql = "SELECT * FROM `df_auth_permission` {$where} ORDER BY pre_code ASC";

        return $this->db->query($sql)->fetchAll();

    }

    /**
     * 添加权限
     * @param $data
     */
    public function addPermission($data){
        //获取permission 基本信息
        $count = $this->db->count("df_auth_permission",["pre_code" => $data['pre_code'],"suf_code" => $data['suf_code']]);

        if($count){
            $data['id'] = $this->db->select("df_auth_permission","id",["pre_code" => $data['pre_code'],"suf_code" => $data['suf_code']])[0];

            return $this->editPermission($data);
        }else{
            //写入
            $this->db->insert("df_auth_permission",[
                "pre_code" => $data['pre_code'],
                "suf_code" => $data['suf_code'],
                "title" => $data['title'],
                "status" => $data['status'],
                "condition" => $data['condition']
            ]);

            return $this->db->id();
        }
    }

    /**
     * 获取权限信息
     * @param $id    权限id
     * @return mixed
     */
    public function getPermissionInfo($id){
        $data = $this->db->select("df_auth_permission","*",["id"=>$id]);

        return $info = $data[0];
    }

    /**
     * 编辑权限
     * @param $data
     * @return mixed
     */
    public function editPermission($data){
        //修改
        $data =  $this->db->update("df_auth_permission",[
            "pre_code" => $data['pre_code'],
            "suf_code" => $data['suf_code'],
            "title" => $data['title'],
            "status" => $data['status'],
            "condition" => $data['condition']
        ],[
            "id" => $data['id']
        ]);

        return $data->rowCount();
    }

    /**
     * 删除权限
     * @param $id   权限id
     * @return mixed
     */
    public function deletePermission($id){
        //删除权限
        $data = $this->db->delete("df_auth_permission",[
            "id" => $id
        ]);

        return $data->rowCount();
    }

//*-------------权限end----------------------

    /**
     * 获取管理员列表
     * @return mixed
     */
    public function listAdmin($search){
        $filter = array();

        // 字段
        $fields = "a.id, a.user_name, i.mobile, i.email, r.title, a.create_at, a.status";
        // join on 条件
        $joins = " LEFT JOIN df_infos i ON a.id = i.uid
                   LEFT JOIN df_auth_role_access ra ON ra.aid = a.id
                   LEFT JOIN df_auth_role r ON r.id = ra.role_id
                   ";

        //筛选条件
        if (isset($search['nick']) && $search['nick'] != '') {
            $filter[] = " LOCATE('{$search['nick']}',i.nick) > 0 ";
        }

        if (isset($search['create_start']) && $search['create_start'] != ''){
            $filter[] = " a.create_at >= {$search['create_start']}";
        }

        if (isset($search['create_end']) && $search['create_end'] != ''){
            $filter[] = " a.create_at <= {$search['create_start']}";
        }

        $where = " WHERE ";

        if (count($filter) >= 1) {
            $where .= implode(" AND ", $filter);
        }else{
            $where .= " i.u_type = 0 ";
        }

        $sql = "SELECT {$fields} FROM `df_a_users` a {$joins} {$where} ";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * 禁用管理员
     * @param $id   管理员id
     * @return mixed
     */
    public function stopAdmin($id,$status){
        //删除管理员
        //修改
        $data =  $this->db->update("df_a_users",[
            "status" => $status,
        ],[
            "id" => $id
        ]);

        return $data->rowCount();
    }

    /**
     * 添加管理员
     * @param $data
     */
    public function addAdmin($data){
        $this->db->beginTransaction();

        try{
            // 创建账号
            $aid = $this->auth->createUser($data['user_name'],$data['user_pwd'],'a');

            if(!$aid){
                return false;
            }

            $data['uid'] = $aid;

            // 写入个人资料
            $iid = $this->info->createInfo($data);

            if(!$iid){
                return false;
            }
            // 绑定角色
            $is_bind = $this->bindAdmin($aid,$data['role_id']);

            if(!$is_bind){
                return false;
            }

            $this->db->commit();

            return true;
        }catch(\Yaf\Exception $e){
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * 获取管理员信息
     * @param $id    管理员id
     * @return mixed
     */
    public function getAdminInfo($id){
        $data = $this->db->select("df_a_users","*",["id"=>$id]);

        return $info = $data[0];
    }

    /**
     * 编辑管理员
     * @param $data
     * @return mixed
     */
    public function editAdmin($data){
        //修改
        $data =  $this->db->update("df_a_users",[
            "name" => $data['name'],
            "title" => $data['title'],
            "status" => $data['status'],
            "condition" => $data['condition']
        ],[
            "id" => $data['id']
        ]);

        return $data->rowCount();
    }

    /**
     * 删除管理员
     * @param $id   管理员id
     * @return mixed
     */
    public function deleteAdmin($id){
        //删除管理员
        $data = $this->db->delete("df_a_users",[
            "id" => $id
        ]);

        return $data->rowCount();
    }

//*-------------管理员end----------------------

    /**
     * 绑定关系
     * @param $aid
     * @param $role_id
     */
    public function bindAdmin($aid,$role_id){
        if($this->checkAccess($aid)){
            return $this->updateAccess($aid,$role_id);
        }

        $this->db->insert("df_auth_role_access",[
            "aid" => $aid,
            "role_id" => $role_id,
        ]);

        return true;
    }

    /**
     * 解绑
     * @param $aid
     * @param $role_id
     */
    public function unbindAdmin($aid,$role_id){
        if($this->checkAccess($aid)){
            $this->db->delete("df_auth_role_access",["aid" => $aid, "role_id" => $role_id]);

            return $this->db->rowCount();
        }else{
            return false;
        }
    }

    /**
     * 更新绑定关系
     * @param $aid
     * @param $role_id
     */
    public function updateAccess($aid,$role_id){
        if($this->checkAccess($aid)){
            $this->db->update("df_auth_role_access",["role_id" => $role_id],["aid" => $aid]);

            return $this->db->rowCount();
        }else{
            return $this->bindAdmin($aid,$role_id);
        }
    }

    /**
     * 通过role_id 获取管理员列表
     * @param $id
     * @return mixed
     */
    public function getAdminListByRoleId($id){
        $sql = "SELECT a.user_name FROM `df_a_users` a LEFT JOIN `df_auth_role_access` r ON a.id = r.aid WHERE r.role_id = {$id}";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * 检测是否存在绑定关系
     * @param $aid
     * @return mixed
     */
    private function checkAccess($aid){
        return $this->db->count("df_auth_role_access",["aid" => $aid]);
    }

//*-------------角色、管理员end----------------------
}