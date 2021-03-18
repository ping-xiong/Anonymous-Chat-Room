# keyword_shielding
PHP敏感词过滤，基于DFA敏感词过滤算法，忽略(空格,`,~,#,$,^,&amp;,*,_),并且自动将敏感词更换为\*

如果大家觉得好用，请给我一颗星星鼓励，谢谢！

 * 敏感词过滤，基于DFA敏感词过滤算法，大字典的时候比较高效。
 * 使用只需要将本文件引入，然后再调用shildDirtyWords::findAndHideKeyWords($testWords)即可
 * 如果使用shildDirtyWords::findAndHideKeyWords($testWords)来屏蔽，会有比较好的屏蔽效果，
 * 这个是对字典的补充，忽略了一些无意义的词，但也可能造成语义的一点曲解。注意斟酌使用
 * 需要及时更新敏感词字典，敏感词字典在/Resources/BadWord.txt
 -----------------------------------------------------------
DEMO举例

 * 引入屏蔽处理类
## include_once __DIR__.'/shildDirtyWords.php';
 * 要处理的对话内容
## $testWords="好圣女峰，你个大s #b，全部去吃^ 屎cs  b吧！";
 * 调用屏蔽函数，返回屏蔽后的内容
## var_dump(shildDirtyWords::findAndHideKeyWords($testWords));
