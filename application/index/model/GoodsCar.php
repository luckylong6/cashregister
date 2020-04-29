<?php

namespace app\index\model;

class GoodsCar extends Common
{
    // 展示购物车数据
    public function showOrderRow($store_id) {
        $good_list = $this->alias('gc')
        ->leftJoin('goods g', 'g.id = gc.good_id')
        ->where(['gc.store_id' => $store_id])
        ->field(['gc.good_num, gc.good_price, g.name'])
        ->paginate(10);
        return $good_list;
    }

    public function addGoodCar($good_row, $store_id) {
        $good_car_row = $this->where(['good_id' => $good_row['id'], 'store_id' => $store_id])->find();
        if(!empty($good_car_row)) {
            $res = $this->where(['id' => $good_car_row['id']])->setInc('good_num');
        } else {
            $good_car_data = [
                'store_id' => $store_id,
                'good_id' => $good_row['id'],
                'good_num' => 1,
                'good_price' => $good_row['price'],
                'create_time' => time(),
            ];
            $res = $this->create($good_car_data);
        }
        if(!$res) {
            $this->error = ['code' => 406, 'msg' => '添加失败，请刷新后重试'];
            return false;
        }
    }    
}
