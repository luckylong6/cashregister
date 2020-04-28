<?php

namespace app\index\controller;

class Active extends Common{
    
    // 活动详情信息
    public function read() {
        $url = web_url('active/read/1', ['active_id' => 1]);
    }

    // 活动编辑页面信息展示
    public function edit() {

    }

    // 活动详情信息更新
    public function update() {

    } 
}