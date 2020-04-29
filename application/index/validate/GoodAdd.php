<?php

namespace app\index\validate;

use think\Validate;

class GoodAdd extends Validate
{
    protected $rule = [
        'name' => 'require',
        'type' => 'require',
        'inventory_num' => 'require|egt:0',
        'price' => 'require|egt:0',
    ];
    protected $message = [
        'name.require' => '商品名称不能为空',
        'type.require' => '商品类型不能为空',
        'inventory_num.require' => '商品库存不能为空',
        'inventory_num.egt' => '商品库存不能小于0',
        'price.require' => '价格不能为空',
        'price.egt' => '价格不能小于0',
    ];
}
