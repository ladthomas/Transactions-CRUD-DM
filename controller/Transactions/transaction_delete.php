<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $transactionId = $_GET['id'];

    // Chargez toutes les transactions depuis le fichier
    $transactions = file_get_contents('transactions.txt');
    $transactions = json_decode($transactions, true);

    // Recherchez la transaction à supprimer
    $indexToRemove = -1;
    foreach ($transactions as $index => $transaction) {
        if ($transaction['id'] == $transactionId) {
            $indexToRemove = $index;
            break;
        }
    }

    // Si la transaction est trouvée, supprimez-la
    if ($indexToRemove !== -1) {
        array_splice($transactions, $indexToRemove, 1);

        // Enregistrez les transactions mises à jour dans le fichier
        file_put_contents('transactions.txt', json_encode($transactions));

        // Rediriger vers la liste des transactions après la suppression
        header('Location: /transactions');
        exit;
    } else {
        echo 'Transaction non trouvée.';
    }
}
?>
