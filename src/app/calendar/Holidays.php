<?php
namespace App\calendar;

class Holidays {

   private $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }

   public function getHolidays(\DateTimeImmutable $start, \DateTimeImmutable $end) : array
   {
      $sql = "SELECT * FROM holidays WHERE start BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}'";
      $query =  $this->pdo->query($sql);
      $result = $query->fetchAll();

      return $result;
   }

   public function getHolidaysByDay(\DateTimeImmutable $start,\DateTimeImmutable $end) : array
   {
      $events = $this->getHolidays($start, $end);
      $days = [];
      foreach($events as $event) {
         $date = explode(' ' , $event['start'])[0];

         if (isset($event['end'])) {
            $first = new \DateTime($event['start']);
            $last = new \DateTime($event['end']);
            $diff = intval($first->diff($last)->format('%d'));

            for ($i = 0; $i <= $diff; $i++) {
               $date = ((clone $first)->modify("+" . $i . "days"))->format('Y-m-d');
               $days[$date][] = $event;
            }
         } else {
            if (!isset($days[$date])) {
               $days[$date] = [$event];
            } else {
               $days[$date][] = $event;
            }
         }
      }
 
      return $days;
   }

   public function getHolidayById(int $id): Holiday 
   {
      $query = $this->pdo->query("SELECT * FROM holidays WHERE id = $id");
      $query->setFetchMode(\PDO::FETCH_CLASS, Holiday::class);
      $result = $query->fetch();
      if ($result === false) {
         throw new \Exception("Aucun resultat n'a été trouvé.");
      }

      return $result;
   }

   public function create(Holiday $holiday): bool
   {
      $sql = "INSERT INTO holidays (name, start) VALUES (?, ?)";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $holiday->getName(),
         $holiday->getStart()->format('Y-m-d')
      ]);
   }

   public function update(Holiday $holiday): bool
   {
      $sql = "UPDATE holidays SET name = ?, start = ? WHERE id = ?";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $holiday->getName(),
         $holiday->getStart()->format('Y-m-d'),
         $holiday->getID()
      ]);
   }

   public function delete(int $id)
   {
      $query = $this->pdo->prepare("DELETE FROM holidays WHERE id = ?");
      $valid = $query->execute([$id]);
      if ($valid === false) {
         throw new \Exception("Impossible de supprimer l'id $id.");
      }
   }
}