<?php

require 'db_connect.php'; 
function sanitizeInput($data) {

  $conn = connectDB();
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $conn->real_escape_string($data);
}

// Check if form is submitted
if (isset($_POST['register'])) {

  $firstName = sanitizeInput($_POST['first_name']);
  $lastName = sanitizeInput($_POST['last_name']);
  $email = sanitizeInput($_POST['email']);
  $password = sanitizeInput($_POST['password']);
  $conn = connectDB();


  if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
    $errors = "Please fill in all required fields.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } else {


    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $errors = "Email already exists.";
    } else {

  
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

     
      $sql = "INSERT INTO users (first_name, last_name, email, password_hash) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";

      if ($conn->query($sql) === TRUE) {
        $success = "Registration successful! Please login.";
        header("Location: login.php");
      } else {
        $errors = "Error: " . $conn->error;
      }
    }
  }

  $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>User Registration</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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
                    <h3>Register</h3>
                  </div>
                </div>
              </div>

              <?php if (isset($errors)): ?>
                <p style="color: red;"><?php echo $errors; ?></p>
              <?php endif; ?>

              <?php if (isset($success)): ?>
                <p style="color: green;"><?php echo $success; ?></p>
              <?php endif; ?>

              <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="row gy-3 gy-md-4 overflow-hidden">
                  <div class="col-12">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                  </div>
                  <div class="col-12">
                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                  </div>
                  
                  <div class="col-12">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" id="email" required>
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" required>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn bsb-btn-xl btn-primary" type="submit" name="register">Register now</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
