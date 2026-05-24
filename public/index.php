<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;
use App\Models\Track;
use App\Repositories\TrackRepository;

$dbConnection = new Connection();
$db = $dbConnection->connect();

$repositoryController = new TrackRepository($db);

$test = new Track("Radiohead", "Alternative Rock", 120, "testPathxxxxxxx");
$repositoryController->save($test);





echo ("<h2>Our tracks: </h2><br>");

echo ("<p>This is track that I was looking for: $test->title</p>");
