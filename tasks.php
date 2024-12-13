<?php
session_start();
include "connection.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT title, due_date, is_completed FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        "title" => $row["title"],
        "due_date" => $row["due_date"],
        "is_completed" => $row["is_completed"]
    ];
}

header('Content-Type: application/json');
echo json_encode($tasks);
$stmt->close();
$conn->close();
?>
