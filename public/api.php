<?php 

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

$controller = new ProductsApiController();

header('Content-Type: application/json');

// Match product detail by ID
if ($method === 'GET' && preg_match('#^api/products/(\d+)$#', $uri, $matches)) {
    $id = $matches[1];
    $controller->detail($id);
    exit;
}

switch ("$method $uri") {
  case 'POST api/login':
    $controller = new AuthApiController();
    $controller->login();
  break;
  case 'GET api/products':
    $controller->index();
    break;

  case 'POST api/products/create':
    $controller->create();
    break;

  case 'PUT api/products/update':
    $controller->update();
    break;

  case 'DELETE api/products/delete':
    $controller->delete();
    break;

  default:
    http_response_code(404);
    echo json_encode(['error' => 'Not found uri']);
    break;
}
?>