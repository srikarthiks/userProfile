<?php

session_start();


if (!isLoggedIn() || !hasPermission('change_status')) {
  http_response_code(403); 
  exit('Unauthorized');
}

$userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$newStatus = filter_input(INPUT_POST, 'new_status', FILTER_VALIDATE_INT);

if (!$userId || !$newStatus) {
  http_response_code(400); 
  exit('Invalid user ID or status');
}

function updateStatus($userId, $newStatus) {
   
    $db = new PDO("mysql:host=localhost;dbname=userdeatils", "root", "");
   
   
    $sql = "UPDATE users SET status_id = :new_status WHERE id = :user_id";
    $stmt = $db->prepare($sql);
  
    $stmt->bindParam(':new_status', $newStatus, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
  

    $success = $stmt->execute();

    $db = new PDO("mysql:host=localhost;dbname=userdeatils", "root", "");
    $sql = "INSERT INTO status (user_id, status_id, updated_at) VALUES (:user_id, :new_status, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':new_status', $newStatus, PDO::PARAM_INT);
    $stmt->execute();
  

    if ($success) {
     
      $sql = "SELECT name,color FROM master_status WHERE id = :new_status";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':new_status', $newStatus, PDO::PARAM_INT);
      $stmt->execute();
      $statusData = $stmt->fetch(PDO::FETCH_ASSOC);
      $updatedStatusName = $statusData['name'];
      $colorCode = $statusData['color'];
      $db = null;

    }
  
   
    return array('status' => $success, 'updatedstatus' => $updatedStatusName, 'color' => $colorCode);
  }
  
  $updateResult = updateStatus($userId, $newStatus);

if ($updateResult['status']) {
  echo json_encode($updateResult);
} else {
  echo 'Error updating status'; 
}


function isLoggedIn() {
  return isset($_SESSION['user_id']);
}

function hasPermission($permission) {
  
  return true;
}
