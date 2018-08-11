<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/1
 * Time: 15:31
 */
include DOCUMENT_ROOT.'controller/Controller.php';
class Login extends Controller
{
    public function verification(){
        if (isset($_POST['username'])&&isset($_POST['password'])){
            //验证用户名及密码是否合法
            $username = $_POST['username'];
            $password = $_POST['password'];
            $this->verificationLength($username);
            $this->verificationLength($password);
            //查询数据库看账号是否存在
            include  DOCUMENT_ROOT.'model/user/AccountModel.php';
            $sql = 'select password,id from users.account where account=? limit 1';
            $arr = array($username);
            $account = new AccountModel();
            $res = $account->select($sql,$arr);
            if (empty($res)){
                echo "<script>alert('账号不存在');history.go(-1)</script>";die;
            }
            //验证密码是否正确
            if (!password_verify($password,$res[0]['password'])){
                echo "<script>alert('密码错误');history.go(-1)</script>";die;
            }
            //更新登录时间
            $updateSql = 'update users.account set last_time=? where id=?';
            $account->update($updateSql,[date('Y-m-d H:i:s'),$res[0]['id']]);
            //存入账号信息
            $infoSql = 'select nick from users.info where account_id=? limit 1';
            include  DOCUMENT_ROOT.'model/user/InfoModel.php';
            $infoObj = new InfoModel();
            $infoData = $infoObj->select($infoSql,[$res[0]['id']]);
            $_SESSION['account'] = [
                'accountId'=>$res[0]['id'],
                'nick'=>$infoData[0]['nick']
            ];
            $serverName = $_SERVER['SERVER_NAME'];
            header("Location:template/index.html");
            //include DOCUMENT_ROOT.'template/index.html';
        }
    }

    public function register(){
        //验证信息是否填写完整
        if (empty($_POST['username'])||empty($_POST['password'])||empty($_POST['again'])||empty($_POST['nick'])){
            echo "<script>alert('请填写完整信息');history.go(-1)</script>";die;
        }

        //验证信息是否符合标准
        $username = $_POST['username'];
        $password = $_POST['password'];
        $this->verificationLength($username);
        $this->verificationLength($password);

        //判断两次填写代码是否一致
        if ($_POST['password']!==$_POST['again']) {
            echo "<script>alert('两次填写的密码不一致');history.go(-1)</script>";die;
        }

        //判断账号是否已经注册
        include DOCUMENT_ROOT . 'model/user/AccountModel.php';
        $accountObj = new AccountModel();
        $uSql = 'select account from users.account where account=? limit 1';
        $user = $accountObj->select($uSql, [$username]);
        if (!empty($user)) {
            echo "<script>alert('此用户已存在');history.go(-1)</script>";die;
        }

        //注册用户账号
        $arr = [
            'account' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'register_time'=>date('Y-m-d H:i:s')
        ];
        $accountObj->beginTransaction();//开启事务
        try{
            $accountId = $accountObj->insert($arr);
        }catch (Exception $exception){
            throw new Exception($exception);
        }
        //注册用户信息
        include DOCUMENT_ROOT.'service/toLocation/ToLocation.php';
        $obj = new ToLocation();
        $data = $obj->getlocation($_SERVER['REMOTE_ADDR']);//获取IP及所在地域
        $info = [
            'account_id'=>$accountId,
            'mail'=>$_POST['mail']?:'',
            'mobile'=>$_POST['mobile']?:'',
            'nick'=>$_POST['nick']?:'',
            'ip'=>$_SERVER['REMOTE_ADDR']?:'',
            'address_detail'=>$data['country']?:'',
        ];
        include DOCUMENT_ROOT.'model/user/InfoModel.php';
        $infoObj = new InfoModel();
        try{
            $infoObj->insert($info);
        }catch (Exception $exception){
            $infoObj->rollBack();//回滚事务
            throw new Exception($exception);//抛出异常
        }
        $infoObj->commit();//提交所有事务
        echo "<script>alert('注册成功');history.go(-2)</script>";die;
    }
    private function verificationLength($str){
        $str = strlen($str);
        if ($str<6){
            echo "<script>alert('字符太短，请重新输入');history.go(-1)</script>";die;
        }
        if ($str>24){
            echo "<script>alert('字符太长，请重新输入');history.go(-1)</script>";die;
        }
    }
}