<?php
namespace App;

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

   public function date(string $field) {
      if (\DateTime::createFromFormat('Y-m-d', $this->data[$field]) === false) {
         $this->errors[$field] = "La date ne semble pas valide";
      }
   }

   public function time(string $field) {
      if (\DateTime::createFromFormat('H:i', $this->data[$field]) === false) {
         $this->errors[$field] = "L'heure ne semble pas valide";
      }
   }
}