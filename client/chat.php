<?php
    $rows = [];
    $room = null;
    if(isset($_GET['room']) && !empty($_GET['room'])){

        include_once "connect.php";
        $db = new connectDataBase();

        $room_id = $db->test_input($_GET['room']);

        if (preg_match("/[\w.]+/", $room_id, $matches)){
            $sql = $db->link->prepare("SELECT * FROM `room` WHERE `room_id` = ?");
            $sql->bind_param('s', $matches[0]);
            $sql->execute();
            $room = $sql->get_result()->fetch_assoc();
            if (!empty($room)){
                // 获取聊天记录
                $sql = $db->link->prepare("SELECT * FROM `chat` WHERE `room_id` = ?");
                $sql->bind_param('s', $matches[0]);
                $sql->execute();
                $chats = $sql->get_result()->fetch_all(1);
            }else{
                die("房间不存在");
            }
        }
    }else{
        die("参数缺失");
    }
?>


<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="扩列神器！在规定的时间内，假装对方是你的情侣，开始匿名聊天吧！">
    <meta name="keywords" content="情侣">
    <meta name="aplus-terminal" content="1">
    <meta name="apple-mobile-web-app-title" content="">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="format-detection" content="telephone=no, address=no">
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <title>聊天记录查询</title>

    <link rel="stylesheet" href="assets/css/amazeui.min.css">
    <link href="https://cdn.bootcss.com/animate.css/3.5.2/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css?date=1215">
</head>
<body style="background: none">

<header data-am-widget="header"
        class="am-header am-header-default am-header-fixed">
    <div class="am-header-left am-header-nav">
        <a href="#" class="leave-room" onclick="leave_room()">

        </a>
    </div>

    <h1 class="am-header-title">
        <a href="javascript: void(0)">
            聊天记录
        </a>
    </h1>

    <div class="am-header-right am-header-nav">
        <a href="javascript: void(0)" class="timer pulse animated" style="animation-iteration-count: infinite;">
            <span class="time-left"><?php echo $room['timelimit'] ?></span>秒
        </a>
    </div>
</header>
<div class="mobile-page" style="padding-top: 49px;">

    <div id="msg-box">

        <span class="time"><?php echo $room['time'] ?></span>

        <?php
            $chats = array_reverse($chats);
            foreach ($chats as $chat){


                switch (chatType($chat['msg'])){
                    case 'add':
                        outputText($chat, "【延长聊天时间】", $room);
                        break;
                    case 'heart':
                        outputText($chat, "【比心动画】", $room);
                        break;
                    case 'img':
                    case 'text':
                        outputText($chat, $chat['msg'], $room);
                        break;
                }


            }

            // 判断聊天类型
            function chatType($content){

                if ($content == '<add_time>true</add_time>'){
                    return 'add';
                }else if ($content == '<effects>heart</effects>'){
                    return 'heart';
                }else if(strpos($content, "<img class='photo'") === 0){
                    return 'img';
                }else{
                    return 'text';
                }

            }

            // 输出文本消息HTML
            function outputText($chat, $content, $room){
                if ($chat['gender'] == 'boy'){
                    echo <<<html

<div class="admin-group animated">
    <img class="admin-img" src="{$room['boy_avatar']}">
    <div class="admin-msg">
        <i class="triangle-admin"></i>
        <span class="admin-reply">{$content}</span>
    </div>
</div>

html;

                }else{

                    echo <<<html
<div class="user-group animated">
    <div class="user-msg">
        <span class="user-reply">{$content}</span>
        <i class="triangle-user "></i>
    </div>
    <img class="user-img" src="{$room['girl_avatar']}">
</div>
html;


                }

            }
        ?>
    </div>
</div>



</body>
</html>
