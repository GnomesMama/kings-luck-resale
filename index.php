<?php


$conn = mysqli_connect('localhost','kelsking@example.com','testpw123456','resale-store');

    if(!$conn){
        echo 'Connection Error:' . mysqli_connect_error(); 
       }
?>
<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to Kings Luck Second Chance Resale</title>
  <link rel="stylesheet" href="assets/styles.css">
<body>
    <h1>Welcome to Kings Luck Second Chance Resale</h1>
    


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
  </main>

  <section class="login-section">
   <form action="login.php" method="POST">
            <h2>Login</h2>
            <?php if(isset($_GET['error'])) {?>
            <p class="error"> <?php echo $_GET['error']; ?> </p>
            <?php } ?>
            <label>Username</label>
            <input type="text" name="username" placeholder="Username"><br>
            <label>Password</label>
            <input type="password" name="password" placeholder="Password"><br>  

            <button type="submit">Login</button>   
            </form> 
  </section>

    <footer>
        <p>&copy; <?= date('Y') ?> Kings Luck Second Chance Resale</p>
    </footer>
</body>
</html>

