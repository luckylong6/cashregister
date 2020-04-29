<?php

namespace app\index\logic;

use app\index\model\Order as OrderModel;
class Order
{
    public function __construct(OrderModel $ordermodel) 
    {   
        $this->order = $ordermodel;
    }
    public function error() {
        return $this->error;
    }
}
