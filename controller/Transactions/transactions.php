<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

// Récupérez et affichez les transactions de l'utilisateur
// Ajoutez ici votre logique pour afficher les transactions

echo 'Transactions de l\'utilisateur: ' . $_SESSION['user'];

//<!-- Ajoutez un lien vers la page de création de transaction -->
//<p><a href="/transactions/create">Créer une transaction</a></p>
