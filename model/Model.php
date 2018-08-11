<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/4
 * Time: 11:11
 */
class Model
{
    protected $table = '';
    protected $columns = [];
    protected $index =null;
    protected static $pdo = null;
    private $space = ' ';//空格
    public function __construct()
    {
        include_once DOCUMENT_ROOT.'fast/Connect.php';
        if (empty(self::$pdo))
        self::$pdo = Connect::dbConnect();
    }

    /**
     * 查询
     * @param string $sql
     * @param array $arr
     * @return array|bool
     */
    public function select(string $sql, array $arr)
    {
        if (empty($arr)) return false;
        $this->createTable();
        $prepare = self::$pdo->prepare($sql);
        $prepare->execute($arr);
        $res = $prepare->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * 插入
     * @param array $arr 需要插入的字段和值，用数组key=>value形式传参
     * @return bool
     */
    public function insert(array $arr){
        if (empty($arr)) return false;
        $this->createTable();//判断是否存在表，没有则创建
        $columns = '';
        $symbol = '';//占位符
        $values = [];
        foreach ($arr as $k=>$v){
            $columns .=$k.',';
            $symbol .='?,';
            $values[] = $v;
        }
        $columns = rtrim($columns,',');
        $symbol = rtrim($symbol,',');
        $preSql = 'insert into '.$this->table.' ('.$columns.') values ('.$symbol.')';
        $prepare = self::$pdo->prepare($preSql);
        $prepare->execute($values);
        return self::$pdo->lastInsertId();
    }

    /**
     * 更新
     * @param string $sql
     * @param array $arr
     * @return bool
     */
    public function update(string $sql,array $arr){
        if (empty($arr)) return false;
        $this->createTable();
        $prepare = self::$pdo->prepare($sql);
        $res = $prepare->execute($arr);
        return $res;
    }


    /**
     * 更新2
     * @param $arr
     * @param $condition
     * @param $linkSymbol
     * @return bool
     */
    public function update2(array $arr,array $condition,string $linkSymbol='and'){
        if (empty($arr)) return false;
        if (empty($condition)) return false;
        $this->createTable();
        $sql = '';
        $values = [];
        foreach ($arr as $k=>$v) {
            $sql .=$k.'=?,';
            $values[] =$v;
        }
        $sql = rtrim($sql,',');

        $where = ' where ';
        foreach ($condition as $k=>$v){
            $where .=$k.'=? '.$linkSymbol.' ';
            $values[] = $v;
        }
        $where = rtrim($where,$linkSymbol.' ');
        $preSql = 'update '.$this->table.' set '.$sql.$where;
        $prepare = self::$pdo->prepare($preSql);
        $res = $prepare->execute($values);
        return $res;
    }

    /**
     * 删除
     * @param $sql
     * @param array $arr
     * @return bool
     */
    public function delete(string $sql,array $arr){
        if (empty($arr)) return false;
        $this->createTable();
        $prepare = self::$pdo->prepare($sql);
        $res = $prepare->execute($arr);
        return $res;
    }

    /**
     * 创建表
     */
    public function createTable(){
        $columns = '';
        $symbol = ',';

        foreach ($this->columns as $key=>$value){
            $columns .= $key.$this->space.$value.$symbol;
        }
        $columns = rtrim($columns,',');
        $sql = "create table if not exists $this->table($columns)";
        self::$pdo->exec($sql);
        //创建索引
        if (!empty($this->index)){
            $this->createIndex();
        }
    }

    /**
     * 创建索引
     */
    public function createIndex(){
        foreach ($this->index as $key=>$value){
            $sql = 'CREATE INDEX if not exists '.$key.' on '.$this->table." ($value)";
            self::$pdo->exec($sql);
        }
    }

    /**
     * 开启事务
     */
    public function beginTransaction(){
        if (!self::$pdo->inTransaction()) self::$pdo->beginTransaction();echo '开启事务';
    }

    /**
     * 提交事务
     */
    public function commit(){
        if (self::$pdo->inTransaction()) {
            self::$pdo->commit();echo '提交事务';
        }else{
            echo '提交事务，还没有开启事务';
        }
    }

    /**
     * 回滚
     */
    public function rollBack(){
        if (self::$pdo->inTransaction()) {
            self::$pdo->rollBack();echo '回滚事务';
        }else{
            echo '回滚事务，还没有开始事务';
        }
    }

}