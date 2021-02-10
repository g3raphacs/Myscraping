<?php
session_start();

require '../vendor/autoload.php';

$router = new App\Router\Router($_GET['url']);

$router->get('/', function(){
    $twig = new App\Twig\Twig('logo.html.twig');
    $twig->render();

    $session = new \App\Session\SessionManager;
    if($session->checkSession()){
        header('Location: ./'.$_SESSION['scraplist']);
    }
});

$router->get('/inscription', function(){
    $session = new \App\Session\SessionManager;
    $session->killSession();

    $twig = new App\Twig\Twig('inscription.html.twig');
    $twig->render();

});

$router->get('/connexion', function(){
    $session = new \App\Session\SessionManager;
    $session->killSession();

    $twig = new App\Twig\Twig('connexion.html.twig');
    $twig->render();

});

$router->get('/:userslug', function($userslug){
    
    $session = new \App\Session\SessionManager;
    if(!$session->checkSession()){
        $session->killSession();
        header('Location: ./');
    }
    echo "page de l'utilisateur : " . $userslug;
});



//AJAX ROUTES
$router->post('login', function(){
    $userM = new \App\User\UserManager;
    $userM->signin();
});

$router->post('signup', function(){
    $userM = new \App\User\UserManager;
    $userM->signup();
});

$router->run();

