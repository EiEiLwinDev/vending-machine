<?php

  $router = new Router();
  //auth routes
  $router->get('/login', [AuthController::class, 'showLogin']);
  $router->post('/login', [AuthController::class, 'login']);
  $router->get('/logout', [AuthController::class, 'logout']);

  //user routes
  $router->get('/users', [UsersController::class, 'index']);
  $router->get('/users/create', [UsersController::class, 'create']);
  $router->post('/users/store', [UsersController::class, 'store']);


  // Product routes
  $router->get('/', [ProductsController::class, 'index']); 
  $router->get('/products/create', [ProductsController::class, 'create']);
  $router->get('/products/edit/{id}', [ProductsController::class, 'edit']);
  $router->get('/products/detail/{id}', [ProductsController::class, 'detail']);
  $router->get('/products/{id}/purchase', [ProductsController::class, 'viewPurchase']);
  $router->post('/products/update', [ProductsController::class, 'update']);
  $router->post('/products/store', [ProductsController::class, 'store']);
  $router->post('/products/delete', [ProductsController::class, 'delete']);
  $router->post('/products/purchase', [ProductsController::class, 'purchase']);

  //Transaction routes
  $router->get('/transactions', [TransactionsController::class, 'index']);
 
  $router->dispatch();
?>