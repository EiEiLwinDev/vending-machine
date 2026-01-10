<?php 

class AuthApiController
{
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function login()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        if (!$email || !$password) {
            http_response_code(422);
            echo json_encode(['error' => 'Email and password required']);
            return;
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            Response::error("Invalid credentials", 401);
            return;
        }

        // Generate JWT token
        $token = JwtHelper::generate([
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ]);

        Response::success([
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role'],
            ],
            'token' => $token
        ], "Login successful"); 
    }
}
?>