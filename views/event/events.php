<?php
use App\Connection;

$pdo = Connection::getPDO();
$events = new \App\calendar\Events($pdo);
$title = "Rendez-vous";
$start = new DateTimeImmutable($params['year'] . '-' . $params['month'] . '-' . $params['day']);
$eventsOfDay = $events->getEvents($start);
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}
?>

<div class="section_events">
   <h1>Rendez-vous du <?= $params['day']?>/<?= $params['month']?> :</h1>
   <div class="events">
      <?php 
      foreach ($eventsOfDay as $event) { 
         $hour = (new DateTime($event['start']))->format('H' . '\h' . 'i'); ?>
      <div class="event">
         <div class="event_title">
            <p class="event_date"><?= htmlentities($event['name'])?><span> à </span><?= $hour ?></p>
            <?php if($_SESSION) : ?> 
            <div class="event_admin">
               <a href="rendez-vous-modif-<?=$event['id']?>">
               <box-icon class="icon_edit" type='solid' name='edit-alt'></box-icon>
               </a>
               <a href="rendez-vous-suppr-<?=$event['id']?>" onclick="return confirm('Voulez vous vraiment supprimer ce rendez-vous ?')">
                  <box-icon class="icon_delete" type='solid' name='message-square-x'></box-icon>
               </a>
            </div>
            <?php endif; ?>
         </div>
         <?php
         if ($event['description']) { ?>
            <p><?= htmlentities($event['description'])?></p>
         <?php } ?>
      </div>
      <?php
      }
      ?>
   </div>
   <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn">Retour</a>
</div>