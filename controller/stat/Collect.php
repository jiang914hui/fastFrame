<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29
 * Time: 16:59
 */
class Collect
{
    private $pdo = null;
    private $redis = null;
    private $recordTable = '';
    private $ancientTable = '';

    public function __construct()
    {
        require '../fast/Connect.php';
        $this->pdo = Connect::dbConnect();
        $this->redis = Connect::redisConnect();
    }

    public function stat($row=1000){
        $record = $this->redis->get('statRecord');
        if (empty($record)){
            //如果发生服务器中途瘫痪等异常情况，缓存中数据可能会丢失，我们就从数据库中获取数据
            $recordSql = 'select'." ancient_id from $this->recordTable order by ancient_id desc limit ?";
            $prepared = $this->pdo->prepare($recordSql);
            $prepared->execute(array(1));
            $recordRes = $prepared->fetchAll(PDO::FETCH_ASSOC);
            $record = $recordRes[0]['ancient_id'];
        }

        $ancientSql = 'select'." * from $this->ancientTable where id>? limit $row";
        $arr = array($record);
        $prepared = $this->pdo->prepare($ancientSql);
        $prepared->execute($arr);
        //获取本次要执行分析的1000条原始数据
        $ancients = $prepared->fetchAll(PDO::FETCH_ASSOC);
        if (empty($ancients)) return ['code'=>false,'msg'=>'获取原始数据为空！'];

        //分析数据
        $this->analysis($ancients);

        $ancientId = $row+$record;
        $executeDate = date('Y-m-d H:i:s');
        //把执行到哪的id记录到缓存中
        $this->redis->set('statRecord',$ancientId,3600);
        //把执行到哪的id记录到数据库中
        $insertSql = 'insert into'." $this->recordTable (ancient_id,execute_date) VALUES (?,?)";
        $insertRes = $this->pdo->prepare($insertSql)->execute(array($ancientId,$executeDate));
        if ($insertRes) return['code'=>false,'msg'=>'插入记录表失败！'];
    }

    private function analysis($ancients){
        foreach ($ancients as $ancient){
            //
        }
    }
    private function urlAnalysis($ancient){
        //判断是什么访问方式
        $this->gteVisitType($ancient['host'],$ancient['referer']);
        //判断redis中是否存在该url
        $this->redis->get('');
    }

    private function gteVisitType($host, $url)
    {
        if (empty($url)) return 1; //直接访问
        if (strpos($url, $host)) return 2; //内部访问
        $arr = ['sg.search.yahoo.com','www.baidu.com','www.so.com','www.sougou.com','www.google.com'];
        foreach ($arr as $value) {
            if (strpos($url, $value) === false) {
                return 3; //外部访问
            }else{
                return 4; //搜索访问
            }
        }
    }

    public function getData(){

    }

    private function analysisUrl($ancient){
        $visitType = $this->gteVisitType($ancient['host'],$ancient['referer']);
        if ($visitType===2){
            //内部访问
        }
        if ($visitType===3 || $visitType===4){
            //一个外部访问的链接表，时间字段，session作为key
            //外部链接作为id然后根据这些id
            $session = $this->redis->get($ancient['session']);
            if (empty($session)){
                $arr = [
                    'visitTime'=>$ancient['time'],
                    'accumulationTime'=>1,
                    ''

                ];
                $this->redis->set($ancient['session'],'value');
            }else{

            }
        }
    }

}