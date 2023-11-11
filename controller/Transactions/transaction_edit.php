<?php
session_start();

// Fonction pour récupérer toutes les transactions à partir du fichier texte
function getAllTransactionsFromFile() {
    $file = 'transactions.txt';

    // Vérifier si le fichier existe
    if (file_exists($file)) {
        // Lire le contenu du fichier
        $contents = file_get_contents($file);

        // Décoder le contenu JSON en un tableau PHP
        $transactions = json_decode($contents, true);

        // Vérifier si le décodage a réussi
        if ($transactions !== null) {
            return $transactions;
        }
    }

    // Retourner un tableau vide si le fichier n'existe pas ou s'il y a une erreur
    return [];
}

// Fonction pour sauvegarder toutes les transactions dans le fichier texte
function saveAllTransactionsToFile($transactions) {
    $file = 'transactions.txt';

    // Encoder le tableau PHP en JSON
    $jsonContents = json_encode($transactions);

    // Écrire le contenu JSON dans le fichier
    file_put_contents($file, $jsonContents);
}

// Vérifier si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

// Vérifier si la requête est une requête GET et si l'identifiant de transaction est défini
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $transactionId = $_GET['id'];

    // Récupérer toutes les transactions
    $allTransactions = getAllTransactionsFromFile();

    // Vérifier si la transaction existe
    if (array_key_exists($transactionId, $allTransactions)) {
        $transaction = $allTransactions[$transactionId];

        // Vérifier si l'utilisateur a le droit de modifier cette transaction
        if ($transaction['user'] == $_SESSION['user']) {
            // Affichez le formulaire de modification avec les données de la transaction
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title >Modifier la transaction</title>
            </head>
            <body>
                <h1>Modifier la transaction</h1>
                <form method="post" action="/transactions/update?id=<?php echo $transactionId; ?>">
                    <label for="amount">Montant:</label>
                    <input type="text" id="amount" name="amount" value="<?php echo $transaction['amount']; ?>" required><br>
                    <!-- Ajoutez d'autres champs pour votre transaction -->
                    <input type="submit" value="Modifier la transaction">
                </form>
            </body>
            </html>
            <?php
            exit;
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo 'Vous n\'avez pas le droit de modifier cette transaction.';
            exit;
        }
    } else {
        header('HTTP/1.1 404 Not Found');
        echo 'La transaction n\'existe pas.';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    // Traitement du formulaire de modification
    $transactionId = $_GET['id'];
    $amount = $_POST['amount'];

    // Récupérer toutes les transactions
    $allTransactions = getAllTransactionsFromFile();

    // Vérifier si la transaction existe
    if (array_key_exists($transactionId, $allTransactions)) {
        $transaction = $allTransactions[$transactionId];

        // Vérifier si l'utilisateur a le droit de modifier cette transaction
        if ($transaction['user'] == $_SESSION['user']) {
            // Ajoutez ici votre logique pour mettre à jour la transaction

            // Mettez à jour le montant dans la transaction
            $allTransactions[$transactionId]['amount'] = $amount;

            // Sauvegarder toutes les transactions dans le fichier
            saveAllTransactionsToFile($allTransactions);

            // Rediriger vers la liste des transactions après la modification
            header('Location: /transactions');
            exit;
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo 'Vous n\'avez pas le droit de modifier cette transaction.';
            exit;
        }
    } else {
        header('HTTP/1.1 404 Not Found');
        echo 'La transaction n\'existe pas.';
        exit;
    }
}
?>
