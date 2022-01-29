<?php

use App\src\app\Router;
use Router as GlobalRouter;

require '../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// $router = new AltoRouter();

// $router->setBasePath('Agenda/public/');

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'calendar/home', 'home')
    ->get('/test', 'calendar/calendars', 'calendars')
    ->run();
// $router->get('/year=[i:year]', 'views/calendar/year', year);

// $router->map('GET', '/', function() {
//     require dirname(__DIR__) . '/views/calendar/home.php';
//     // echo 'Salut';
// });

// $router->map('GET', '/test', function() {
//     require dirname(__DIR__) . '/views/calendar/calendars.php';
// });

// $router->map( 'GET', '/year=[i:year]', function( $year ) {
// 	require dirname(__DIR__) . '/views/calendar/year.php';
// });

// $match = $router->match();
// if( is_array($match) && is_callable( $match['target'] ) ) {
// 	call_user_func_array( $match['target'], $match['params'] ); 
// } else {
// 	// no route was matched
// 	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
// }