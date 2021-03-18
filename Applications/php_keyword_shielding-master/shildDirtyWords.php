<?php

/*
 * 敏感词过滤，基于DFA敏感词过滤算法，大字典的时候比较高效。
 * 使用只需要将本文件引入，然后再调用shildDirtyWords::findAndHideKeyWords($testWords)即可
 * 如果使用shildDirtyWords::findAndHideKeyWords($testWords)来屏蔽，会有比较好的屏蔽效果，
 * 这个是对字典的补充，忽略了一些无意义的词，但也可能造成语义的一点曲解。注意斟酌使用
 * 需要及时更新敏感词字典，敏感词字典在/Resources/BadWord.txt
 */
$localfile = __DIR__;
require_once $localfile . '/Tools/ToTree.php';
require_once $localfile . '/Service/searchBadWords.php';
/**
 * 屏蔽关键词
 *
 * @author zhengbaowow
 * @date 2018-12-27
 */
class shildDirtyWords {
    private static $badWordsUrl=__DIR__.'/Resources/BadWord.txt';
    /**
     * 忽略所有(空格,`,~,#,$,^,&,*,_),并且自动将敏感词更换为*
     * @param type $needToCheckWords
     * @return type
     */
    public static function findAndHideKeyWords($needToCheckWords) {
        //格式化关键词脏话字典为DFA map树
        $treeModel = new ToTree(self::$badWordsUrl);
        //获取map进行关键词匹配
        $searchModel=new searchBadWords($treeModel->getMap());
        return $searchModel->findAndHideKeyWords($needToCheckWords);
    }
}
