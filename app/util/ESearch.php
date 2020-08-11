<?php
namespace app\util;

require root_path().'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class ESearch
{
    protected $client;

    protected static $instance = null;

    // 构造函数
    public function __construct()
    {
        //$this->client = ClientBuilder::create()->build();
        $this->client = ClientBuilder::create()->setHosts(['127.0.0.1:8080'])->build();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * 添加索引
     */
    public function createIndex($indexName, $indexType)
    {
        $indexParams['index'] = $indexName;                         //索引名称
        $indexParams['type'] = $indexType;                          //类型名称
        //$indexParams['body']['settings']['number_of_shards'] = 1;   //当前只有一台ES，1就可以了
        //$indexParams['body']['settings']['number_of_replicas'] = 0; //副本0，因为只有一台ES
        $ret = $this->client->indices()->create($indexParams);
        return $ret;
    }

    //插入索引数据
    public function addDocument($indexName, $indexType, $body, $id = '')
    {
        $params = array();
        $params['index'] = $indexName;      //索引名称
        $params['type'] = $indexType;       //类型名称
        $params['body'] = $body;
        if (!empty($id)) {
            $params['id'] = $id;            //不指定id，系统会自动生成唯一id
        }
        $this->client->index($params);
    }

    // 删除索引
    public function deleteIndex($indexName)
    {
        $deleteParams['index'] = $indexName;
        $this->client->indices()->delete($deleteParams);
    }

    // 删除文档
    public function delete_document($indexName, $indexType, $id)
    {
        $deleteParams = array();
        $deleteParams['index'] = $indexName;
        $deleteParams['type'] = $indexType;
        $deleteParams['id'] = $id;
        $this->client->delete($deleteParams);
    }

    // 更新文档
    public function update_document($indexName, $indexType, $id, $body)
    {
        $updateParams = array();
        $updateParams['index'] = $indexName;
        $updateParams['type'] = $indexType;
        $updateParams['id'] = $id;
        $updateParams['body']  = $body;
       $response = $this->client->update($updateParams);

    }

    public function search($indexName, $indexType, $body)
    {
        $searchParams['index'] = $indexName;
        $searchParams['type'] = $indexType;
        $searchParams['from'] = 0;
        $searchParams['size'] = 100;
        $searchParams['sort'] = array(
            '_score' => array(
                'order' => 'desc'
            )
        );
        $searchParams['body']['query']["bool"]['must']["match"]['content'] = "宝马";
        //$searchParams['body'] = $body;
        $retDoc = $this->client->search($searchParams);
        return $retDoc;
    }

    public function get_document($indexName, $indexType, $body)
    {
        $getParams = array();
        $getParams['index'] = $indexName;
        $getParams['type'] = $indexType;
        $getParams['id'] = $id;
        $retDoc = $this->client->get($getParams);
        print_r($retDoc);
    }
}
