<?php
  class Controller
  {
      protected function render(string $view, array $data = [])
      {
          extract($data);

          ob_start();
          require __DIR__ . "/../app/Views/{$view}.php";
          $content = ob_get_clean();

          require __DIR__ . "/../app/Views/layouts/main.php";
      }
  }
?>