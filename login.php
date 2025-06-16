<?php
session_start();
require_once('classes/database.php');
$sweetAlertConfig = "";
$con = new database();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $con->loginUser($username, $password);

    if ($user) {
        $_SESSION['admin_ID'] = $user['admin_id'];
        $_SESSION['admin_FN'] = $user['admin_FN'];

        $sweetAlertConfig =  "<script>
        Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome, " . addslashes(htmlspecialchars($user['admin_FN'])) . "!',
        confirmButtonText: 'Continue'
        }).then(() => {
        window.location.href = 'index.php';
        });
        </script>";

    } else {
      $sweetAlertConfig = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: 'Invalid username or password.'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center">User Login</h2>
    <form method="POST" action="" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="username" class="form-label">Email</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
      </div>

      <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
      
      <div class="my-3 text-end">
        <span>Don't have an account?</span>
        <a href="registration.php" class="btn btn-link p-0 align-baseline">Register Now</a>
      </div>
    </form>
    <?php echo $sweetAlertConfig; ?>
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>