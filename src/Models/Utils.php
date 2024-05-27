<?php
namespace Matteomcr\ApiSeekSneaker\Models;
use Matteomcr\ApiSeekSneaker\Models\Database;
use PDO;


/**
 * Classe Utils
 * Fournit des méthodes utilitaires pour la manipulation des chaînes et la réponse HTTP.
 */
class Utils {

    /**
     * Nettoie une chaîne de caractères en éliminant les éléments potentiellement dangereux et en remplaçant certains caractères spéciaux.
     * @param string $txt Chaîne de caractères à nettoyer.
     * @return string Chaîne de caractères nettoyée.
     * 
     * Cette méthode utilise `filter_var` pour filtrer la chaîne en utilisant le filtre UNSAFE_RAW,
     * supprime les balises potentiellement dangereuses et les caractères nuls,
     * et remplace les apostrophes et guillemets pour prévenir les injections HTML et SQL.
     */
    public static function sanitizeString(string $txt): string
    {
        $txt = filter_var($txt, FILTER_UNSAFE_RAW);
        $txt = preg_replace('/\x00|<[^>]*>?/', "", $txt);
        return str_replace(["'", '"'], ["&#39;", "&#34;"], $txt);
    }


     /**
     * Envoie une réponse JSON au client, incluant les headers nécessaires pour l'API.
     * @param array $response Tableau associatif contenant les données à renvoyer.
     * 
     * Cette méthode configure les en-têtes pour autoriser les requêtes cross-origin et définit le type de contenu en JSON,
     * puis envoie la réponse JSON encodée à l'utilisateur.
     */
    public static function returnResponse($response)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($response);
    }
}