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

/**
 * Class Login 登陆
 * @package App\Controllers\Admin
 */
class Login extends \YP\Core\YP_Controller
{
    /**
     * 零售登录
     */
    public function login()
    {
        $this->display();
    }

    /**
     * 商户登录
     */
    public function login2()
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
        $isReg     = UserModel::whereRaw('phone = "' . $username . '"')->where('status', 1)->select([
                'id',
                'phone',
                'password',
                'name',
            ])->first() ?? false;
        if (!$isReg) {
            call_back(2, '', '用户不存在或未授权!');
        }
        $userData = $isReg->toArray();
        // 验证密码
        if (!password_verify(md5($password), $userData['password'])) {
            call_back(2, '', '密码或用户名错误!');
        }
        // 实例化session对象
        $session = Services::session();
        //  设置session
        $userInfo = [
            'uid'        => $userData['id'],
            'phone'      => $userData['phone'],
            'is_manager' => $isManager,
        ];
        $session->set('userInfo', $userInfo);
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
