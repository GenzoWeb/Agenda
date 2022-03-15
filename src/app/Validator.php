<?php
namespace App;

require("../views/holidays/testDaysOff.php");

class Validator {

   private $data;
   protected $errors = [];

   public function validates(array $data) {
      $this->errors = [];
      $this->data = $data;
   }

   public function validate(string $field, string $method, ...$params) {
      if (!isset($this->data[$field])) {
         $this->errors[$field] = "Le champ $field n'est pas rempli";
      } else {
         call_user_func([$this, $method], $field, ...$params);
      }
   }

   public function minLength(string $field, int $length) {
      if(!empty($this->data[$field])) {
         if (strlen($this->data[$field]) < $length) {
            $this->errors[$field] = "Le champ doit avoir plus de $length caractères";
         }
      } else {
         $this->blank($field);
      }
   }

   public function blank(string $field) {
      if (empty($this->data[$field])) {
         $this->errors[$field] = "Veuillez remplir ce champ";
      }
   }

   public function time(string $field) {
      if (\DateTime::createFromFormat('H:i', $this->data[$field]) === false) {
         $this->errors[$field] = "L'heure ne semble pas valide";
      }
   }

   public function date(string $field) {
      if (\DateTime::createFromFormat('Y-m-d', $this->data[$field]) === false) {
         $this->errors[$field] = "La date ne semble pas valide";
      } else {
         $this->noWork($field);
      }
   }

   public function noWork(string $field) {

      if (testNoWorkingDay((new \DateTime($this->data[$field]))->format('d-m-Y')) || 
         (new \DateTime($this->data[$field]))->format('w') == 0 ||
         (new \DateTime($this->data[$field]))->format('w') == 6
      ) {
         $this->errors[$field] = "Ce jour est un jour non travaillé";
      }
   }
}