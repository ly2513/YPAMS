<?php
/**
 * User: yongli
 * Date: 17/5/12
 * Time: 18:05
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

use Config\Services;
use Admin\UserModel;
use Admin\RoleModel;
use \YP\Core\YP_Controller as Controller;

/**
 * Class Login 登陆
 *
 * @package App\Controllers\Admin
 */
class Login extends Controller
{
    /**
     * 登录
     */
    public function login()
    {
        $this->display();
    }

    /**
     * 执行登录
     */
    public function doLogin()
    {
        $username  = $this->request->getPost('username');
        $password  = $this->request->getPost('password');
        $isManager = $this->request->getPost('is_manager') ?? false;
        $userData  = UserModel::select(
            [
            'id',
            'roles',
            'name',
            'password',
            'nickname',
            ]
        )->whereName($username)->whereStatus(1)->get()->toArray();
        $userData  = $userData ? $userData[0] : [];
        if (!$userData) {
            call_back(2, '', '用户不存在或被禁用!');
        }
        // 验证密码
        if (!password_verify(md5($password), $userData['password'])) {
            call_back(2, '', '密码或用户名错误!');
        }
        // 实例化session对象
        $session = Services::session();
        //  设置session
        $userInfo = [
            'uid'        => $userData['id'],
            'name'       => $userData['name'],
            'nickname'   => $userData['nickname'],
            'rid'        => json_decode($userData['roles'], true),
            'is_manager' => $isManager,
        ];
        $role     = RoleModel::select(['name'])->whereIn('id', json_decode($userData['roles'], true))->get()->toArray();
        $roleName = implode(',', array_column($role, 'name'));
        $session->set('userInfo', $userInfo);
        $session->set('roleName', $roleName);
        $session->set('uid', $userData['id']);
        // 登录成功
        call_back(0);
    }

    /**
     * 退出
     */
    public function out()
    {
        $session = Services::session();
        $session->remove('userInfo');
        call_back(0);
    }
}
