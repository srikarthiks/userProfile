<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Topbar</title>
  <style>

    #topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      background-color: #f0f0f0;
    }

    #topbar a {
      text-decoration: none;
      color: #333;
      padding: 5px 10px;
    }

    #topbar a:hover {
      background-color: #ddd;
    }
  </style>
</head>
<body>

<div id="topbar">
  <div>
    <a href="profile.php">Update Profile</a>
    <a href="index.php">User List</a>
    </div>

  <?php if (isset($_SESSION['user_id'])) : ?>
    <a href="logout.php">Logout</a>
  <?php endif; ?>
</div>

</body>
</html>
