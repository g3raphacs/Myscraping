<?php
session_start();

require '../vendor/autoload.php';
use App\Router\Router;
use App\Twig\Twig;
use App\Session\SessionManager;

$router = new Router($_GET['url']);

//root
$router->get('/', function(){
    $twig = new Twig('logo.html.twig');
    $twig->render();

    $session = new SessionManager;
    if($session->checkSession()){
        header('Location: ./'.$_SESSION['scraplist']);
    }
});

//signup
$router->get('/inscription', function(){
    $session = new SessionManager;
    $session->killSession();

    $twig = new Twig('inscription.html.twig');
    $twig->render();
});

//signin
$router->get('/connexion', function(){
    $session = new SessionManager;
    $session->killSession();

    $twig = new Twig('connexion.html.twig');
    $twig->render();
});

//dashboard
$router->get('/:userslug', function($userslug){
    
    $session = new SessionManager;
    if(!$session->checkSession()){
        $session->killSession();
        header('Location: ./');
    }

    $twig = new Twig('dashboard.html.twig');
    $twig->render();
});



//AJAX ROUTES
//login
$router->post('login', function(){
    $userM = new \App\User\UserManager;
    $userM->signin();
});
//signup
$router->post('signup', function(){
    $userM = new \App\User\UserManager;
    $userM->signup();
});

$router->run();

