<?php


class config
{
    private static $config = [
        // 数据库地址
        'mysql_host' => '127.0.0.1',
        // 数据库端口
        'mysql_port' => 3306,
        // 数据库用户名
        'mysql_username' => 'root',
        // 数据库密码
        'mysql_password' => '',
        // 数据库名字
        'mysql_db' => 'chat',
        // 网站域名，此处填写部署的客户端连接，也就是说打开下面的链接要能够访问网站。必填，否则会影响聊天头像的显示。
        'website' => 'http://pingxonline.com',


        // 下面是聊天设置

        // 是否开启主题，默认为true，关闭主题则填写false
        'enable_topic' => true,

        // 随机聊天时间限制，单位为秒，如果不想随机就把两个数字设置为一样
        'max_time' => 360,  // 最高360秒
        'min_time' => 180, // 最低180秒

        // 是否在匹配的时候现在当前正在匹配的人数
        'show_matching' => true,

        // 是否开启敏感文本过滤，去除脏话，敏感词，插件地址：https://github.com/ilovehuahua/php_keyword_shielding
        'fitter_words' => false
    ];

    /**
     * 获取配置
     * @param $key
     * @return mixed
     */
    public static function getConfig($key){
        return self::$config[$key];
    }

}
