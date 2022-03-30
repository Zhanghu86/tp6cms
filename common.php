<?php

// 获取详情URL
function getShowUrl($v)
{
    if ($v) {
        if (isset($v['url']) && !empty($v['url'])) {
            return $v['url'];
        }
        if (isset($v['cate_id']) && !empty($v['cate_id'])) {
            if (isset($v['cate'])) {
                $cate = $v['cate'];
            } else {
                $cate = \app\common\model\Cate::field('id,cate_folder,module_id')
                    ->where('id', $v['cate_id'])
                    ->find();
            }
            if ($cate['cate_folder']) {
                $url = (string)\think\facade\Route::buildUrl($cate['cate_folder'] . '/info', ['id' => $v['id']])->domain('');
            } else {
                if (isset($v['cate']['module'])) {
                    //Article
                    $modelName = $v['cate']['module']['model_name'];
                } else {
                    $modelName = \app\common\model\Module::where('id', $cate['module_id'])
                        ->value('model_name');
                }
                /*
                Article/info.html?cate=5&id=1
                */
                //$url = (string)\think\facade\Route::buildUrl($modelName . '/info', ['cate' => $cate['id'], 'id' => $v['id']])->domain('');

                $url = (string) url('/index/news_'.$v['id'])->domain('');


            }
        }
    }
    return $url ?? '';
}
