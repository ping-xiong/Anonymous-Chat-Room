<?php
/**
 * Created by PhpStorm.
 * User: https://pingxonline.com/
 * Date: 2018-03-30
 * Time: 12:48
 */
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'] == 1){}else{
    header("Location:login.php");
}


include_once "connect.php";
$db = new connectDataBase();

if (isset($_POST['api'])){

    $api = $_POST['api'];
    switch ($api){
        case 'upload':
            $boy = uniqid('', true).$_FILES["boy"]["name"];
            $girl = uniqid('', true).$_FILES["girl"]["name"];

            if (check_file_boy($_FILES["boy"]["name"])){
                move_uploaded_file($_FILES["boy"]["tmp_name"], "images/headshot/" .$boy);
            }

            if (check_file_girl($_FILES["girl"]["name"])){
                move_uploaded_file($_FILES["girl"]["tmp_name"], "images/headshot/" .$girl);
            }

            $sql = "INSERT INTO `headshot`(`girl`, `boy`) VALUES ('{$girl}','{$boy}')";
            mysqli_query($db->link, $sql);
            break;
        case 'add_topic':
            $topic = $db->test_input($_POST['topic']);
            $sql = "INSERT INTO `topic`(`topic`) VALUES ('{$topic}')";
            mysqli_query($db->link, $sql);
            return;
            break;
        case 'delete_topic':
            $topic = $db->test_input($_POST['id']);
            $sql = "DELETE FROM `topic` WHERE `id` = {$topic}";
            mysqli_query($db->link, $sql);
            return;
            break;
        case 'update_topic':
            $id = $db->test_input($_POST['id']);
            $topic = $db->test_input($_POST['topic']);
            $sql = "UPDATE `topic` SET `topic`= '$topic' WHERE `id` = {$id}";
            mysqli_query($db->link, $sql);
            return;
            break;
        case 'get_status':
            // 获取服务器状态
            $sql = "SELECT * FROM `status` WHERE `id` = 1";
            $status = mysqli_query($db->link, $sql);
            $statu = mysqli_fetch_assoc($status);

            $json = array();
            $json['boy_matching'] = $statu['boy'];
            $json['girl_matching'] = $statu['girl'];
            $json['rooms'] = $statu['total'];

            // 获取中匹配的房间数量
            $sql = "SELECT count(*) as total_rooms FROM `room` WHERE 1";
            $total_room = mysqli_fetch_assoc(mysqli_query($db->link, $sql))['total_rooms'];
            $json['total_room'] = $total_room;

            // 获取总聊天数
            $sql = "SELECT count(*) as total_chat FROM `chat` WHERE 1";
            $total_chat = mysqli_fetch_assoc(mysqli_query($db->link, $sql))['total_chat'];
            $json['total_chat'] = $total_chat;

            // 获取中匹配的次数
            $sql = "SELECT count(*) as total FROM `matching` WHERE 1";
            $total_matching = mysqli_fetch_assoc(mysqli_query($db->link, $sql))['total'];
            $json['total_matching'] = $total_matching;

            // 获取匹配的男生数
            $sql= "SELECT count(*) as total FROM `matching` WHERE `gender` = 'boy'";
            $total_boy = mysqli_fetch_assoc(mysqli_query($db->link, $sql))['total'];
            $json['total_boy'] = $total_boy;

            // 获取匹配的女生数
            $sql= "SELECT count(*) as total FROM `matching` WHERE `gender` = 'girl'";
            $total_girl = mysqli_fetch_assoc(mysqli_query($db->link, $sql))['total'];
            $json['total_girl'] = $total_girl;

            // 获取IP数量
            $sql = "SELECT COUNT(*) as total FROM (SELECT * FROM `matching` WHERE 1 GROUP BY `ip`) as ips";
            $total_ip = mysqli_fetch_assoc(mysqli_query($db->link, $sql))['total'];
            $json['total_ip'] = $total_ip;

            echo json_encode($json);
            return;
            break;
        case 'delete_headshot':
            $id = $db->test_input($_POST['id']);
            $sql = "DELETE FROM `headshot` WHERE `id` = {$id}";
            mysqli_query($db->link, $sql);
            break;
        case 'room_list':
            $page = $db->test_input($_POST['page']);
            $page_size = 100;

            $sql = "SELECT * FROM `room` ORDER BY `time` DESC LIMIT ".((int)$page - 1)*$page_size.", $page_size";
            $result = mysqli_query($db->link, $sql);

            $sql = "SELECT COUNT(*) as total FROM `room`";
            $total_room = mysqli_fetch_assoc(mysqli_query($db->link, $sql))['total'];
            $total_page = $total_room / $page_size;
            if ($total_page == 0){
                $total_page = 1;
            }

            echo json_encode([
                    "data" => $result->fetch_all(1),
                    "total" => ceil($total_page)
            ]);
            return;
            break;
    }
}

