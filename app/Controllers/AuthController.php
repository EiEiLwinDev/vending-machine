<?php

  class AuthController extends Controller
  { 
      private PDO $db;

      public function __construct() {
          $this->db = Database::connect();
      }

      public function showLogin()
      {
          $this->render('auth/login', [
              'title' => 'Login'
          ]);
      }

      public function login()
      {
          $email = trim($_POST['email'] ?? '');
          $password = $_POST['password'] ?? '';

          if ($email === '' || $password === '') {
              Session::set('error', 'Email and password are required');
              header('Location: /login');
              exit;
          }

          $stmt = $this->db->prepare(
              "SELECT id, name, email, password, role FROM users WHERE email = ?"
          );
          $stmt->execute([$email]);
          $user = $stmt->fetch();

          if (!$user || !password_verify($password, $user['password'])) {
              Session::set('error', 'Invalid credentials');
              header('Location: /login');
              exit;
          }

          // Login success
          Session::set('user', [
              'id' => $user['id'],
              'name' => $user['name'],
              'email' => $user['email'],
              'role' => $user['role']
          ]);

          header('Location: /');
          exit;
      }

      public function logout()
      {
          session_destroy();
          header('Location: /login');
          exit;
      }
  }
?>