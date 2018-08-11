<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 16:07
 */
include DOCUMENT_ROOT.'model/Model.php';
class AccountModel extends Model
{
    protected $table = 'users.account';
    protected $columns = [
        'id' => "serial primary key",
        'account' => 'varchar(255) COLLATE "default" NOT NULL',//账号
        'password' => 'varchar(255) COLLATE "default" NOT NULL',//密码
        'last_time'=> 'timestamp(6)',//最后登录时间
        'register_time'=> 'timestamp(6)'//注册时间
    ];
    protected $index = [
        'users_account_account' => 'account'
    ];

}