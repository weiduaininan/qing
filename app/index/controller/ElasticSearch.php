<?php
namespace app\index\controller;
require './vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class ElasticSearch
{
    private $client;
    // 构造函数
    public function __construct()
    {
        $params = array(
            '127.0.0.1:9200'
        );
        $this->client = ClientBuilder::create()->setHosts($params)->build();
    }

    // 创建索引
    public function create_index($index_name = 'test_ik') { // 只能创建一次
        $params = [
            'index' => $index_name,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 0
                ]
            ]
        ];

        try {
            return $this->client->indices()->create($params);
        } catch (Elasticsearch\Common\Exceptions\BadRequest400Exception $e) {
            $msg = $e->getMessage();
            $msg = json_decode($msg,true);
            return $msg;
        }
    }

    // 删除索引
    public function delete_index($index_name = 'test_ik') {
        $params = ['index' => $index_name];
        $response = $this->client->indices()->delete($params);
        return $response;
    }

    // 创建文档模板
    public function create_mappings($type_name = 'goods',$index_name = 'test_ik') {

        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'body' => [
                $type_name => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'integer', // 整型
                            'index' => 'not_analyzed',
                        ],
                        'title' => [
                            'type' => 'string', // 字符串型
                            'index' => 'analyzed', // 全文搜索
                            'analyzer' => 'ik_max_word'
                        ],
                        'content' => [
                            'type' => 'string',
                            'index' => 'analyzed',
                            'analyzer' => 'ik_max_word'
                        ],
                        'price' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->client->indices()->putMapping($params);
        return $response;
    }

    // 查看映射
    public function get_mapping($type_name = 'goods',$index_name = 'test_ik') {
        $params = [
            'index' => $index_name,
            'type' => $type_name
        ];
        $response = $this->client->indices()->getMapping($params);
        return $response;
    }

    // 添加文档
    public function add_doc($id,$doc,$index_name = 'test_ik',$type_name = 'goods') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id,
            'body' => $doc
        ];

        $response = $this->client->index($params);
        return $response;
    }

    // 判断文档存在
    public function exists_doc($id = 1,$index_name = 'test_ik',$type_name = 'goods') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id
        ];

        $response = $this->client->exists($params);
        return $response;
    }


    // 获取文档
    public function get_doc($id = 1,$index_name = 'test_ik',$type_name = 'goods') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id
        ];

        $response = $this->client->get($params);
        return $response;
    }

    // 更新文档
    public function update_doc($id = 1,$index_name = 'test_ik',$type_name = 'goods') {
        // 可以灵活添加新字段,最好不要乱添加
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id,
            'body' => [
                'doc' => [
                    'title' => '苹果手机iPhoneX'
                ]
            ]
        ];

        $response = $this->client->update($params);
        return $response;
    }

    // 删除文档
    public function delete_doc($id = 1,$index_name = 'test_ik',$type_name = 'goods') {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id
        ];

        $response = $this->client->delete($params);
        return $response;
    }

    // 查询文档 (分页，排序，权重，过滤)
    public function search_doc($keywords = "电脑",$index_name = "test_ik",$type_name = "goods",$from = 0,$size = 2) {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [ 'match' => [ 'title' => [
                                'query' => $keywords,
                                'boost' => 3, // 权重大
                            ]]],
                            [ 'match' => [ 'content' => [
                                'query' => $keywords,
                                'boost' => 2,
                            ]]],
                        ],
                    ],
                ],
                'sort' => ['price'=>['order'=>'desc']]
                , 'from' => $from, 'size' => $size
            ]
        ];

        $results = $this->client->search($params);
//        $maxScore  = $results['hits']['max_score'];
//        $score = $results['hits']['hits'][0]['_score'];
//        $doc   = $results['hits']['hits'][0]['_source'];
        return $results;
    }

    function index(){
        $es = new ElasticSearch();

        //$r = $es->delete_index();

        //$r = $es->create_index();

        //$r = $es->create_mappings();

        $r = $es->get_mapping();
        print_r($r);

        $docs = [];
        $docs[] = ['id'=>1,'title'=>'苹果手机','content'=>'苹果手机，很好很强大。','price'=>1000];
        $docs[] = ['id'=>2,'title'=>'华为手环','content'=>'荣耀手环，你值得拥有。','price'=>300];
        $docs[] = ['id'=>3,'title'=>'小度音响','content'=>'智能生活，快乐每一天。','price'=>100];
        $docs[] = ['id'=>4,'title'=>'王者荣耀','content'=>'游戏就玩王者荣耀，快乐生活，很好很强大。','price'=>998];
        $docs[] = ['id'=>5,'title'=>'小汪糕点','content'=>'糕点就吃小汪，好吃看得见。','price'=>98];
        $docs[] = ['id'=>6,'title'=>'小米手环3','content'=>'秒杀限量，快来。','price'=>998];
        $docs[] = ['id'=>7,'title'=>'iPad','content'=>'iPad，不一样的电脑。','price'=>2998];
        $docs[] = ['id'=>8,'title'=>'中华人民共和国','content'=>'中华人民共和国，伟大的国家。','price'=>19999];

        foreach ($docs as $k => $v) {
            $r = $es->add_doc($v['id'],$v);
            print_r($r);
        }

        $r = $es->get_doc();

        // $r = $es->update_doc();

        // $r = $es->delete_doc();

        // $r = $es->exists_doc();

        $r = $es->search_doc("手环 电脑");
        // $r = $es->search_doc("玩");
        // $r = $es->search_doc("中华");

        print_r($r);
    }

}

