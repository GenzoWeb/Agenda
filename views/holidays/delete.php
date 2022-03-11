<?php

use App\Connection;

$pdo = Connection::getPDO();
$events = new \App\calendar\Holidays($pdo);
$events->delete($params['id']);
$errors = [];
header('location: /Agenda/public?supprimer=congés');
exit();
?>