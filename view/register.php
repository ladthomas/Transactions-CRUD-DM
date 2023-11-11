
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>

    <p> 
        <?php
            isset($_SESSION['message']['inscription'])?$_SESSION['message']['inscription']:'';
        ?>
    </p>
    <!-- Utiliser la même page pour le traitement du formulaire -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        

        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php
// Démarrer la session PHP
session_start();

// Vérifier si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
}
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    // Vérifier si tous les champs sont remplis
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        echo 'Tous les champs sont obligatoires.';
    } 
    // Vérifier la longueur du mot de passe
    elseif (strlen($password) < 8) {
        echo 'Le mot de passe doit contenir au moins 8 caractères.';
    } 
    // Vérifier si l'adresse email est valide
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'L\'adresse email n\'est pas valide.';
    } 
    // Vérifier si les mots de passe correspondent
    elseif ($password !== $confirm_password) {
        echo 'Les mots de passe ne correspondent pas.';
    } else {
   
// si toute les info sont bien conforme 

// Exemple de connexion à une base de données MySQL
$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = 'root';
$dbName = 'schema';

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}  else {
    

    // Exemple de hachage du mot de passe avec bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Ajoutez votre logique d'inscription à la base de données ici
    $stmt = $conn->prepare("INSERT INTO users SET `name`=?, `password`=?, `email`=?");
   
    // Liaison des paramètres
    $stmt->bind_param("sss", $username, $hashed_password, $email);

        // Vérifier la préparation de la requête
        if ($stmt === false) { 
            die('Erreur de préparation de la requête SQL.');
        }

    // Exécuter la requête
    if ($stmt->execute()) {
        $_SESSION['message']['inscription'] = 'Inscription réussie, veuillez vous enregistré !';
        // Fermer la connexion et la requête
        $stmt->close();
        $conn->close();

        // Rediriger vers la page de connexion après l'inscription
        header("Location: login.php");
        exit();
    } else { 
        //si ca fonctionne pas
        $_SESSION['message']['inscription'] = 'Inscription échoué ! veuillez verifié vos information';
        header("Location: register.php");
        exit();
        }

    }
}