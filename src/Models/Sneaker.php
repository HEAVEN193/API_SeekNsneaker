<?php
namespace Matteomcr\ApiSeekSneaker\Models;
use Matteomcr\ApiSeekSneaker\Models\Database;
use PDO;


/**
 * Classe Sneaker
 * Représente une sneaker et gère les interactions avec la base de données pour les opérations CRUD.
 */

class Sneaker {
    public $AnnonceID;
    public $Nom;
    public $Description;
    public $Marque;
    public $Taille;
    public $Couleur;
    public $Prix;
    public $StockDisponible;
    public $Image;
    public $idVendeur;
    public $DateAnnonce;
    public $EstVendu;


    
    /**
     * Crée une nouvelle annonce de sneaker dans la base de données.
     * @param string $nom Nom de la sneaker.
     * @param string $description Description de la sneaker.
     * @param string $marque Marque de la sneaker.
     * @param int $taille Taille de la sneaker.
     * @param string $couleur Couleur de la sneaker.
     * @param float $prix Prix de la sneaker.
     * @param int $stockDisponible Stock disponible de la sneaker.
     * @param string $image URL de l'image de la sneaker.
     * @param int $idvendeur Identifiant du vendeur de la sneaker.
     * @param string $dateAnnonce Date de l'annonce.
     * @throws \Exception Si une erreur survient pendant l'insertion.
     */
    public static function create($nom, $description, $marque, $taille, $couleur, $prix, $stockDisponible, $image, $idvendeur, $dateAnnonce ) {
        try {
            $pdo = Database::connection();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare("INSERT INTO Annonce (Nom, Description, Marque, Taille, Couleur, Prix, StockDisponible, `Image`, idVendeur, DateAnnonce) VALUES (:Nom, :Description, :Marque, :Taille, :Couleur, :Prix, :StockDisponible, :Image, :idVendeur, :dateAnnonce)");
            
            $stmt->bindParam(':Nom', $nom);
            $stmt->bindParam(':Description', $description);
            $stmt->bindParam(':Marque', $marque);
            $stmt->bindParam(':Taille', $taille);
            $stmt->bindParam(':Couleur', $couleur);
            $stmt->bindParam(':Prix', $prix);
            $stmt->bindParam(':StockDisponible', $stockDisponible);
            $stmt->bindParam(':Image', $image);
            $stmt->bindParam(':idVendeur', $idvendeur);
            $stmt->bindParam(':dateAnnonce', $dateAnnonce);
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Met à jour une annonce existante de sneaker.
     * @param int $id Identifiant de la sneaker.
     * @param string $nom Nom de la sneaker.
     * @param string $description Description de la sneaker.
     * @param string $marque Marque de la sneaker.
     * @param int $taille Taille de la sneaker.
     * @param string $couleur Couleur de la sneaker.
     * @param float $prix Prix de la sneaker.
     * @param int $stockDisponible Stock disponible.
     * @param string $image URL de l'image de la sneaker.
     * @throws \Exception Si l'annonce ne peut pas être mise à jour.
     */
    public static function update($id, $nom, $description, $marque, $taille, $couleur, $prix, $stockDisponible, $image) {
        try {
            $pdo = Database::connection();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $sql = "UPDATE Annonce SET 
                Nom = :Nom, 
                `Description` = :Description, 
                Marque = :Marque, 
                Taille = :Taille, 
                Couleur = :Couleur, 
                Prix = :Prix, 
                StockDisponible = :StockDisponible, 
                `Image` = :Image
                WHERE AnnonceID = :id";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':Nom', $nom);
            $stmt->bindParam(':Description', $description);
            $stmt->bindParam(':Marque', $marque);
            $stmt->bindParam(':Taille', $taille);
            $stmt->bindParam(':Couleur', $couleur);
            $stmt->bindParam(':Prix', $prix);
            $stmt->bindParam(':StockDisponible', $stockDisponible);
            $stmt->bindParam(':Image', $image);
        
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                throw new \Exception("Sneaker avec l'ID $id n'existe pas !");
            }
    
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * Supprime une annonce de sneaker.
     * @param int $id Identifiant de la sneaker à supprimer.
     * @throws \Exception Si l'annonce ne peut pas être supprimée.
     */
    public static function delete(int $id){
        try {
            $pdo = Database::connection();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active le rapport d'erreurs
    
            $stmt = $pdo->prepare("DELETE FROM Annonce WHERE AnnonceID = :id");
            $stmt->bindParam(':id', $id);
    
            $stmt->execute();
    
            // Si aucune ligne n'a été affectée, on suppose que l'ID n'existe pas
            if ($stmt->rowCount() == 0) {
                throw new \Exception("Sneaker avec l'ID $id n'existe pas !");
            }
    
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    
    /**
     * Récupère toutes les annonces de sneakers.
     * @return array Liste des sneakers.
     * @throws \Exception Si une erreur survient lors de la récupération des données.
     */
    public static function fetchAll() :array
    {
        try {
            $statement = Database::connection()->prepare("SELECT * FROM Annonce");
            $statement->execute();
            $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
            return $statement->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Récupère une sneaker par son identifiant.
     * @param int $id Identifiant de la sneaker.
     * @return Sneaker|false Retourne la sneaker ou false si elle n'est pas trouvée.
     * @throws \Exception Si une erreur survient lors de la récupération de la sneaker.
     */
    public static function fetchById(int $id) : Sneaker|false
    {
        try {
            $statement = Database::connection()->prepare("SELECT * FROM Annonce WHERE AnnonceID = :id");
            $statement->execute([':id' => $id]);
            $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
            return $statement->fetch();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Vérifie si une sneaker existe dans la base de données.
     * @param int $id Identifiant de la sneaker.
     * @return bool Vrai si la sneaker existe, faux sinon.
     */
    public static function exist($id) :bool{
        $pdo = Database::connection();

        $stmt = $pdo->prepare("SELECT * FROM Annonce WHERE AnnonceID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            return true;
        }

        return false;
    }
}