<?php


use Matteomcr\ApiSeekSneaker\Controllers\UserController;
use Matteomcr\ApiSeekSneaker\Controllers\SneakerController;


$app->post('/create-account', [UserController::class, 'createAccount']);
$app->post('/login', [UserController::class, 'login']);
$app->get('/logout/{id:[0-9]+}', [UserController::class, 'logout']);

$app->get('/annonce', [SneakerController::class, 'getSneakers']);
$app->get('/annonce/{id:[0-9]+}', [SneakerController::class, 'getSneakerById']);
$app->post('/create-annonce', [SneakerController::class, 'createSneaker']);
$app->delete('/delete-annonce/{id:[0-9]+}', [SneakerController::class, 'deleteSneaker']);
$app->update('/update-annonce/{id:[0-9]+}', [SneakerController::class, 'updateSneaker']);





/*
{
    "nom": "Air max 96",   
    "description": "bon état",
    "marque": "nike",
    "taille": "41",
    "couleur": "rouge",
    "prix": "90",
    "stock": "1",
    "image": "https://rb.gy/el1nn0",
}

{
    
    "nom": "Nicastro",   
    "prenom": "Luca",
    "email": "luca@gmail.com",
    "password": "peutetre",
    "adresse": "Rue des golmons",
    "droit": "1"
}


*/