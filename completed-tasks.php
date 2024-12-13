<?php
session_start();
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php");
    exit;
}

$user_id = $_SESSION['user_id']; // getting user id in session

// get only completed tasks
$sql = "SELECT * FROM tasks WHERE user_id = ? AND is_completed = 1 ORDER BY due_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

//for deleting there
if (isset($_GET['task_id'])) {
    $id = $_GET['task_id'];
    $sqlu = "DELETE FROM `tasks` WHERE task_id = ?";
    $stmts = $conn->prepare($sqlu);
    $stmts->bind_param("i", $id);
    $stmts->execute();
    $stmts->close();
    
    // Redirect after deletion
    header("Location: completed-tasks.php");
    exit;
}

//search bar
 // Get the search term from the query parameter
 $searchTerm = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%"; // Ensure wildcards are added to search term

 // SQL query to search for tasks where title or description matches the search term
 $sqls = "SELECT * FROM tasks WHERE user_id = ? AND (title LIKE ? OR description LIKE ?) AND is_completed=1 ORDER BY due_date ASC";
 $stmte = $conn->prepare($sqls);
 
 // Bind parameters: 'i' for integer (user_id) and 'ss' for two string parameters (searchTerm for title and description)
 $stmte->bind_param("iss", $user_id, $searchTerm, $searchTerm); // Correct binding
 $stmte->execute();
 $result = $stmte->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <style>
        .task-card.completed {
          background-color: #90EE90; /* light green for completed tasks */
        }
       
        .wavy{
            text-decoration-style: wavy;
            text-decoration-line: underline;
            text-decoration-color: #FFCCEA;
        }

        .sidebar{
            background: #BFECFF;
        }

        body {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>
<body>
    <aside class="sidebar d-flex justify-content-center">
        <ul class="container">
            <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link active" href="calendar.php">Calendar</a></li>
            <li class="nav-item"><a class="nav-link active" href="completed-tasks.php">Completed Tasks</a></li>
            <li class="nav-item"><a class="nav-link active" href="analysis.php">My Analysis</a></li>
            <li class="nav-item">
                <form action="logout.php" method="post">
                    <button type="submit" class="logout  btn btn-light">Log out</button>
                </form>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="container-fluid">
            <h1 class="my-3 fw-bold">Task Management</h1>
        </header>
        <section class="task-panel container">
            <div class="task-header container">
                <h2 class="wavy fw-bold"> Completed Tasks </h2>
                <div class="search-bar">
                    <form action="completed-tasks.php" method="get">
                        <input type="text" name="search" class="form-control-sm border border-dark-subtle" placeholder="Search tasks" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <button type="submit" class="btn btn-light">Search</button>
                    </form>
                </div>
                <div class="add-task">
                    <button type="button" class="btn btn-light"><a class="add-task-page text-decoration-none text-dark" href="add_task_page.php">Add Task</a></button>
                </div>
            </div>
            <div class="task-carousel container mt-4">
                <button class="arrow left-arrow">⬅</button>
                <div class="task-container container">
                    <div class="task-cards container ms-2 me-2">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="task-card col-3 <?= $row['is_completed'] == 1 ? 'completed' : 'not-completed'; ?>" data-id="<?= $row['task_id']; ?>">
                                <h3 class="task-title fw-bold "><?= htmlspecialchars($row['title']); ?></h3>
                                <p class="task-description"><?= htmlspecialchars($row['description']); ?></p>
                                <p class="task-due-date fw-bold">Due Date: <?= htmlspecialchars($row['due_date']); ?></p>
                                <div class="actions grid gap-3 justify-content-center">
                                    <a href="not-completed.php?task_id=<?= $row['task_id']; ?>" class="undo text-decoration-none">X</a>
                                    <a href="edit-task.php?task_id=<?= $row['task_id']; ?>" class="edit text-dark text-decoration-none">Edit</a>
                                    <a href="completed-tasks.php?task_id=<?= $row['task_id']; ?>" class="delete text-decoration-none">🗑️</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows === 0): ?> 
                            <div class="no-tasks-message fs-3 text-muted">
                                You don't have completed task!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="arrow right-arrow">➡</button>
            </div>
        </section>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function markAsIncomplete(taskId) {
                fetch('/update-task-status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: taskId, status: false })
                })
            .then(response => response.json())
            .then(data => {
                alert("Task updated!");
                // Ekranda güncelleme yap
                document.getElementById(`task-${taskId}`).classList.remove('completed');
            });
        }

        //background of tasks after search
        document.addEventListener('DOMContentLoaded', function () {
            const taskCards = document.querySelectorAll('.task-card');
    
            taskCards.forEach(function(card) {
                const isCompleted = card.classList.contains('completed');
        
                if (isCompleted) {
                    card.style.backgroundColor = '#90EE90'; // light green for completed tasks
                } else {
                    card.style.backgroundColor = 'white'; // White background for not completed tasks
                }
            });
        });


        // Function to trigger search and update URL
        function searchTasks() {
            const searchTerm = document.getElementById('search').value;
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchTerm); // Set the search parameter
            window.history.pushState({}, '', url); // Update the URL without reloading the page
            window.location.reload(); // Reload the page to apply the search (or use AJAX for smoother experience)
        }
    </script>
</body>
</html>
