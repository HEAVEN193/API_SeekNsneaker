<?php
namespace Matteomcr\ApiSeekSneaker\Models;
use Matteomcr\ApiSeekSneaker\Models\Database;
use PDO;

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
                throw new \Exception("Sneaker avec l'ID $id n'existe pas.");
            }
    
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }
    

    public static function delete(int $id){
        try {
            $pdo = Database::connection();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active le rapport d'erreurs
    
            $stmt = $pdo->prepare("DELETE FROM Annonce WHERE AnnonceID = :id");
            $stmt->bindParam(':id', $id);
    
            $stmt->execute();
    
            // Si aucune ligne n'a été affectée, on suppose que l'ID n'existe pas
            if ($stmt->rowCount() == 0) {
                throw new \Exception("Sneaker avec l'ID $id n'existe pas.");
            }
    
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

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