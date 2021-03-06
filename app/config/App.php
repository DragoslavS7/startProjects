<?php

namespace App\config;


class App {

    protected $controller = 'homeController';
    protected $method = 'index';
    protected $params = [];


    public function __construct()
    {
        $url = $this->parseUrl();

        if(file_exists(__DIR__.'./../Controller/' . $url[0] . '.php' )) {

            $this->controller = $url[0];
            unset( $url[0] );
        }

        require_once __DIR__.'./../Controller/' . $this->controller . '.php';

        $namespace = 'App\\Controller\\'.$this->controller;

        $this->controller = new $namespace;

        if(isset( $url[1] ))
        {
            if(method_exists($this->controller, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array( [$this->controller, $this->method], $this->params );
    }

    public function parseUrl()
    {
        if( isset($_GET['url']) ) {

            return $url = explode('/', filter_var( rtrim( $_GET['url'], '/' ), FILTER_SANITIZE_URL ));

        }
    }
}