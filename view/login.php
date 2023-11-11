<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
    <p style="color:red;">
        <?php
            session_start();
            isset($_SESSION['message']['connexion'])?$_SESSION['message']['connexion']:' ';
        ?>
    </p>
</body>
</html>

<?php
// Démarrer la session PHP
session_start();

// Vérifier si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Exemple de connexion à une base de données MySQL
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPassword = 'root';
    $dbName = 'schema';

    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
    } else {
        // Exécuter la requête pour récupérer le mot de passe associé au nom d'utilisateur
        $stmt = $conn->prepare("SELECT `password` FROM users WHERE `name` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Vérifier si le mot de passe est correct
        if (password_verify($password, $hashed_password)) {
            $_SESSION['message']['connexion'] = 'Connexion réussie !';
            $_SESSION['user_logged_in']="1";
            // Rediriger vers la page d'accueil ou toute autre page souhaitée après la connexion
            header("Location: transaction.php");
            exit();
        } else {
            $_SESSION['message']['connexion'] = 'Nom d\'utilisateur ou mot de passe incorrect.';
            // Rediriger vers la page de connexion en cas d'échec de connexion
            header("Location: login.php");
            exit();
        }

    }
}

?>
