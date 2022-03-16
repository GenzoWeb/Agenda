<?php
use App\Connection;

$auth = new \App\Auth();
$auth->log($_SERVER['REDIRECT_URL']);
$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$title = "Nombre de jours";
$daysHolidays = $holidays->getCounter();
$nameHolidays = $holidays->getNameHolidays();
$children = "Enfant malade";
$totalDays = [];
$daysSet = [];
$diffDays = [];

foreach($daysHolidays as $t) {
   $totalDays[$t['name']] = intVal($t['numbers']);
   if ($t['name'] === $nameHolidays[0]) {
      $year = $t['year'];
   }
   if ($t['name'] === $children) {
      $yearChildren = $t['year'];
   }
}

foreach($nameHolidays as $t) {
   if($t === $children) {
      $yearFinal = $yearChildren;
   } else {
      $yearFinal = $year;
   }
   $daysSet[$t] = $holidays->getSetDays($t, $yearFinal);
}

foreach($totalDays as $key => $value) {
   if(array_key_exists($key, $daysSet)) {
      $diffDays[$key] = intVal($value) - $daysSet[$key];
   } else {
      $diffDays[$key] = intVal($value);
   }
}
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