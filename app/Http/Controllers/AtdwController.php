<?php

namespace App\Http\Controllers;

use App;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;
use TheSeer\Tokenizer\Exception;

class AtdwController extends Controller
{
    protected $apiKey;
    protected $baseUrl;
    protected $httpClient;
    protected $redisClient;

    public function __construct()
    {
        $this->apiKey = \Config::get('atdw.api_key');
        $this->baseUrl = \Config::get('atdw.url');
        $this->httpClient = new Client();
    }
    public function index(Request $request)
    {
        $this->init();
        return view('welcome');
    }

    public function init()
    {
        $this->initRedisByKey('regions');
        $this->initRedisByKey('areas');
        $this->initProducts();
        //$this->printRedisDataByKey('regions');
        // $this->printRedisDataByKey('areas');
    }

    public function initRedisByKey(String $key, String $state = 'NSW')
    {
        $redisHasData = Redis::get($key);
        if (!$redisHasData) {
            // fetch data in NSW and store to Redis
            $data = $this->getDataByState($key, $state);
            if (isset($data) && !empty($data)) {
                $this->addArrToRedis($key, $data);
            }
        }
    }

    public function initProducts()
    {
        $redisHasData = Redis::get('products');
        if (!$redisHasData) {
            $query = [
                'key' => $this->apiKey,
                'out' => 'json',
                'st' => 'NSW',
                'cats' => 'ACCOMM',
                'size' => 10,
                'pge' => 1
            ];
            $data = $this->getProducts($query);
            if (isset($data) && !empty($data)) {
                $this->addArrToRedis('products', $data);
            }
        }
    }

    public function getDataByState($key, $state = 'NSW')
    {
        $dataUrl = $this->baseUrl . $key;
        $query = [
            'key' => $this->apiKey,
            'out' => 'json',
            'st' => $state
        ];
        $data = (string)$this->httpClient->get($dataUrl, [
            'query' => $query
        ])->getBody();
        return $this->decodeResponse($data);
    }

    public function getProducts($queryArr)
    {
        $dataUrl = $this->baseUrl . 'products';

        $data = (string)$this->httpClient->get($dataUrl, [
            'query' => $queryArr
        ])->getBody();
        return $this->decodeResponse($data);
    }

    public function printRedisDataByKey(String $key)
    {
        exit(json_encode([
            $key => $this->getArrFromRedis($key)
        ]));
    }


    public function getAreasByState(String $state = "NSW")
    {
        $areasUrl = $this->baseUrl . 'areas';
        $query = [
            'key' => $this->apiKey,
            'out' => 'json',
            'st' => $state
        ];
        $areas = (string)$this->httpClient->get($areasUrl, [
            'query' => $query
        ])->getBody();
        return $this->decodeResponse($areas);
    }

    public function getRegionsByState(String $state = 'NSW')
    {
        $areasUrl = $this->baseUrl . 'regions';
        $query = [
            'key' => $this->apiKey,
            'out' => 'json',
            'st' => $state
        ];
        $areas = (string)$this->httpClient->get($areasUrl, [
            'query' => $query
        ])->getBody();
        return $this->decodeResponse($areas);
    }

    public function decodeResponse($data, $assArr = true)
    {
        return json_decode(mb_convert_encoding($data, 'UTF-8', 'UTF-16LE'), $assArr);
    }

    public function getArrFromRedis(String $key)
    {
        try {
            $data = json_decode(Redis::get($key), true);
            return $data;
        } catch (Exception $e) {
            exit(json_encode([
                'error' => $e->getMessage()
            ]));
        }
    }

    public function addArrToRedis(String $key, array $arr)
    {
        Redis::set($key, json_encode($arr));
    }

    public function delKeyValFromRedis(String $key)
    {
        Redis::set($key, null);
        Redis::del($key);
    }
}
