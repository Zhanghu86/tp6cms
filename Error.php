<?php

// 这里我们匹配所有数字到Article控制器的infoArticle方法
    public function infoArticle(string $id)
    {
        // 获取栏目ID
        $catId = getCateId();
    
        // 当前模型ID
        $this->moduleId = Cate::where('id', '=', $catId)->value('module_id');
    
        // 当前表名称
        $this->tableName = Module::where('id', '=', $this->moduleId)->value('table_name');
        // 当前模型字段列表
        $this->fields = Field::getFieldList($this->moduleId);
    
        if (empty($catId)) {
            $this->error('未找到对应栏目');
        }
        // 获取栏目信息
        $cate = Cms::getCateInfo($catId);
        // 更新点击数
        Cms::addHits($id, $this->tableName);
        // 查找内容详情
        $info = Cms::getInfo($id, $this->tableName);
        // 跳转
        if (isset($info['url']) && !empty($info['url'])) {
            return redirect($info['url']);
        }
        // 当前地址
        $info['url'] = getShowUrl($info);
        // tdk
        $tdk = Cms::getInfoTdk($info, $cate, $this->system);
        // 模板
        $template = Cms::getInfoView($info, $cate, $this->tableName);
    
        $view = [
            'cate'        => $cate,         // 栏目信息
            'fields'      => $this->fields, // 字段列表
            'info'        => $info,         // 详情信息
            'system'      => $this->system, // 系统信息
            'public'      => $this->public, // 公共目录
            'title'       => $tdk['title'],
            'keywords'    => $tdk['keywords'],
            'description' => $tdk['description'],
        ];
    
        View::assign($view);
        return View::fetch($template);
    }
