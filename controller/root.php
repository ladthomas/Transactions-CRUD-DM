<?php

// Récupérer l'URL demandée
$request_uri = $_SERVER['REQUEST_URI'];

// Charger les classes automatiquement
spl_autoload_register(function ($class_name) {
    include 'controllers/' . $class_name . '.php';
});

// Créer un tableau de correspondances entre les URL et les contrôleurs
$routes = array(
    '/' => 'HomeController',
    '/login' => 'AuthController',
    '/register' => 'AuthController',
    '/transactions' => 'TransactionsController',
);

// Vérifier si l'URL demandée correspond à une entrée dans le tableau des routes
if (array_key_exists($request_uri, $routes)) {
    $controller_name = $routes[$request_uri];
    $controller = new $controller_name();
    $controller->index();
} else {
    // Gérer les cas où l'URL n'a pas de correspondance
    header('HTTP/1.0 404 Not Found');
    echo 'Page non trouvée';
}
