<?php

require '../vendor/autoload.php';

$router = new App\Router\Router($_GET['url']);

$router->get('/', function(){
    $twig = new App\Twig\Twig('logo.html.twig');
    $twig->render();
});

$router->get('/inscription', function(){
    //render template
    $twig = new App\Twig\Twig('inscription.html.twig');
    $twig->render([
   
        ]);
});

$router->get('/connexion', function(){

    $twig = new App\Twig\Twig('connexion.html.twig');
    $twig->render([
   
        ]);
});

$router->get('/:userslug', function($userslug){
    echo "page de l'utilisateur : " . $userslug;
});

$router->get('/:user/:scrap', function($user,$scrap){
    echo "nom du scrap : " . $scrap . ", pour l'utilisateur : ".$user;
});


//AJAX ROUTES
$router->post('login', function(){
    $userM = new \App\UserManager;
    echo $userM->signin();
});

$router->post('signup', function(){
    $userM = new \App\UserManager;
    echo $userM->signup();
});

$router->run();

