<?php
namespace App;

class Auth {
   public function testAuth(): bool
   {
      if (session_status() === PHP_SESSION_NONE) {
         session_start();
      }

      return !empty($_SESSION['logged']);
   }

   public function log($url) {
      if (!$this->testAuth()) {
         $_SESSION['url'] = $url;
         header('location: /Agenda/public/login');
         exit();
      }
   }
}