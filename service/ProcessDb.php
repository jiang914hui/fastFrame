<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/13
 * Time: 16:34
 */
//模拟减库存
/**
 * 首先查询redis，把相应的
 */
define('DOCUMENT_ROOT','/usr/local/nginx/html/newframe/');
$workerNum = 10;
$pool = new Swoole\Process\Pool($workerNum);

$pool->on("WorkerStart", function ($pool, $workerId) {
    processDb();
    echo "Worker#{$workerId} is started\n";
});

$pool->on("WorkerStop", function ($pool, $workerId) {
    echo "Worker#{$workerId} is stopped\n";
});

$pool->start();
function processDb(){
    include DOCUMENT_ROOT.'model/Model.php';
    $model = new Model();
    $sql = 'num from public.test WHERE name= ?';
    $res = $model->select($sql,['iphoneX']);
    $num = $res[0]['num']-1;
    $update = 'public.test set num = ? where name=?';
    $model->update($update,[$num,'iphoneX']);
}

