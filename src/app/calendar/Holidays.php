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
      $sql = "SELECT * FROM holidays WHERE id = ?";
      $query = $this->pdo->prepare($sql);
      $query->execute([$id]);
      $query->setFetchMode(\PDO::FETCH_CLASS, Holiday::class);
      $result = $query->fetch();
      if ($result === false) {
         throw new \Exception("Aucun resultat n'a été trouvé.");
      }

      return $result;
   }

   public function getHolidayByDate(\DateTimeImmutable $start) : bool
   {
      $date = $start->format('Y-m-d');
      $sql = "SELECT * FROM holidays WHERE start = ?";
      $query = $this->pdo->prepare($sql);
      $query->execute([$date]);
      $result = $query->fetch();

      if ($result) {
         return false;
      }
      
      return true;
   }

   public function create(Holiday $holiday): bool
   {
      $sql = "INSERT INTO holidays (name, start, semi) VALUES (?, ?, ?)";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $holiday->getName(),
         $holiday->getStart()->format('Y-m-d'),
         $holiday->getSemi(),
      ]);
   }

   public function update(Holiday $holiday): bool
   {
      $sql = "UPDATE holidays SET name = ?, start = ?, semi = ? WHERE id = ?";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $holiday->getName(),
         $holiday->getStart()->format('Y-m-d'),
         $holiday->getSemi(),
         $holiday->getID()
      ]);
   }

   public function delete(int $id)
   {
      $query = $this->pdo->prepare("DELETE FROM holidays WHERE id = ?");
      $valid = $query->execute([$id]);
      if ($valid === false) {
         throw new \Exception("Impossible de supprimer la journée");
      }
   }

   public function getCounter() : array
   {
      $sql = "SELECT * FROM days";
      $query =  $this->pdo->query($sql);
      $result = $query->fetchAll();

      return $result;
   }

   public function updateDaysTotal($numbers, $name) : bool
   {
      $sql = "UPDATE days SET numbers = ? WHERE name = ?";
      $query = $this->pdo->prepare($sql);

      return $query->execute([
         $numbers,
         $name
      ]);
   }

   public function updateYears($year, $name) : bool
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

   public function getSetDays($name, $year) : array
   {
      if($name === "Enfant malade") {
         $start = new DateTimeImmutable($year . '-01-01');
         $end = new DateTimeImmutable($year . '-12-31');
      } else {
         $lastYear = $year - 1;
         $start = new DateTimeImmutable($lastYear . '-06-01');
         $end = new DateTimeImmutable($year . '-05-31');
      }
      
      $sql = "SELECT name, sum(semi) FROM holidays WHERE name = ? AND start BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}'";
      $query =  $this->pdo->prepare($sql);
      $query->execute([$name]);
      $result = $query->fetchAll(\PDO::FETCH_COLUMN|\PDO::FETCH_GROUP);

      $days = [];
      foreach($result as $r) {
         if($r[0] === null){
            $days[$name] = "0";
         } else {
            $days[$name] = $r[0];
         }
      }

      return $days;
   }

   public function getNumberDaysSet() : array
   {
      $daysTotal = $this->getCounter();
      $nameHolidays = $this->getNameHolidays();
      $children = "Enfant malade";

      foreach($daysTotal as $total) {
         $totalDays[$total['name']] = intVal($total['numbers']);
         if ($total['name'] === $nameHolidays[0]) {
            $year = $total['year'];
         }
         if ($total['name'] === $children) {
            $yearChildren = $total['year'];
         }
      }

      foreach($nameHolidays as $nameH) {
         if($nameH === $children) {
            $yearFinal = $yearChildren;
         } else {
            $yearFinal = $year;
         }
         $daysSet[] = $this->getSetDays($nameH, $yearFinal);
      }
      
      foreach($daysSet as $dS) {
         foreach($dS as $key => $value) {
            if(array_key_exists($key, $totalDays)) {
               $diffDays[$key] = $totalDays[$key] - floatval($value);
            } else {
               $diffDays[$key] = intVal($value);
            }
         }
      }

      return $diffDays;
   }

   public function yearCalculate($daysHolidays) {
      foreach($daysHolidays as $d) {
         if ($d['name'] === "CP" || $d['name'] === "Enfant malade") {
            $yearHoliday[$d['name']] = $d['year'];
         }
      }

      $holidayYear = date($yearHoliday['CP']);
      $holidayYearChildren = date($yearHoliday['Enfant malade']);
      $date = new DateTimeImmutable(date('d-m-Y'));
      $year = $date->format('Y');
      $dateChange = new DateTimeImmutable(date('01-06-' . $holidayYear));
      $dateChangeCurrent = new DateTimeImmutable(date('01-06-' . $year));

      if ($date >= $dateChange && $date >= $dateChangeCurrent) {
         $year = $date->modify("+1year")->format('Y');
         foreach ($daysHolidays as $total){
            if ($total['name'] !== "Enfant malade") {
               $this->updateYears($year, $total['name']);
            }
         }
      } else {
         if ($holidayYear !== $year && $date < $dateChangeCurrent) {
            foreach ($daysHolidays as $total){
               if ($total['name'] !== "Enfant malade") {
                  $this->updateYears($date->format('Y'), $total['name']);
               }
            }
         }
      }

      if ($holidayYearChildren !== $year) {
         $this->updateYears($date->format('Y'), "Enfant malade");
      }
   }
}