<?php

namespace Matteomcr\ApiSeekSneaker\Controllers;

// use Slim\Http\Response as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Matteomcr\ApiSeekSneaker\Models\User;
use Matteomcr\ApiSeekSneaker\Models\Utils;
use Matteomcr\ApiSeekSneaker\Models\Sneaker;



header('Access-Control-Allow-Origin: *');

class SneakerController {

    public function createSneaker(ServerRequestInterface $request, ResponseInterface $response) {
        $body = json_decode($request->getBody(), true);
    
        // Vérifie s'il ne manque aucune informations
        if (!isset($body['nom']) || !isset($body['description']) || !isset($body['marque']) || !isset($body['taille']) || !isset($body['couleur']) || !isset($body['prix']) || !isset($body['stock']) || !isset($body['image']) || !isset($body['idVendeur'])) {
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(400);
            return $response->withJson(['Erreur' => 'Informations manquante']);
        }
    
        $nom = Utils::sanitizeString($body['nom']);
        $description = Utils::sanitizeString($body['description']);
        $marque = Utils::sanitizeString($body['marque']);
        $taille = $body['taille'];
        $couleur = Utils::sanitizeString($body['couleur']);
        $prix = $body['prix'];
        $stock = $body['stock'];
        $image = Utils::sanitizeString($body['image']);
        $idVendeur = $body['idVendeur'];
        $dateAnnonce = date('Y-m-d');
    
        // Vérifie si l'email ou le mot de passe sont vides après nettoyage
        if (empty($nom) || empty($description) || empty($marque) || empty($taille) || empty($couleur) 
            || empty($prix) || empty($stock) || empty($image) || empty($idVendeur)) {
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(400);
            return $response->withJson(['Erreur' => 'Les informations entrées ne sont pas valides']);
        }
    
        // Crée le compte utilisateur
        try {
            $result = Sneaker::create($nom, $description, $marque, $taille, $couleur, $prix, $stock, $image, $idVendeur, $dateAnnonce);
            return $response->withHeader('Content-Type', 'application/json')
                            ->withJson(['Succès' => 'La sneaker a été créée avec succès']);
        } catch (\Throwable $th) {
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(500);
            return $response->withJson(['Erreur' => 'Erreur interne du serveur']);
        }
    }
    

    public function updateSneaker(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $id = $args['id'];
        $body = json_decode($request->getBody(), true);
    
        // Vérification des champs obligatoires
        if (!isset($body['nom']) || !isset($body['description']) || !isset($body['marque']) || !isset($body['taille']) || !isset($body['couleur']) || !isset($body['prix']) || !isset($body['stock']) || !isset($body['image'])) {
            $response = $response->withHeader('Content-Type', 'application/json')
                                 ->withStatus(400); // Code 400 pour "Bad Request"
            return $response->withJson(['Erreur' => 'Informations manquantes']);
        }
    
        $nom = Utils::sanitizeString($body['nom']);
        $description = Utils::sanitizeString($body['description']);
        $marque = Utils::sanitizeString($body['marque']);
        $taille = $body['taille'];
        $couleur = Utils::sanitizeString($body['couleur']);
        $prix = $body['prix'];
        $stock = $body['stock'];
        $image = Utils::sanitizeString($body['image']);
    
        // Tentative de mise à jour de la sneaker
        try {
            Sneaker::update($id, $nom, $description, $marque, $taille, $couleur, $prix, $stock, $image);
            return $response->withHeader('Content-Type', 'application/json')
                            ->withJson(['Succès' => 'La sneaker a été modifiée avec succès']);
        } catch (\Exception $e) {
            $response = $response->withHeader('Content-Type', 'application/json')->withStatus(500); 
            return $response->withJson(['Erreur lors de la modification' => $e->getMessage()]);
        }
    }
    
    public function deleteSneaker(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $id = $args['id'];
    
        try {
            Sneaker::delete($id);
            return $response->withHeader('Content-Type', 'application/json')
                            ->withJson(['Succès' => 'La sneaker a été supprimée avec succès']);
        } catch (\Exception $e) {
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(500) 
                            ->withJson(['Erreur lors de la suppression' => $e->getMessage()]);
        }
    }

    public function getSneakers(ServerRequestInterface $request, ResponseInterface $response) {
        try {
            $allSneakers = Sneaker::fetchAll();
            return $response->withHeader('Content-Type', 'application/json')->withJson($allSneakers);
        } catch (\Exception $e) {
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500) 
                            ->withJson(['Erreur' => 'Impossible de récupérer les données']);
        }
    }

    public function getSneakerById(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $id = $args['id'];
    
        try {
            $sneaker = Sneaker::fetchById($id);
            if (!$sneaker) {
                return $response->withHeader('Content-Type', 'application/json')
                                ->withStatus(404) // Code 404 pour "Not Found"
                                ->withJson(['Erreur' => 'Sneaker non trouvée']);
            }
            return $response->withHeader('Content-Type', 'application/json')
                            ->withJson($sneaker);
        } catch (\Exception $e) {
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(500) 
                            ->withJson(['Erreur' => 'Erreur lors de la récupération de la sneaker']);
        }
    }

}