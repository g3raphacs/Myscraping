<?php

require '../vendor/autoload.php';



$router = new App\Router\Router($_GET['url']);

var_dump($_GET['url']);

$router->get('/', function(){
    echo 'home page';
});

$router->get('/inscription', function(){
    echo 'Inscription';
});

$router->get('/connexion', function(){
    echo 'Connexion';
});

$router->get('/:userslug', function($userslug){
    echo "page de l'utilisateur : " . $userslug;
});

$router->get('/:user/:scrap', function($user,$scrap){
    echo "nom du scrap : " . $scrap . ", pour l'utilisateur : ".$user;
});

$router->post('/ajax/:user/:scrap', function($user,$scrap){
    echo "nom du scrap : " . $scrap . ", pour l'utilisateur : ".$user;
});

$router->run();

