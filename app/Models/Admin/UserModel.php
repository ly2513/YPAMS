<?php
/**
 * User: yongli
 * Date: 17/5/12
 * Time: 19:58
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace Admin;

use YP\Core\YP_Model as Model;

/**
 * Class UserModel  后端用户模型
 */
class UserModel extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'ams_sys_user';

    /**
     * 获得角色与账号关联表
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getUserRole()
    {
        return $this->hasMany('\Admin\UserRoleModel', 'uid', 'id');
    }

}
