<?php
use App\src\app\Router;

require '../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'calendar/home', 'home')
    ->get('/annee', 'calendar/calendars', 'calendars')
    ->run();