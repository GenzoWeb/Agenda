<?php
namespace App\calendar;

class Events {

   public function getEvents(\DateTimeImmutable $start, \DateTimeImmutable $end) : array
   {
      $pdo = new \PDO('mysql:dbname=agenda;host=localhost', 'root', '', [
         \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
         \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
      ]);
      $sql = "SELECT * FROM events WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'";
      $query = $pdo->query($sql);
      $result = $query->fetchAll();

      return $result;
   }

   public function getEventsByDay(\DateTimeImmutable $start,\DateTimeImmutable $end) : array
   {
      $events = $this->getEvents($start, $end);
      $days = [];
      foreach($events as $event) {
         $date = explode(' ' , $event['start'])[0];
         if (!isset($days[$date])) {
            $days[$date] = [$event];
         } else {
            $days[$date][] = $event;
         }
      }

      return $days;
   }
}