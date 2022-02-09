<?php
namespace App\calendar;

class Month {

   public $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

   public $days = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];

   public function __construct(?int $numberMonth = null, ?int $month = null, ?int $year = null)
   {
      if ($numberMonth === null) {
         $numberMonth = 1;
      }

      if ($month === null || $month <= 0 || $month >= 13) {
         $month = intval(date('m'));
      }

      if ($year === null) {
         $year = intval(date('Y'));
      }

      $this->numberMonth = $numberMonth;
      $this->month = $month;
      $this->year = $year;
   }

   public function getDate(): string 
   {
      return $this->months[$this->month - 1] . ' ' . $this->year;
   }

   public function getFirstDay(): \DateTimeImmutable 
   {
      return new \DateTimeImmutable("{$this->year}-{$this->month}-01");
   }

   public function getWeeks(): int 
   {
      $start = $this->getFirstDay();
      $end = $start->modify('+1 month -1 day');
      $startWeek = intval($start->format('W'));
      $endWeek = intval($end->format('W'));

      if ($startWeek === 52 || $startWeek === 53) {
         $startWeek = 1;
         $endWeek = intval($end->format('W')) + 1;
      }
      if ($endWeek === 1) {
         $endWeek = intval($end->modify('-7 days')->format('W')) + 1;
      }

      $weeks = $endWeek - $startWeek + 1;
      if ($weeks < 0) {
         $weeks = intval($end->format('W'));
      }

      return $weeks;
   }
}