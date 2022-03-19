<?php
use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$title = "Nombre de jours";

$daysHolidays = $holidays->getCounter();
$diffDays = $holidays->getNumberDaysSet();
?>

<div class="section_admin_number_day">
   <h1>Nombre de jours</h1>
   <table> 
      <?php foreach($daysHolidays as $total): ?>
         <tr>
            <td> <?= $total['name'] ?></td>
            <td> <?= $total['numbers'] ?></td>
         </tr>
      <?php endforeach ?>
   </table>
   <a href="/Agenda/public/gestion-modif-compteur" class="btn">Modifier Nombre de jours</a>
   <h1>Nombre de jours restant</h1>
   <table>
      <tr>
         <td>Nom</td>
         <td>Nombre restant</td>
      </tr>
      <?php foreach($diffDays as $key => $value): ?>
         <tr>
            <td> <?= $key ?></td>
            <td> <?= $value ?></td>
         </tr>
      <?php endforeach ?>
   </table>
</div>