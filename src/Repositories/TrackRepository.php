<?php

namespace App\Repositories;
namespace App\Models\Track;

class TrackRepository {
    private $dbConnection;

    public function __construct($db) {
        $this->dbConnection = $db;
    }

    public function findAll(){
        $sql = "SELECT * FROM tracks";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute();


    }
}