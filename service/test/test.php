<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/13
 * Time: 17:00
 */
//if (count([1,2,3])>2){
//    echo 123;
//}else{
//    echo 456;
//}
//die;
define('DOCUMENT_ROOT','/usr/local/nginx/html/newframe/');
include DOCUMENT_ROOT.'fast/Connect.php';
$redis = Connect::redisConnect();
$act = 'iphoneX';//活动名称
$timeOut = 1800;//活动时长
for ($i=0;$i<=1000;$i++){
    act($redis,$timeOut,$act);
}
function act($redis,$timeOut,$act){

    $uid = rand(0,99999);

    $actStr = $redis->get($act);
    if ($actStr){
        $actArr = unserialize($actStr);
        if(count($actArr)<=1000){

            array_push($actArr,$uid);
            //var_dump($actArr);echo '<br>';
            $actStr = serialize($actArr);
            $redis->set($act,$actStr,$timeOut);
        }else{
            echo $act.'已经抢完！';die;
        }
    }else{
        //说明这是第一次访问，获得到的为空
        $actArr = [$uid];
        $actStr = serialize($actArr);
        $redis->set($act,$actStr,$timeOut);
    }
}