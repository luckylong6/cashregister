<?php

namespace app\index\logic;

use app\index\model\Order as OrderModel;
use app\index\model\GoodsCar;

class Order
{
    public function __construct(OrderModel $ordermodel, GoodsCar $goodsCar)
    {
        $this->order = $ordermodel;
        $this->goodscar = $goodsCar;
    }
    public function error()
    {
        return $this->error;
    }

    
}
