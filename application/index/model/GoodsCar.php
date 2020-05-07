<?php

namespace app\index\model;

use think\Db;
use think\Exception;

class GoodsCar extends Common
{
    // 展示购物车数据
    public function showOrderRow($store_id) {
        $good_list = $this->alias('gc')
        ->leftJoin('goods g', 'g.id = gc.good_id')
        ->where(['gc.store_id' => $store_id])
        ->field(['gc.good_num, gc.good_price, g.name, g.type, gc.id'])
        ->paginate(10);
        return $good_list;
    }

    public function addGoodCar($good_row, $store_id, $order_id) {
        //TODO::修改订单绑定的商品信息
        if($order_id) {

        }
        // 购物车和商品数据
        $good_car_row = $this->alias('gc')->where(['gc.good_id' => $good_row['id'], 'gc.store_id' => $store_id])-leftJoin('goods g', 'g.id=gc.good_id')->field('gc.*, g.price as good_one_price, g.inventory_num as good_one_num')->find();
        if($good_car_row['good_one_num'] <= $good_car_row['good_num']) {
            $this->error = ['code' => 403, 'msg' => "库存不够，不能添加"];
        }
        if(!empty($good_car_row)) {
            $res = $this->where(['id' => $good_car_row['id']])->update(['good_num' => $goodcar_row['good_num'] + 1, 'good_price' => $goodcar_row['good_price'] + $goodcar_row['good_one_price']]);
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

    /** 
     * 修改购物车或者订单中的商品数量或者删除商品
     * @param:array(good_change， order_id/store_id, order_bind_good_id)，good_change：reduce减少，add增加，all删除。order_id/store_id:展示的是订单表还是购物车表;order_bind_good_id:订单绑定商品id
     * @return：array('code', 'msg');
     * */
    public function changeGood($param)
    {

        Db::startTrans();
        try {
            // 查找数据是否存在
            $goodcar_row = $this->where(['id' => $param['order_bind_good_id']])->find();
            if (empty($goodcar_row)) {
                throw new Exception("数据不存在，请检查后重试！");
            }
            $good_row = Db::name('goods')->where(['id' => $goodcar_row['good_id']])->field('name, inventory_num, price,status')->find();
            if (!$good_row) {
                exception("增加的商品不存在", 0);
            }

            // $order_row = Db::name('order')->where(['id' => $param['store_id']])->field('order_sn,price, store_id, status');
            // if (!$order_row) {
            //     throw new Exception("商品的订单不存在");
            // }
            switch ($param['good_change']) {

                // 删除某个商品
                case 'all':
                // 删除商品
                $res = $this->where(['id' => $param['order_bind_good_id']])->delete();
                if (!$res) {
                    throw new Exception("商品删除失败");
                }

                // 更新订单价钱
                // $res = Db::name('order')->save(['price' => $order_row['price'] - $goodcar_row['good_price']], ['id' => $param['order_id']]);
                // if (!$res) {
                //     throw new Exception("订单金额更新失败！");
                // }
                break;

                // 增加绑定商品的数量和价钱
                case 'add':
                if ($good_row['status'] != 1) {
                    throw new Exception("增加的商品已下架，不能添加");
                }
                if ($goodcar_row['good_num'] >= $good_row['inventory_num']) {
                    throw new Exception("商品库存不足，不能添加");
                }

                // 更新商品绑定信息的数量和价钱
                $res = $this->save(['good_num' => $goodcar_row['good_num'] + 1, 'good_price' => $goodcar_row['good_price'] + $good_row['price']], ['id' => $param['order_bind_good_id']]);
                if (!$res) {
                    throw new Exception("商品更新失败");
                }

                // // 更新订单信息的价钱
                // $res = Db::name('order')->save(['price' => $order_row['price'] + $good_row['price']], ['id' => $param['order_id']]);
                // if (!$res) {
                //     throw new Exception("订单金额更新失败！");
                // }
                break;

                // 减少绑定商品的数量和价钱
                case 'reduce':
                if ($good_row['status'] != 1) {
                    throw new Exception("不能修改已下架的商品，");
                }
                if($goodcar_row['good_num'] <=0 ) {
                    exception("商品数量为0，不能再减少");
                }
                // 更新商品绑定信息的数量和价钱
                $res = $this->save(['good_num' => $goodcar_row['good_num'] - 1, 'good_price' => $goodcar_row['good_price'] - $good_row['price']], ['id' => $param['order_bind_good_id']]);
                if (!$res) {
                    throw new Exception("商品更新失败");
                }
                // 更新订单信息的价钱
                // $res = Db::name('order')->save(['price' => $order_row['price'] - $good_row['price']], ['id' => $param['order_id']]);
                // if (!$res) {
                //     throw new Exception("订单金额更新失败！");
                // }
                break;
            };

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error = ['code' => 403, 'msg' => $e->getMessage()];
            return false;
        }
    }
}
