<?php
namespace app\index\logic;

use app\index\model\GoodsCar as GoodsCarModel;
use app\index\logic\Goods;

class GoodsCar{
    public function __construct(GoodsCarModel $goodsCar, Goods $goods) 
    {
        $this->goodcar = $goodsCar;
        $this->goods = $goods;
    }

    public function error() {
        return $this->error;
    }

    // 展示购物车的数据
    public function showOrderRow($store_id) {
        $goodlist = $this->goodcar->showOrderRow($store_id);
        return $goodlist;
    }

    // 添加商品到购物车
    public function addGoodCar($good_id, $store_id, $order_id) {
        $good_row = $this->goods->goodRow($good_id);
        $res = $this->goodcar->addGoodCar($good_row, $store_id, $order_id);
        if($res === false) {
            $this->error = ['code' => $this->goodcar->getError()['code'], 'msg' => $this->goodcar->getError()['msg']];
            return false;
        }
    }
    
}