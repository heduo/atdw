<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;
use TheSeer\Tokenizer\Exception;

class AtdwController extends Controller
{
    protected $apiKey; // ATLAS api key
    protected $baseUrl; // ATLAS base url
    protected $httpClient; // guzzle client
    protected $redisClient;

    public function __construct()
    {
        $this->apiKey = \Config::get('atdw.api_key'); // get api key from .env file
        $this->baseUrl = \Config::get('atdw.url'); // get api base url .env file
        $this->httpClient = new Client(); // create guzzle client
        $this->init(); // initial Redis data with regions and areas
    }

    /**
     *  Index page, which has will include Vue code
     * @param Request $request
     */
    public function index(Request $request)
    {
        return view('index');
    }

    /**
     *  Initial regions and areas records to Redis
     */
    public function init()
    {
        $this->initRedisByKey('regions');
        $this->initRedisByKey('areas');
    }

    /**
     * @param string $key 
     * @param string $state
     * @return void
     */
    public function initRedisByKey(String $key, String $state = 'NSW')
    {
        $redisHasData = Redis::get($key);
        if (!$redisHasData) {
            // fetch data in NSW and store to Redis
            $data = $this->getRedisDataByKey($key, $state);
            if (isset($data) && !empty($data)) {
                $this->addArrToRedis($key, $data);
            }
        }
    }

    /**
     * @param string $key 
     * @param string $state
     * @return array $dataArr
     */
    public function getRedisDataByKey($key, $state = 'NSW')
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
        $dataArr = $this->decodeResponse($data);
        return $dataArr;
    }

    /**
     * Method that send API request to ATLAS to filter products
     * @param array $queryArr 
     * @return array $dataArr
     */
    public function getProducts($queryArr)
    {
        $dataUrl = $this->baseUrl . 'products';

        $data = (string)$this->httpClient->get($dataUrl, [
            'query' => $queryArr
        ])->getBody();

        $dataArr = $this->decodeResponse($data);
        return $dataArr;
    }

    /**
     * @param string $data 
     * @param boolean $assArr
     * @return array $dataArr
     */
    public function decodeResponse($data, $assArr = true)
    {
        $dataArr = json_decode(mb_convert_encoding($data, 'UTF-8', 'UTF-16LE'), $assArr);
        return $dataArr;
    }

    /**
     * Get array collection from Redis as an associate array
     * @param string $key  
     * @return array $data
     */
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

    /**
     * Save array to Redis
     * @param string $key
     * @param array $arr
     * @return void
     */
    public function addArrToRedis(String $key, array $arr)
    {
        Redis::set($key, json_encode($arr));
    }

    /**
     * Get options to be used as selections options, include regions and areas
     * @param string $stateCode
     */
    public function getOptions(String $stateCode)
    {
        try {
            $regions = $this->getRedisDataByKey('regions', $stateCode);
            $areas = $this->getRedisDataByKey('areas', $stateCode);
            if ($regions && $areas) {
                $options['regions'] = $regions;
                $options['areas'] = $areas;

                exit(json_encode([
                    'success' => true,
                    'options' => $options
                ]));
            }
            exit(json_encode([
                'success' => false,
                'err_msg' => 'Fail to fetch options'
            ]));
        } catch (Exception $e) {
            exit(json_encode([
                'success' => false,
                'err_msg' => $e->getMessage()
            ]));
        }
    }

    /**
     * Filter products via query from request
     * @param Request $request
     */
    public function filterProducts(Request $request)
    {
        // prepare query data array
        $queryArr = $request->query();
        $queryArr['key'] = $this->apiKey;
        $queryArr['cats'] = 'ACCOMM';
        $queryArr['order'] = 'rnd'; // random order
        $queryArr['out'] = 'json';

        // send api request to fetch filtered data
        $products = $this->getProducts($queryArr);
        if (isset($products) && sizeof($products)) {
            exit(json_encode([
                'success' => true,
                'products' => $products
            ]));
        }
    }
}
