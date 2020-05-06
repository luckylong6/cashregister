<?php

namespace app\index\controller;

use app\index\logic\Order as OrderLogic;
use app\index\logic\OrderBindGood;
use app\index\logic\GoodsCar;
use think\facade\Request;

class Order extends Common
{
    public function __construct(OrderLogic $OrderLogic, OrderBindGood $orderBindGood, GoodsCar $goodsCar)
    {
        parent::__construct();
        $this->order = $OrderLogic;
        $this->orderbindgood = $orderBindGood;
        $this->goodcar = $goodsCar;
    }

    // 展示当前购物车商品
    public function showOrderRow() {
        // 判断展示的是订单数据还是购物车数据
        $param = Request::get();
        if(isset($param['order_id']) && !empty($param['order_id'])) {
            $good_list = $this->orderbindgood->showOrderRow($param['order_id']);
        }
        if(isset($param['store_id']) && !empty($param['store_id'])) {
            $good_list = $this->goodcar->showOrderRow($param['store_id']);
        }
        $this->assign('order_id', isset($param['order_id']) ? $param['order_id'] : 0);
        $this->assign('store_id', isset($param['store_id']) ? $param['store_id'] : 0);
        $this->assign('goodlist', $good_list);
        return view('order/goodcar');
    }

    
}