function check_file_boy($filename){
    // 允许上传的图片后缀
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    // $temp = $filename;
    $temp = explode(".", $filename);
    $extension = end($temp);        // 获取文件后缀名
    if ((($_FILES["boy"]["type"] == "image/gif")
            || ($_FILES["boy"]["type"] == "image/jpeg")
            || ($_FILES["boy"]["type"] == "image/jpg")
            || ($_FILES["boy"]["type"] == "image/pjpeg")
            || ($_FILES["boy"]["type"] == "image/x-png")
            || ($_FILES["boy"]["type"] == "image/png"))
        && ($_FILES["boy"]["size"] < 204800)    // 小于 200 kb
        && in_array($extension, $allowedExts))
    {
        if ($_FILES["boy"]["error"] > 0)
        {
            echo "错误：: " . $_FILES["boy"]["error"] . "<br>";
            return false;
        }else{
            return true;
        }
    }
    else
    {
        echo "非法的文件格式";
        return false;
    }
}

function check_file_girl($filename){
    // 允许上传的图片后缀
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    // $temp = $filename;
    $temp = explode(".", $filename);
    $extension = end($temp);        // 获取文件后缀名
    if ((($_FILES["girl"]["type"] == "image/gif")
            || ($_FILES["girl"]["type"] == "image/jpeg")
            || ($_FILES["girl"]["type"] == "image/jpg")
            || ($_FILES["girl"]["type"] == "image/pjpeg")
            || ($_FILES["girl"]["type"] == "image/x-png")
            || ($_FILES["girl"]["type"] == "image/png"))
        && ($_FILES["girl"]["size"] < 204800)    // 小于 200 kb
        && in_array($extension, $allowedExts))
    {
        if ($_FILES["girl"]["error"] > 0)
        {
            echo "错误：: " . $_FILES["girl"]["error"] . "<br>";
            return false;
        }else{
            return true;
        }
    }
    else
    {
        echo "非法的文件格式";
        return false;
    }
}

// 获取话题列表
$sql = "SELECT * FROM `topic` WHERE 1";
$topics_result = mysqli_query($db->link, $sql);

// 获取头像列表
$sql = "SELECT * FROM `headshot` WHERE 1";
$headshot_result = mysqli_query($db->link, $sql);

?>

<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>假装情侣后台管理</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="assets/i/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="assets/i/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
    <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="assets/i/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="assets/css/amazeui.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <style>
        .headshot-list img{
            height: 50px;
            width: 50px;
        }
        .status-box{
            max-width: 100px;
            text-align: center;
            border: 2px solid #607D8B;
            margin: 0 auto;
        }
        .status-box p{
            margin: 0;
            padding: 4px;
        }
        .status-box p:nth-child(2){
            background: #607D8B;
            color: #fff;
        }
    </style>
</head>
<body>
<header data-am-widget="header"
        class="am-header am-header-default">
    <div class="am-header-left am-header-nav"  style="visibility: hidden">
        <a href="#left-link" class="">

            <i class="am-header-icon am-icon-home"></i>
        </a>
    </div>

    <h1 class="am-header-title">
        <a href="#title-link" class="">
            假装情侣后台管理
        </a>
    </h1>

    <div class="am-header-right am-header-nav" style="visibility: hidden">
        <a href="#right-link" class="">

            <i class="am-header-icon am-icon-bars"></i>
        </a>
    </div>
