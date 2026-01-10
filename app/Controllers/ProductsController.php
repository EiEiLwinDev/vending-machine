<?php

  class ProductsController extends Controller {
      private PDO $db;

      public function __construct() {
          $this->db = Database::connect();
      }

      public function index()
      {
        if (!isset($_GET['page'])) {
          ListStateHelper::reset();
        }

        // echo $_GET['page'];exit;
        // Save state only if query params exist
        ListStateHelper::remember();

        // Read state ONLY from helper
        $state = ListStateHelper::get();

        $page  = max(1, (int) $state['page']);
        $limit = (int) Env::get('PAGINATION_LIMIT', 10);
        $offset = ($page - 1) * $limit;

        $allowedSorts = ['name', 'price', 'quantity'];
        $sort = in_array($state['sort'], $allowedSorts)
            ? $state['sort']
            : 'name';

        $dir = in_array($state['dir'], ['asc', 'desc'])
            ? $state['dir']
            : 'asc';

        // Total count
        $total = $this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $totalPages = (int) ceil($total / $limit);

        // Fetch data
        $stmt = $this->db->prepare("
            SELECT * FROM products
            ORDER BY {$sort} {$dir}
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll();

        $this->render('products/index', [
            'products'   => $products,
            'page'       => $page,
            'limit'      => $limit,
            'totalCount' => $total,
            'totalPages' => $totalPages,
            'sort'       => $sort,
            'dir'        => $dir,
        ]);
      }

      public function create() {
          adminOnly();
          $this->render('products/create', [
              'title' => 'Add New Product'
          ]);
      }

      public function edit($id) {
        adminOnly();
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            die('Product not found');
        }

        $this->render('products/edit', [
            'product' => $product,
            'title' => 'Edit Product'
        ]);
      }

      public function detail($id) {
        adminOnly();
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            die('Product not found');
        }

        $this->render('products/detail', [
            'product' => $product,
            'title' => 'View Product'
        ]);
      }

      public function store() {
        adminOnly();
        $name     = trim($_POST['name'] ?? '');
        $price    = $_POST['price'] ?? '';
        $quantity = $_POST['quantity'] ?? '';

        $errors = [];

        // ---- Validation ----
        if ($name === '') {
            $errors['name'] = 'Product name is required';
        }

        if ($price === '') {
            $errors['price'] = 'Price is required';
        }  

        if ($quantity === '') {
            $errors['quantity'] = 'Quantity is required';
        }elseif (!is_numeric($quantity) || (int)$quantity < 0) {
            $errors['quantity'] = 'Quantity must be a non-negative integer';
        }

        // ---- If validation fails ----
        if (!empty($errors)) {
          Session::set('errors', $errors);
          Session::set('old', [
              'name'  => $name,
              'price' => $price,
              'quantity' => $quantity
          ]);

          header('Location: /products/create');
          exit;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products (name, description, price, quantity)
            VALUES (?,?,?,?)"
        );
        $stmt->execute([
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['quantity']
        ]);

        header('Location: /');
      }

      public function update() {
        adminOnly();
        $id = (int) $_POST['id'];

        $redirect = $_POST['redirect_to'] ?? '/';

        if (!str_starts_with($redirect, '/')) {
            $redirect = '/';
        }

        if ($id <= 0) {
            die('Invalid product ID');
        }
        
        $stmt = $this->db->prepare(
            "UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE id = ?"
        );
        $stmt->execute([
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['quantity'],
            $id
        ]);  

        header('Location: ' . $redirect);
      }

      public function delete() {
        adminOnly();
        $id = (int) $_POST['id'];
        if ($id <= 0) {
            die('Invalid product ID');
        }
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        // Restore list state
        $page = (int) ($_POST['page'] ?? 1);
        $sort = $_POST['sort'] ?? 'name';
        $dir  = $_POST['dir'] ?? 'asc';

        header(
            'Location: ' . ListStateHelper::url('/', [
                'page' => $page,
                'sort' => $sort,
                'dir'  => $dir
            ])
        );
        exit;
      } 

      public function viewPurchase($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        if (!$product) {
            die('Product not found');
        }
        $this->render('products/purchase', [
            'product' => $product,
            'title' => 'Product Details'
        ]);
      }

      public function purchase()
      {
        $redirect = $_POST['redirect_to'] ?? '/';

        if (!str_starts_with($redirect, '/')) {
            $redirect = '/';
        }

        $productId = (int) $_POST['product_id'];
        $paidAmount = (float) $_POST['amount'];
        $quantity = (int) $_POST['quantity']; 

        if ($productId <= 0 || $quantity <= 0) {
          Session::set('error', "Invalid purchase request");
          header('Location: /');
          exit;
        }

        $user = Session::get('user');

        if ($user) {
            $userId = $user['id'];
        }else {
            $userId = null; // guest user
        }

        try {
            $this->db->beginTransaction();
            // Lock product row
            $stmt = $this->db->prepare(
                "SELECT * FROM products WHERE id = ? FOR UPDATE"
            );
            $stmt->execute([$productId]);
            $product = $stmt->fetch();

            if (!$product || $product['quantity'] < $quantity) {
                throw new Exception("Out of stock");
            }

            $totalAmount = $product['price'] * $quantity;

            if ($paidAmount < $totalAmount) {
                throw new Exception("Insufficient amount");
            }

            // Update stock
            $stmt = $this->db->prepare(
                "UPDATE products SET quantity = quantity - ? WHERE id = ?"
            );
            $stmt->execute([$quantity, $productId]);

            // Log transaction
            $stmt = $this->db->prepare(
                "INSERT INTO transactions (user_id, product_id, quantity, unit_price, total_price)
                VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $userId,
                $productId,
                $quantity,
                $product['price'],
                $totalAmount
            ]);

            $this->db->commit();

            Session::set('success',
                "Purchase successful! Your change is $" .
                number_format($paidAmount - $totalAmount, 3));

            header('Location: ' . $redirect);
            exit;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
              $this->db->rollBack();
            }

            Session::set('error', $e->getMessage());
            header('Location: '. $redirect);
            exit;
        }
      }
  }
?>