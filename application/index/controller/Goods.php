<?php

namespace app\index\controller;

use app\index\logic\Goods as GoodsLogic;
use think\facade\Request;
use app\index\logic\GoodsCar;

class Goods extends Common
{
    public function __construct(GoodsLogic $GoodsLogic, GoodsCar $goodsCar)
    {
        parent::__construct();
        $this->logic = $GoodsLogic;
        $this->goodcar = $goodsCar;
    }
    // 商品列表
    public function goodList()
    {
        // $GoodsLogic = new GoodsLogic();
        $param = Request::param();
        $list = $this->logic->goodList([
            'good_type' => isset($param['good_type']),
            'good_status' => isset($param['good_status']),
        ]);
        $this->assign('goodlist', $list['goodlist']);
        $this->assign('page', $list['page']);
        return view('goods/goodlist');
    }

    // 商品添加或者更新
    public function goodAdd()
    {
        if (Request::isAjax()) {
            $param = Request::param();
            $res = $this->logic->goodAdd($param);
            if ($res === false) {
                return \json(['code' => $this->logic->error()['code'], 'msg' => $this->logic->error()['msg']]);
            }
            return \json(['code' => 200, 'msg' => '操作成功']);
        }
        $good_id = Request::param('good_id');
        $good_row = $this->logic->goodRow($good_id);
        $this->assign('good_row', $good_row);
        return view('goodadd');
    }

    // 商品添加到购物车
    public function addGoodCar()
    {
        $good_id = Request::post('good_id');
        $param = Request::post();
        $store_id = 1;
        $store_id = isset($param['order_id']) ? 0: $store_id;
        $order_id = isset($param['order_id']) ? $param['order_id'] : 0;
        $res = $this->goodcar->addGoodCar($good_id, $store_id, $order_id);
        if ($res === false) {
            return \json(['code' => $this->goodcar->error()['code'], 'msg' => $this->goodcar->error()['msg']]);
        }
        return \json(['code' => 200, 'msg' => '添加成功']);
    }

    // 修改购物车或者订单的商品信息
    public function changeGood() {
        $param = Request::param();
        $res = $this->logic->changeGood($param);
        if($res === false) {
            return \json(['code' => $this->logic->error()['code'], 'msg' => $this->logic->error()['msg']]);
        }
        return \json(['code' => 200, 'msg' => '修改成功']);
    }
}
