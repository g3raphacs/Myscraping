<?php
session_start();

require '../vendor/autoload.php';
use App\Router\Router;
use App\Scrap\ScrapManager;
use App\Twig\View;
use App\Session\SessionManager;
use App\User\User;
use App\Scrap\Scrap;

$router = new Router($_GET['url']);

//root
$router->get('/', function(){
    $view = new View('logo.html.twig');

    $session = new SessionManager;
    if($session->checkSession()){
        header('Location: ./'.$_SESSION['scraplist']);
    }
});

//signup
$router->get('/inscription', function(){
    $session = new SessionManager;
    $session->killSession();

    $view = new View('inscription.html.twig');
});

//signin
$router->get('/connexion', function(){
    $session = new SessionManager;
    $session->killSession();

    $view = new View('connexion.html.twig');
});

//dashboard
$router->get('/:userslug', function($userslug){
    
    $session = new SessionManager;
    if(!$session->checkSession() || $userslug != $_SESSION['scraplist']){
        $session->killSession();
        header('Location: ./');
    }
    $user = new User($_SESSION['scraplist']);

    $scraps = (array) ScrapManager::findScraps($_SESSION['scraplist']);

    $view = new View('dashboard.html.twig' , [
        'username' => $user->get_username(),
        'scraps' => $scraps
    ]);
});


//AJAX ROUTES
//login
$router->post('login', function(){
    checkSession();
    $userM = new \App\User\UserManager;
    $userM->signin();
});
//signup
$router->post('signup', function(){
    checkSession();
    $userM = new \App\User\UserManager;
    $userM->signup();
});
//new scrap
$router->post('newScrap', function(){
    checkSession();
    ScrapManager::newScrap($_SESSION['scraplist']);
});
//twig - Options
$router->post('twigOptions', function(){
    checkSession();
    $view = new View('parts/options.html.twig' , ['ID' => $_POST['ID']]);
});
//twig - Scrap List
$router->post('scraplist', function(){
    checkSession();
    $scraps = (array) ScrapManager::findScraps($_SESSION['scraplist']);

    $view = new View('parts/scraplist.html.twig' , ['scraps' => $scraps]);
});

$router->run();

function checkSession(){
    $session = new SessionManager;
    if(!$session->checkSession()){
        $session->killSession();
        header('Location: ./');
    }
}

