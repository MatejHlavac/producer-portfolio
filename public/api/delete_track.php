<?php
session_start();



if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once "../../vendor/autoload.php";

use App\Database\Connection;
use App\Repositories\TrackRepository;

header("Content-Type: application/json");

$id = $_GET["id"] ?? null;

if ($id) {
    $db = (new Connection())->connect();
    $trackRepo = new TrackRepository($db);

    $trackToDelete = $trackRepo->findById($id);
    if (!$trackToDelete) {
        echo json_encode(["success" => false, "message" => "Track not found"]);
        exit();
    }

    $absolutePath = __DIR__ . '/../../' . $trackToDelete->file_path;

    if (file_exists($absolutePath)) {
        unlink($absolutePath);
    }

    $deletionResult = $trackRepo->delete($id);

    echo json_encode([
        "success" => $deletionResult,
        "message" => $deletionResult ? "Track deleted" : "Database error"
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Missing ID"]);
}
