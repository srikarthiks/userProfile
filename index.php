<?php
session_start();

require 'user_functions.php'; 
require 'topbar.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>User List</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    select {
      padding: 5px;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> <script>
  $(document).ready(function() {
    $('.status-select').change(function() {
      var userId = $(this).data('user-id');
      var newStatus = $(this).val(); 

      $.ajax({
        url: 'update_status.php',
        type: 'POST',
        data: { user_id: userId, new_status: newStatus },
        success: function(response) 
        {
          console.log(response)
          var responseObject = JSON.parse(response);
           
          if (responseObject.status) {

         
    var statusCell = $('#user-status-' + userId);  


    statusCell.text(responseObject.updatedstatus);
    statusCell.css('color', responseObject.color); 
  } else {
    console.log('Error updating status: ' + response);
  }
}
,
        error: function(jqXHR, textStatus, errorThrown) {
          console.error('AJAX error:', textStatus, errorThrown);
        }
      });
    });
  });
  </script>
</head>
<body>

  <h1>User List</h1>
  <table>
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Position</th>
        <th>Status</th>
        <?php ?>
        <?php if (isLoggedIn()) : ?>
          <th>Action</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php
        $users = getUsers(); 
        if ($users) {
          foreach ($users as $user) {
          
            $canEdit = ($user['user_id'] === getLoggedInUserId()); 

            echo "<tr>";
            echo "<td>" . $user['first_name'] . "</td>";
            echo "<td>" . $user['last_name'] . "</td>";
            echo "<td>" . $user['position'] . "</td>";
         
            $color = $user['color']; 

        
            echo "<td style=' color: " . $color . ";' id='user-status-" . $user['user_id'] . "'>" . $user['status_name'] . "</td>";
            

            if ($canEdit) 
            {
              echo "<td>";
              echo "<select class='form-select form-select-sm status-select' data-user-id='" . $user['user_id'] . "'>";
              $statuses = getStatuses(); 
              foreach ($statuses as $status) {
                echo "<option value='" . $status['id'] . "'" . ($user['status_id'] == $status['id'] ? ' selected' : '') . ">" . $status['name'] . "</option>";
              }
              echo "</select>";
              echo "</td>";
            }
            else{
              echo "<td>-";
              echo "</td>";

            }
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='" . (isLoggedIn() ? '6' : '5') . "'>No users found</td></tr>";
        }
      ?>
    </tbody>
  </table>

  <?php ?>
  <?php
  function isLoggedIn() {
    return isset($_SESSION['user_id']); 
  }

  function getLoggedInUserId() {
    if (isLoggedIn()) {
      return $_SESSION['user_id']; 
    } else {
  
      header('Location: login.php');
      exit();
    }
  }
  ?>

</body>
</html>
