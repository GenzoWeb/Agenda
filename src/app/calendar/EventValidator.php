<?php
namespace App\calendar;

use App\Validator;

class EventValidator extends Validator {

   public function validatesEvent(array $data) : array
   { 
      parent::validates($data);
      $this->validate('name', 'minLength', 3);
      $this->validate('date', 'dateEvent');
      $this->validate('start', 'time');

      return $this->errors;
   }

   public function validatesHoliday(array $data) : array
   {
      parent::validates($data);
      $this->validate('name', 'listName');
      if(isset($data['start'])){
         $this->validate('start', 'dateHolidays');
      }

      if (!empty($this->errors)) {
         return $this->errors;
      } else {
         $dateValid['valid'] = $this->result;
         return $dateValid;
      }
   }

   public function validatesDays(array $data) : array 
   {
      parent::validates($data);
      $this->validate('validDay', 'testTotalDaysHoliday');
      
      return $this->errors;
   }
}