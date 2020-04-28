<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/** 
 * @param $url :路由地址
 * @param $param :地址参数
 * @return string
*/
function web_url($route, $param=[]) {

    $url = URL_PATH . 'index.php';
    foreach($param as $key=>$val) {
        $url .= "&$key=$val";
    }
    $result_url = $url . "?s=$route";

    return $result_url;
}