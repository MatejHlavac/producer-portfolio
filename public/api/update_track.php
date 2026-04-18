<?php
session_start();
require_once "../../vendor/autoload.php";

use App\Database\Connection;
use App\Models\Track;
use App\Repositories\TrackRepository;

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$db = (new Connection())->connect();
$trackRepo = new TrackRepository($db);

$id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? '';
$genre = $_POST['genre'] ?? '';
$bpm = $_POST['bpm'] ?? 0;

$track = $trackRepo->findById($id);

if ($track) {
    $track->title = $title;
    $track->genre = $genre;
    $track->bpm = $bpm;

    $result = $trackRepo->update($track);
    echo (json_encode(['success' => $result]));
} else {
    echo (json_encode(['success' => false, 'message' => 'Track not found']));
}
