<?php
/*
 * This file is part of the openapi package.
 *
 * (c) 商城组<shop-rd@boqii.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Wyw\Weather;

use GuzzleHttp\Client;
use Wyw\Weather\Exceptions\HttpException;
use Wyw\Weather\Exceptions\InvalidException;

class Weather
{
    protected $key;
    protected $baseUri;
    protected $guzzleOptions = [];

    public function __construct(string  $key, string $baseUri)
    {
        $this->key = $key;
        $this->baseUri = $baseUri;
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    public function getHttpClient()
    {
        return (new Client($this->guzzleOptions));
    }

    public function getWeather(string $cityName, string $type = 'base', string $format = 'json')
    {
        if (!in_array(strtolower($format), ['xml', 'json'])) {
            throw new InvalidException('参数错误', 2001);
        }
        $query = [
            'key' => $this->key,
            'city' => $cityName,
            'extensions' => $type,
            'output' => $format,
        ];

        try {
            $response = $this->getHttpClient()->get($this->baseUri, ['query' => $query]);
            $data = $response->getBody()->getContents();
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }


        return $format == 'json' ? json_decode($data, true) : $data;
    }

}