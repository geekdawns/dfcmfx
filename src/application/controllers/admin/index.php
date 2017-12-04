<?php
class Admin_IndexController extends Admin_BaseController {
    public function init(){
        parent::init();

        $this->getView()->assign('is_allow', $this->is_allow);
        $this->getView()->assign('userInfo', $this->userInfo);

        $this->cf = new CheckForm();
    }

   public function indexAction() {//默认Action
        $params = [];
        $this->getView()->make("admin.index.index", $params);
   }

/**
 * 会员管理-start
 */
    /**
     * 角色管理
     */
    public function roleAction(){
        //角色列表
        $params['list'] = $this->rbac->listRole();
        $params['count'] = count($params['list']);

        $this->getView()->make("admin.index.rbac.role", $params);
    }

    /**
     * 查看列表
     */
    public function roleLookAction(){
        $request = $this->getRequest();

        $id = htmlspecialchars(trim($request->getRequest("id")));

        $params['admin_list'] = $this->rbac->getAdminListByRoleId($id);

        $this->getView()->make("admin.index.rbac.role-look",$params);
    }

    /**
     * 添加角色
     */
    public function roleAddAction(){
        //设定权限
        $list = $this->rbac->listPermission(1);

        foreach($list as $k => $v){
            if($v['suf_code'] == 100){
                $params['list'][$v['pre_code']]['fa'] = $v;
            }else{
                $params['list'][$v['pre_code']]['ch'][] = $v;
            }

        }

        $this->getView()->make("admin.index.rbac.role-add", $params);
    }

    /**
     * 编辑角色
     */
    public function roleEditAction(){
        $request = $this->getRequest();

        $id = htmlspecialchars(trim($request->getRequest("id")));

        // 获取角色信息
        $params['info'] = $this->rbac->getRoleInfo($id);

        // 权限规则数组化
        $rules_map = [];
        if(strlen($params['info']['rules']) > 1){
            $rules_map = explode(",",$params['info']['rules']);
        }

        //设定权限
        $list = $this->rbac->listPermission(1);

        foreach($list as $k => $v){
            //增加 checked 标记
            if($params['info']['rules'] == ''){
                $v['checked'] = 0;
            }else{
                $auth_code = $v['pre_code'].$v['suf_code'];

                $v['checked'] = in_array($auth_code,$rules_map)? 1 : 0;
            }

            if($v['suf_code'] == 100){
                $params['list'][$v['pre_code']]['fa'] = $v;
            }else{
                $params['list'][$v['pre_code']]['ch'][] = $v;
            }

        }


        $this->getView()->make("admin.index.rbac.role-edit", $params);
    }

    /**
     * 处理角色（添加、编辑）
     */
    public function roleHandleAction(){
        $request = $this->getRequest();

        $handle_type = $request->getPost("handle");

        $permissions = $request->getPost("permission") ? $request->getPost("permission") : [];

        $rules = '';
        // 格式化 权限
        if(count($permissions)){
            foreach($permissions as $k => $v){
                foreach($v as $cv){
                    $rules .= $k.$cv.',';
                }
            }
        }

        $data = [
            'title' => htmlspecialchars(trim($request->getPost("roleName"))),
            'description' => htmlspecialchars(trim($request->getPost("description"))),
            'rules' => $rules ? $rules : '',
        ];

        if($handle_type == "add"){
            //添加
            $is_add = $this->rbac->addRole($data);

            if($is_add){
                $info['types'] = 'success';
                $info['msg'] = '添加成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '添加失败！';
            }

        }else{
            //编辑
            $data['id'] = htmlspecialchars(trim($request->getPost("id")));

            $is_edit = $this->rbac->editRole($data);

            if($is_edit){
                $info['types'] = 'success';
                $info['msg'] = '编辑成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '编辑失败！';
            }
        }

        echo json_encode($info,true);
    }

    /**
     * 删除角色
     */
    public function roleDeleteAction(){
        $request = $this->getRequest();

        $id = htmlspecialchars(trim($request->getPost("id")));

        //鉴权
        if($this->is_allow){
            $is_delete = $this->rbac->deleteRole($id);

            if($is_delete){
                $info['types'] = 'success';
                $info['msg'] = '删除成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '删除失败！';
            }
        }else{
            $info['types'] = 'fail';
            $info['msg'] = '权限不足！';
        }
        echo json_encode($info,true);
    }

    /**
     * 权限管理
     */
    public function permissionAction(){
        $request = $this->getRequest();
        //角色列表
        $search = [
            "title" => htmlspecialchars(trim($request->getPost("title")))
        ];
        $params['list'] = $this->rbac->listPermission($search);

        foreach($params['list'] as $k => $v){

            if($v['suf_code'] == 100){
                $params['list'][$k]['title'] = '|—— '.$v['title'];
            }else{
                $params['list'][$k]['title'] = '|  - | -  '.$v['title'];
            }
        }


        $params['count'] = count($params['list']);

        $this->getView()->make("admin.index.rbac.permission", $params);
    }

    /**
     * 添加权限
     */
    public function permissionAddAction(){
        $params = [];
        $this->getView()->make("admin.index.rbac.permission-add", $params);
    }

    /**
     * 编辑权限
     */
    public function permissionEditAction(){
        $request = $this->getRequest();

        $id = $request->getRequest("id");

        $params['info'] = $this->rbac->getPermissionInfo($id);
        $this->getView()->make("admin.index.rbac.permission-edit", $params);
    }

