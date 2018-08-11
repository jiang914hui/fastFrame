<?php
//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 9502);
$redis = new Redis();
$redis->connect('127.0.0.1', '6379');
//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    //$request->fd连接webSocket的用户id，存到redis中保存起来
    saveData($request->fd);
    //$ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    $msg = 'from' . $frame->fd . ":{$frame->data}\n";
    //$ws->push($frame->fd,'msg:'.$msg);
    //$frame->fd这个id是发送消息的人，而$i则是当前接收消息的人
    //获取当前连接上webSocket服务器所有id
    $fdArr = getData();
    var_dump($fdArr);
    foreach ($fdArr as $i) {
        $ws->push($i, $msg);
    }
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();
/**
 * 这是使用redis存储fd
 * @param $fd
 */
function saveData($fd){
    global $redis;
    $fdStr = $redis->get('fd');
    if (empty($fdStr)){
        //如果不存在值，那么就把fd存到新数组中，序列化后放到redis中,时间为30分钟
        $fdArr[] = $fd;
        $str = serialize($fdArr);
        $redis->set('fd',$str,3600);
    }else{
        //如果之前存在值
        $fdArr = unserialize($fdStr);
        $fdArr[] = $fd;
        $str = serialize($fdArr);
        $redis->set('fd',$str,3600);
    }
}

function getData(){
    global $redis;
    $fdStr = $redis->get('fd');
    $fdArr = unserialize($fdStr);
    return $fdArr;
}
