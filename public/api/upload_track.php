<?php

session_start();
require_once "../../vendor/autoload.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

use App\Database\Connection;
use App\Models\Track;
use App\Repositories\TrackRepository;

$db = (new Connection())->connect();
$trackRepo = new TrackRepository($db);

if ($_SERVER['REQUEST METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $file = $_FILES['audio'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo (json_encode(['success' => false, 'message' => 'File upload error code: ' . $file['error']]));
        exit;
    }

    $title = trim($_POST['title']);
    $genre = trim($_POST['genre']);
    $bpm = (int)$_POST['bpm'];


    if (empty($title) || empty($genre) || $bpm <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid text data']);
        exit;
    }




    //sanitization and generation of file name

    $originalFileName = $file['name'];
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

    $pureName = pathinfo($originalFileName, PATHINFO_FILENAME);

    $cleanName = iconv('UTF-8', 'ASCII//TRANSLIT', $pureName);
    $cleanName = preg_replace('/[^a-zA-Z0-9]/', '-', $cleanName);
    $cleanName = strtolower(trim($cleanName, '-'));
    $cleanName = preg_replace('/-+/', '-', $cleanName);

    $finalFileName = time() . '_' . $cleanName . '.' . $fileExtension;


    $uploadDir = '../../uploads/tracks';
    $destination = $uploadDir . $finalFileName;
    $relativeFilePath = 'uploads/tracks' . $finalFileName;


    //transfer uploaded file from temporary folder to our folder 
    // AND 
    //create new instance of track in database

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $newTrack = new Track($title, $genre, $bpm, $relativeFilePath);

        $newTrackId = $trackRepo->save($newTrack);

        if ($newTrackId) {
            echo (json_encode([
                'success'   => true,
                'message'   => 'Track uploaded successfully',
                'id'        => $newTrackId,
                'file_path' => $newTrack->file_path
            ]));
        } else {
            echo (json_encode(['success' => false, 'message' => 'Database saving failed']));
        }
    } else {
        echo (json_encode(['success' => false, 'message' => 'Failed to move uploaded file']));
    }
}
