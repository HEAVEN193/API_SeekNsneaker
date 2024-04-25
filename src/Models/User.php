<?php
namespace Matteomcr\ApiSeekSneaker\Models;
use Matteomcr\ApiSeekSneaker\Models\Database;
use PDO;

class User {

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
                throw new \Exception("Mot de passe incorrect");
            }
        } else {
            // Email n'existe pas
            throw new \Exception("Email inexistant");
        }
    }

    public static function logout($id){
        $pdo = Database::connection();

        $stmt = $pdo->prepare("UPDATE Utilisateurs SET isConnected = 0 WHERE UserID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }


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