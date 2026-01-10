<?php

class UsersController extends Controller
{
    private PDO $db;

    public function __construct()
    {
      $this->db = Database::connect();
    }

    function index()
    {
        adminOnly();

        if (!isset($_GET['page'])) {
          ListStateHelper::reset();
        }

        // Save state only if query params exist
        ListStateHelper::remember();

        // Read state ONLY from helper
        $state = ListStateHelper::get();

        $page  = max(1, (int) $state['page']);
        $limit = (int) Env::get('PAGINATION_LIMIT', 10);
        $offset = ($page - 1) * $limit;

        $allowedSorts = ['name', 'email', 'role'];
        $sort = in_array($state['sort'], $allowedSorts)
            ? $state['sort']
            : 'name';

        $dir = in_array($state['dir'], ['asc', 'desc'])
            ? $state['dir']
            : 'asc';

        // Total count
        $total = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalPages = (int) ceil($total / $limit);

        // Fetch data
        $stmt = $this->db->prepare("
            SELECT id, name, email, role, created_at FROM users
            ORDER BY {$sort} {$dir}
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll();

        $this->render('users/index', [
            'users'   => $users,
            'title'    => 'User List',
            'page'       => $page,
            'limit'      => $limit,
            'totalCount' => $total,
            'totalPages' => $totalPages,
            'sort'       => $sort,
            'dir'        => $dir
        ]);
    }   

    // Show create user form
    public function create()
    {
      adminOnly();

      $this->render('users/create', [
          'title' => 'Create User'
      ]);
    }

    // Handle form submission
    public function store()
    {
      adminOnly();

      $name     = trim($_POST['name'] ?? '');
      $email    = trim($_POST['email'] ?? '');
      $password = $_POST['password'] ?? '';
      $role     = $_POST['role'] ?? 'user';

      $errors = [];

      // ---- Validation ----
      if ($name === '') {
          $errors['name'] = 'Name is required';
      }

      if ($email === '') {
          $errors['email'] = 'Email is required';
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $errors['email'] = 'Invalid email format';
      }

      if ($password === '') {
          $errors['password'] = 'Password is required';
      } elseif (strlen($password) < 6) {
          $errors['password'] = 'Password must be at least 6 characters';
      }

      if (!in_array($role, ['admin', 'user'])) {
          $errors['role'] = 'Invalid role selected';
      }

      // ---- If validation fails ----
      if (!empty($errors)) {
          Session::set('errors', $errors);
          Session::set('old', [
              'name'  => $name,
              'email' => $email,
              'role'  => $role
          ]);

          header('Location: /users/create');
          exit;
      }

      // ---- Store user ----
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

      try {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$name, $email, $hashedPassword, $role]);

        Session::set('success', 'User created successfully');
        header('Location: /users');
        exit;
      } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            Session::set('errors.email', 'Email already exists');
            Session::set('old', compact('name', 'email', 'role'));
        } else {
            Session::set('errors.general', 'Failed to create user');
        }

        header('Location: /users/create');
        exit;
    }
  }
}