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
}