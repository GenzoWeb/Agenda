<?php
namespace App;

class Router {

   private $viewPath;
   private $router;

   public function __construct(string $viewPath)
   {
      $this->viewPath = $viewPath;
      $this->router = new \AltoRouter();
   }

   public function get(string $url, string $view, ?string $name = null): self
   {
      $this->router->setBasePath('Agenda/public/');
      $this->router->map('GET', $url, $view, $name);
      return $this;
   }

   public function run()
   {
      $match = $this->router->match();
      $view = $match['target'];
      $params = $match['params'];
      $router = $this ->router;
      ob_start();
      require $this->viewPath . '/' . $view . '.php';
      $content = ob_get_clean();
      require $this->viewPath . '/' . 'base/base.php';

      return $this;
   }
}