<?php

namespace app\index\model;

use think\facade\Request;

class Goods extends Common
{
    // 展示商品
    public function goodList($param)
    {
        $db = $this->alias('g');
        // 筛选商品类型
        if (isset($param['good_type']) && !empty($param['good_type'])) {
            $db->where(['g.type' => $param['good_type']]);
        }
        // 筛选商品状态
        if (isset($param['good_status']) && !empty($param['good_status'])) {
            $db->where(['g.status' => $param['good_status']]);
        }
        $good_list = $db->order(['id' => 'desc'])->paginate(10);
        $page = $good_list->render();
        $good_list = $good_list->toArray()['data'];
        return [
            'goodlist' => $good_list,
            'page' => $page,
        ];
    }

    // 更新商品
    public function goodUpdate($good_data, $good_id)
    {
        $good_row = $this->goodRow($good_id);
        if (empty($good_row)) {
            $this->error = ['code' => 404, 'msg' => '商品不存在请检查后重试！'];
            return false;
        }
        // unset($good_data['id']);
        $res = $this->save($good_data, ['id' => $good_id]);
        // $res = $this->save($good_data, ['id' => $good_id]);
        if (!$res) {
            $this->error = ['code' => 406, 'msg' => '更新失败,请稍后重试！'];
            return false;
        }
    }

    // 添加商品
    public function goodAdd($good_data)
    {
        $good_data['create_time'] = time();
        $res = $this->create($good_data);
        if (!$res) {
            $this->error = ['code' => 406, 'msg' => '添加成功！'];
            return false;
        }
    }

    // 单组数据
    public function goodRow($good_id) {
        $row = $this->where(['id' => $good_id])->field('id, name, type, inventory_num, price, status')->find();
        return $row;
    }
}
