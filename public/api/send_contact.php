<?php
header('Content-Type: application/json');

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$tracks  = $_POST['tracks'] ?? [];

if (!$name || !$email || !$subject || !$message) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

$safeName    = str_replace(["\r", "\n"], '', $name);
$safeEmail   = filter_var($email, FILTER_SANITIZE_EMAIL);
$safeSubject = str_replace(["\r", "\n"], '', $subject);

$body  = "Meno: $safeName\n";
$body .= "Email: $safeEmail\n";
$body .= "Predmet: $safeSubject\n";

if (!empty($tracks)) {
    $body .= "Záujem o tracky:\n";
    foreach ($tracks as $t) {
        $body .= "  - " . strip_tags($t) . "\n";
    }
}

$body .= "\nSpráva:\n$message\n";

$to      = 'matohlavac1@gmail.com';
$headers = "From: portfolio@hlinkinn.com\r\nReply-To: $safeEmail\r\n";

$sent = mail($to, "Portfolio contact: $safeSubject", $body, $headers);

echo json_encode([
    'success' => $sent,
    'message' => $sent
        ? 'Message sent. I\'ll get back to you soon.'
        : 'Something went wrong. Please try again or email me directly.'
]);
