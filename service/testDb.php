<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/2
 * Time: 10:22
 */
require '../fast/Connect.php';
$db = Connect::dbConnect();
$table = '"user".address';
$sql = "select mid,fid from $table limit 10" ;
$arr = [];
$prepare = $db->prepare($sql);
$prepare->execute($arr);
$fetchAll = $prepare->fetchAll(PDO::FETCH_ASSOC);
echo '<pre>';
var_dump($fetchAll);
die;
