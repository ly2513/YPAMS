<?php
/**
 * User: yongli
 * Date: 18/5/6
 * Time: 10:49
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers\Admin;

use YP\Core\YP_Controller;

/**
 * 前端框架
 *
 * Class Html
 *
 * @package App\Controllers
 */
class Html extends YP_Controller
{
    public function index()
    {
        $this->display();
    }
}
