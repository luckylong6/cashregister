<?php

namespace app\index\logic;

use app\index\model\Goods as GoodsModel;
use think\Validate;

class Goods
{
    public function __construct(GoodsModel $GoodsModel)
    {
        $this->model = $GoodsModel;
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
}
