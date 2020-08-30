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

class WeatherServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Weather::class, function () {
            return new Weather(config('weather.weather.key'), config('weather.weather.url'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}
