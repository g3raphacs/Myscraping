<?php

namespace App\Router;

class Router {
    private $url;

    private $routes = [];

    public function __construct($url){
        $this->url = $url;
    }

    public function get($path, $func){
        $route = new Route($path, $func);
        $this->routes['GET'][] = $route;
    }

    public function post($path, $func){
        $route = new Route($path, $func);
        $this->routes['POST'][] = $route;
    }

    public function run(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach($this->routes[$_SERVER['REQUEST_METHOD']]as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        
        throw new RouterException('No matching route');
    }
}