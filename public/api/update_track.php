<?php
session_start();

const MAX_FILE_SIZE = 10 * 1024 * 1024;
const ALLOWED_MIME_TYPES = ['audio/mpeg'];

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
    $oldTrackPath = $track->file_path;

    if (isset($_FILES['audio']) && $_FILES['audio']['size'] > 0) {
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
        $track->file_path = $relativeFilePath;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
        } else {
            echo (json_encode(['success' => false, 'message' => 'Failed to move uploaded file']));
            exit;
        }
    }

    $track->title = $title;
    $track->genre = $genre;
    $track->bpm = $bpm;


    $result = $trackRepo->update($track);
    echo (json_encode(['success' => $result, 'file_path' => $track->file_path]));

    $absolutePath = __DIR__ . '/../../' . $oldTrackPath;

    if (isset($relativeFilePath) && file_exists($absolutePath)) {
        unlink($absolutePath);
    }
} else {
    echo (json_encode(['success' => false, 'message' => 'Track not found']));
}
