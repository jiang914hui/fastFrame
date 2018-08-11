<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 16:35
 */
class Connect
{
    public static function dbConnect($persistent = true){
        $config = include DOCUMENT_ROOT.'config/Database.php';
        //'pgsql:host=192.168.65.133;dbname=postgres;port=6666','postgressql',''
        echo '连接数据库';
        return new \PDO(
            $config['dsn'],
            $config['user'],
            $config['password'],
            [
                \PDO::ATTR_PERSISTENT => $persistent,//长连接
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,//抛出异常
                \PDO::ATTR_TIMEOUT => $config['timeout']
            ]
        );
    }

    public static function redisConnect(){
        $config = require DOCUMENT_ROOT.'config/Config.php';
        //连接本地的 Redis 服务
        $redis = new Redis();
        $redis->connect($config['host'], $config['port']);
        return $redis;
    }

}