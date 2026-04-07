<form action="login.php" method="POST">
  <div>
    <label>Username:</label>
    <input type="text" name="username" required>
  </div>
  
  <div>
    <label>Password:</label>
    <input type="password" name="password" required>
  </div>

  <button type="submit">Login</button>
</form>

<?php
require_once "../vendor/autoload.php";
use App\Database\Connection;
use PDOException;

session_start();    

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $db = (new Connection())->connect();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :user");
        $stmt->execute([':user' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            
            header('Location: admin.php');
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error."; 
    }
}
?>

<?php if ($error): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>