</header>
<div data-am-widget="tabs" class="am-tabs am-tabs-default" style="margin: 0">
    <ul class="am-tabs-nav am-cf">
        <li class="am-active"><a href="[data-tab-panel-0]">房间</a></li>
        <li class=""><a href="[data-tab-panel-1]">话题</a></li>
        <li class=""><a href="[data-tab-panel-2]">情头</a></li>
        <li class=""><a href="[data-tab-panel-3]">聊天记录</a></li>
    </ul>
    <div class="am-tabs-bd">
        <div data-tab-panel-0 class="am-tab-panel am-active">
            <p style="text-align: center;font-size: 12px;color: #777777;margin: 0">数据实时更新</p>
            <h2 style="margin-top: 4px">正在匹配</h2>
            <ul class="am-avg-sm-3">
                <li>
                    <div class="status-box">
                        <p id="room"></p>
                        <p>房间</p>
                    </div>
                </li>
                <li>
                    <div class="status-box">
                        <p id="matching_boy"></p>
                        <p>男生</p>
                    </div>
                </li>
                <li>
                    <div class="status-box">
                        <p id="matching_girl"></p>
                        <p>女生</p>
                    </div>
                </li>
            </ul>
            <h2>匹配统计</h2>
            <ul class="am-avg-sm-3">
                <li>
                    <div class="status-box">
                        <p id="total_matching"></p>
                        <p>匹配总数</p>
                    </div>
                </li>
                <li>
                    <div class="status-box">
                        <p id="total_boy"></p>
                        <p>男生</p>
                    </div>
                </li>
                <li>
                    <div class="status-box">
                        <p id="total_girl"></p>
                        <p>女生</p>
                    </div>
                </li>
            </ul>
            <h2>其他统计</h2>
            <ul class="am-avg-sm-3">
                <li>
                    <div class="status-box">
                        <p id="total_chat"></p>
                        <p>聊天条数</p>
                    </div>
                </li>
                <li>
                    <div class="status-box">
                        <p id="total_room"></p>
                        <p>总房间数</p>
                    </div>
                </li>
                <li>
                    <div class="status-box">
                        <p id="total_ip"></p>
                        <p>总IP数</p>
                    </div>
                </li>
            </ul>
        </div>
        <div data-tab-panel-1 class="am-tab-panel">
            <button type="button" class="am-btn am-btn-default am-round" style="    margin: 0 auto;
    text-align: center;
    display: block;" onclick="open_add_topic()">添加话题</button>
            <ul class="am-list am-list-static">
                <?php
                    while ($row = mysqli_fetch_assoc($topics_result)){
                        echo <<<topic
                        <li>
                            {$row['topic']}
                            <div class="am-btn-group">
                                <button type="button" class="am-btn am-btn-primary am-radius" onclick="open_edit_topic({$row['id']},'{$row['topic']}')">编辑</button>
                                <button type="button" class="am-btn am-btn-warning am-radius" onclick="delete_topic({$row['id']})">删除</button>
                            </div>
                        </li>
topic;

                    }
                ?>
            </ul>

        </div>
        <div data-tab-panel-2 class="am-tab-panel ">
            <form class="am-form" action="admin.php" method="post" enctype="multipart/form-data" style="text-align: center;">
                <h2>上传情侣头像</h2>
                <p>文件大小必须小于200k</p>
                <div class="am-form-group am-form-file">
                    <label for="doc-ipt-file-1">男生头像上传</label>
                    <div>
                        <button type="button" class="am-btn am-btn-default am-btn-sm">
                            <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                    </div>
                    <input type="file" name="boy" id="doc-ipt-file-1">
                    <div id="file-list"></div>
                </div>
                <div class="am-form-group am-form-file">
                    <label for="doc-ipt-file-2">女生头像上传</label>
                    <div>
                        <button type="button" class="am-btn am-btn-default am-btn-sm">
                            <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
                    </div>
                    <input type="file" name="girl" id="doc-ipt-file-2">
                    <div id="file-list2"></div>
                </div>
                <input type="hidden" name="api" value="upload">
                <p><button type="submit" class="am-btn am-btn-primary">提交</button></p>
            </form>
            <br>
            <div>
                <ul class="am-list am-list-static headshot-list">
                <?php
                    while ($headshot_row = mysqli_fetch_assoc($headshot_result)){

                        echo <<<topic
                        <li>
                            <img src="images/headshot/{$headshot_row['boy']}" alt="">
                            <img src="images/headshot/{$headshot_row['girl']}" alt="">
                            <div class="am-btn-group">
                                <button type="button" class="am-btn am-btn-warning am-radius" onclick="delete_headshot({$headshot_row['id']})">删除</button>
                            </div>
                        </li>
topic;
                    }
                ?>
                </ul>

            </div>
        </div>
        <div data-tab-panel-3 class="am-tab-panel">

            <div class="am-input-group" style="margin-bottom: 20px; border-bottom: 1px solid #eee;">
                <input id="input-room-id" type="text" placeholder="输入房间ID" class="am-form-field">
                <span class="am-input-group-btn">
                    <button class="am-btn am-btn-default" type="button" onclick="openRoom()">打开</button>
                </span>
            </div>

            <div id="room-list">

                <ul id="room-list-ul">

                    <li>
                        <div class="li-div">
                            <div class="li-div-div">
                                <div class="room-id">ID: 5df4e2fa2cb662.11539383</div>
                                <div>
                                    <img src="https://shop.3dmgame.com//page/images/ad_shop.jpg" alt="男生头像">
                                    <img src="https://shop.3dmgame.com//page/images/ad_shop.jpg" alt="女生头像">
                                </div>
                                <dvi class="room-time"> 序号：5 - 时间：2019-12-14 21:26:18</dvi>
                            </div>
                            <div class="room-limit">
                                268秒
                            </div>
                            <div class="open-btn">
                                <span>打开</span>
                            </div>
                        </div>
                    </li>

                </ul>

            </div>

            <ul data-am-widget="pagination"
                class="am-pagination am-pagination-select"
            >


                <li class="am-pagination-prev" onclick="prePage()">
                    <a href="#">上一页</a>
                </li>


                <li class="am-pagination-select">
                    <select id="page-options">
                        <option value="#">1
                            / 3
                        </option>
                        <option value="#">2
                            / 3
                        </option>
                        <option value="#">3
                            / 3
                        </option>
                    </select>
                </li>


                <li class="am-pagination-next " onclick="nextPage()">
                    <a href="#">下一页</a>
                </li>

            </ul>

        </div>
    </div>
