<?php
/**
 * Created by PhpStorm.
 * User: https://pingxonline.com/
 * Date: 2018-03-30
 * Time: 21:11
 */

include_once "connect.php";

$db = new connectDataBase();

$sql = "SELECT COUNT(*) AS total FROM `room` WHERE 1";
$result = mysqli_query($db->link, $sql);
$num = mysqli_fetch_assoc($result)['total'];
echo $num;