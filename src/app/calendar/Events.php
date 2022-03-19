<?php
namespace App\calendar;

use Exception;

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

      $sql = "SELECT * FROM events WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}' ORDER BY start ASC";
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

   public function getEventById(int $id): Event 
   {
      $sql = "SELECT * FROM events WHERE id = ?";
      $query = $this->pdo->prepare($sql);
      $query->execute([$id]);
      $query->setFetchMode(\PDO::FETCH_CLASS, Event::class);
      $result = $query->fetch();
      if ($result === false) {
         throw new \Exception("Aucun resultat n'a été trouvé.");
      }

      return $result;
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

   public function update(Event $event): bool
   {
      $sql = "UPDATE events SET name = ?, description = ? , start = ? WHERE id = ?";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $event->getName(),
         $event->getDescription(),
         $event->getStart()->format('Y-m-d H-i-s'),
         $event->getID()
      ]);
   }

   public function delete(int $id)
   {
      $query = $this->pdo->prepare("DELETE FROM events WHERE id = ?");
      $valid = $query->execute([$id]);
      if ($valid === false) {
         throw new \Exception("Impossible de supprimer l'id $id.");
      }
   }
}