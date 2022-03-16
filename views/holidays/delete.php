<?php

use App\Connection;

$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$holidays->delete($params['id']);
$errors = [];
header('location: /Agenda/public?supprimer=congés');
exit();
?>