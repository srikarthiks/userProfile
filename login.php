<?php

require 'db_connect.php'; 


function sanitizeInput($data) {
  $conn = connectDB();
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $conn->real_escape_string($data);
}

if (isset($_POST['login'])) {

  $email = sanitizeInput($_POST['email']);
  $password = sanitizeInput($_POST['password']);

  if (empty($email) || empty($password)) {
    $errors = "Please fill in all required fields.";
  } else {

    $conn = connectDB(); 
  
    $sql = "SELECT id, password_hash FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $hashedPassword = $row['password_hash'];

      if (password_verify($password, $hashedPassword)) {
     
        session_start();
        $_SESSION['user_id'] = $row['id'];
        header('Location: index.php');
      } else {
        $errors = "Invalid email or password.";
      }
    } else {
      $errors = "Invalid email or password.";
    }

    $conn->close(); 
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>User Login</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/components/logins/login-4/assets/css/login-4.css">
  <style>

    body {
      font-family: Arial, sans-serif;
    }
  </style>
</head>
<body>
<section class="p-3 p-md-4 p-xl-5">
  <div class="container">
    <div class="card border-light-subtle shadow-sm">
    
      <div class="row g-0">
      <div class="col-12 col-md-6">
          <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="./images/register.svg" alt="Registration Logo">
            </div>
        <div class="col-12 col-md-6">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-5">
                  <h3>Log in</h3>
                </div>
              </div>
            </div>
           
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <div class="row gy-3 gy-md-4 overflow-hidden">
                <div class="col-12">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                </div>
                <div class="col-12">
                  <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" name="password" id="password" value="" required>
                </div>
               
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn bsb-btn-xl btn-primary" type="submit" name="login">Log in now</button>
                  </div>
                </div>
              </div>
            </form>
            <?php if (isset($errors)): ?>
    <p style="color: red;"><?php echo $errors; ?></p>
  <?php endif; ?>
            <div class="row">
              <div class="col-12">
                <hr class="mt-5 mb-4 border-secondary-subtle">
                <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
  <a href="registration.php" class="link-secondary text-decoration-none">Create new account</a>
</div>

              </div>
            </div>
          
          </div>
        </div>
      </div>
      
    </div>
  </div>
</section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwbZS7/BXzYhFVNqO1ayay46YiAdsoIh7++VVAxXfyvb9CgkQIzLtTXIvLiqacrN" crossorigin="anonymous"></script>
</body>
</html>

