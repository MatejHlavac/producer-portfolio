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


    public function delete($id){
        $sql = "DELETE FROM tracks WHERE id = :id";
        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }


    public function update(Track $track) {
        $sql = "UPDATE tracks
                SET title = :title, genre = :genre, bpm = :bpm
                WHERE id = :id";
        $stmt = $this->dbConnection->prepare($sql);
        return $stmt->execute([
            ":title" => $track->title, 
            ":genre" => $track->genre, 
            ":bpm"   => $track->bpm, 
            ":id"    => $track->id
        ]);
    }





}