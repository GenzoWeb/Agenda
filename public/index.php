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
    ->get('/conges-[i:year]-[i:month]-[i:day]', 'holidays/holiday', 'holiday', 'post')
    ->get('/login', 'log/login', 'login', 'post')
    ->get('/logout', 'log/logout', 'logout')
    ->get('/rendez-vous/[i:day]?/[i:month]?/[i:year]?', 'admin/event/createEvent', 'createEvent', 'post')
    ->get('/rendez-vous-modif-[i:id]', 'admin/event/edit', 'edit', 'post')
    ->get('/rendez-vous-suppr-[i:id]', 'admin/event/delete', 'delete')
    ->get('/repos/[i:day]?/[i:month]?/[i:year]?', 'admin/holidays/daysOff', 'daysOff', 'post')
    ->get('/conges-modif-[i:id]', 'admin/holidays/edit', 'editHoliday', 'post')
    ->get('/conges-suppr-[i:id]', 'admin/holidays/delete', 'deleteHoliday')
    ->get('/gestion-suppr', 'admin/holidays/deleteList', 'adminDelete', 'post')
    ->get('/gestion-compteur', 'admin/holidays/numberDays', 'adminCounter')
    ->get('/gestion-modif-compteur', 'admin/holidays/editNumberDays', 'adminEditCounter', 'post')
    ->get('/erreur', 'base/notFound', 'notFound')
    ->run();