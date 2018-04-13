<?php
/**
 * User: yongli
 * Date: 17/5/19
 * Time: 18:16
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

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
        'name'           => 'required|min_length[1]|max_length[128]',
        'license_num'    => 'required|is_natural|exact_length[13]',
        'build_time'     => 'required|is_date',
        'register_money' => 'required|numeric',
        'agency_id'      => 'required|min_length[1]|max_length[128]'
    ];

    // 提示信息
    protected $message = [
        'name'           => ['required' => '机构名称不能为空', 'min_length' => '最小长度为1', 'max_length' => '最大长度为128'],
        'license_num'    => ['required' => '营业执照编号', 'is_natural' => '请输入正确的营业执照编号', 'exact_length' => '输入的营业执照编号有误'],
        'build_time'     => ['required' => '成立时间不能为空', 'is_date' => '请输入正确的日期格式(如:2017-01-01)'],
        'register_money' => ['required' => '注册资金不能为空', 'numeric' => '请输入有效的值'],
        'agency_id'      => ['required' => '机构编号不能为空', 'min_length' => '最小长度为1', 'max_length' => '最大长度为128'],
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
        $this->assign('c', $this->controller);
        $this->assign('a', $this->method);
        $this->assign('uid', $_SESSION['uid']);
        $this->assign('page', $page);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 显示添加角色
     */
    public function addRole()
    {
        $this->assign('c', $this->controller);
        $this->assign('a', $this->method);
        $this->assign('uid', $_SESSION['uid']);
        $this->display();
    }

    /**
     * 执行添加
     */
    public function add()
    {
        // 开始校验
        if (!$this->validate($this->request, $this->rules, $this->message)) {
            // 校验失败,输出错误信息
            call_back(1, '', $this->errors);
        }
        $addData = $this->request->getPost();
        //        $agencyData = AgencyModel::select('*')->whereName($addData['name'])->get()->first();
        // 检测机构是否已存在
        $agencyData = RoleModel::select('id')->whereName($addData['name'])->get()->toArray();
        if ($agencyData) {
            call_back(2, '', '该机构已存在');
        }
        $addData['build_time']  = strtotime($addData['build_time']);
        $addData['create_time'] = time();
        $addData['update_time'] = time();
        $addData['create_by']   = 1;
        $addData['update_by']   = 1;
        // 获得自增ID(机构ID) 插入数据
        $id = RoleModel::insertGetId($addData);
        if (!$id) {
            call_back(2, '', '操作失败');
        }
        // 添加成功
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
            'agency_id',
            'name',
            'license_num',
            'register_money',
            'build_time'
        ])->whereId($id)->get()->toArray();
        if (!$data) {
            call_back(2, '', '无此机构');
        }
        $data[0]['build_time'] = date('Y-m-d', $data[0]['build_time']);
        $this->assign('c', $this->controller);
        $this->assign('a', $this->method);
        $this->assign('uid', $_SESSION['uid']);
        $this->assign('data', $data[0]);
        $this->display();
    }

    /**
     * 执行编辑
     */
    public function update()
    {
        // 开始校验
        if (!$this->validate($this->request, $this->rules, $this->message)) {
            // 校验失败,输出错误信息
            call_back(1, '', $this->errors);
        }
        $addData    = $this->request->getPost();
        $agencyData = RoleModel::select('id')->whereName($addData['name'])->where('id', '!=',
            $addData['id'])->get()->toArray();
        if ($agencyData) {
            call_back(2, '', '该机构已存在');
        }
        // 跟新的数据
        $addData['build_time']  = strtotime($addData['build_time']);
        $addData['update_time'] = time();
        $addData['update_by']   = 1;
        $status                 = RoleModel::whereId($addData['id'])->update($addData);
        if (!$status) {
            call_back(2, '', '操作失败');
        }
        // 添加成功
        call_back(0);
    }

    /**
     * 删除角色
     *
     * @param $id
     */
    public function deleteRole($id)
    {
        $addData['id']        = $id;
        $addData['is_delete'] = 1;
        $status               = RoleModel::whereId($id)->update($addData);
        if (!$status) {
            call_back(2, '', '操作失败');
        }
        // 添加成功
        call_back(0);
    }
}