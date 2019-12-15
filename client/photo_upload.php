<?php
/**
 * Created by pingxiong.
 * User: l5979
 * Date: 2018-05-21
 * Time: 23:51
 */

if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/png")
        || ($_FILES["file"]["type"] == "image/x-png"))
)
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "无效文件";
    }
    else
    {
        $file_name = uniqid();
        move_uploaded_file($_FILES["file"]["tmp_name"],
            "images/photo/" . $file_name.$_FILES["file"]["name"]);
        echo $file_name.$_FILES["file"]["name"];
    }
}
else
{
    echo "无效文件";
}

