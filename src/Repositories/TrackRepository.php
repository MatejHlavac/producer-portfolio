<?php

namespace App\Repositories;

use App\Models\Track;
use PDO;

class TrackRepository {
    private $dbConnection;

    public function __construct(PDO $db) {
        $this->dbConnection = $db;
    }

    public function findAll(){
        $sql = "SELECT * FROM tracks";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Track::class);
    }
}