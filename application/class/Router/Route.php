<?php

namespace App\Router;

class Route {

    private $path;
    private $func;
    private $matches;

    public function __construct($path , $func){
        $this->path = trim($path, '/');
        $this->func = $func;
    }

    public function match($url){
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)' ,$this->path );
        $regex ="#^$path$#i";
        if(!preg_match($regex, $url , $matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    public function call(){
        return call_user_func_array($this->func, $this->matches);
    }

}