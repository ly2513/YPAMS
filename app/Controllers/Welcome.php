<?php
/**
 * User: yongli
 * Date: 17/4/21
 * Time: 10:54
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
namespace App\Controllers;

use YP\Config\Services;
use YP\Core\YP_Controller as Controller;

class Welcome extends Controller {

    public function index()
    {
        Services::log()->alert();
        p($_SERVER);
    }
    
    
}
