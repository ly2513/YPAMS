<?php
/**
 * User: yongli
 * Date: 17/4/25
 * Time: 14:03
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

use Admin\RoleModel;
use Admin\UserModel;
use Admin\UserRoleModel;

/**
 * Class User 用户管理
 *
 * @package App\Controllers\Admin
 */
class User extends Auth
{

    /**
     * 用户列表
     */
    public function getUser()
    {
        $userList = UserModel::select([
            'id',
            'nickname',
            'name',
            'status',
            'create_time',
            'login_time'
        ])->with([
            'getUserRole' => function ($query) {
                $query->select('id', 'uid', 'rid')->whereStatus(0);
            }
        ])->get()->toArray();
        if (!$userList) {
            call_back(0);
        }
        foreach ($userList as $key => &$value) {
            $rid                  = array_column($value['get_user_role'], 'rid');
            $role                 = RoleModel::select(['id', 'name'])->whereIn('id',
                $rid)->whereStatus(1)->get()->toArray();
            $role_name            = $role ? implode(',', array_column($role, 'name')) : '';
            $value['role_name']   = $role_name;
            $value['create_time'] = date('Y-m-d', $value['create_time']);
            $value['login_time']  = date('Y-m-d', $value['login_time']);
            unset($value['get_user_role']);
        }
        $this->assign('uid', $_SESSION['uid']);
        $this->assign('data', $userList);
        $this->display();
    }

    /**
     * 添加账号
     */
    public function add()
    {
        $addData  = $this->request->getPost();
        $userInfo = UserModel::select('*')->whereName($addData['name'])->get()->toArray();
        if ($userInfo) {
            call_Back(2, '', '账号已存在');
        }
        // 开启事务
        $build = UserModel::select();
        $build->getConnection()->beginTransaction();
        $password               = '123456';
        $addData['password']    = password_hash(md5(md5($password)), PASSWORD_DEFAULT);
        $addData['create_time'] = time();
        $addData['update_time'] = time();
        $addData['create_by']   = $_SESSION['uid'];
        $addData['update_by']   = $_SESSION['uid'];
        $roleIds                = $addData['role_id'];
        unset($addData['role_id']);
        $id       = UserModel::insertGetId($addData);
        $roleData = [];
        foreach ($roleIds as $value) {
            array_push($roleData, [
                'uid'         => $id,
                'rid'         => $value,
                'status'      => 0,
                'create_by'   => $_SESSION['uid'],
                'update_by'   => $_SESSION['uid'],
                'create_time' => time(),
                'update_time' => time()
            ]);

        }
        $status = UserRoleModel::insert($roleData);
        if ($id && $status) {
            // 提交事务
            $build->getConnection()->commit();
            call_back(0);
        } else {
            // 回滚事务
            $build->getConnection()->rollBack();
            call_Back(2, '', '添加账号失败!');
        }
    }

    /**
     * 账号信息
     *
     * @param $id
     */
    public function getUserInfo($id)
    {
        $userInfo        = UserModel::select([
            'id',
            'nickname',
            'name',
            'status'
        ])->with([
            'getUserRole' => function ($query) {
                $query->select('id', 'uid', 'rid')->whereStatus(0);
            }
        ])->whereId($id)->get()->toArray();
        $userInfo        = $userInfo ? $userInfo[0] : $userInfo;
        $userInfo['rid'] = array_column($userInfo['get_user_role'], 'rid');
        unset($userInfo['get_user_role']);
        call_back(0, $userInfo);
    }

    /**
     * 编辑账号
     */
    public function update()
    {
        $addData  = $this->request->getPost();
        $userInfo = UserModel::select('*')->whereName($addData['name'])->where('id', '!=',
            $addData['id'])->get()->toArray();
        if ($userInfo) {
            call_back(2, '', '账号已存在');
        }
        $addData['update_time'] = time();
        $addData['update_by']   = $_SESSION['uid'];
        // 开启事务
        $build = UserModel::select();
        $build->getConnection()->beginTransaction();
        unset($addData['role_id']);
        $status   = UserModel::whereId($addData['id'])->update($addData);
        if ($status) {
            // 提交事务
            $build->getConnection()->commit();
            call_back(0);
        } else {
            // 回滚事务
            $build->getConnection()->rollBack();
            call_Back(2, '', '保存账号失败!');
        }
        call_back(0);
    }

