<?php

session_start();
if (isset($_SESSION['id'])) {
  header('Location: home.php'); exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Create account</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <main class="auth-page">
    <h1>Create an account</h1>
    <form id="signupForm" action="signup_process.php" method="POST" novalidate>
      <label for="username">Username</label>
      <input id="username" name="username" type="text" required minlength="3" maxlength="50">

      <label for="email">Email</label>
      <input id="email" name="email" type="email" required>

      <label for="password">Password</label>
      <input id="password" name="password" type="password" required minlength="8">

      <label for="password_confirm">Confirm password</label>
      <input id="password_confirm" name="password_confirm" type="password" required minlength="8">

      <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>

    <p>Already have an account? <a href="index.php">Log in</a></p>
  </main>

  <script>
    
    document.getElementById('signupForm').addEventListener('submit', function(e){
      const pw = document.getElementById('password').value;
      const pwc = document.getElementById('password_confirm').value;
      if (pw !== pwc) {
        e.preventDefault();
        alert('Passwords do not match');
      }
    });
  </script>
</body>
</html>