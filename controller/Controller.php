<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/3
 * Time: 14:42
 */
class Controller
{
    protected function outputError(string $message,int $backNum){
        echo "<script>alert({$message});history.go(-1)</script>";die;
    }

}