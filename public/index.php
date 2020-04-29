<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

define('APP_DEBUG', true);
define('URL_PATH', '/');

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';


// 支持事先使用静态方法设置Request对象和Config对象
defined("PUBLIC_STATIC") ?: define("PUBLIC_STATIC", "/static");
// defined('URI_PATH') ?: define('URI_PATH', '/public');
// 执行应用并响应
Container::get('app')->run()->send();
