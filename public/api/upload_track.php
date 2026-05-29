<?php

session_start();

const MAX_FILE_SIZE = 10 * 1024 * 1024;
const ALLOWED_MIME_TYPES = ['audio/mpeg'];


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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $file = $_FILES['audio'];

    if ($file['size'] > MAX_FILE_SIZE) {
        echo json_encode(['success' => false, 'message' => 'File size is too big. Maximum is 10 MB.']);
        exit;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
        echo json_encode(['success' => false, 'message' => 'Type of file not allowed. Only MP3 files allowed.']);
        exit;
    }

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


    $uploadDir = '../../uploads/tracks/';
    $destination = $uploadDir . $finalFileName;
    $relativeFilePath = 'uploads/tracks/' . $finalFileName;


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
            exit();
        } else {
            echo (json_encode(['success' => false, 'message' => 'Database saving failed']));
            exit();
        }
    } else {
        echo (json_encode(['success' => false, 'message' => 'Failed to move uploaded file']));
        exit();
    }
}
