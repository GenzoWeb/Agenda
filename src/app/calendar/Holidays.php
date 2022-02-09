<?php
namespace App\calendar;

class Holidays {

   public function getHolidays(\DateTimeImmutable $start, \DateTimeImmutable $end) : array
   {
      $pdo = new \PDO('mysql:dbname=agenda;host=localhost', 'root', '', [
         \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
         \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
      ]);
      $sql = "SELECT * FROM holidays WHERE start BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}'";
      $query = $pdo->query($sql);
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
}