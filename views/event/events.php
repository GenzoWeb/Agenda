<?php
use App\Connection;

$pdo = Connection::getPDO();
$events = new \App\calendar\Events($pdo);
$title = "Rendez-vous";
$start = new DateTimeImmutable($params['year'] . '-' . $params['month'] . '-' . $params['day']);
$eventsOfDay = $events->getEvents($start);
?>

<div class="section_events">
   <h1>Rendez-vous du <?= $params['day']?>/<?= $params['month']?> :</h1>
   <div class="events">
      <?php 
      foreach ($eventsOfDay as $event) { 
         $hour = (new DateTime($event['start']))->format('H' . '\h' . 'i'); ?>
      <div class="event">
         <p class="event_date"><?= htmlentities($event['name'])?><span> à </span><?= $hour ?></p>
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