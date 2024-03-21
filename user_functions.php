<?php

require 'db_connect.php'; 

$conn = connectDB();

function getUsers() {
  global $conn; 
  
  $sql = "SELECT 
  u.id AS user_id,
  u.first_name, 
  u.last_name, 
  u.position, 
  COALESCE(ms.name, 'No Status') AS status_name, 
  COALESCE(ms.color, 'gray') AS color, 
  ms.id AS status_id
FROM 
  users AS u 
LEFT JOIN 
  (
      SELECT 
          user_id,
          MAX(id) AS max_status_id
      FROM 
          status
      GROUP BY 
          user_id
  ) AS latest_status ON u.id = latest_status.user_id
LEFT JOIN 
  status AS s ON latest_status.max_status_id = s.id
LEFT JOIN 
  master_status AS ms ON s.status_id = ms.id
ORDER BY 
  u.id;
";
  $result = $conn->query($sql);

  $users = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $users[] = $row;
    }
  }

  return $users;
}


function getStatuses() {
  global $conn; 

  $sql = "SELECT id, name FROM master_status";
  $result = $conn->query($sql);
  $statuses = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $statuses[] = $row;
    }
  }
  return $statuses;
}


function getUserInfo($userId) {
  global $conn; 
  $sql = "SELECT u.id AS user_id, u.first_name, u.last_name, u.position,
          MAX(s.status_id) AS latest_status_id 
          FROM users AS u
          LEFT JOIN status AS s ON u.id = s.user_id
          WHERE u.id = ?
          GROUP BY u.id";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $userId)
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    return $result->fetch_assoc();
  } else {
    return null;
  }
}


function updateUserInfo( $userId, $firstName, $lastName, $statusId, $position) {
  global $conn; 
  $sql = "UPDATE users SET first_name = ?, last_name = ?, position = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssss', $firstName, $lastName, $position, $userId);

  $updateSuccessful = $stmt->execute();

  if ($updateSuccessful) {
    $insertStatusSql = "INSERT INTO status (user_id, status_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertStatusSql);
    $insertStmt->bind_param('ii', $userId, $statusId);
    $insertStmt->execute();
  }

  return $updateSuccessful; 
}



?>
