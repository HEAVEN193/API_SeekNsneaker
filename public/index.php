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
// Lancer l'application
$app->run();