<?php
/**
 * User: Zhiqiang
 * Date: 17/07/03
 * Time: 15:17
 * Email: zhiqiang.zhao@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

use Admin\MenuModel;
use App\Libraries\YP_Data;

/**
 * Class Menu 菜单管理
 * @package App\Controllers\Admin
 */
class Menu extends Auth
{
    /**
     * redis对象
     *
     * @var null
     */
    private $redis = null;

    /**
     * 构造方法
     */
    public function initialization()
    {
        parent::initialization();
        $this->redis = \Config\Services::redis();
        // 初始化redis
        if (!$this->redis->getRedis()) {
            $this->redis->initialize();
        }
    }

    /**
     * 菜单列表
     *
     * @return  Response
     */
    public function getMenu()
    {
        $condition = $this->request->getGet('name') ? $this->request->getGet('name') : '';
        $query     = MenuModel::select(['id', 'pid', 'icon', 'name', 'sort', 'create_time']);
        if ($condition) {
            $query->where('name', 'like', "%$condition%");
        }
        $list = $query->get()->toArray();
        if ($list) {
            $this->redis->set('menusData', json_encode($list));
        }
        $tree    = new YP_Data();
        $list    = $tree->tree($list, 'name', 'id', 'pid');
        $awesome = $this->getAwesome();
        $this->assign('condition', $condition);
        $this->assign('list', $list);
        $this->assign('awesome', $awesome);
        $this->display();
    }

    /**
     * 添加菜单
     *
     * @return  Response
     */
    public function add()
    {
        $data = $this->request->getPost();
        // 每个货品下都会有 自有产品 和 平台产品，同时判断pid和name可以排除重复数据
        $info = MenuModel::where('name', $data['name'])->wherePid($data['pid'])->first();
        if ($info) {
            call_back(2, '', '该菜单已存在!');
        }
        if ($data['pid'] != 0) {
            $parent        = MenuModel::findOrFail($data['pid']);
            $data['level'] = $parent->level + 1;
        }
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['create_by']   = 1;
        $data['update_by']   = 1;
        $id                  = MenuModel::insertGetId($data);
        if ($id) {
            $data['id']        = $id;
            $data['redisFlag'] = 1;
            // 添加缓存
            $this->redis->hmset('menu:' . $data['id'], '', $data);
        } else {
            call_back(2, '', ' 保存失败!');
        }
        call_back(0);
    }

    /**
     * 菜单信息
     *
     * @param  int $id
     *
     * @return  Json
     */
    public function getMenuInfo($id)
    {
        $data = MenuModel::findOrFail($id)->toArray();
        call_back(0, $data);
    }

    /**
     * 编辑菜单
     *
     * @return  Response
     */
    public function update()
    {
        $data = $this->request->getPost();
        if ($data['pid'] != 0) {
            $parent        = MenuModel::findOrFail($data['pid']);
            $data['level'] = $parent->level + 1;
        } else {
            $data['level'] = 1;
        }
        $data['update_time'] = time();
        $data['update_by']   = 1;
        $id                  = MenuModel::whereId($data['id'])->update($data);
        $this->getSubArray($data['id'], $data['level']);
        $id ? call_back(0) : call_back(2, '', '保存失败');
    }

    /**
     * 编辑子菜单
     *
     * @param $id
     * @param $level
     */
    public function getSubArray($id, $level)
    {
        $parent = MenuModel::wherePid($id)->get()->toArray();
        if ($parent) {
            foreach ($parent as $key => $value) {
                $value['level'] = $level + 1;
                MenuModel::whereId($value['id'])->update($value);
                $this->getSubArray($value['id'], $value['level']);
            }
        }
    }

    /**
     * 删除菜单
     *
     * @param int
     *
     * @return  Response
     */
    public function delete($id)
    {
        $info = MenuModel::whereId($id)->get()->toArray();
        if (!$info[0]) {
            call_back(2, '', '无效的ID');
        }
        $ids = $id . $this->getSub($id);
        foreach (explode(',', $ids) as $key => $id) {
            $info              = MenuModel::findOrFail($id);
            $info->is_delete   = 1;
            $info->update_time = time();
            $info->update_by   = 1;
            $info->save();
        }
        call_back(0);
    }

    /**
     * Obtain the sub menu id via parent id
     *
     * @param  int $id
     *
     * @return string
     */
    public function getSub($id)
    {
        $parent = MenuModel::wherePid($id)->get()->toArray();
        $ids    = '';
        if ($parent) {
            foreach ($parent as $key => $value) {
                $ids .= ',' . $value['id'];
                $ids .= $this->getSub($value['id']);
            }
        }

        return $ids;
    }

    /**
     * 菜单图表
     *
     * @return array
     */
    public function getAwesome()
    {
        $icons_file  = FRONT_PATH . '/Static/font-awesome/css/font-awesome.css';
        $parsed_file = file_get_contents($icons_file);
        preg_match_all("/fa\-([a-zA-z0-9\-]+[^\:\.\,\s])/", $parsed_file, $matches);
        $exclude_icons = [
            "fa-lg",
            "fa-2x",
            "fa-3x",
            "fa-4x",
            "fa-5x",
            "fa-ul",
            "fa-li",
            "fa-fw",
            "fa-border",
            "fa-pulse",
            "fa-rotate-90",
            "fa-rotate-180",
            "fa-rotate-270",
            "fa-spin",
            "fa-flip-horizontal",
            "fa-flip-vertical",
            "fa-stack",
            "fa-stack-1x",
            "fa-stack-2x",
            "fa-inverse"
        ];

        return $this->array_delete($matches[0], $exclude_icons);
    }

    /**
     * Diff the element not in $array
     *
     * @param  array $array
     * @param        array or string $element
     *
     * @return array
     */
    function array_delete($array, $element)
    {
        return (is_array($element)) ? array_values(array_diff($array, $element)) : array_values(array_diff($array,
            [$element]));
    }
}