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

        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
    }
    

    public function findById($id){
        $sql = "SELECT * FROM tracks WHERE id = :id";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute([':id' => $id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Track::class);
        return $stmt->fetch();
    }











}