<?php

/*
 * This file is part of the wyw/weather.
 *
 *   (c) wangyawei <wangyw@boqii.com>
 *
 *  This source file is subject to the MIT license that is bundled.
 *
 */

namespace Wangyw\Weather;

use GuzzleHttp\Client;
use Wangyw\Weather\Exceptions\HttpException;
use Wangyw\Weather\Exceptions\InvalidException;

class Weather
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var array
     */
    protected $guzzleOptions = [];

    /**
     * Weather constructor.
     */
    public function __construct(string $key, string $baseUri)
    {
        $this->key = $key;
        $this->baseUri = $baseUri;
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * @param string|string $type
     * @param string|string $format
     *
     * @throws HttpException
     * @throws InvalidException
     *
     * @return mixed
     */
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

        return 'json' == $format ? json_decode($data, true) : $data;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }
}
