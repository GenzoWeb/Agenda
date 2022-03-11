<?php

use App\Connection;

$pdo = Connection::getPDO();
$events = new \App\calendar\Events($pdo);
$events->delete($params['id']);
$errors = [];
header('location: /Agenda/public?supprimer=rendez-vous');
exit();
?>