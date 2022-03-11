<?php
namespace App\calendar;

class Holiday {
   private $id;
   private $name;
   private $start;

   public function getId (): int
   {
      return $this->id;
   }

   public function getName (): string
   {
      return $this->name;
   }

   public function getStart (): \DateTime
   {
      return new \DateTime($this->start);
   }

   public function setName (string $name) {
      $this->name = $name;
   }

   public function setStart (string $start) {
      $this->start = $start;
   }
}