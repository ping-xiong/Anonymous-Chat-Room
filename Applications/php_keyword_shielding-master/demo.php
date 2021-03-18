<?php
//这是个demo，详细介绍见：shildDirtyWords.php

//引入屏蔽处理类
include_once __DIR__.'/shildDirtyWords.php';

$testWords="好圣女峰，你个大s #b，全部去吃^ 屎cs  b吧！";
echo "before:";
var_dump($testWords);
echo "<br/>after:";
//调用屏蔽函数，返回屏蔽后的内容
var_dump(shildDirtyWords::findAndHideKeyWords($testWords));