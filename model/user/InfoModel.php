<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/3
 * Time: 16:26
 */
include_once DOCUMENT_ROOT.'model/Model.php';
class InfoModel extends Model
{
    protected $table = 'users.info';
    protected $columns = [
        'id' => "serial primary key",
        'account_id' => 'int8 NOT NULL',//用户id
        'name'=> 'varchar(50) COLLATE "default"',//姓名
        'nick'=> 'varchar(50) COLLATE "default"',//昵称
        'mail'=> 'varchar(50) COLLATE "default"',//邮箱
        'mobile'=> 'varchar(50) COLLATE "default"',//手机号
        'ip'=> 'varchar(20) COLLATE "default"',//ip
        'province'=> 'varchar(50) COLLATE "default"',//所在省份
        'city'=> 'varchar(50) COLLATE "default"',//所在城市
        'address_detail' => 'varchar(50) COLLATE "default"',//详细地址
        'sex'=> 'int2',//性别
        'avatar'=> 'varchar(255) COLLATE "default"',//头像
        'describe' => 'varchar(255) COLLATE "default"'//签名
    ];
    protected $index = [
        'users_info_account_id' => 'account_id'
    ];

}