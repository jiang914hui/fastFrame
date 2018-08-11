<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 17:22
 */
//模拟增加redis队列
define('DOCUMENT_ROOT','/usr/local/nginx/html/newframe/');
include DOCUMENT_ROOT.'fast/Connect.php';
$redis = Connect::redisConnect();
$workerNum = 10;
$act = 'iphoneX';//活动名称
$timeOut = 1800;//活动时长
function act($redis,$timeOut,$act){
    $uid = rand(0,99999);
    $actStr = $redis->get($act);
    if ($actStr){
        var_dump($actStr);
        echo '<br>';
        var_dump(unserialize($actStr));echo '<br>';
        $actArr = unserialize($actStr);
        if(count($actArr)<=1000){

            array_push($actArr,$uid);
            var_dump($actArr);echo '<br>';
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

$pool = new Swoole\Process\Pool($workerNum);

$pool->on("WorkerStart", function ($pool, $workerId) {
    global $redis,$timeOut,$act;
    act($redis,$timeOut,$act);
    echo "Worker#{$workerId} is started\n";
});

$pool->on("WorkerStop", function ($pool, $workerId) {
    echo "Worker#{$workerId} is stopped\n";
});

$pool->start();

