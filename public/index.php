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
    ->get('/rendez-vous-[i:year]-[i:month]-[i:day]', 'event/events', 'events')
    ->get('/rendez-vous/[i:day]?/[i:month]?/[i:year]?', 'event/createEvent', 'createEvent', 'post')
    ->get('/rendez-vous-modif-[i:id]', 'event/edit', 'edit', 'post')
    ->get('/rendez-vous-suppr-[i:id]', 'event/delete', 'delete')
    ->get('/conges-[i:year]-[i:month]-[i:day]', 'holidays/holiday', 'holiday', 'post')
    ->get('/repos/[i:day]?/[i:month]?/[i:year]?', 'holidays/daysOff', 'daysOff', 'post')
    ->get('/conges-modif-[i:id]', 'holidays/edit', 'editHoliday', 'post')
    ->get('/conges-suppr-[i:id]', 'holidays/delete', 'deleteHoliday')
    ->get('/login', 'log/login', 'login', 'post')
    ->get('/logout', 'log/logout', 'logout')
    ->run();