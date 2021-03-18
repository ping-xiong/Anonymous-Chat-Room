<?php

/*
 * 关键词屏蔽
 */

/**
 * Description of badwords
 *
 * @author zhengbaowow
 */
class ToTree {

    public $map = null;
    public $fireUrl = null;

    public function __construct($fireUrl) {
        $this->fireUrl = $fireUrl;
    }

    /**
     * 将分行文本转化为数组
     * @param type $file
     * @return type
     */
    private function toArr() {
        $file = $this->fireUrl;
        $fileArr = file($file);
        foreach ($fileArr as $key => $value) {
            $lenth = mb_strlen($value);
            $new = array();
            for ($i = 0; $i < $lenth; $i++) {
                $tmp_i = mb_substr($value, $i, 1);
                if (empty($tmp_i)) {
                    continue;
                } else {
                    $new[] = $tmp_i;
                }
            }
            array_pop($new);
            array_pop($new);
            $fileArr[$key] = $new;
        }
        return $fileArr;
    }

    /**
     * 将数组转化为map树
     */
    public function getMap() {
        $arr = $this->toArr();
        foreach ($arr as $key => $word) {
            $len = count($word);
            if (is_null($this->map)) {
                $map = new MyMap();
                $map->put('isEnd', 0);
            } else {
                $map = $this->map;
            }
            $tmp = $map;
            foreach ($word as $key_w => $nowWord) {
                $nowMap = $map->get($nowWord);
                if (!is_null($nowMap)) {
                    $map = $nowMap;
                } else {
                    $newMap = new MyMap();
                    $newMap->put('isEnd', 0);
                    $map->put($nowWord, $newMap);
                    $map = $newMap;
                }
                if ($key_w == ($len - 1)) {
                    $map->put('isEnd', 1);
                }
            }
            $this->map = $tmp;
        }
        return $this->map;
    }

}

/**
 * 敏感词map数据结构
 */
class MyMap {

    public function get($key) {
        return isset($this->$key) ? $this->$key : null;
    }

    public function put($key, $value) {
        $this->$key = $value;
    }

}
