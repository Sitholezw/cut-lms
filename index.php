<?php
// Prevent any output before headers
ob_start();

// 1. Start session at the very top
session_start();

// 2. Enable full error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

// 3. CSRF token generation if missing
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    // 4. CSRF validation
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }

    // 5. Sanitize and validate inputs
    $uname    = filter_var(trim($_POST['username']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($uname, FILTER_VALIDATE_EMAIL)) {
        $loginError = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $loginError = "Password must be at least 6 characters.";
    } else {
        // 6. Fetch exactly one row
        $sql  = "SELECT id, Password, Status FROM tblemployees WHERE EmailId = :uname LIMIT 1";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':uname', $uname, PDO::PARAM_STR);
        $stmt->execute();
        $row  = $stmt->fetch(PDO::FETCH_OBJ);

        // 7. Strict MD5 comparison
        if ($row && md5($password) === $row->Password) {
            if ((int)$row->Status === 0) {
                $loginError = "Your account is inactive. Please contact admin.";
            } else {
                // 8. Set session vars and redirect
                $_SESSION['eid']      = $row->id;
                $_SESSION['emplogin'] = $uname;
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $loginError = "Invalid email or password.";
        }
    }
}

// 9. Flush buffer (if any) and render form
ob_end_flush();
?>
<!doctype html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <title>Staff Login | CUT LMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Materialize CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
  <link href="assets/css/alpha.min.css" rel="stylesheet">

   <!-- favicoins -->
        <link href="assets\images\favicon.png" rel="icon">
        <link href="assets\images\favicon.png" rel="apple-touch-icon">
        
  <style>
    body { background: url('assets/images/background.jpg') center/cover no-repeat; }
    .card { margin-top: 5%; border-radius: 8px; }
  </style>
</head>
<body>
  <main class="container">
    <div class="row">
      <div class="col s12 m6 l4 offset-l4">
        <div class="card white">
          <div class="card-content">
            <!-- Logo inside card-content -->
            <div style="text-align: center; margin-bottom: 20px;">
              <img src="assets/images/favicon.png" alt="Logo" width="100px" style="border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            </div>
            <h5 class="center-align">Staff Login</h5>
            <?php if ($loginError): ?>
              <div class="card-panel red lighten-4 red-text">
                <strong>Error:</strong> <?= htmlentities($loginError) ?>
              </div>
            <?php endif; ?>
            <form method="post" onsubmit="return validateForm();">
              <div class="input-field">
                <input id="username" type="email" name="username" required>
                <label for="username">Email Address</label>
              </div>
              <div class="input-field">
                <input id="password" type="password" name="password" minlength="6" required>
                <label for="password">Password</label>
              </div>
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <button type="submit" name="signin" class="btn indigo waves-effect waves-light" style="width:100%">
                Sign In
              </button>
            </form>
            <p class="center-align" style="margin-top:1rem;">
              <a href="forgot-password.php">Forgot Password?</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    function validateForm() {
      const u = document.getElementById('username').value.trim();
      const p = document.getElementById('password').value;
      if (!u || !p) {
        alert('Both fields are required.');
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
