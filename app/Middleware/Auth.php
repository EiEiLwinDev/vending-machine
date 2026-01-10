<?php

// app/Middleware/Auth.php
function auth() {
    if (!isset(Session::get('user')['id'])) {
        header('Location: /login');
        exit;
    }
}

function adminOnly() {
    auth();
    if (Session::get('user')['role'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }
}
?>