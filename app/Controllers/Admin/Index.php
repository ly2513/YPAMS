<?php
/**
 * User: xuehe.yang
 * Date: 17/9/2
 * Time: 14:00
 * Email: xuehe.yang@szypwl.com
 * Copyright: 深圳优品未来科技有限公司.
 */
namespace App\Controllers\Admin;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * 统计数据控制器
 * Class Index.
 *
 * @notice fun命名规则
 * 主页+index
 * 在主页下触发展示页的+view
 * 对数据有增删改的+action
 * ajax请求+ajax
 */
class Index extends Auth
{
    public function index()
    {
        $this->display();

    }

}
