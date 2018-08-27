<?php

if (!function_exists('route_class')) {
    function route_class() 
    {
        // 将当前请求的路由名称转换为CSS类名称，作用是允许我们针对某个页面某个页面样式定制
        return str_replace('.', '-', Route::currentRouteName());
    }
}