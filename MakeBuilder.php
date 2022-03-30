<?php
    /**
     * 新增、修改保存时改变提交的信息为需要的格式[日期、时间、日期时间]
     * @param array  $formData
     * @param string $tableName
     * @return array
     */
    public function changeFormData(array $formData, string $tableName)
    {
        // 查询所有字段信息
        $fields    = self::getFields($tableName);
        $fieldsNew = [];
        foreach ($fields as $k => $v) {
            $fieldsNew[$v['field']] = $v;
        }
        foreach ($formData as $k => $v) {
            if (array_key_exists($k, $fieldsNew)) {
                // 改变日期等格式
                if ($fieldsNew[$k]['type'] == 'date' || $fieldsNew[$k]['type'] == 'time' || $fieldsNew[$k]['type'] == 'datetime') {
                    $formData[$k] = strtotime($v);
                } else if ($fieldsNew[$k]['type'] == 'password') {
                    // 密码为空则不做修改，不为空则做md5处理
                    if (empty($v)) {
                        unset($formData[$k]);
                    } else {
                        $formData[$k] = md5($v);
                    }
                } else if ($fieldsNew[$k]['type'] == 'images' || $fieldsNew[$k]['type'] == 'files') {
                    $images = [];
                    for ($i = 0; $i < count($formData[$k]); $i++) {
                        if ($formData[$k][$i]) {
                            $images[$i]['image'] = $formData[$k][$i];
                            $images[$i]['title'] = $formData[$k . '_title'][$i];
                        }
                    }
                    $formData[$k] = json_encode($images);
                } else if ($fieldsNew[$k]['type'] == 'checkbox') {
                    for ($i = 0; $i < count($formData[$k]); $i++) {
                        if ($formData[$k][$i] === '') {
                            unset($formData[$k][$i]);
                        }
                    }
                }

            } else {
                unset($formData[$k]);
            }
        }
        // 保存内容中第一张图片开始
        if (isset($formData['content']) && isset($formData['image']) && empty($formData['image'])) {
            $pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
            preg_match_all($pattern, $formData['content'], $matchContent);
            if (isset($matchContent[1][0])) {
                $formData['image'] = $matchContent[1][0];
            }
        }
        // 保存内容中第一张图片结束
        return $formData;
    }
