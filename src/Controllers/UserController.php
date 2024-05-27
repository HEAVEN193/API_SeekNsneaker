<?php

namespace Matteomcr\ApiSeekSneaker\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Matteomcr\ApiSeekSneaker\Models\User;
use Matteomcr\ApiSeekSneaker\Models\Utils;


/**
 * Classe UserController
 * Gère les opérations liées aux comptes utilisateurs comme la création, la connexion et la déconnexion.
 */
class UserController {

/**
     * Crée un compte utilisateur avec les données fournies.
     * @param ServerRequestInterface $request Requête contenant les données de l'utilisateur.
     * @param ResponseInterface $response Réponse à renvoyer au client.
     * @return ResponseInterface Réponse avec le résultat de la création du compte.
     */
public function createAccount(ServerRequestInterface $request, ResponseInterface $response)
{
    $body = json_decode($request->getBody(), true);

    // Vérifie si tout les paramètre sont entrées
    if (!isset($body['prenom']) || !isset($body['nom']) || !isset($body['email']) 
    || !isset($body['password']) || !isset($body['adresse']) || !isset($body['droit'])){
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400)
                        ->withJson(['Erreur' => 'Informations manquantes !']);
    }

    // Filtre les entrées
    $prenom     = Utils::sanitizeString($body['prenom']);
    $nom        = Utils::sanitizeString($body['nom']);
    $email      = Utils::sanitizeString($body['email']);
    $password   = Utils::sanitizeString($body['password']);
    $adresse    = Utils::sanitizeString($body['adresse']);
    $droit      = Utils::sanitizeString($body['droit']);

    // Vérifie que les champs ne soi pas vide après voir été sanitize
    if (empty($prenom) || empty($nom) || empty($email) 
    || empty($password) || empty($adresse) || empty($droit)) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400)
                        ->withJson(['Erreur' => 'Les identifiants sont invalides !']);
    }

    // Vérifie si l'email existe déjà
    if (User::emailAlreadyExist($email)) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(409)
                        ->withJson(['Erreur' => 'Email déjà associé à un compte !']);
    }

    // Tentative de création de compte
    try {
        $result = User::create($prenom, $nom, $email, $password, $adresse, $droit);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withJson(['Succès' => 'Le compte a été créé avec succès !']);
    } catch (\Exception $e) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(500)
                        ->withJson(['Erreur' => $e->getMessage()]);
    }
}


/**
     * Connecte un utilisateur en vérifiant ses identifiants.
     * @param ServerRequestInterface $request Requête contenant l'email et le mot de passe de l'utilisateur.
     * @param ResponseInterface $response Réponse à renvoyer au client.
     * @return ResponseInterface Réponse indiquant si la connexion a réussi ou échoué.
*/
public function login(ServerRequestInterface $request, ResponseInterface $response)
{
    $body = json_decode($request->getBody(), true);

    // Vérifie que l'email et le mot de passe sont bien entré
    if (!isset($body['email']) || !isset($body['password'])) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400)
                        ->withJson(['Erreur' => 'Email et mot de passe requis !']);
    }

    $email      = Utils::sanitizeString($body['email']);
    $password   = Utils::sanitizeString($body['password']);

    // Vérifie que les champs ne sont pas vides 
    if (empty($email) || empty($password)) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400)
                        ->withJson(['Erreur' => 'Email ou mot de passe invalide !']);
    }

    // tentative d'authentification
    try {
        $user = User::login($email, $password);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withJson(['Succès' => 'Connexion réussie !']);
    } catch (\Exception $e) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(500)
                        ->withJson(['Erreur' => $e->getMessage()]);
    }
}

    
/**
     * Déconnecte un utilisateur.
     * @param ServerRequestInterface $request Requête pour la déconnexion.
     * @param ResponseInterface $response Réponse à renvoyer au client.
     * @param array $args Arguments incluant l'id de l'utilisateur à déconnecter.
     * @return ResponseInterface Réponse indiquant si la déconnexion a été réussie.
     */
    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $id = $args['id'];

        // Vérifie que l'utilisateur existe
        if (!User::exist($id)) {
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(404)
                            ->withJson(['Erreur' => 'Utilisateur inexistant !']);
        }

        try {
            User::logout($id);
            return $response->withHeader('Content-Type', 'application/json')
                            ->withJson(['Succès' => 'Vous avez été déconnecté !']);
        } catch (\Exception $e) {
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(500)
                            ->withJson(['Erreur' => $e->getMessage()]);
        }


    }

}