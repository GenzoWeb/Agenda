<?php
use App\Connection;

$pdo = Connection::getPDO();
$holidays = new \App\calendar\Holidays($pdo);
$title = "Congés";
$start = new DateTimeImmutable($params['year'] . '-' . $params['month'] . '-' . $params['day']);
$holidaysOfDay = $holidays->getHolidays($start, $start);
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}
?>

<div class="section_events">
   <h1>Gérer le congés du <?= $params['day']?>/<?= $params['month']?> :</h1>
   <div class="events"><div class="event">
      <div class="event_title">
         <p class="event_date"><?= htmlentities($holidaysOfDay[0]['name'])?></p>
         <?php if($_SESSION) : ?> 
         <div class="event_admin">
            <a href="conges-modif-<?=$holidaysOfDay[0]['id']?>">
            <box-icon class="icon_edit" type='solid' name='edit-alt'></box-icon>
            </a>
            <a href="conges-suppr-<?=$holidaysOfDay[0]['id']?>" onclick="return confirm('Voulez-vous vraiment supprimer ce conges ?')">
               <box-icon class="icon_delete" type='solid' name='message-square-x'></box-icon>
            </a>
         </div>
         <?php endif; ?>
      </div>
   </div>
   <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn">Retour</a>
</div>