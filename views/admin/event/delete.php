<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$events = new \App\calendar\Events($pdo);
$events->delete($params['id']);
$errors = [];
$_SESSION['delete'] = "Votre rendez-vous a bien été supprimé.";
header('location: /Agenda/public');
exit();
?>