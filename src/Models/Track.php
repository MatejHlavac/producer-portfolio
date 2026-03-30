<?php

namespace App\Models;

class Track {
    public $id;
    public $title;
    public $bpm;
    public $key_signature;
    public $file_path;
    public $created_at;


    public function __construct($title = null, $bpm = null, $key_signature = null, $file_path = null){
        $this->title = $title;
        $this->bpm = $bpm;
        $this->key_signature = $key_signature;
        $this->file_path = $file_path;
    }

    
}