</div>


<div id="add_topic_box" style="display: none">
    <div class="am-form-group">
        <textarea style="width: 100%;" class="new_topic" rows="5" id="new_topic"></textarea>
    </div>
    <p><button type="submit" class="am-btn am-btn-default" onclick="add_topic()">提交</button></p>
</div>
<div id="update_topic_box" style="display: none">
    <div class="am-form-group">
        <textarea style="width: 100%;" class="update_topic" rows="5"></textarea>
    </div>
    <p><button type="submit" class="am-btn am-btn-default" onclick="update_topic()">提交</button></p>
</div>


<!--在这里编写你的代码-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="jquery-3.2.1.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="assets/js/amazeui.min.js"></script>
<script src="layer_mobile/layer.js"></script>
<script src="admin.js?v=0521"></script>

<script>
    $(function() {
        $('#doc-ipt-file-1').on('change', function() {
            var fileNames = '';
            $.each(this.files, function() {
                fileNames += '<span class="am-badge">' + this.name + '</span> ';
            });
            $('#file-list').html(fileNames);
        });

        $('#doc-ipt-file-2').on('change', function() {
            var fileNames = '';
            $.each(this.files, function() {
                fileNames += '<span class="am-badge">' + this.name + '</span> ';
            });
            $('#file-list2').html(fileNames);
        });

        get_room_list();
    });
</script>
</body>
</html>
