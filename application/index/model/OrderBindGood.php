<?php

namespace app\index\model;

use think\Db;
use think\Exception;

class OrderBindGood extends Common
{
	public function showOrderRow($order_id)
	{
		$order_sn = Db::name('order')->where(['id' => $order_id])->value('order_sn');
		$good_list = $this->alias('obg')
		->leftJoin('goods g', 'g.id = obg.good_id')
		->where(['obg.order_sn' => $order_sn])
		->field('obg.id,obg.good_num, g.name, obg.good_price, g.type')
		->paginate(10);
		return $good_list;
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
			$bind_good_row = $this->where(['id' => $param['order_bind_good_id']])->find();
			if (empty($bind_good_row)) {
				throw new Exception("数据不存在，请检查后重试！");
			}
			$good_row = Db::name('goods')->where(['id' => $bind_good_row['good_id']])->field('name, inventory_num, price');
			if (!$good_row) {
				throw new Exception("增加的商品不存在");
			}

			$order_row = Db::name('order')->where(['id' => $param['order_id']])->field('order_sn,price, store_id, status');
			if (!$order_row) {
				throw new Exception("商品的订单不存在");
			}
			switch ($param['good_change']) {

				// 删除某个商品
				case 'all':
				// 删除商品
				$res = $this->where(['id' => $param['order_bind_good_id']])->delete();
				if (!$res) {
					throw new Exception("商品绑定失败");
				}

				// 更新订单价钱
				$res = Db::name('order')->save(['price' => $order_row['price'] - $bind_good_row['good_price']], ['id' => $param['order_id']]);
				if (!$res) {
					throw new Exception("订单金额更新失败！");
				}
				break;

				// 增加绑定商品的数量和价钱
				case 'add':
				if ($good_row['status'] != 1) {
					throw new Exception("增加的商品已下架，不能添加");
				}
				if ($bind_good_row['good_num'] >= $good_row['inventory_num']) {
					throw new Exception("商品库存不足，不能添加");
				}

				// 更新商品绑定信息的数量和价钱
				$res = $this->save(['good_num' => $bind_good_row['good_num'] + 1, 'good_price' => $bind_good_row['good_price'] + $good_row['price']], ['id' => $param['order_bind_good_id']]);
				if (!$res) {
					throw new Exception("商品更新失败");
				}

				// 更新订单信息的价钱
				$res = Db::name('order')->save(['price' => $order_row['price'] + $good_row['price']], ['id' => $param['order_id']]);
				if (!$res) {
					throw new Exception("订单金额更新失败！");
				}
				break;

				// 减少绑定商品的数量和价钱
				case 'reduce':
				if ($good_row['status'] != 1) {
					throw new Exception("不能修改已下架的商品，");
				}
				if($bind_good_row['good_num'] <=0 ) {
					throw new Exception("商品数量为0，不能再减少");
				}

				// 更新商品绑定信息的数量和价钱
				$res = $this->save(['good_num' => $bind_good_row['good_num'] - 1, 'good_price' => $bind_good_row['good_price'] - $good_row['price']], ['id' => $param['order_bind_good_id']]);
				if (!$res) {
					throw new Exception("商品更新失败");
				}
					// 更新订单信息的价钱
				$res = Db::name('order')->save(['price' => $order_row['price'] - $good_row['price']], ['id' => $param['order_id']]);
				if (!$res) {
					throw new Exception("订单金额更新失败！");
				}
				break;
			}

			Db::commit();
		} catch (Exception $e) {
			Db::rollback();
			return \json(['code' => 403, 'msg' => $e->getMessage()]);
		}
		return \json(['code' => 200]);
	}
}
