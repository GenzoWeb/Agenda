<?php
namespace App;

require("../views/holidays/testDaysOff.php");

class Validator {

   private $data;
   protected $errors = [];

   public function validates(array $data) {
      $this->errors = [];
      $this->data = $data;
      $this->result = [];
   }

   public function connectionHolidays()
   {
      $pdo = Connection::getPDO();
      $holidays = new \App\calendar\Holidays($pdo);

      return $holidays;
   }

   public function validate(string $field, string $method, ...$params) 
   {
      if (!isset($this->data[$field])) {
         $this->errors[$field] = "Le champ $field n'est pas rempli";
      } else {
         call_user_func([$this, $method], $field, ...$params);
      }
   }

   public function getName() : array 
   {
      $holidays = $this->connectionHolidays();
      $nameHolidays = $holidays->getNameHolidays();

      return $nameHolidays;
   }

   public function listName(string $field)
   {
      $nameHolidays = $this->getName();

      if(!in_array($this->data[$field], $nameHolidays)) {
         $this->errors[$field] = "Sélectionner le bon type de congés";
      }
   }

   public function minLength(string $field, int $length) 
   {
      if(!empty($this->data[$field])) {
         if (strlen($this->data[$field]) < $length) {
            $this->errors[$field] = "Le champ doit avoir plus de $length caractères";
         }
      } else {
         $this->blank($field);
      }
   }

   public function blank(string $field)
   {
      if (empty($this->data[$field])) {
         $this->errors[$field] = "Veuillez remplir ce champ";
      }
   }

   public function date(string $field) 
   {
      if (\DateTime::createFromFormat('Y-m-d', $this->data[$field]) === false) {
         $this->errors[$field] = "La date ne semble pas valide";
         return;
      }
   }

   public function time(string $field) 
   {
      if (\DateTime::createFromFormat('H:i', $this->data[$field]) === false) {
         $this->errors[$field] = "L'heure ne semble pas valide";
      }
   }

   public function dateEvent(string $field) 
   {
      $this->date($field);
      $pdo = Connection::getPDO();
      $events = new \App\calendar\Events($pdo);
      $event = $events->getEvents(new \DateTimeImmutable($this->data[$field]));
      if($event){
         foreach($event as $e) {
            $timeTest[] = ((new \DateTime($e['start']))->format("H:i"));
          }
    
          if (in_array($this->data['start'], $timeTest)) {
             $this->errors['start'] = "Vous avez déjà un rendez vous";
          }
      }
   }

   public function dateHolidays(string $field)
   {
      $this->date($field);
      $this->noWork($field);

      if (empty($this->errors)) {
         if (isset($this->data['end'])){
            if ($this->data['end'] !== "" ) {
               $this->date('end');
               $this->noWork('end');
            }
         }

         $this->testDateExist($field);
         $this->testNumberDay($field);
      }
   }

   public function noWork(string $field) 
   {
      if (testNoWorkingDay((new \DateTime($this->data[$field]))->format('d-m-Y')) || 
         (new \DateTime($this->data[$field]))->format('w') == 0 ||
         (new \DateTime($this->data[$field]))->format('w') == 6
      ) {
         $this->errors[$field] = "Ce jour est un jour non travaillé";
      }
   }

   public function testDateExist(string $field)
   {
      $holidays = $this->connectionHolidays();
      $dayExistStart = $holidays->getHolidayByDate(new \DateTimeImmutable($this->data[$field]));
      if(!$dayExistStart) {
         $this->errors[$field] = "La date du " . (new \DateTime($this->data[$field]))->format("d-m-Y") . " est déjà posée";
         return;
      }

      if (isset($this->data['end'])){
         if ($this->data['end'] !== "" ) {
            $dayExistEnd = $holidays->getHolidayByDate(new \DateTimeImmutable($this->data['end']));
            $verifDate = $holidays->getHolidaysByDay(new \DateTimeImmutable($this->data[$field]), new \DateTimeImmutable($this->data['end']));
            if(!$dayExistEnd || $verifDate) {
               if(count($verifDate) > 1) {
                  foreach($verifDate as $key => $value) {
                     $errors[]= "La date du " . (new \DateTime($key))->format("d-m-Y") . " est déjà posée";
                  } 
                  $this->errors['end'] = $errors;
               } else {
                  foreach($verifDate as $key => $value){
                     $this->errors['end'] = "La date du " . (new \DateTime($key))->format("d-m-Y") . " est déjà posée";
                  }
               }
            }
         }
      }
   }

   public function testTotalDaysHoliday(string $field) 
   {
      $nameHolidays = $this->getName();
      foreach ($this->data as $key => $value) {
         if ($key !== "validDay" && $key !== "valid") {  
            $name[] = str_replace("_", " ", $key);
         }
      }
      
      if(!array_diff($nameHolidays, $name)) {
         foreach ($this->data as $n => $value) {
            if ($n !== 'validDay' && $n !== "valid") {
               if (is_numeric($this->data[$n])) {
                  if(intVal($this->data[$n]) <= 0 || intVal($this->data[$n]) >= 30) {
                     $this->errors[$field] = "Le nombre de jours n'est pas correct";
                  }
               } else {
                  $this->errors[$field] = "Mettez un nombre correct";
               }
            }
         }
      } else {
         $this->errors[$field] = "Le type de congés n'est pas valable";
      }
   }

   public function testNumberDay($field) 
   {
      $holidays = $this->connectionHolidays();
      $holidayRest = $holidays->getNumberDaysSet();
      $dateStart = new \DateTime($this->data['start']); 
      if (isset($this->data['end'])) {
         if ($this->data['end'] !== "" ) {
            $dateEnd = new \DateTime($this->data['end']);
            $diff = ($dateStart->diff($dateEnd)->format("%a")) + 1;

            for ($i = 0; $i < $diff; $i++) {
               
               $a = $i === 0? 0 : 1;
               $test = '+' . $a . 'day';
               $testDay = (clone $dateStart)->modify($test)->format("w");
               $date = $dateStart->modify($test)->format("d-m-Y");
               $noWork = testNoWorkingDay($date);

               if($testDay > 0 && $testDay < 6 && !$noWork) {
                  $dateFinal[] = $date;
               }
            }
         } else {
            $testDay = $dateStart->format("w");
            $noWork = testNoWorkingDay($dateStart->format("d-m-Y"));
   
            if($testDay > 0 && $testDay < 6 && !$noWork) {
               $dateFinal[] = $this->data['start'];
            }
         }
      }  else {
         $testDay = $dateStart->format("w");
         $noWork = testNoWorkingDay($dateStart->format("d-m-Y"));

         if($testDay > 0 && $testDay < 6 && !$noWork) {
            $dateFinal[] = $this->data['start'];
         }
      }

      if (isset($dateFinal)) {
         $numberDays = count($dateFinal);
         $testNumberDaysRest = $holidayRest[$this->data['name']] - $numberDays;
         if ($testNumberDaysRest < 0 ) {
            $this->errors[$field] = "Vous n'avez pas assez de jour";
         }
      } else {
         $this->errors[$field] = 'Pas de date posée le week-end ou jour férié';
      }

      if(!$this->errors){
         $this->result = $dateFinal;
      }
   }
}