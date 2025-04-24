<?php

namespace app\services;

use app\models\search\MoexSearch;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Request;

class MoexService
{
    private const MAIN_URL ='http://iss.moex.com/iss/';

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function moexInfo(): void
    {
        $response = $this->responseMoex('securities/', [
            'limit' => 1000,
            'group_by' => 'group',
            'group_by_filter' => MoexSearch::STOCK_BONDS
        ]);

        printr($response->send(), 1);

        //sleep(1);
    }

    /**
     * @throws InvalidConfigException
     */
    private function responseMoex($action, array $data = [], string $method = 'get'): Request
    {
        $client = new Client();

        return $client->createRequest()
            ->setMethod($method)
            ->setUrl(self::MAIN_URL . $action)
            ->setData($data);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getColumnInfo(): void
    {
        $response = $this->responseMoex('index/');

        printr($response->send(), 1);
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function dividends(string $stock): void
    {
        $response = $this->responseMoex('securities/' . $stock . '/dividends');

        printr($response->send(), 1);

        //sleep(1);
    }
}