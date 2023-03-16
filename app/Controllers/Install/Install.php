<?php
/**
 * Created by IntelliJ IDEA.
 *
 * @Author: yongli
 * @Date  : 2022/8/22
 * @Time  : 10:18 上午
 */

namespace App\Controllers\Install;

use YP\Core\YP_Controller;

class Install extends YP_Controller
{

    public function install()
    {
        $action = $this->request->getGet('a');
        $action = $action ?? 'copyright';
        $this->assign('action', $action);
        if ($action == 'copyright') { // 版权信息
            $this->assign('title', 'YPAMS 版权声明');
            $this->display('copyright');
        } else if ($action == 'environment') { // 环境检测
            $result = $this->__checkEnvironment();
            $this->assign('title', '安装环境检测');
            $this->assign('env', $result);
            $this->display('environment');
        } else if ($action == 'database') { // 数据库配置
            $this->assign('title', 'YPAMS 数据库配置');
            // 连接mysql


            // 写入数据








            $this->display('database');
        } else if ($action == 'finish') { // 完成安装
            $this->assign('title', '安装完成');
            $this->display('finish');
        }

    }


    private function __checkEnvironment(){
        //系统信息
        $data['PHP_OS']              = PHP_OS;
        $data['SERVER_SOFTWARE']     = $_SERVER['SERVER_SOFTWARE'];
        $data['PHP_VERSION']         = PHP_VERSION;
        $data['upload_max_filesize'] = get_cfg_var( 'upload_max_filesize' ) ??  '不允许上传附件';
        $data['max_execution_time']  = get_cfg_var( 'max_execution_time' ) . '秒 ';
        $data['memory_limit']        = get_cfg_var( "memory_limit" ) ? get_cfg_var( 'memory_limit' ) : '0';
        //运行环境
        $data['h_PHP_VERSION'] = PHP_VERSION;
        $data['h_mysql']       = extension_loaded( 'mysqli' ) ? '<i class="fa fa-check-circle fa-1x" style="color:#3c763d"></i>' : '<i class="fa fa-times-circle" style="color:#a94442"></i>';
        $data['h_Pdo']         = extension_loaded( 'Pdo' ) ? '<i class="fa fa-check-circle fa-1x" style="color:#3c763d"></i>' : '<i class="fa fa-times-circle" style="color:#a94442"></i>';
        $data['h_Gd']          = extension_loaded( 'Gd' ) ? '<i class="fa fa-check-circle fa-1x" style="color:#3c763d"></i>' : '<i class="fa fa-times-circle" style="color:#a94442"></i>';
        $data['h_curl']        = extension_loaded( 'curl' ) ? '<i class="fa fa-check-circle fa-1x" style="color:#3c763d"></i>' : '<i class="fa fa-times-circle" style="color:#a94442"></i>';
        $data['h_openSSL']     = extension_loaded( 'openSSL' ) ? '<i class="fa fa-check-circle fa-1x" style="color:#3c763d"></i>' : '<i class="fa fa-times-circle" style="color:#a94442"></i>';
        //目录状态
        $data['d_root'] = is_writable( '.' ) ? '<i class="fa fa-check-circle fa-1x" style="color:#3c763d"></i>' : '<i class="fa fa-times-circle" style="color:#a94442"></i>';
        return $data;
    }
}