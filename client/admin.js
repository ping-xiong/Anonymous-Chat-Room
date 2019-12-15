var current_editing_topic_id = null;

var page = 1;
var total_page = 0;

function open_add_topic() {
    //页面层
    layer.open({
        content: $("#add_topic_box").html()
        ,title: [
            '新话题',
            'background-color: #FF4351; color:#fff;'
        ]
    });
}

function add_topic() {
    var new_topic = $($(".new_topic")[1]).val();
    $.ajax({
        url:"admin.php",
        type:'POST',
        dataType: 'html',
        data:{api: 'add_topic',topic:new_topic},
        success: function () {
            refreshPage();
        }
    });
}

function delete_topic(id) {
//询问框
    layer.open({
        content: '你确定要删除这个话题吗？'
        ,btn: ['确定', '不要']
        ,yes: function(index){
            $.ajax({
               url:"admin.php",
               type:'POST',
               dataType: 'html',
               data:{api: 'delete_topic',id:id},
               success: function () {
                   refreshPage();
                }
            });
        }
    });
}

function open_edit_topic(id, content) {
    current_editing_topic_id = id;
    $(".update_topic").html(content);
    //页面层
    layer.open({
        content: $("#update_topic_box").html()
        ,title: [
            '修改话题',
            'background-color: #FF4351; color:#fff;'
        ]
    });
}
function update_topic() {
    var new_topic = $($(".update_topic")[1]).val();
    $.ajax({
        url:"admin.php",
        type:'POST',
        dataType: 'html',
        data:{api: 'update_topic',id:current_editing_topic_id, topic:new_topic},
        success: function () {
            refreshPage();
        }
    });
}

// 定时获取服务器状态
function get_status() {
    $.ajax({
        url:"admin.php",
        type:'POST',
        dataType: 'json',
        data:{api: 'get_status'},
        success: function (result) {
            $("#room").text(result.rooms);
            $("#matching_boy").text(result.boy_matching);
            $("#matching_girl").text(result.girl_matching);
            $("#total_matching").text(result.total_matching);
            $("#total_boy").text(result.total_boy);
            $("#total_girl").text(result.total_girl);
            $("#total_chat").text(result.total_chat);
            $("#total_room").text(result.total_room);
            $("#total_ip").text(result.total_ip);
        }
    });
}

window.setInterval("get_status()", 1000);


// 删除情头
function delete_headshot(id) {
    layer.open({
        content: '你确定要删除这组头像吗？'
        ,btn: ['确定', '不要']
        ,yes: function(index){
            $.ajax({
                url:"admin.php",
                type:'POST',
                dataType: 'html',
                data:{api: 'delete_headshot', id:id},
                success: function () {
                    refreshPage();
                }
            });
        }
    });

}

// 获取房间列表
function get_room_list() {

    $.ajax({
        url:"admin.php",
        type:'POST',
        dataType: 'html',
        data:{api: 'room_list', page:page},
        success: function (res) {
            document.getElementById('room-list-ul').innerHTML = ""
            // console.log(res)
            var json_arr = JSON.parse(res)
            for (var i = 0; i < json_arr.data.length; i++) {
                var room = json_arr.data[i];
                document.getElementById('room-list-ul').innerHTML += '<li>\n' +
                    '                        <div class="li-div">\n' +
                    '                            <div class="li-div-div">\n' +
                    '                                <div class="room-id">'+room.room_id+'</div>\n' +
                    '                                <div>\n' +
                    '                                    <img src="'+room.boy_avatar+'" alt="男生头像">\n' +
                    '                                    <img src="'+room.girl_avatar+'" alt="女生头像">\n' +
                    '                                </div>\n' +
                    '                                <dvi class="room-time"> 序号：'+room.id+' - 时间：'+room.time+'</dvi>\n' +
                    '                            </div>\n' +
                    '                            <div class="room-limit">\n' +
                    '                                '+room.timelimit+'秒\n' +
                    '                            </div>\n' +
                    '                            <div class="open-btn">\n' +
                    '                                <a target="_blank" href="chat.php?room='+room.room_id+'"><span>打开</span></a>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </li>'

            }


            // 渲染分页组件
            total_page = parseInt(json_arr.total);

            document.getElementById('page-options').innerHTML = ""

            for (let i = 1; i <= total_page; i++) {

                if (i == page){
                    document.getElementById('page-options').innerHTML += '<option value="'+i+'" selected="selected">'+i+' / '+total_page+' </option>'
                }else{
                    document.getElementById('page-options').innerHTML += '<option value="'+i+'">'+i+' / '+total_page+' </option>'
                }

            }



        }
    })

}

$("#page-options").change(function(){

    // console.log($("#page-options").val());
    jumpPage($("#page-options").val());

});


function nextPage() {

    if (page >= total_page){

        alert("后面没有了")

    }else{

        page++;
        get_room_list();

    }

}

function prePage() {

    if (page <= 1){
        alert('前面没有了')
    }else{
        page--;
        get_room_list();
    }

}

function jumpPage(newPage) {

    page = newPage;
    get_room_list();

}


function openRoom() {

    var room_id = $("#input-room-id").val();

    if (room_id == ""){
        alert("请输入房间ID")
        return
    }

    window.open('chat.php?room=' + room_id, '_blank');
}


function refreshPage() {
    window.location.href = 'admin.php'
}
