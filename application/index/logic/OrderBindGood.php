<?php

namespace app\index\logic;

use app\index\model\OrderBindGood as OrderBindGoodModel;

class OrderBindGood
{
    public function __construct(OrderBindGoodModel $OrderBindGoodModel) 
    {   
        $this->orderbindgood = $OrderBindGoodModel;
    }
    public function error() {
        return $this->error;
    }
    public function showOrderRow($order_id) {
        $good_list = $this->orderbindgood->showOrderRow($order_id);
        return $good_list;
    }
}
