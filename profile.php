<?php
require 'user_functions.php'; // Include the connection file
require 'topbar.php';
// Function to sanitize user input (prevent SQL injection)
function sanitizeInput($data) {
    // Get a connection within the function for consistency
    $conn = connectDB();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
  }
  
 // Include the connection file
session_start(); // Start the session to access user ID

// Check if a user is logged in (session exists and has a valid user ID)
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit; // Terminate script execution
}

$userId = $_SESSION['user_id']; // Get the user ID from the session

// Function to retrieve user information based on ID

  


  

// Get the connection object
$userInfo = getUserInfo($userId); // Fetch user information

$errors = array(); // Array to store any errors

if (!$userInfo) 
{
  echo "An error occurred while retrieving user information."; // Handle missing user data
} 
else 
{

  if (isset($_POST['update'])) 
  {
    $firstName = sanitizeInput($_POST['first_name']); 
    $lastName = sanitizeInput($_POST['last_name']);
    $statusId = isset($_POST['status']) ? (int)$_POST['status'] : null; 
    $position = sanitizeInput($_POST['position']); 

        if (empty($firstName) || empty($lastName)) {
            $errors[] = "Please fill in both first and last name.";
          } else if ($statusId === null || !is_int($statusId)) {
            $errors[] = "Invalid status selected.";
          } else if (empty($position)) {
            $errors[] = "Please enter the user's position.";
          }
      
          if (empty($errors)) {
            if (updateUserInfo($userId, $firstName, $lastName, $statusId, $position)) {
              
              $userInfo['first_name'] = $firstName; 
              $userInfo['last_name'] = $lastName;
              echo "<p style='color: green;'>Profile updated successfully!</p>";
            } else {
              echo "<p style='color: red;'>Error updating profile: " . $conn->error . "</p>";
            }
          } else {
            // Display validation errors
            echo "<p style='color: red;'>Please fix the following errors:</p>";
            echo "<ul>";
            foreach ($errors as $error) {
              echo "<li>$error</li>";
            }
            echo "</ul>";
          }
        }

  ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <html>
    <head>
      <title>Edit Profile</title>
    </head>
    <body>
      <h1>Edit Profile</h1>

      <?php if ($userInfo): ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <table class="table table-bordered">
        <tbody>
          <tr>
            <th scope="row">First Name:</th>
            <td> <input type="text" name="first_name" id="first_name" value="<?php echo $userInfo['first_name']; ?>" required><br><br></td>
          </tr>
          <tr>
            <th scope="row">Last Name:</th>
            <td><input type="text" name="last_name" id="last_name" value="<?php echo $userInfo['last_name']; ?>" required></td>
          </tr>
          <tr>
            <th scope="row">Position:</th>
            <td> <input type="text" name="position" id="position" value="<?php echo (isset($userInfo['position'])) ? $userInfo['position'] : ''; ?>"></td>
          </tr>
          <tr>
            <th scope="row">Status:</th>
            <td> <select name="status" id="status">
          <?php
                 $statusOptions = array(
              1 => "Online",
              2 => "Away",
              3 => "Do not disturb"
            ); 
          foreach ($statusOptions as $statusId => $statusName) {
            $selected = ($userInfo['latest_status_id'] == $statusId) ? 'selected' : '';
            echo "<option value='$statusId' $selected>$statusName</option>";
          }
          ?>
        </select></td>
          </tr>
          <tr>
            <th scope="row"></th>
            <td>   <button type="submit" name="update">Update Profile</button></td>
          </tr>
        </tbody>
      </table>
  
    
      </form>
      
      

    <?php else: ?>
      <p>An error occurred while retrieving user information.</p>
    <?php endif; ?>

  </body>
</html>

<?php } ?>