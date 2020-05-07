<?php

namespace app\index\logic;

use app\index\model\Goods as GoodsModel;
use app\index\model\GoodsCar;
use app\index\model\OrderBindGood as OrderBindGoodModel;

class Goods
{
    protected $goodsCar,$GoodsModel,$OrderBindGood;
    public function __construct(GoodsModel $GoodsModel, GoodsCar $goodsCar, OrderBindGoodModel $OrderBindGood)
    {
        $this->model = $GoodsModel;
        $this->goodscar = $goodsCar;
        $this->orderbindgood = $OrderBindGood;
    }
    public function error()
    {
        return $this->error;
    }
    // 商品列表
    public function goodList($param)
    {
        // $GoodsModel = new GoodsModel();
        // if(isset($param['type']) && $param['type']) {
        //     return \json(['code' => 403, 'msg' => '类型参数有误！'])->send();
        // }
        $list = $this->model->goodList($param);
        return $list;
    }

    // 添加或者更新商品
    public function goodAdd($param)
    {
        $validate = validate("GoodAdd");
        if (!$validate->check($param)) {
            $this->error = ['code' => 403, 'msg' => $validate->getError()];
            return false;
        }
        $data = $this->goodAddValidate($param);
        if ($data === false) {
            return false;
        }
        // 商品更新
        if (isset($param['id']) && !empty($param['id'])) {
            $res = $this->model->goodUpdate($data, $param['id']);
        } else {
            // 商品增加
            $res = $this->model->goodAdd($data);
        }
        if ($res === false) {
            $this->error = ['code' => $this->model->getError()['code'], 'msg' => $this->model->getError()['msg']];
            return false;
        }
    }

    // 组装数据
    private function goodAddValidate($param)
    {
        // if ($param['type']) {
        //     $this->error = ['code' => 403, 'msg' => '商品类型不正确！'];
        //     return false;
        // }
        $data = [
            'name' => $param['name'],
            'type' => $param['type'],
            'inventory_num' => $param['inventory_num'],
            'price' => $param['price'],
            'status' => $param['status'],
        ];
        // if(isset($param['id']) && !empty($param['id'])) {
        //     $data = ['id' => $param['id']] + $data;
        // }
        return $data;
    }

    // 单组数据
    public function goodRow($good_id)
    {
        $row = $this->model->goodRow($good_id);
        return $row;
    }

   /** 
     * 修改购物车或者订单中的商品数量或者删除商品
     * @param:array(good_change， order_id/store_id, order_bind_good_id)，good_change：reduce减少，add增加，all删除。order_id/store_id:展示的是订单表还是购物车表;order_bind_good_id:订单绑定商品id
     * @return：array('code', 'msg');
     * */
    public function changeGood($param)
    {
        if (isset($param['order_id']) && !empty($param['order_id'])) {

            $res = $this->orderbindgood->changeGood($param);
            if ($res === false) {
                $this->error = ['code' => $this->orderbindgood->getError()['code'], 'msg' => $this->orderbindgood->getError()['msg']];
                return false;
            }
        }
        if (isset($param['store_id']) && !empty($param['store_id'])) {
            $res = $this->goodscar->changeGood($param);
            if ($res === false) {
                $this->error = ['code' => $this->goodscar->getError()['code'], 'msg' => $this->goodscar->getError()['msg']];
                return false;
            }
        }
    }
}
