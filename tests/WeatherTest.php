<?php
/*
 * This file is part of the openapi package.
 *
 * (c) 商城组<shop-rd@boqii.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wangyw\Weather\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery\Matcher\AnyArgs;
use PHPUnit\Framework\TestCase;
use Wangyw\Weather\Exceptions\HttpException;
use Wangyw\Weather\Exceptions\InvalidException;
use Wangyw\Weather\Weather;

class WeatherTest extends TestCase
{
    //测试参数类型
    public function testInvalidType()
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';
        $weather = new Weather('mock_key', $url);
        $this->expectException(InvalidException::class);
        $this->expectExceptionMessage('参数错误');
        $weather->getWeather('深圳', 'base', 'array');

        $this->fail('参数错误通过验证失败');
    }

    public function testHttpException()
    {
        $url = 'https://qrestapi.amap.com/v3/weather/weatherInfo';
        $weather = new Weather('mock_key', $url);
        $this->expectException(HttpException::class);
        $weather->getWeather('深圳', 'base', 'json');
        $this->fail('网络不通验证失败');
    }

    //测试获取天气信息地址
    public function testGetWeather()
    {
        //设置返回
        $response = new Response(200, [], json_encode(['success' => true], true));

        //模拟http请求返回
        $client = \Mockery::mock(Client::class);
        $query = [
            'key'        => 'mokey-key',
            'city'       => '深圳',
            'extensions' => 'base',
            'output'     => 'json',
        ];
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';
        $client->allows()->get($url, ['query' => $query])->andReturn($response);

        //将客户端返回添加到weather对象中去
        $weather = \Mockery::mock(Weather::class, ['mokey-key', $url])
            ->makePartial();
        $weather->allows()->getHttpClient()->andReturn($client);

        $this->assertSame(['success' => true], $weather->getWeather('深圳'));
    }

    //测试获取天气信息获取异常
    public function testGetWeatherWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()->get(new AnyArgs())->andThrow(new \Exception('request time out'));
        $url = 'https://qrestapi.amap.com/v3/weather/weatherInfo';
        $weather = \Mockery::mock(Weather::class, ['mokey-key', $url])->makePartial();
        $weather->allows()->getHttpClient()->andReturn($client);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request time out');
        $weather->getWeather('深圳');
    }

    //测试获取Httpclient
    public function testGetHttpClient()
    {
        $url = 'https://qrestapi.amap.com/v3/weather/weatherInfo';
        $weather = \Mockery::mock(Weather::class, ['mokey-key', $url])->makePartial();
        //$weather = new Weather('mokey-key', $url);
        $this->assertInstanceOf(ClientInterface::class, $weather->getHttpClient());
    }

    //测试生成Guzzle
    public function testSetGuzzleOptions()
    {
        $url = 'https://qrestapi.amap.com/v3/weather/weatherInfo';
        $weather = \Mockery::mock(Weather::class, ['mokey-key', $url])->makePartial();
        $weather->setGuzzleOptions(['timeout' => 5000]);

        $this->assertSame(5000, $weather->getHttpClient()->getConfig('timeout'));
    }
}
