<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/7
 * Time: 15:29
 */
define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT'].'/webSocket/newframe/');


//include DOCUMENT_ROOT.'service/toLocation/ToLocation.php';
//$obj = new ToLocation();
//$data = $obj->getlocation($_SERVER['REMOTE_ADDR']);
//var_dump($data);die;
/**
 * pdo测试
 */
//require DOCUMENT_ROOT.'model/user/AccountModel.php';
//$obj = new AccountModel();
//$sql = '(account,password,name,last_time,register_time,nick,mail,ip,province,city,sex,avatar,describe) values (?,?,?,?,?,?,?,?,?,?,?,?,?)';
//$arr = ['ceshi123','123456','宇智波','2018-08-03 14:34:31','2018-08-03 14:34:31',1,1,1,1,1,1,1,1];
//$arr = ['ceshi123','123456','宇智波','2018-08-03 14:34:31','2018-08-03 14:34:31','','','','','',1,'',''];
//$sql = '(account,password) values (?,?)';
//$arr = ['ceshi123','123456'];
//$arr = [
//    'account'=>'test888',
//    'password'=>'888999'
//];
//$data = $obj->insert($arr);
//var_dump($data);die;
////$arr = [
//    'account'=>'test456',
//    'password'=>'123456'
//];
//$sql = '* from users.account WHERE register_time>?';
//$arr = ['2018-7-1'];
//$data = $obj->select($sql,$arr);
//var_dump($data);die;
/**
 * 更新
 */
//$sql = 'update users.account set account=? where id=?';
//$data = $obj->update($sql,['888',9]);
//var_dump($data);die;
/**
 * 删除
 */
//$sql = 'delete from users.account where id=?';
//$obj->delete($sql,['*']);die;
/**
 * redis测试
 */
//$redis = Connect::redisConnect();
//$redis->set('aaa',123);
//var_dump($redis->get('aaa'));die;
//define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT'].'/newframe/');

if (isset($_POST['c']) && isset($_POST['m'])) {
    $controller = $_POST['c'];
    $method = $_POST['m'];
    $controllerFile = DOCUMENT_ROOT.'controller/'.$controller.'.php';
    if (file_exists($controllerFile)){
        include $controllerFile;
        $controllerObj = new $controller();
        if (method_exists($controllerObj,$method)){
            if (is_callable(array($controllerObj,$method))){
                $controllerObj->$method();
            }else{
                die('该方法是不可执行的');
            }
        }else{
            die('请求方法不存在');
        }
    }else{
        die('控制器不存在');
    }

}else{
//    $template = DOCUMENT_ROOT.'template/login.html';
//    var_dump($template);die;
//    header('Location:'.$template);
    header('Location:template/login.html');
}