    /**
     * 处理权限（添加、编辑）
     */
    public function permissionHandleAction(){
        $request = $this->getRequest();

        $handle_type = $request->getPost("handle");

        $data = [
            'title' => htmlspecialchars(trim($request->getPost("title"))),
            'pre_code' => htmlspecialchars(trim($request->getPost("pre_code"))),
            'suf_code' => htmlspecialchars(trim($request->getPost("suf_code"))),
            'status' => htmlspecialchars(trim($request->getPost("status"))),
            'condition' => htmlspecialchars(trim($request->getPost("condition"))),
        ];

        if($handle_type == "add"){
            //添加
            $is_add = $this->rbac->addPermission($data);

            if($is_add){
                $info['types'] = 'success';
                $info['msg'] = '添加成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '添加失败！';
            }

        }else{
            //编辑
            $data['id'] = htmlspecialchars(trim($request->getPost("id")));

            $is_edit = $this->rbac->editPermission($data);

            if($is_edit){
                $info['types'] = 'success';
                $info['msg'] = '编辑成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '编辑失败！';
            }
        }

        echo json_encode($info,true);
    }

    /**
     * 删除角色
     */
    public function permissionDeleteAction(){
        $request = $this->getRequest();

        $id = htmlspecialchars(trim($request->getPost("id")));

        if($this->is_allow){
            //鉴权
            $is_delete = $this->rbac->deletePermission($id);

            if($is_delete){
                $info['types'] = 'success';
                $info['msg'] = '删除成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '删除失败！';
            }
        }else{
            $info['types'] = 'fail';
            $info['msg'] = '权限不足！';
        }


        echo json_encode($info,true);
    }



    /**
     * 管理员列表
     */
    public function adminListAction(){
        $request = $this->getRequest();
        //管理员列表
        $search = [
            "nick" => htmlspecialchars(trim($request->getPost("nick"))),
            "create_start" => htmlspecialchars(trim($request->getPost("create_start"))),
            "create_end" => htmlspecialchars(trim($request->getPost("create_end"))),
        ];
        $params['list'] = $this->rbac->listAdmin($search);

        $params['count'] = count($params['list']);

        $this->getView()->make("admin.index.rbac.admin", $params);
    }

    /**
     * 禁用管理员
     */
    public function adminStopAction(){
        $request = $this->getRequest();

        $id = htmlspecialchars(trim($request->getRequest("id")));
        $status = htmlspecialchars(trim($request->getRequest("status")));

        $is_stop = $this->rbac->stopAdmin($id, $status);

        if($status){
            // 禁用
            if($is_stop){
                $info['types'] = 'success';
                $info['msg'] = '禁用成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '禁用失败！';
            }
        }else{
            // 启用
            if($is_stop){
                $info['types'] = 'success';
                $info['msg'] = '启用成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '启用失败！';
            }
        }

        echo json_encode($info, true);

    }

    /**
     * 添加管理员
     */
    public function adminAddAction(){
        $params['roles'] = $this->rbac->listRole();
        $this->getView()->make("admin.index.rbac.admin-add", $params);
    }

    /**
     * 编辑管理员
     */
    public function adminEditAction(){
        $request = $this->getRequest();

        $id = $request->getRequest("id");

        $params['info'] = $this->rbac->getRoleInfo($id);
        $this->getView()->make("admin.index.rbac.admin-edit", $params);
    }

    /**
     * 处理管理员（添加、编辑）
     */
    public function adminHandleAction(){
        $request = $this->getRequest();

        $handle_type = $request->getPost("handle");

        if($handle_type == "add"){
            //添加
            $data = [
                'user_name' => htmlspecialchars(trim($request->getPost("adminName"))),
                'user_pwd' => htmlspecialchars(trim($request->getPost("password"))),
                'repassword' => htmlspecialchars(trim($request->getPost("password2"))),
                'sex' => htmlspecialchars(trim($request->getPost("sex"))),
                'mobile' => htmlspecialchars(trim($request->getPost("phone"))),
                'email' => htmlspecialchars(trim($request->getPost("email"))),
                'role_id' => htmlspecialchars(trim($request->getPost("adminRole"))),
                'description' => htmlspecialchars(trim($request->getPost("description"))),
                'u_type' => 0,
                'create_at' => time(),
            ];

            if($data['user_pwd'] !== $data['repassword']){
                $info['types'] = 'fail';
                $info['msg'] = '添加失败！';
            }else{
                $is_add = $this->rbac->addAdmin($data);

                if($is_add){
                    $info['types'] = 'success';
                    $info['msg'] = '添加成功！';
                }else{
                    $info['types'] = 'fail';
                    $info['msg'] = '添加失败！';
                }
            }
        }else{
            //编辑
            $data = [
                'id' => $request->getPost("id"),
                'title' => htmlspecialchars(trim($request->getPost("adminName"))),
                'description' => htmlspecialchars(trim($request->getPost("description"))),
            ];

            $is_edit = $this->rbac->editRole($data);

            if($is_edit){
                $info['types'] = 'success';
                $info['msg'] = '编辑成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '编辑失败！';
            }
        }

        echo json_encode($info,true);
    }

    /**
     * 删除管理员
     */
    public function adminDeleteAction(){
        $request = $this->getRequest();

        $id = htmlspecialchars(trim($request->getPost("id")));

        //鉴权
        if($this->is_allow){
            $is_delete = $this->rbac->deleteRole($id);

            if($is_delete){
                $info['types'] = 'success';
                $info['msg'] = '删除成功！';
            }else{
                $info['types'] = 'fail';
                $info['msg'] = '删除失败！';
            }
        }else{
            $info['types'] = 'fail';
            $info['msg'] = '权限不足！';
        }


        echo json_encode($info,true);
    }
/**
 * 会员管理-end
 */
}
?>
