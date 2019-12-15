// 修改这里的ws连接！！！ 修改为服务器的地址
var wsUri = "ws://127.0.0.1:8282";

var websocket = null;
var gender = null;
var heart_beat_id = null;
var limit = 0;
var timer_id = null;

var headshot_me = "";
var headshot_TA = "";

// 是否正在聊天
var isChatting = 0;

var more_time = 0;

function start_chat() {
    if(gender == null){
        layer.open({
            content: '请先选择性别哦'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        var $modal = $("#looking-alert");
        $modal.modal({"closeViaDimmer":0});
        // 显示匹配中的提示
        websocket = new WebSocket(wsUri);
        websocket.onopen = function(evt) {
            onOpen(evt)
        };
        websocket.onclose = function(evt) {
            onClose(evt)
        };
        websocket.onmessage = function(evt) {
            onMessage(evt)
        };
        websocket.onerror = function(evt) {
            onError(evt)
        };
    }
}

// 切换性别
function switch_gender(type) {
    if (type == "boy"){
        gender = "boy";
        $("#select-gender-boy").removeClass("am-btn-default").addClass("am-btn-primary");
        $("#select-gender-girl").removeClass("am-btn-danger").addClass("am-btn-default");
    }else if(type == "girl"){
        gender = "girl";
        $("#select-gender-boy").removeClass("am-btn-primary").addClass("am-btn-default");
        $("#select-gender-girl").removeClass("am-btn-default").addClass("am-btn-danger");
    }
}

function onOpen(evt) {
    // 发送初始化数据，匹配数据
    // gender = $("input[name='gender']:checked").val();
    var data = {
        'type': 'start',
        'gender': gender,
        'message':""
    };
    doSend(JSON.stringify(data));

    // 心跳包，十秒一次
    heart_beat_id = window.setInterval("heart_beat()", 10000);
}

function onClose(evt) {
    // 清除心跳包
    if(heart_beat_id != null){
        clearInterval(heart_beat_id);
    }
    if(timer_id != null){
        clearInterval(timer_id);
    }
    heart_beat_id = null;
    timer_id = null;
    // layer.open({
    //     content: '连接已断开'
    //     ,skin: 'msg'
    //     ,time: 2 //2秒后自动关闭
    // });
}

function onMessage(evt) {
    // console.log(evt.data);
    var data = JSON.parse(evt.data);
    var type = data.type;

    var isEnd = false;
    // 判断滚动条是在最底部
    // 如果在最底部
    if(parseInt($(document).scrollTop())+parseInt($(window).height()) == parseInt($(document).height())){
        isEnd = true;
    }else{
        isEnd = false;
    }

    switch (type){
        case 'enter_room':
            // console.log(evt.data);
            // 进入房间，隐藏匹配界面，显示聊天界面
            isChatting = 1;
            $("body").css("background-image", 'none');
            close_model();
            $("#msg-box").html("");
            $("#start").hide();
            $("#chat").show();
            limit = data.limit;
            layer.open({
                content: '匹配成功！你们有'+limit+'秒的时间，开始聊天吧'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            $(".time-left").text(limit);
            if (data.topic == ''){
                $("#topic").html('匹配成功，珍惜这次短暂的偶遇吧！');
            }else{
                $("#topic").html(data.topic);
                //提示
                layer.open({
                    content: data.topic
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }

            if(gender == "boy"){
                headshot_me = data.boy;
                headshot_TA = data.girl;
            }else{
                headshot_me = data.girl;
                headshot_TA = data.boy;
            }
            // 开始倒计时
            timer_id = window.setInterval("timer_count()", 1000);

            // 显示房间ID
            $("#room-id").html('房间ID：' + data.room_id)

            break;
        case 'msg':

            if (data.msg.indexOf("<img") !== -1){
                //渲染聊天框
                var img_div = "<div class=\"admin-group\">" +
                    "     <img class=\"admin-img\" src=\""+headshot_TA+"\"/>" +
                    "        <div class=\"admin-msg\">" +
                    "            <i class=\"triangle-admin\"></i>" +
                    "            <span class=\"admin-reply\">"+data.msg+"</span>" +
                    "        </div>" +
                    "    </div>";
                $(img_div).appendTo("#msg-box");
            } else if(data.msg.indexOf("<add_time>")!== -1){
                more_time = 1;
                limit += 180;
                $("#add_time").hide();
                //提示
                layer.open({
                    content: '对方还想跟你多聊三分钟'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            } else if(data.msg == "<effects>heart</effects>"){
                show_heart();
            }else{
                var msg = {
                    "msg":data.msg,
                    "headshot":headshot_TA
                };
                var html = template('chat-msg-TA', msg);
                document.getElementById('msg-box').innerHTML += html;

                // 执行定时器，删除动画class
                setTimeout("delete_animation_class()",1000);
            }
            break;
        case 'leave_room':
            layer.open({
                content: '对方已跟你分手'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            var msg = {
                "msg":'leave'
            };
            // $("body").css("background-image", 'url("http://7xrs03.com1.z0.glb.clouddn.com/background.jpg")');
            isChatting = 0;
            var html = template('chat-msg-leave', msg);
            document.getElementById('msg-box').innerHTML += html;
            // 清除计时器
            if(timer_id != null){
                window.clearInterval(timer_id);
            }
            timer_id = null;
            break;
        case 'total_online':
            document.getElementById('total_matching').innerHTML = " (在线：" + data.total + ')';
            break;
    }

    if(isEnd || $("#msg").is(":focus")){
        $('html,body').animate({scrollTop:parseInt($(document).height())-parseInt($(window).height())}, 300);
    }
}

function onError(evt) {
    close_model();
    //提示
    layer.open({
        content: '连接服务器失败，请稍后再试'
        ,skin: 'msg'
        ,time: 2 //2秒后自动关闭
    });
}

function doSend(message) {
    if(websocket.readyState == 3){

    }else{
        websocket.send(message);
    }
}

// 发送按钮
function send_msg() {
    $msg = $("#msg").val();
    if($msg == "" || $msg == null){
        layer.open({
            content: '说点什么吧'
            ,skin: 'msg'
            ,time: 2 //2秒后自动关闭
        });
    }else{
        var isEnd = false;
        // 判断滚动条是在最底部
        // 如果在最底部
        if(parseInt($(document).scrollTop())+parseInt($(window).height()) == parseInt($(document).height())){
            isEnd = true;
        }else{
            isEnd = false;
        }

        var data = {
            'type':'send',
            'gender': gender,
            'message':$msg
        };
        doSend(JSON.stringify(data));
        $("#msg").val("");
        //渲染聊天框
        var msg = {
            "msg":$msg,
            "headshot":headshot_me
        };
        var html = template('chat-msg-me', msg);
        document.getElementById('msg-box').innerHTML += html;

        // 执行定时器，删除动画class
        setTimeout("delete_animation_class()",1000);

        if(isEnd || $("#msg").is(":focus")){
            $('html,body').animate({scrollTop:parseInt($(document).height())-parseInt($(window).height())}, 300);
        }

    }
}

// 心跳包
function heart_beat() {
    var data = {
        'type':'heart_beat'
    };
    doSend(JSON.stringify(data));
}


// 取消匹配
function cancel_looking() {
    websocket.close();
    close_model();
    // 清除心跳包
    if(heart_beat_id != null){
        window.clearInterval(heart_beat_id);
    }
    heart_beat_id = null;
}

function close_model() {
    var $model = $("#looking-alert");
    $model.modal('close');
}

// 离开房间

function leave_room() {
    layer.open({
        content: '确定要离开聊天室吗？注意：离开不能返回'
        ,btn: ['坚决离开', '再待一会']
        ,yes: function(index){
            var data = {
                'type':'leave'
            };
            doSend(JSON.stringify(data));
            websocket.close();
            $("#start").show();
            $("#chat").hide();
            // 清除计时器
            if(timer_id != null){
                window.clearInterval(timer_id);
            }
            timer_id = null;
            location.reload();
            layer.close(index);
        }
    });
}

// 倒计时
function timer_count() {

    if(limit < 1){
        var msg = {
            "msg":'timeout'
        };
        var html = template('chat-msg-timeout', msg);
        document.getElementById('msg-box').innerHTML += html;
        // 清除计时器
        if(timer_id != null){
            window.clearInterval(timer_id);
        }
        timer_id = null;
        $("#add_time").hide();
        more_time = 1;
        websocket.close();
    }else{
        limit--;
        $(".time-left").text(limit);
        if(limit == 30){
            layer.open({
                content: '时间还剩下'+limit+"秒"
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            if (more_time === 0){
                $("#add_time").show().popover("open");
                setTimeout("close_add_time_tooltip()", 3000);
            }
        }
    }
}

// 关闭tooltip
function close_add_time_tooltip(){
    $("#add_time").popover("close");
}


// 获取总共匹配次数
$.ajax({
    url:'api.php',
    type:'POST',
    success: function (result) {
        $("#room-num").text(result);
    }
});


// 屏蔽安卓返回按钮
XBack.listen(function(){
    if (isChatting === 1){
        leave_room();
    }
});

// 上传表情包
$(':file').on('change', function() {
    var file = this.files[0];
    $.ajax({
        // Your server script to process the upload
        url: 'photo_upload.php',
        type: 'POST',

        // Form data
        data: new FormData($('form')[0]),

        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        cache: false,
        contentType: false,
        processData: false,

        // Custom XMLHttpRequest
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                // For handling the progress of the upload
                myXhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        $('progress').attr({
                            value: e.loaded,
                            max: e.total
                        });
                    }
                } , false);
            }
            return myXhr;
        },
        success:function (result) {
            // console.log(result);
            if (result !== "无效文件"){
                var data = {
                    'type':'send',
                    'gender': gender,
                    'message':"<img class='photo' src='images/photo/"+result+"'>"
                };
                doSend(JSON.stringify(data));
                //渲染聊天框
                var img_div = "<div class=\"user-group\">" +
                    "        <div class=\"user-msg\">" +
                    "            <span class=\"user-reply\"><img class='photo' src='images/photo/"+result+"'></span>" +
                    "            <i class=\"triangle-user\"></i>" +
                    "        </div>" +
                    "        <img class=\"user-img\" src=\""+headshot_me+"\"/>" +
                    "    </div>";
                $(img_div).appendTo("#msg-box");
            } else{
                //提示
                layer.open({
                    content: '无效的图片'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }

        }
    });
});


//回车事件绑定
$('#msg').bind('keyup', function(event) {
    if (event.keyCode == "13") {
        //发送消息
        send_msg();
    }
});



// 延长时间
function add_time() {
    layer.open({
        content: '是否要延长三分钟的聊天时间？'
        ,btn: ['是', '否']
        ,yes: function(index){
            more_time = 1;
            limit += 180;
            var data = {
                'type':'send',
                'gender': gender,
                'message':"<add_time>true</add_time>"
            };
            doSend(JSON.stringify(data));
            $("#add_time").hide().popover("close");
            //提示
            layer.open({
                content: '延长三分钟成功！'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            layer.close(index);
        }
    });
}


// 比心动画效果
function show_heart() {
    // 需求：点击按钮，双方都显示动画。心从底部飘上去
    var total_heart = 20;
    for (var i=0; i < total_heart; i++){
        // 获取延迟执行时间
        var delay = (Math.random() * 2000).toFixed(0);
        setTimeout("render_heart()", delay);
    }
    setTimeout("delete_heart()",3000);
}

function send_heart() {
    var data = {
        'type':'send',
        'gender': gender,
        'message':"<effects>heart</effects>"
    };
    doSend(JSON.stringify(data));
}

function render_heart() {
    // 获取屏幕宽度
    var b_widh = $("body").width() - 32;
    var heaart_random = Math.random() * 3;
    var heart_positon = Math.random() * b_widh;
    if (heaart_random < 1){
        $("<img>").attr('src', "images/heart/heart2.png").addClass("heart").css('left',heart_positon+"px").appendTo("body");
    } else if (heaart_random >= 1 && heaart_random < 2){
        $("<img>").attr('src', "images/heart/heart3.png").addClass("heart").css('left',heart_positon+"px").appendTo("body");
    } else{
        $("<img>").attr('src', "images/heart/heart1.png").addClass("heart").css('left',heart_positon+"px").appendTo("body");
    }
}

function delete_heart() {
    $(".heart").remove();
}

// 删除动画class
function delete_animation_class() {
    $(".slideInRight").removeClass("slideInRight");
    $(".slideInLeft").removeClass("slideInLeft");
}
