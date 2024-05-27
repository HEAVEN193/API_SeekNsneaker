<?php
namespace Matteomcr\ApiSeekSneaker\Models;
use Matteomcr\ApiSeekSneaker\Models\Database;
use PDO;

/**
 * Classe User
 * Gère les interactions avec la table des utilisateurs dans la base de données.
 */
class User {

    /**
     * Crée un nouvel utilisateur dans la base de données.
     * @param string $nom Nom de l'utilisateur.
     * @param string $prenom Prénom de l'utilisateur.
     * @param string $email Email de l'utilisateur.
     * @param string $motDePasse Mot de passe de l'utilisateur.
     * @param string $adresse Adresse de l'utilisateur.
     * @param int $droitId ID du droit associé à l'utilisateur.
     */
    public static function create($nom, $prenom, $email, $motDePasse, $adresse, $droitId) {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("INSERT INTO Utilisateurs (Nom, Prenom, Email, MotDePasse, Adresse, DroitID ) VALUES (:nom, :prenom, :email, :motdepasse, :adresse, :droitid)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':motdepasse', password_hash($motDePasse, PASSWORD_DEFAULT));
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':droitid', $droitId);
        $stmt->execute();
    }

    /**
     * Authentifie un utilisateur avec son email et son mot de passe.
     * @param string $email Email de l'utilisateur.
     * @param string $password Mot de passe de l'utilisateur.
     * @return array Retourne les informations de l'utilisateur si l'authentification est réussie.
     * @throws \Exception Si l'email n'existe pas ou si le mot de passe est incorrect.
     */
    public static function login($email, $password) {

        $pdo = Database::connection();

        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifie si le mot de passe correspond
            if (password_verify($password, $user['MotDePasse'])) {
                // Connexion réussie
                $stmt = $pdo->prepare("UPDATE Utilisateurs SET isConnected = 1 WHERE UserID = :id");
                $stmt->bindParam(':id', $user['UserID']);
                $stmt->execute();

                return $user;
            } else {
                throw new \Exception("Mot de passe incorrect !");
            }
        } else {
            // Email n'existe pas
            throw new \Exception("Email inexistant !");
        }
    }

    /**
     * Déconnecte un utilisateur en réinitialisant son statut de connexion.
     * @param int $id Identifiant de l'utilisateur à déconnecter.
     */
    public static function logout($id){
        $pdo = Database::connection();

        $stmt = $pdo->prepare("UPDATE Utilisateurs SET isConnected = 0 WHERE UserID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    /**
     * Vérifie si un email est déjà associé à un compte existant.
     * @param string $email Email à vérifier.
     * @return bool Retourne vrai si l'email existe déjà, faux sinon.
     */
    public static function emailAlreadyExist($email) :bool{
        $pdo = Database::connection();

        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            return true;
        }

        return false;
    }

    /**
     * Vérifie si un utilisateur existe dans la base de données par son identifiant.
     * @param int $id Identifiant de l'utilisateur à vérifier.
     * @return bool Retourne vrai si l'utilisateur existe, faux sinon.
     */
    public static function exist($id) :bool{
        $pdo = Database::connection();

        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE UserID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            return true;
        }

        return false;
    }
}