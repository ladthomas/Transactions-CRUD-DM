<?php
session_start();

// Vérifier si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $label = $_POST['label']; // Add this line to retrieve the label from the form

    // Vérifier si les champs du montant et du libellé ne sont pas vides
    if (empty($amount) || empty($label)) {
        echo 'Les champs montant et libellé sont obligatoires.';
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

        // Préparer la requête pour insérer la transaction avec le libellé
        $stmt = $conn->prepare("INSERT INTO transactions (amount, label, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("dsi", $amount, $label, $_SESSION['user']['user_id']); // Update the "bind_param" line

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

        <!-- pour ajouter libellé de la transaction  -->
        <label for="label">Libellé:</label>
        <input type="text" id="label" name="label" required><br>

        <!-- Ajoutez d'autres champs pour votre transaction -->
        <input type="submit" value="Créer la transaction">
    </form>
</body>
</html>
