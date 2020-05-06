<?php
namespace app\index\model;

use think\Db;
class OrderBindGood extends Common{
    public function showOrderRow($order_id) {
        $order_sn = Db::name('order')->where(['id' => $order_id])->value('order_sn');
        $good_list = $this->alias('obg')
        ->leftJoin('goods g', 'g.id = obg.good_id')
        ->where(['obg.order_sn' => $order_sn])
        ->field('obg.id,obg.good_num, g.name, obg.good_price, g.type')
        ->paginate(10);
        return $good_list;
    }
}