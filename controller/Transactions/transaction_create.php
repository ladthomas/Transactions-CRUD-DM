<?php
session_start();

// Vérifier si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];

    // Vérifier si le champ du montant est vide
    if (empty($amount)) {
        echo 'Le champ montant est obligatoire.';
    } else {
        // Ajoutez votre logique de création de transaction ici

        // Exemple de connexion à une base de données MySQL
        $dbHost = 'localhost';
        $dbUser = 'root';
        $dbPassword = 'root';
        $dbName = 'schema';

        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        // Vérifier la connexion à la base de données
        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données: " . $conn->connect_error);
        }

        // Préparer la requête pour insérer la transaction
        $stmt = $conn->prepare("INSERT INTO transactions (amount, user_id) VALUES (?, ?)");
        $stmt->bind_param("di", $amount, $_SESSION['user']['user_id']);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Fermer la connexion et la requête
            $stmt->close();
            $conn->close();

            // Rediriger vers la liste des transactions après la création
            header('Location: /transactions');
            exit;
        } else {
            echo 'Erreur lors de l\'enregistrement de la transaction.';
        }

        // Fermer la connexion et la requête
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une transaction</title>
</head>
<body>
    <h1>Créer une transaction</h1>
    <form method="post" action="/transactions">
        <label for="amount">Montant:</label>
        <input type="text" id="amount" name="amount" required><br>
        <!-- Ajoutez d'autres champs pour votre transaction -->
        <input type="submit" value="Créer la transaction">
    </form>
</body>
</html>
