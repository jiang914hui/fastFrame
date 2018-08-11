<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/7
 * Time: 11:46
 */
class person{
    protected $name=null;
    protected $action=null;

    protected function eat(){
        echo 123;
    }
}
class zhangsan extends person{
    protected $names=null;


    public function test(){
        self::eat();

    }
}
class lisi extends person{

}
interface A{
    public function methodA();

}
abstract class classB implements A{
    public function methodA()
    {
        // TODO: Implement methodA() method.
    }
    abstract function methodB();
}
abstract class Employee{
    abstract function continueToWork();
}
class Sales extends Employee{
    private function makeSalePlan(){
        echo "make sale plan";
    }

    public function continueToWork(){
        $this->makeSalePlan();
    }
}

class Market extends Employee{
    private function makeProductPrice(){
        echo "make product price";
    }

    public function continueToWork(){
        $this->makeProductPrice();
    }
}

class Engineer extends Employee{
    private function makeNewProduct(){
        echo "make new product";
    }

    public function continueToWork(){
        $this->makeNewProduct();
    }
}

class Demo{
    public function Work($employeeObj){
        $employeeObj->continueToWork();
    }
}
//调用
$obj = new Demo();
$obj->Work(new Sales());
$obj->Work(new Market());
$obj->Work(new Engineer());
echo '<br>';
class Obj
{
    public function __construct()
    {
        echo "当类在被实例化的时候，自动执行该函数";
    }
    public function __toString()
    {
        return "当对象被当作字符串形式输出时，自动执行该函数";
    }
    public function __invoke($value)
    {
        echo "当对象被当作函数调用时，自动执行该函数".$value;
    }
    /*
    *    当对象访问不存在的方法时，自动执行该函数。也称之为“方法重载”
    *    $fun : 方法名称
    *    $param : 传递的参数
    */
    public function __call($fun,$param)
    {
        echo "调用".$fun."方法不存在，传递的参数".implode(',',$param);
    }
    /*
    *    当对象访问不存在的静态方法时，自动执行该函数。
    *    $fun : 方法名称
    *    $param : 传递的参数
    */
    static function __callStatic($fun,$param)
    {
        echo "调用".$fun."静态方法不存在，传递的参数".implode(',',$param);
    }
    public function __get($key)
    {
        echo "当读取对象中不可访问（未定义）的属性值时，自动调用该函数。".$key."属性不可访问或未定义";
    }
    public function __set($key,$value)
    {
        echo "当给对象中不可访问（未定义）的属性赋值时，自动调用该函数。".$key."属性不可访问或未定义，值".$value;
    }
    public function __isset($key)
    {
        echo "判断对象中的属性不存在时，自动执行该函数。属性：".$key."值未定义";
    }
    public function __unset($key)
    {
        echo "释放对象中的不存在的属性值时，自动执行该函数。属性：".$key."值未定义";
    }
    public function __clone()
    {
        echo "当对象被克隆时，自动执行该函数。";
    }
    public function __destruct()
    {
        echo "当程序执行完成后，自动执行该函数";
    }
    public function test(){
        echo 'test';
    }
}
$obj = new Obj();
echo '<br>';
echo $obj;
echo '<br>';
$obj->abc('aaa');
echo '<br>';
Obj::bcd();
echo '<br>';
$obj->test;
echo '<br>';
$obj->test=123;
echo '<br>';
$obj->test();
$str = 'test';
$num = '123';
echo '<br>';
echo trim($str);
echo intval($num);
var_dump("1e123" == "1e456");  //true
var_dump("0e123" == "0eabc");  //flase
echo '----------------------';
var_dump('0e10'=='0e20');
echo '<br>';
echo md5('123abc');
echo '<br>';
echo md5('123abc');
echo '<br>';
echo password_hash('123abc',PASSWORD_DEFAULT);
echo '<br>';
echo password_hash('123abc',PASSWORD_DEFAULT);
var_dump('2018-6-11-------------------------------------');
$orig = "I'll \"walk\" the <b>dog</b> now";

$a = htmlentities($orig);

$b = html_entity_decode($a);

echo $a; // I'll &quot;walk&quot; the &lt;b&gt;dog&lt;/b&gt; now

echo $b; // I'll "walk" the <b>dog</b> now
$str = '12/\3a"bv';
$str = htmlspecialchars($str);
echo $str;


echo "值传递的方式";
echo "<hr/>";
$var1= "PHP";
$var2=&$var1;
echo $var1."<hr/>";
echo $var2."<hr/>";

echo "<p>改变其中一个变量的值,另一个不会有变化</p>";
$var2="HTML";

echo $var1."<hr/>";
echo $var2."<hr/>";

class Test{
    public static $a = 123;
    public $b = 456;
    public function c(){
        self::d();
        $this->b;
    }
    public static function d(){
        Test::c();
        self::c();
    }
}
$obj = new Test();
$obj::$a;
//htmlspecialchars_decode();
