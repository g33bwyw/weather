<h1 align="center"> weather </h1>

<p align="center"> 基于高德地图的测试包.</p>
[![Build status](https://travis-ci.org/g33bwyw/weather.svg?branch=master)](https://travis-ci.org/github/g33bwyw/weather) 
![StyleCI build status](https://github.styleci.io/repos/289841371/shield) 

## 安装

```shell
$ composer require wangyw/weather -vvv
```

## 使用
```
use Wangyw\Weather\Weather;

$key = '*************';
$url = 'https://restapi.amap.com/v3/weather/weatherInfo';
$weather = new Weather('mock_key', $url);

```

## 获取实时天气
```
$response = $weather->getWeather('深圳', 'base', 'json');
```

## 参数说明
```
getWeather(string $cityName, string $type = 'base', string $format = 'json')
```
- $cityName -城市名
- $type -返回内容类型 base:实时天气 all:返回预报天气,默认为base
- $format -返回内容格式 默认为json ,可以指定为xml

## 在laravel中使用
- 在config文件中添加weather.php
```
return [
    'weather' => [
        'key' => '********',
        'url' => 'https://restapi.amap.com/v3/weather/weatherInfo',
    ],
];
```
- 方法参数注入
```
public function getBaseWeather(Weather $weather) {
    $response = $weather->getWeather('上海');
}
```
- 服务名域名访问
```
$response = app('weather')->getWeather('上海');
``` 
## 参考
[高德地图开放平台api接口](https://lbs.amap.com/api/webservice/guide/api/weatherinfo/)
## License

MIT