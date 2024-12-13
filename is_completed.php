<?php
session_start();
include "connection.php";

header('Content-Type: application/json');

// checking task_id by using POST
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['task_id']) || empty($input['task_id'])) {
    echo json_encode(['success' => false, 'message' => 'Görev ID eksik']);
    exit;
}

$task_id = intval($input['task_id']); // convert the id to int
$user_id = $_SESSION['user_id'];

// mark the task as completed
$sql = "UPDATE tasks SET is_completed = 1 WHERE task_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $task_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Task is completed.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Task could not be completed.']);
}

$stmt->close();
$conn->close();
?>


