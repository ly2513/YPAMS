<?php
/**
 * User: yongli
 * Date: 17/4/28
 * Time: 16:37
 * Email: yong.li@szypwl.com
 * Copyright: 深圳优品未来科技有限公司
 */
use Config\Services;

if (!function_exists('call_back')) {
    /**
     * 接口返回函数
     *
     * @param int    $errCode
     * @param array  $data
     * @param string $msg
     */
    function call_back($errCode = 0, $data = [], $msg = '')
    {
        $errorResult = Services::error()->getAllError();
        $msg         = $msg ??  $errorResult[$errCode];
        $data        = [
            'code' => $errCode,
            'data' => $data ?? [],
            'msg'  => $msg,
        ];
        echo json_encode($data);
        die();
    }
}
/**
 * 设置分页
 *
 * @param     $row          总条数
 * @param     $url          跳转链接
 * @param     $uri_segment  当前页码
 * @param int $per_page     每页显示多少条
 *
 * @return mixed
 */
function set_page_config($row, $url, $uri_segment, $per_page = 10)
{
    $config['base_url']          = $url;
    $config['total_rows']        = $row;
    $config['per_page']          = $per_page;//每页显示多少条
    $config['uri_segment']       = $uri_segment;
    $config['num_links']         = 2;//数量链接
    $config['page_query_string'] = true;
    $config['full_tag_open']     = '<ul class="pagination">';
    $config['full_tag_close']    = '</ul>';
    $config['first_link']        = '首页';
    $config['first_tag_open']    = '<li class="pre">';
    $config['first_tag_close']   = '</li>';
    $config['last_link']         = '最后一页';
    $config['last_tag_open']     = '<li>';
    $config['last_tag_close']    = '</li>';
    $config['next_link']         = '下一页';
    $config['next_tag_open']     = '<li class="next">';//下一页
    $config['next_tag_close']    = '</li>';
    $config['prev_link']         = '上一页';
    $config['prev_tag_open']     = '<li>';
    $config['prev_tag_close']    = '</li>';
    $config['cur_tag_open']      = '<li class="active"><a>';//当前页
    $config['cur_tag_close']     = '</a></li>';
    $config['num_tag_open']      = '<li class="num">';
    $config['num_tag_close']     = '</li>';
    $config['use_page_numbers']  = true;

    return $config;
}

/**
 * 并行查询 Post
 *
 * @param     $url_array
 * @param int $wait_usec
 *
 * @return array|bool
 */
function multi_curl_post($url_array, $wait_usec = 0)
{
    if (!is_array($url_array)) {
        return false;
    }
    $wait_usec = intval($wait_usec);
    $data      = [];
    $handle    = [];
    $running   = 0;
    $mh        = curl_multi_init(); // multi curl handler
    $i         = 0;
    foreach ($url_array as $url_info) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_info['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $url_info['data']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($url_info['data']),
        ]);
        // 把 curl resource 放进 multi curl handler 里
        curl_multi_add_handle($mh, $ch);
        $handle[$i++] = $ch;
    }
    // 执行
    do {
        curl_multi_exec($mh, $running);
        if ($wait_usec > 0) { // 每个 connect 要间隔多久
            usleep($wait_usec); // 250000 = 0.25 sec
        }
    } while ($running > 0);
    // 读取资料
    foreach ($handle as $i => $ch) {
        $content  = curl_multi_getcontent($ch);
        $data[$i] = (curl_errno($ch) == 0) ? $content : false;
    }
    // 移除 handle
    foreach ($handle as $ch) {
        curl_multi_remove_handle($mh, $ch);
    }
    curl_multi_close($mh);

    return $data;
}

/**
 * 生成随机字符串
 *
 * @param $length
 *
 * @return string
 */
function random($length)
{
    $hash  = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $max   = strlen($chars) - 1;
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }

    return $hash;
}

