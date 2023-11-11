<?php
session_start();

// Simuler une liste d'utilisateurs enregistrés (vous devrez utiliser une base de données en production)
$users = [
    'user1' => password_hash('password1', PASSWORD_DEFAULT),
    'user2' => password_hash('password2', PASSWORD_DEFAULT),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Login
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (array_key_exists($username, $users) && password_verify($password, $users[$username])) {
            $_SESSION['username'] = $username;
            header('Location: ../pages/dashboard.php');
            exit();
        } else {
            echo 'Invalid credentials';
        }
    }
    // Register
    elseif (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (!array_key_exists($username, $users)) {
            $users[$username] = password_hash($password, PASSWORD_DEFAULT);
            echo 'Registration successful. You can now <a href="../pages/login.php">login</a>.';
        } else {
            echo 'Username already exists. Choose a different username.';
        }
    }
}
?>
