<?php
session_start();

require '../vendor/autoload.php';
use App\Router\Router;
use App\Scrap\ScrapManager;
use App\Twig\View;
use App\Session\SessionManager;
use App\User\User;
use App\Selector\Selector;
use App\Selector\SelectorManager;
use App\Scrap\ScrapExec;

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
//update params
$router->post('validparams', function(){
    checkSession();
    ScrapManager::updateParams($_POST['ID']);
});
//update selector
$router->post('updateSelector', function(){
    checkSession();
    SelectorManager::updateSelector($_POST['ID']);
});
//exec scrap
$router->post('scrapExec', function(){
    checkSession();
    $scrapEx = new ScrapExec($_POST['ID']);
});
//load elements
$router->post('getElements', function(){
    checkSession();
    ScrapManager::loadElements($_POST['ID']);
});
//show elements
$router->post('loadElements', function(){
    checkSession();
    $elements = json_decode($_POST['Elements']);
    $view = new View('parts/scrapelement.html.twig' , ['ID' => $_POST['ID'],
                                                        'Elements' => $elements
    ]);
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
    $params = (array) ScrapManager::findParams($_POST['ID']);
    $view = new View('parts/editwindow.html.twig' , ['ID' => $_POST['ID'],
                                                    'params' => $params ]);
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
//Window - seeScrapPoints
$router->post('seeScrappoints', function(){
    checkSession();
    $dates = (array) ScrapManager::getDates($_POST['ID']);
    $view = new View('parts/scrappoint.html.twig' , ['ID' => $_POST['ID'],
                                                    'Dates' => $dates]);
});
//Window - loadSingles
$router->post('loadsingles', function(){
    checkSession();
    $singles = (array) ScrapManager::getSingles($_POST['ID']);
    $view = new View('parts/scrapsingle.html.twig' , ['ID' => $_POST['ID'],
                                                    'singles' => $singles]);
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

