<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/2
 * Time: 16:02
 */
$a = [2];
var_dump($a);
echo '<br>';
$b = serialize($a);
var_dump($b);
echo '<br>';
$c = unserialize($b);
var_dump($c);



