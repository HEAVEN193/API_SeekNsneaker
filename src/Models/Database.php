<?php

namespace Matteomcr\ApiSeekSneaker\Models;
require_once "constantes.php";


use PDO;

class Database
{
    /**
     * Open a new database connection if needed, returns the current connection
     *
     * @return PDO
     */
    public static function connection(): PDO
    {
        static $pdo = null;

        if ($pdo === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
    
                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (\Throwable $th) {
                // @todo Add log entry
                die("Can't connect to database");
            }
        }

        return $pdo; 
    }
}