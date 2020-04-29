<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::pattern([
    'active_id' => '\d+',
]);
Route::get('/', 'Index/index');
Route::group('active', function() {
    Route::get('read/:active_id', 'Active/read');
});

Route::group('good', function() {   
    Route::get('List', 'Goods/goodList')->name('商品列表'); // 商品列表
    Route::post('goodAdd', 'Goods/goodAdd')->name('商品添加'); // 商品添加或修改
    Route::post('goodDelete', 'Goods/goodDelete')->name('商品删除'); // 商品删除
});

return [

];
