<?php
class BaseController
{
    protected function baseUrl($url = '')
    {
        $path       = dirname($_SERVER['SCRIPT_NAME']);
        $path       = trim($path, '/');
        $baseUrl    = Request::getUrl();
        $baseUrl    = trim($baseUrl, '/');
        return $baseUrl.'/'.$path.( $path ? '/' : '' ) . $url;
    }
}