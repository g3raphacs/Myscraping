<?php
session_start();

require '../vendor/autoload.php';
use App\Router\Router;
use App\Scrap\ScrapManager;
use App\Twig\View;
use App\Session\SessionManager;
use App\User\User;
use App\Scrap\Scrap;
use App\Selector\Selector;

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
    $userM = new \App\User\UserManager;
    $userM->signin();
});
//signup
$router->post('signup', function(){
    $userM = new \App\User\UserManager;
    $userM->signup();
});
//new scrap
$router->post('newScrap', function(){
    checkSession();
    ScrapManager::newScrap($_SESSION['scraplist']);
});
//new scrap
$router->post('delScrap', function(){
    checkSession();
    ScrapManager::delete($_POST['ID']);
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
//Window - delete
$router->post('window-del', function(){
    checkSession();
    $view = new View('parts/deletewindow.html.twig' , ['ID' => $_POST['ID']]);
});
//Window - edit
$router->post('window-edit', function(){
    checkSession();
    $view = new View('parts/editwindow.html.twig' , ['ID' => $_POST['ID']]);
});
//Window - save
$router->post('window-save', function(){
    checkSession();
    $view = new View('parts/exportwindow.html.twig' , ['ID' => $_POST['ID']]);
});
//Window - collect
$router->post('window-collect', function(){
    checkSession();
    $view = new View('parts/launchwindow.html.twig' , ['ID' => $_POST['ID']]);
});
//Window - see
$router->post('seeScrap', function(){
    checkSession();
    $view = new View('parts/viewwindow.html.twig' , ['ID' => $_POST['ID']]);
});
//Window - logo
$router->post('logo', function(){
    checkSession();
    $view = new View('parts/logo.html.twig');
});
//EDITION
//new element
$router->post('newSelector', function(){
    checkSession();
    $selector = new Selector(array(
        'name' => 'Nouvel Element',
        'format' => 'Texte',
        'parent' => '',
        'element' => '',
    ));
    $selector->addToDB($_POST['ID']);
});
//del element
$router->post('delSelector', function(){
    checkSession();
    $selector = new Selector(array());
    $selector->delToDB($_POST['ID']);
});

//elements list
$router->post('selectorlist', function(){
    checkSession();
    $selector = new Selector(array());
    $data = $selector->getValues($_POST['ID']);
    $view = new View('parts/scrapselector.html.twig' , ['ID' => $_POST['ID'],
                                                        'formats' => array('Texte', 'Nombre' , 'Prix', 'Date' ,'Image'),
                                                        'selectors' => $data,
    ]);
});

$router->run();

function checkSession(){
    $session = new SessionManager;
    if(!$session->checkSession()){
        $session->killSession();
        header('Location: ./');
    }
}

