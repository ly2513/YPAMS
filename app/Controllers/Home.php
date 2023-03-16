<?php
/**
 * User: yongli
 * Date: 17/4/24
 * Time: 15:21
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */

namespace App\Controllers;

use Admin\DriverModel;
use Admin\UserModel;
use YP\Core\YP_Controller as Controller;
use Elasticsearch\ClientBuilder;

/**
 * 框架默认控制器
 *
 * Class Home
 *
 * @package App\Controllers
 */
class Home extends Controller
{

    public function index()
    {
        if (!is_file(FRONT_PATH . 'install.lock')) { // 没有安装
            
            header("Location: /Install/Install/install");
            exit;
        } else {

        }
//        $this->display();
    }


    public function testElasticsearch()
    {
        $client   = ClientBuilder::create()->build();
        $userData = UserModel::select(['id', 'pid', 'name', 'nickname', 'phone'])->get()->toArray();
        foreach ($userData as $key => $value) {
            $params          = [];
            $params['body']  = [
                'id'       => $value['id'],
                'pid'      => $value['pid'],
                'name'     => $value['name'],
                'nickname' => $value['nickname'],
                'phone'    => $value['phone'],
            ];
            $params['index'] = 'emp_index';
            $params['type']  = 'emp_type';
            //Document will be indexed to log_index/log_type/autogenerate_id
            $status = $client->index($params);
            P($status);
        }
        echo 'create index done!';
    }

    public function search()
    {
        $para                                     = $this->request->getGet();
        $client                                   = ClientBuilder::create()->build();
        $params                                   = [];
        $params['index']                          = 'emp_index';
        $params['type']                           = 'emp_type';
        $params['body']['query']['match']['name'] = $para['q'];
        $params['body']['sort']                   = ['id' => ['order' => 'desc']];
        $params['size']                           = 3;
        $params['from']                           = 1;
        $rtn                                      = $client->search($params);
        P($rtn);
    }

    public function update()
    {
        phpinfo();

    }

    public function check()
    {


    }

}
