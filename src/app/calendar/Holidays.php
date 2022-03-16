<?php
namespace App\calendar;

use DateTimeImmutable;

class Holidays {

   private $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }

   public function getHolidays(\DateTimeImmutable $start, \DateTimeImmutable $end) : array
   {
      $sql = "SELECT * FROM holidays WHERE start BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}' ORDER BY start ASC";
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

   public function getHolidayByDate(\DateTimeImmutable $start) 
   {
      $date = $start->format('Y-m-d');
      $query = $this->pdo->query("SELECT * FROM holidays WHERE start = '$date'");
      $result = $query->fetch();
      if ($result) {
         throw new \Exception("La date est déjà prise.");
         // return false;
      }
      
      return true;
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

   public function getCounter() : array
   {
      $sql = "SELECT * FROM days";
      $query =  $this->pdo->query($sql);
      $result = $query->fetchAll();

      return $result;
   }

   public function updateDaysTotal($numbers, $name)
   {
      $sql = "UPDATE days SET numbers = ? WHERE name = ?";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $numbers,
         $name
      ]);
   }

   public function updateYears($year, $name)
   {
      $sql = "UPDATE days SET year = ? WHERE name = ?";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $year,
         $name
      ]);
   }

   public function getNameHolidays() : array
   {
      $sql = "SELECT name FROM days";
      $query =  $this->pdo->query($sql);
      $result = $query->fetchAll(\PDO::FETCH_COLUMN, 0);

      return $result;
   }

   public function getSetDays($name, $year)
   {
      if($name === "Enfant malade") {
         $start = new DateTimeImmutable($year . '-01-01');
         $end = new DateTimeImmutable($year . '-12-31');
      } else {
         $lastYear = $year - 1;
         $start = new DateTimeImmutable($lastYear . '-06-01');
         $end = new DateTimeImmutable($year . '-05-31');
      }
      
      $sql = "SELECT name, COUNT('name') FROM holidays WHERE name = '$name' AND start BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}'";
      $query =  $this->pdo->query($sql);
      $result = $query->fetchAll(\PDO::FETCH_COLUMN|\PDO::FETCH_GROUP);

      foreach($result as $r) {
         if($r[0] === "0"){
         return "0";
         } else {
            return $result[$name][0];
         }
      }
   }
}