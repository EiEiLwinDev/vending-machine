<?php 

class ProductsApiController
{
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    protected function authenticate()
    {
        $token = JwtHelper::getBearerToken();
        if (!$token) {
            Response::error("Unauthorized", 401);
            exit;
        }
        $payload = JwtHelper::verify($token);
        
        if (!$payload || ($payload['role'] ?? '') !== 'admin') {
            Response::error("Forbidden!You do not have permission to access this resource.", 403);
            exit;
        }
        return $payload;
    }

    public function index()
    {
        $products = $this->db->query("SELECT * FROM products")->fetchAll();
        Response::success($products, "Products retrieved");
    }

    public function detail($id)
    {
        if (!$id) {
            http_response_code(400);
            Response::error("Product ID is required", 400);
            return;
        }
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        if (!$product) {
            http_response_code(404);
            Response::error("Product not found", 404);
            return;
        }
        Response::success($product, "Product detail retrieved");
    }

    public function create()
    {
        $this->authenticate();

        $input = json_decode(file_get_contents('php://input'), true);
        $errors = [];

        if(empty($input['name'])) {
            $errors['name'] = "Name is required";
        }
        if(empty($input['price'])) {
            $errors['price'] = "Price is required";
        }elseif($input['price'] <= 0) {
            $errors['price'] = "Price must be greater than zero";
        }

        if(!isset($input['quantity'])) {
            $errors['quantity'] = "Quantity is required";
        }elseif($input['quantity'] < 0) {
            $errors['quantity'] = "Quantity must be greater than zero";
        }

        if (!empty($errors)) {
            Response::error("Validation errors", 422, $errors);
            return;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$input['name'], $input['description'] ?? '', $input['price'], $input['quantity']]);

        // Get last inserted ID
        $lastId = $this->db->lastInsertId();

        // Fetch the full inserted row
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$lastId]);
        $product = $stmt->fetch();

        Response::success($product, "Product created", 201);
    }

    public function update()
    {
        $this->authenticate();
        $input = json_decode(file_get_contents('php://input'), true);

        $errors = [];

        if(empty($input['name'])) {
            $errors['name'] = "Name is required";
        }
        if(empty($input['price'])) {
            $errors['price'] = "Price is required";
        }elseif($input['price'] <= 0) {
            $errors['price'] = "Price must be greater than zero";
        }

        if(!isset($input['quantity'])) {
            $errors['quantity'] = "Quantity is required";
        }elseif($input['quantity'] < 0) {
            $errors['quantity'] = "Quantity must be greater than zero";
        }

        if (!empty($errors)) {
            Response::error("Validation errors", 422, $errors);
            return;
        }

        $stmt = $this->db->prepare(
            "UPDATE products SET name=?, description=?, price=?, quantity=? WHERE id=?"
        );
        $stmt->execute([$input['name'], $input['description'] ?? '', $input['price'], $input['quantity'], $input['id']]);
        // Fetch the full inserted row
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$input['id']]);
        $product = $stmt->fetch();

        Response::success($product, "Product updated");
    }

    public function delete()
    {
        $this->authenticate();
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['id'])) {
            Response::error("Product ID is required", 400);
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$input['id']]);

        Response::success(null, "Product deleted");
    }
}
?>