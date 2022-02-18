<?php
require '../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'calendar/home', 'home')
    ->get('/mois-[i:month]-annee-[i:year]', 'calendar/month', 'month')
    ->get('/annee-[i:year]', 'calendar/calendars', 'calendars')
    ->get('/evenements-[i:year]-[i:month]-[i:day]', 'event/events', 'events')
    ->get('/login', 'log/login', 'login', 'post')
    ->get('/rendez-vous/[i:day]?/[i:month]?/[i:year]?', 'event/createEvent', 'createEvent', 'post')
    ->get('/repos/[i:day]?/[i:month]?/[i:year]?', 'event/daysOff', 'daysOff', 'post')
    ->get('/logout', 'log/logout', 'logout')
    ->run();