<?php
if( !session_id() ) @session_start();
require '../vendor/autoload.php';

/*function register(){
    require "../page_register.php";
}*/
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
   
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
    $r->addRoute('GET', '/',['App\controllers\RegisterController','get']);
    $r->addRoute('POST', '/',['App\controllers\RegisterController','post']);
    $r->addRoute('GET', '/login',['App\controllers\LoginController','get']);
    $r->addRoute('POST', '/login',['App\controllers\LoginController','post']);
    $r->addRoute('GET', '/users',['App\controllers\UsersController','get']);
    
    $r->addRoute('GET', '/logout',['App\controllers\LogOutController','get']);
    
    $r->addRoute('GET', '/createuser',['App\controllers\CreateUserController','get']);
    
    $r->addRoute('POST', '/createuser',['App\controllers\CreateUserController','post']);
    
    $r->addRoute('GET', '/edit/{id:\d+}',['App\controllers\EditController','get']);
    $r->addRoute('POST', '/edit/{id:\d+}',['App\controllers\EditController','post']);
    
    $r->addRoute('GET', '/profile/{id:\d+}',['App\controllers\ProfileController','get']);
    
    $r->addRoute('POST', '/status/{id:\d+}',['App\controllers\StatusController','post']);
    $r->addRoute('GET', '/status/{id:\d+}',['App\controllers\StatusController','get']);
    $r->addRoute('POST', '/media/{id:\d+}',['App\controllers\MediaController','post']);
    $r->addRoute('GET', '/media/{id:\d+}',['App\controllers\MediaController','get']);
    $r->addRoute('POST', '/security/{id:\d+}',['App\controllers\SecurityController','post']);
    $r->addRoute('GET', '/security/{id:\d+}',['App\controllers\SecurityController','get']);
    
    $r->addRoute('GET', '/delete/{id:\d+}',['App\controllers\DeleteController','get']);

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        
        $controller=new $handler[0];
        
        call_user_func([$controller,$handler[1]],$vars);
        break;
}

?>