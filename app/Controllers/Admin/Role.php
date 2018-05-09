<?php
/**
 * User: yongli
 * Date: 17/5/19
 * Time: 18:16
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

use Admin\MenuModel;
use Admin\RoleModel;

/**
 * Class Role 角色管理
 *
 * @package App\Controllers\Admin
 */
class Role extends Auth
{
    /**
     *  构造函数
     */
    public function initialization()
    {
        parent::initialization();
    }

    // 校验规则
    protected $rules = [
        'name'   => 'required|min_length[1]|max_length[128]',
        'status' => 'required|is_natural|exact_length[1]',
    ];

    // 提示信息
    protected $message = [
        'name'   => ['required' => '机构名称不能为空', 'min_length' => '最小长度为1', 'max_length' => '最大长度为128'],
        'status' => ['required' => '请选择状态', 'is_natural' => '请选择状态', 'exact_length' => '请选择状态'],
    ];

    /**
     * 机构列表
     */
    public function getRole()
    {
        // 机构数据
        $agencyData = RoleModel::select('*')->get()->toArray();
        // 实例化分页类
        $pagination = \Config\Services::pagination();
        // 获得分页配置
        $config = set_page_config(count($agencyData), $this->url, 3, $this->perPage);
        // 初始化分页配置
        $pagination->initialize($config);
        // 生成页码
        $page = $pagination->create_links();
        $data = RoleModel::select([
            'id',
            'name',
            'create_time',
            'status'
        ])->skip(($this->page - 1) * $this->perPage)->take($this->perPage)->orderBy('id', 'desc')->get()->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['create_time'] = date('Y-m-d', $value['create_time']);
        }
        $this->assign('uid', $_SESSION['uid']);
        $this->assign('page', $page);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 执行添加
     */
    public function add()
    {
        // 开始校验
        if (!$this->validate($this->rules, $this->message)) {
            // 校验失败,输出错误信息
            call_back(1, '', $this->errors);
        }
        $addData = $this->request->getPost();
        // 检测机构是否已存在
        $agencyData = RoleModel::select('id')->whereName($addData['name'])->get()->toArray();
        if ($agencyData) {
            call_back(2, '', '该角色已存在');
        }
        $addData['permissions'] = '[]';
        $addData['menus']       = '[]';
        $addData['create_time'] = time();
        $addData['update_time'] = time();
        $addData['create_by']   = $_SESSION['uid'];
        $addData['update_by']   = $_SESSION['uid'];
        // 获得自增ID(机构ID) 插入数据
        $id = RoleModel::insertGetId($addData);
        if (!$id) {
            call_back(2, '', '操作失败');
        }
        // 操作成功
        call_back(0);

    }

    /**
     * 编辑角色
     *
     * @param $id
     */
    public function updateRole($id)
    {
        $data = RoleModel::select([
            'id',
            'name',
            'status'
        ])->whereId($id)->get()->toArray();
        if (!$data) {
            call_back(2, '', '无此角色!');
        }
        call_back(0, $data[0]);
    }

    /**
     * 执行编辑
     */
    public function update()
    {
        // 开始校验
        if (!$this->validate($this->rules, $this->message)) {
            // 校验失败,输出错误信息
            call_back(1, '', $this->errors);
        }
        $addData    = $this->request->getPost();
        $agencyData = RoleModel::select('id')->whereName($addData['name'])->where('id', '!=',
            $addData['id'])->get()->toArray();
        if ($agencyData) {
            call_back(2, '', '该角色已存在');
        }
        // 跟新的数据
        $addData['update_time'] = time();
        $addData['update_by']   = $_SESSION['uid'];
        $status                 = RoleModel::whereId($addData['id'])->update($addData);
        if (!$status) {
            call_back(2, '', '操作失败');
        }
        // 操作成功
        call_back(0);
    }

    /**
     * 激活、禁用角色
     *
     * @param $id
     * @param $status
     */
    public function changeStatus($id, $status)
    {
        $status = $status == 1 ? 2 : 1;
        $status = RoleModel::whereId($id)->update(['status' => $status]);
        if (!$status) {
            call_back(2, '', '操作失败');
        }
        // 操作成功
        call_back(0);
    }

    /**
     * 设置权限
     *
     * @param $id
     */
    public function setPermissions($id)
    {
        $result = RoleModel::select(['id', 'permissions'])->whereId($id)->get()->toArray();
        $result = $result ? $result[0] : [];
        if (!$result) {
            callBack(2, '', '该记录不存在!');
        }
        $permissionArr = json_decode($result['permissions'], true);
        if (empty($permissionArr)) {
            $permissionArr = [];
        }
        if (!is_array($permissionArr) && !empty($permissionArr)) {//传入角色的权限ids
            $permissionArr = [$permissionArr];
        }
        // 获得系统所有的菜单
        $sysMenus = MenuModel::select([
            'id',
            'pid',
            'icon',
            'name',
            'level',
            'url',
            'description',
            'sort',
            'type'
        ])->get()->toArray();
        //        $menuIds  = array_column($sysMenus, 'id');
        //        if ($result['name'] == 'Administrators') {
        //            // 获得超级管理员的所有的菜单
        //            $permissionArr = array_merge($menuIds, $permissionArr);
        //        }
        foreach ($sysMenus as $key => $value) {//添加type 值 与checked值
            if (in_array($value['id'], $permissionArr)) {
                $checked = 1;
            } else {
                $checked = 0;
            }
            $sysMenus[$key]['checked'] = $checked;
        }
        unset($result['permissions']);
        $result['data'] = MenuModel::serializeMapList($sysMenus, 0);
//        P($result['data']);die;
        $this->assign('menu', $result['data']);
        $this->display();
    }

    /**
     * 获得角色信息
     *
     * @return mixed
     */
    public function getRoleInfo()
    {
        $result = RoleModel::select(['id', 'permissions'])->whereId($this->param['id'])->get()->toArray();
        $result = $result ? $result[0] : [];
        if (!$result) {
            callBack(2, '', '该记录不存在!');
        }
        $permissionArr = json_decode($result['permissions'], true);
        if (empty($permissionArr)) {
            $permissionArr = [];
        }
        if (!is_array($permissionArr) && !empty($permissionArr)) {//传入角色的权限ids
            $permissionArr = [$permissionArr];
        }
        // 获得系统所有的菜单
        $sysMenus = MenuModel::select([
            'id',
            'pid',
            'icon',
            'name',
            'level',
            'url',
            'description',
            'sort'
        ])->get()->toArray();
        //        $menuIds  = array_column($sysMenus, 'id');
        //        if ($result['name'] == 'Administrators') {
        //            // 获得超级管理员的所有的菜单
        //            $permissionArr = array_merge($menuIds, $permissionArr);
        //        }
        foreach ($sysMenus as $key => $value) {//添加type 值 与checked值
            if (in_array($value['id'], $permissionArr)) {
                $checked = 1;
            } else {
                $checked = 0;
            }
            $sysMenus[$key]['checked'] = $checked;
        }
        unset($result['permissions']);
        $result['data'] = $this->menusModel->serializeMapList($sysMenus, 0);
        callBack(0, $result);
    }

}