<?php

namespace App\Models;

class Track {
    public $id;
    public $title;
    public $genre;
    public $bpm;
    public $file_path;
    public $created_at;


    public function __construct($title = null, $genre = null, $bpm = null, $file_path = null){
        $this->title = $title;
        $this->genre = $genre;
        $this->bpm = $bpm;
        $this->file_path = $file_path;
    }


}