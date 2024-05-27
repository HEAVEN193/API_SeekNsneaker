<?php
// Indiquer les classes à utiliser
use Slim\Factory\AppFactory;
// Activer le chargement automatique des classes
require __DIR__ . '/../vendor/autoload.php';
// Créer l'application
$app = AppFactory::create();
// Ajouter certains traitements d'erreurs
$app->addErrorMiddleware(true, true, true);
// Définir les routes
require __DIR__ . '/../routes/web.php';

header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Headers:X-Request-With');

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Lancer l'application
$app->run();