    /**
     * 修改账号状态
     *
     * @param $id
     * @param $status
     */
    public function changeStatus($id, $status)
    {
        $status = $status == 1 ? 2 : 1;
        $status = UserModel::whereId($id)->update(['status' => $status]);
        if (!$status) {
            call_back(2, '', '操作失败!');
        }
        call_back(0);
    }

    /**
     *  修改密码
     */
    public function changPassword()
    {
        $data     = $this->request->getPost();
        $userInfo = UserModel::select(['id', 'password'])->whereId($data['id'])->get()->toArray();
        // 验证原密码
        if (!password_verify(md5($data['oldPassword']), $userInfo[0]['password'])) {
            call_back(2, '', '原密码错误!');
        }
        if ($data['newPassword'] != $data['rePassword']) {
            call_back(2, '', ' 两次密码输入不一致!');
        }
        $updateData['password'] = password_hash(md5($data['newPassword']), PASSWORD_DEFAULT);
        // 跟新数据
        $status = UserModel::whereId($data['id'])->update($updateData);
        if (!$status) {
            call_back(2, '', '修改密码失败!');
        }
        call_back(0);
    }

    /**
     * 重置密码
     */
    public function resetPassword()
    {
        $id                     = $this->request->getPost('id');
        $updateData['password'] = password_hash(md5(md5('123456')), PASSWORD_DEFAULT);
        $status                 = UserModel::whereId($id)->update($updateData);
        if (!$status) {
            call_back(2, '', '重置密码失败!');
        }
        call_back(0);
    }

    public function addSession()
    {
        $session = \Config\Services::session();
        $session->start();
        //        $a = $session->read(123456789);
        //        P($a);
        //        P($session);
    }

    public function testValidate()
    {
        // 校验规则
        $rules = [
            'age'      => 'required|min_length[2]',
            'username' => 'required|max_length[10]',
            'email'    => 'required|valid_email'
        ];
        // 提示信息
        $message = [
            'age'      => [
                'min_length' => '最小长度为2',
                'required'   => 'age不能为空'
            ],
            'username' => [
                'max_length' => '最大长度为10',
                'required'   => '名称必填',
            ],
            'email'    => [
                'required'    => '邮箱不能为空',
                'valid_email' => '请检查电子邮件字段,无效的邮箱地址'
            ]
        ];
        // 开始校验
        if (!$this->validate($this->request, $rules, $message)) {
            // 校验失败,输出错误信息
            P($this->errors);
        }
        // 获得$_GET数据
        $param = $this->request->getGet();
        // 获得$_POST数据
        $param1 = $this->request->getPost();
        P($param);
        P($param1);
        $userInfo = UserModel::select('id', 'username', 'email', 'photo_url')->get()->toArray();
        //        P($userInfo);
        $this->assign('company', '优品未来');
        $this->assign('title', 'Admin下的User');
        $this->display();
    }

    public function testJsonSchema()
    {
        $this->checkSchema();
        //        P($this->input->json);
        die;
    }

    public function get()
    {
        P(UserModel);
    }

    public function page()
    {
        $pagination  = \Config\Services::pagination();
        $url         = '/Admin/User/page';
        $uri_segment = 3;
        $num         = isset($_GET['per_page']) && !empty($_GET['per_page']) ? intval(strip_tags($_GET['per_page'])) : 10;
        //设置分页类总条数，跳转链接
        $config = set_page_config(20, $url, $num, 10);
        // 配置分页
        $pagination->initialize($config);
        //获得账号数据
        // 生成页码
        $page = $pagination->create_links();
        P($page);
    }

    public function query()
    {
        $url_info['url']  = 'https://apist.baiqishi.com/services/decision';
        $urlData          = [
            'name'   => '李勇',
            'mobile' => '18518178485',
            'certNo' => '362429199005072513',
        ];
        $url_info['data'] = json_encode(['data' => $urlData]);
        $data             = multiCurlPost([$url_info]);
        P($data);
    }
}