/**
 * 获取指定长度的随机密码
 *
 * @param int $length
 *
 * @return string
 */
function get_password($length = 6)
{
    $str = substr(md5(time()), 0, $length);

    return $str;
}

/**
 * 根据多维数组中的某个值 直接重构数据结构
 *
 * @param array $data
 * @param       $key
 *
 * @return array
 *
 * @TODO 目前只支持二维数组
 */
function array_flatten_key(array $data, $key)
{
    $finalData = [];
    foreach ($data as $val) {
        $k             = isset($val[$key]) ? $val[$key] : '';
        $finalData[$k] = $val;
    }

    return $finalData;
}

/**
 * 获得两个日期之间的连续日期
 *
 * @param $startDate    开始日期
 * @param $endDate      结束日期
 *
 * @return array
 */
function get_date_range($startDate, $endDate)
{
    $dateArr = [];
    // 开始时间戳
    $dt_start = strtotime($startDate);
    // 结束时间戳
    $dt_end = strtotime($endDate);
    array_push($dateArr, $startDate);
    while ($dt_start < $dt_end) {
        $dt_start = strtotime('+1 day', $dt_start);
        $dateTmp  = date('Y-m-d', $dt_start);
        array_push($dateArr, $dateTmp);
    }

    return array_unique($dateArr);
}

/**
 * 获得连续的年份
 *
 * @param $startDate  开始日期
 * @param $endDate    结束日期
 *
 * @return array
 */
function get_year_range($startDate, $endDate)
{
    $yearArr = [];
    // 开始时间戳
    $dt_start = strtotime($startDate);
    // 结束时间戳
    $dt_end = strtotime($endDate);
    if ($dt_start > $dt_end) {
        $date     = $dt_start;
        $dt_start = $dt_end;
        $dt_end   = $date;
    }
    // 开始时间
    $start_year = intval(date('Y', $dt_start));
    // 结束时间
    $end_year = intval(date('Y', $dt_end));
    while ($start_year <= $end_year) {
        array_push($yearArr, $start_year);
        $start_year += 1;
    }

    return array_unique($yearArr);
}

/**
 * 获得连续的年月
 *
 * @param $startDate  开始日期
 * @param $endDate    结束日期
 *
 * @return array
 */
function get_year_month($startDate, $endDate)
{
    $dateArr = [];
    // 开始时间戳
    $dt_start = strtotime($startDate);
    // 结束时间戳
    $dt_end = strtotime($endDate);
    array_push($dateArr, date('Y-m', $dt_start));
    while ($dt_start < $dt_end) {
        $dt_start = strtotime('+1 day', $dt_start);
        $dateTmp  = date('Y-m', $dt_start);
        array_push($dateArr, $dateTmp);
    }

    return array_values(array_unique($dateArr));
}

/**
 * 获得连续的季度数据
 *
 * @param $startDate  开始日期
 * @param $endDate    结束日期
 *
 * @return array
 */
function get_quarter_range($startDate, $endDate)
{
    $quarter = [];
    $dateArr = get_year_month($startDate, $endDate);
    foreach ($dateArr as $value) {
        list($year, $month) = explode('-', $value);
        $quarterValue   = ceil($month / 3);
        $yearQuarterStr = $year . '-' . $quarterValue;
        array_push($quarter, $yearQuarterStr);
    }

    return array_values(array_unique($quarter));
}

/**
 * 将数据格式化成树形结构
 *
 * @param         $items
 * @param  string $id
 * @param  string $pid
 * @param  string $child
 *
 * @return array
 */
function get_tree($items, $id = 'id', $pid = 'pid', $child = 'children')
{
    $tree = []; //格式化好的树
    foreach ($items as $item) {
        if (isset($items[$item[$pid]])) {
            $items[$item[$pid]][$child][] = &$items[$item[$id]];
        } else {
            $tree[] = &$items[$item[$id]];
        }
    }

    return $tree;

}
