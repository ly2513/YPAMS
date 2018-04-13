<?php
/**
 * User: yongli
 * Date: 17/5/19
 * Time: 18:15
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

use Admin\NodeModel;

/**
 * Class Node 节点管理
 * @package App\Controllers\Admin
 */
class Node extends Auth
{
    /**
     * 节点列表
     */
    public function getNode()
    {
        // 父级节点数据
        $pidData  = NodeModel::select(['id', 'title'])->wherePid(0)->get()->toArray();
        $nodeData = NodeModel::select(['id', 'pid', 'name', 'title'])->orderBy('level', 'desc')->get()->toArray();
        $data     = new \YP_Data();
        $tree     = $data->tree($nodeData, 'title', 'id', 'pid');
        $this->assign('c', $this->controller);
        $this->assign('a', $this->method);
        $this->assign('uid', $_SESSION['uid']);
        $this->assign('pidData', $pidData);
        $this->assign('data', $tree);
        $this->display();

    }

    /**
     * 添加节点
     */
    public function add()
    {
        $addData  = $this->request->getPost();
        $dataInfo = NodeModel::select('*')->where([
            'name'  => $addData['name'],
            'title' => $addData['title']
        ])->get()->toArray();
        if ($dataInfo) {
            call_back(2, '', '已存在相应的节点');
        }
        $addData['create_time'] = time();
        $addData['update_time'] = time();
        $id                     = NodeModel::insertGetId($addData);
        if (!$id) {
            call_back(2, '', '添加失败!');
        }
        call_back(0);
    }

    /**
     * 获得节点信息
     *
     * @param $id
     */
    public function getNodeById($id)
    {
        $data = NodeModel::select(['id', 'name', 'title', 'pid', 'level'])->whereId($id)->get()->toArray();
        if (!$data) {
            call_back(2, '', '无此记录!');
        }
        call_back(0, $data);
    }

    /**
     * 显示更新页码
     *
     * @param $id
     */
    public function updateNode($id)
    {
        $nodeData = NodeModel::select(['id', 'title', 'level'])->wherePid(0)->orderBy('level',
            'desc')->get()->toArray();
        $nodeInfo = NodeModel::select(['id', 'name', 'pid', 'title', 'level'])->whereId($id)->get()->toArray();
        $nodeInfo = $nodeInfo ? $nodeInfo[0] : [];
        $this->assign('c', $this->controller);
        $this->assign('a', $this->method);
        $this->assign('uid', $_SESSION['uid']);
        $this->assign('nodeData', $nodeData);
        $this->assign('data', $nodeInfo);
        $this->display();
    }

    /**
     * 执行更新
     */
    public function update()
    {
        $updateData = $this->request->getPost();
        $where      = ['name' => $updateData['name'], 'title' => $updateData['title']];
        $dataInfo   = NodeModel::select('*')->where($where)->where('id', '!=', $updateData['id'])->get()->toArray();
        if ($dataInfo) {
            call_back(2, '', '已存在相应的节点');
        }
        $updateData['update_time'] = time();
        $data                      = NodeModel::whereId($updateData['id'])->update($updateData);
        if (!$data) {
            call_back(2, '', '保存失败!');
        }
        call_back(0);
    }

    /**
     * 删除节点
     *
     * @param $id
     */
    public function delete($id)
    {
        $update['is_delete'] = 1;
        $status              = NodeModel::whereId($id)->update($update);
        if (!$status) {
            call_back(2, '', '删除失败!');
        }
        call_back(0);
    }

}