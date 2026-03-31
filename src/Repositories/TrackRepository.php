<?php

namespace App\Repositories;

class TrackRepository {
    private $dbConnection;

    public function __construct($db) {
        $this->dbConnection = $db;
    }
}