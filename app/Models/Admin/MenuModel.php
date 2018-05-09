<?php
/**
 * User: Zhiqiang
 * Date: 17/07/03
 * Time: 15:17
 * Email: zhiqiang.zhao@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */

namespace Admin;

class MenuModel extends \YP\Core\YP_Model
{
	/**
	 * The model associated with the table
	 * 
	 * @var string
	 */
    protected $table = 'ams_sys_menus';

    /**
     * The roles that belong to the user
     * 
     * @return void
     */
    public function roles()
    {
    	return $this->belongsToMany('App\Models\Admin\RoleModel', 'role_menu', 'menu_id', 'role_id');
    }

	/**
	 * 序列化列表,以层级关系显示
	 *
	 * @param array $data   需要序列号的数据
	 * @param int   $status 表示是否写入选中状态1:不写入,0:写入
	 *
	 * @return array
	 */
	public static function serializeMapList(array $data, $status = 1)
	{
		if (!is_array($data) || !isset($data[0])) {
			return [];
		}
		//开始处理数据
		$mapData = array_flatten_key($data, 'id');
		//处理树形结构
		$data = get_tree($mapData);
		//        P($data);
		$data = self::sortChild($data, $status);

		return $data;
	}

	/**
	 * 根据权重 递归排序 整个list
	 * 权重排序规则 由大=>小
	 *
	 * @param array $child  子节点数组
	 * @param       $status 表示是否写入选中状态1:不写入,0:写入
	 *
	 * @return array
	 */
	public static function sortChild(array $child, $status)
	{
		$docker = function ($a, $b) {
			return $a['level'] < $b['level'] ? -1 : 1;
		};
		usort($child, $docker);
		foreach ($child as $k => $val) {
			$tmpP_id = $val['pid'];
			if ($tmpP_id != 0) {
				$tmpP_nameArr  = MenuModel::select('name')->whereId($tmpP_id)->get()->toArray();
				$tmpP_nameArr = $tmpP_nameArr ? $tmpP_nameArr[0] : [];
				$val['p_name'] = isset($tmpP_nameArr['name']) ? $tmpP_nameArr['name'] : '';
			} else {
				$val['p_name'] = '';
			}
			if (isset($val['children'])) {
				$child[$k]['p_name']   = $val['p_name'];
				$child[$k]['children'] = self::sortChild($val['children'], $status);
				unset($child[$k]['pid'], $child[$k]['p_name']);
			} else {
				$child[$k] = self::filterMapList($val, $status);
			}
		}

		return $child;
	}

	/**
	 * 处理数据维度最后一级,格式化数据,并处理命名逻辑关系
	 *
	 * @param $data     需最后处理的数据
	 * @param $status   表示是否写入选中状态1:不写入,0:写入
	 *
	 * @return array
	 */
	public static function filterMapList($data, $status)
	{
		if (empty($data)) {
			return [];
		}
		$finalData             = [];
		$finalData['children'] = [];
		$finalData['name']     = $data['name'];
		$finalData['id']       = $data['id'];
		$finalData['type']     = $data['type'];
		if (!$status) {
			$finalData['checked'] = $data['checked'];
		}

		return $finalData;
	}
}