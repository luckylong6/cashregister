<?php

namespace app\index\controller;

use app\index\logic\Goods as GoodsLogic;
use think\facade\Request;

class Goods extends Common
{
    public function __construct(GoodsLogic $GoodsLogic)
    {
        parent::__construct();
        $this->logic = $GoodsLogic;
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
    public function goodAdd() {
        if(Request::isAjax()) {
            $param = Request::param();
            $res = $this->logic->goodAdd($param);
            if($res === false) {
                return \json(['code' => $this->logic->error()['code'], 'msg' => $this->logic->error()['msg']]);
            }
            return \json(['code' => 200, 'msg' => '操作成功']);
        }
        $good_id = Request::param('good_id');
        $good_row = $this->logic->goodRow($good_id);
        $this->assign('good_row', $good_row);
        return view('goodadd');
    }
}
