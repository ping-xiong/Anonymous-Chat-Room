<?php
/**
 * Created by PhpStorm.
 * User: l5979
 * Date: 2018-05-20
 * Time: 1:25
 */
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] == 1){
    header("Location: admin.php");
}

if (isset($_POST['username'])){
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'admin'){
        $_SESSION['login'] = 1;
        header("Location: admin.php");
    }else{
        header("Location: login.php");
    }
}


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
            登录
        </a>
    </h1>

    <div class="am-header-right am-header-nav" style="visibility: hidden">
        <a href="#right-link" class="">

            <i class="am-header-icon am-icon-bars"></i>
        </a>
    </div>
</header>

<div class="am-g">
    <div class="am-u-md-8 am-u-sm-centered">
        <form class="am-form" method="post" action="login.php">
            <div class="am-form-group" style="padding: 10px;">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">账号</label>
                <div class="am-u-sm-10">
                    <input type="text" name="username" id="doc-ipt-3" placeholder="账号">
                </div>
            </div>
            <br>
            <div class="am-form-group" style="padding: 10px;">
                <label for="doc-ipt-pwd-2" class="am-u-sm-2 am-form-label">密码</label>
                <div class="am-u-sm-10">
                    <input type="password" name="password" id="doc-ipt-pwd-2" placeholder="密码">
                </div>
            </div>

            <button style="margin-top: 80px" type="submit" class="am-btn am-btn-primary am-btn-block">登录</button>
        </form>
    </div>
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
</body>
</html>
