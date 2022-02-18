<?php
namespace App\calendar;

class Events {

   private $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }

   public function getEvents(\DateTimeImmutable $start, ?\DateTimeImmutable $end = null) : array
   {
      if ($end === null) {
         $end = $start;
      }

      $sql = "SELECT * FROM events WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'";
      $query = $this->pdo->query($sql);
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

   public function create(Event $event): bool
   {
      $sql = "INSERT INTO events (name, description, start) VALUES (?, ?, ?)";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $event->getName(),
         $event->getDescription(),
         $event->getStart()->format('Y-m-d H-i-s')
      ]);
   }
}