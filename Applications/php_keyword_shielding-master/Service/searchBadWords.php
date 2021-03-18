<?php

/*
 * DFA查找、匹配关键字
 */

/**
 * Description of searchBadWords
 *
 * @author zhengbaowow
 */
class searchBadWords {
    private $map=null;
    
    public function __construct($map) {
        $this->map=$map;
    }
    /**
     * 返回匹配到的关键词
     * @param type $string
     * @return array
     */
    public function search($string)
    {
        $len = mb_strlen($string);
        $tmp = $this->map;
        $map = $this->map;
        $str = '';
        $result = [];
        for ($i = 0; $i < $len; $i++) {
            $nowWord = mb_substr($string, $i, 1);
            $nowMap = $map->get($nowWord);
            if (!is_null($nowMap)) {
                $str .= $nowWord;
                if ($nowMap->get('isEnd')) {
                    array_push($result, $str);
                    $str = '';
                    $map = $tmp;
                } else {
                    $map = $nowMap;
                }
            } else {
                //第一次匹配失败，取消上一次匹配，重置进入下一次匹配。
                if (!empty($str)) {
                    $i--;
                }   
                $str = '';
                $map = $tmp;
            }
        }
        return $result;
    }
    /**
     * 忽略所有(空格,`,~,#,$,^,&,*,_),并且自动将敏感词更换为*
     * @param type $string
     * @return type
     */
    public function findAndHideKeyWords($string) {
        //去除无意义字符
        $string=preg_replace("/[ ,`,~,#,$,^,&,*,_]/","",$string);
        $len = mb_strlen($string);
        $tmp = $this->map;
        $map = $this->map;
        $outString='';
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $nowWord = mb_substr($string, $i, 1);
            $nowMap = $map->get($nowWord);
            if (!is_null($nowMap)) {
                $str .= $nowWord;
                if ($nowMap->get('isEnd')) {
                    //进行替换
                    $tmp_s=$this->calCountString($str);
                    $outString.=$tmp_s;
                    $str = '';
                    $map = $tmp;
                } else {
                    $map = $nowMap;
                }
            } else {
                //第一次匹配失败，取消上一次匹配，重置进入下一次匹配。
                if (!empty($str)) {
                    $i--;
                    $outString.=$str;
                }else{
                    $outString.=$nowWord;
                }
                $str = '';
                $map = $tmp;
            }
        }
        return $outString;
    }
    /**
     * 计算要多少个**
     * @param type $str
     */
    private function calCountString($str) {
        $len_s=mb_strlen($str);
        $tmp_s='';
        for ($j=0;$j<$len_s;$j++) {
            $tmp_s.="*";
        }
        return $tmp_s;
    }
}
