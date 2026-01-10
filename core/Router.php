<?php
// core/Router.php
class Router {
    private array $routes = [];

    public function get($path, $action) {
        $this->routes['GET'][$path] = $action;
    }

    public function post($path, $action) {
        $this->routes['POST'][$path] = $action;
    }

    public function put($path, $action) {
        $this->routes['PUT'][$path] = $action;
    } 

    public function delete($path, $action) {
        $this->routes['DELETE'][$path] = $action;
    }

    public function patch($path, $action) {
        $this->routes['PATCH'][$path] = $action;
    }

    public function dispatch() {
      $method = $_SERVER['REQUEST_METHOD'];
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

      if (!isset($this->routes[$method])) {
          http_response_code(404);
          exit('Not Found');
      }

      foreach ($this->routes[$method] as $route => $handler) {
          // Convert route to regex: /products/edit/{id} => /products/edit/(\d+)
          $pattern = preg_replace('#\{[^\}]+\}#', '([0-9]+)', $route);
          $pattern = "#^$pattern$#";

          if (preg_match($pattern, $path, $matches)) {
              array_shift($matches); // remove full match
              [$class, $methodName] = $handler;
              $controller = new $class();
              call_user_func_array([$controller, $methodName], $matches);
              return;
          }
      }

      http_response_code(404);
      exit('Not Found');
  }

}