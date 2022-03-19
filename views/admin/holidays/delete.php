<?php

use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$holidays->delete($params['id']);
$errors = [];
$_SESSION['delete'] = "Votre congés a bien été supprimé.";
header('location: /Agenda/public');
exit();
?>