<?php

use think\facade\Route;

$cate = \app\common\model\Cate::field('id,cate_name,cate_folder,module_id')->order('sort ASC,id ASC')->select();
foreach ($cate as $k => $v) {
    // 当栏目设置了[栏目目录]字段时注册路由
    if ($v['cate_folder']) {
        if ($v->module->getData('model_name') == 'Page') {
            Route::any('' . $v['cate_folder'] . '', '' . $v['cate_folder'] . '/index');
        } else {
            // 列表+详情模型
            /*
            index/Article/index.html?cate=5
            */
            Route::any('' . $v['cate_folder'] . '', $v['cate_folder'] . '/index');
            /*
            index/Article/info.html?cate=5&id=13
            */
            Route::any('' . $v['cate_folder'] . '/<id>', $v['cate_folder'] . '/info')->pattern(['id' => '\d+']);
        }
    }
}

// tag路由
Route::any('tag_<module>/<t>', 'Index/tag');
// 只给其中一个模块<Article>做优化
Route::any('news_<id>$', 'Article/infoArticle')->pattern(['id' => '\d+']);
