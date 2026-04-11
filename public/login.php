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


<!DOCTYPE html>
<html lang="sk">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<div class="flex items-center justify-center min-h-screen bg-[#050505]">
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[120px]"></div>

    <div class="absolute bottom-[5%] right-[10%] w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-[150px]"></div>

    <div
      class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-900/10 rounded-full blur-[180px]">
    </div>

    <div class="absolute top-[20%] right-[-5%] w-[500px] h-[500px] bg-cyan-500/15 rounded-full blur-[130px]"></div>
  </div>


  <div class="p-10 bg-white/[0.03] backdrop-blur-[40px] border border-white/[0.08] text-white rounded-xl min-w-[550px]">

    <!-- error message that apears when user enters invalit login informations -->
    <?php if ($error): ?>
      <div onclick="this.remove()"
        class="cursor-pointer mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-200 text-sm text-center hover:bg-red-500/30 transition-colors">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <!-- heading plus text -->
    <div class="mb-10 text-center">
      <h1 class="text-3xl font-medium text-white/95 tracking-tight">Admin Login</h1>
      <p class="text-[11px] font-bold text-white/40 tracking-[0.25em] uppercase mt-2">Producer Portfolio</p>
    </div>


    <!-- horizontal breaking line -->
    <div class="h-[1px] w-full bg-white/[0.06] mb-6"></div>


    <!-- login form -->
    <form action="login.php" method="POST" class="space-y-6">

      <!-- username label plus input -->
      <div class="flex flex-col space-y-2">
        <label class="text-[10px] font-bold text-white/40 tracking-[0.2em] uppercase ml-1">Username</label>
        <input
          class="w-full bg-white/[0.05] border border-white/[0.1] rounded-xl p-4 text-white outline-none focus:border-white/30 focus:bg-white/[0.08] transition-all"
          type="text" name="username" required>
      </div>


      <!-- password label plus input -->
      <div class="flex flex-col space-y-2">
        <label class="text-[10px] font-bold text-white/40 tracking-[0.2em] uppercase ml-1">Password</label>
        <input
          class="w-full bg-white/[0.05] border border-white/[0.1] rounded-xl p-4 text-white outline-none focus:border-white/30 focus:bg-white/[0.08] transition-all"
          type="password" name="password" required>
      </div>

      <!-- login button -->
      <div class="flex justify-center">
        <button type="submit" class="
        w-48 mt-4 
        bg-white/20 border border-white/30 
        rounded-xl p-4 
        text-white font-semibold
        hover:bg-white/25 hover:border-white/40 
        transition-all duration-300 
        active:scale-[0.95]
        shadow-lg shadow-white/5
        ">
          Login
        </button>
      </div>


    </form>
  </div>
</div>

</html>