# 匿名聊天
在线实时匹配，两人异性聊天室。为移动端设计。

# 特性
1. 免注册
2. 随机房间时间
3. 随机情侣头像
4. 随机聊天话题
5. 支持发送自定义图片
6. 支持通过输入法键盘输入emoji并发送
7. 后台管理实时监控
8. 后台上传情头和创建话题
9. 后台查看房间聊天记录
10. 支持延长房间时间

# 服务端安装教程
1. 导入anonymous_chat.sql文件到mysql数据库  
2. 修改Application/chat/config.php 文件    

注意：服务端可以放在服务器的任何位置，只要运行成功就可使用。

同时支持Windows服务器和Linux服务器  

运行方法各不相同：  


Windows 服务器：  
双击.bat文件即可运行  


Linux 服务器：  
进入项目根目录，终端输入：  
以debug（调试）方式启动  
php start.php start  
以daemon（守护进程）方式启动  
php start.php start -d  


服务器连不上：确保放行默认的8282端口，如果8282端口被占用，可以自行修改为其他端口，修改教程在环境搭建教程里面有介绍。

# 客户端安装教程

1. 把根目录下的client文件夹部署到web服务器。  
2. 修改client文件夹中的client.js文件的第一行，var wsUri = "ws://127.0.0.1:8282";，改为你服务器的地址和端口8282
3. 修改client文件夹中的connect.php，配置好客户端的服务器连接

# 目录结构说明  
该项目包含了服务端和客户端，只运行服务端是无法使用的，要把根目录下client文件夹部署到HTTP服务器。 client里面的内容才是网站前端。 

# 关于后台管理

默认账号：admin  
默认密码：admin  

## 1. 获取登录账号密码
client/login.php的第15行为登录的账号密码  
## 2. 后台访问地址
网址：/login.php

# 服务器环境需求

http://doc.workerman.net/install/requirement.html


# PHP插件需求

该mysql类依赖pdo和pdo_mysql两个扩展，缺少扩展会报Undefined class constant 'MYSQL_ATTR_INIT_COMMAND' in ....错误。


命令行运行php -m会列出所有php cli已安装的扩展，如果没有pdo 或者 pdo_mysql，请自行安装。


# 软件截图

![输入图片说明](https://images.gitee.com/uploads/images/2019/1215/204848_86c18232_1607414.png "localhost_8080_(iPhone 6_7_8).png")


![输入图片说明](https://images.gitee.com/uploads/images/2019/1215/204856_bd9eb9c3_1607414.png "localhost_8080_(iPhone 6_7_8) (1).png")


![输入图片说明](https://images.gitee.com/uploads/images/2019/1215/205024_2a76e22d_1607414.png "localhost_8080_(iPhone 6_7_8) (6).png")


![输入图片说明](https://images.gitee.com/uploads/images/2019/1215/204923_a6a82fb3_1607414.png "localhost_8080_admin.php(iPhone 6_7_8).png")


![输入图片说明](https://images.gitee.com/uploads/images/2019/1215/204929_94924c49_1607414.png "localhost_8080_admin.php(iPhone 6_7_8) (1).png")


![输入图片说明](https://images.gitee.com/uploads/images/2019/1215/204938_39ceb828_1607414.png "localhost_8080_admin.php(iPhone 6_7_8) (2).png")
