<?php
  session_start();
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  require __DIR__ . '/../vendor/autoload.php';

  // Load env FIRST
//   require __DIR__ . '/../core/Env.php';
//   Env::load(__DIR__ . '/../.env');

  // Core
  require __DIR__ . '/../core/Database.php';
  require __DIR__ . '/../core/Router.php';
  require __DIR__ . '/../core/Controller.php';
  require __DIR__ . '/../core/Session.php';
  require __DIR__ . '/../core/Utils/ListStateHelper.php';
  require __DIR__ . '/../core/Utils/TableHelper.php';
  require __DIR__ . '/../core/Jwt.php';
  require __DIR__ . '/../core/Response.php';
  
  //middleware
  require __DIR__ . '/../app/Middleware/Auth.php';
    
  // Controllers
  require __DIR__ . '/../app/Controllers/ProductsController.php';
  require __DIR__ . '/../app/Controllers/AuthController.php';
  require __DIR__ . '/../app/Controllers/UsersController.php';
  require __DIR__ . '/../app/Controllers/TransactionsController.php';


  require __DIR__ . '/../app/Controllers/Api/ProductsApiController.php';
  require __DIR__ . '/../app/Controllers/Api/AuthApiController.php';

  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  // Serve static files directly
  if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
      return false;
  }

  // Decide which controller to route to
  if (str_starts_with($uri, '/api')) {
      require __DIR__ . '/api.php';  // API entry point
  } else {
      require __DIR__ . '/index.php'; // Web entry point
  }