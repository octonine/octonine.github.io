<?php
session_start();
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Oturumdaki user_id'yi al

// Görevleri is_completed durumuna ve due_date sırasına göre al
$sql = "SELECT * FROM tasks WHERE user_id = ? AND is_completed=0 ORDER BY  due_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

//getting the user name
 $sqlu = "SELECT username FROM users WHERE user_id = ?";
 $stmt = $conn->prepare($sqlu);
 $stmt->bind_param("i", $user_id);
 $stmt->execute();
 $stmt->bind_result($user_name);  // Kullanıcı adını çekiyoruz
 $stmt->fetch();  // Veriyi alıyoruz
 $stmt->close();  // Sorgu kapatılıyor

 //search bar
 // Get the search term from the query parameter
$searchTerm = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%"; // Ensure wildcards are added to search term

// SQL query to search for tasks where title or description matches the search term
$sqls = "SELECT * FROM tasks WHERE user_id = ? AND (title LIKE ? OR description LIKE ?) AND is_completed=0 ORDER BY due_date ASC";
$stmts = $conn->prepare($sqls);

// Bind parameters: 'i' for integer (user_id) and 'ss' for two string parameters (searchTerm for title and description)
$stmts->bind_param("iss", $user_id, $searchTerm, $searchTerm); // Correct binding
$stmts->execute();
$result = $stmts->get_result();
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
        .wavy{
            text-decoration-style: wavy;
            text-decoration-line: underline;
            text-decoration-color: #FFCCEA;
        }

        .sidebar{
            background: #BFECFF;
        }

        .task-title{
            color: #CB9DF0;
        }

        body {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>
<body >
    <aside class="sidebar d-flex justify-content-center">
        <ul class="container">
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="calendar.php">Calendar</a></li>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="completed-tasks.php">Completed Tasks</a></li>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="analysis.php">My Analysis</a></li>
            <li class="nav-item">
            <form action="logout.php" method="post">
                <button type="submit" class="logout btn btn-light">Log out</button>
            </form>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="container-fluid">
            <h1 class="my-3 fw-bold">Task Management</h1>
            <h2 class="h3 text-center mt-3">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        </header>
        <section class="task-panel container">
            <div class="task-header container">
                <h2 class="wavy fw-bold">My Tasks</h2>
                <div class="search-bar">
                    <form action="index.php" method="get">
                        <input type="text" class="form-control-sm border border-dark-subtle" name="search" placeholder="Search tasks" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
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
                            <div class="task-card col-3 bg-light <?= $row['is_completed'] == 1 ? 'completed' : 'not-completed'; ?>" data-id="<?= $row['task_id']; ?>">
                                <h3 class="task-title fw-bold"><?= htmlspecialchars($row['title']); ?></h3>
                                <p class="task-description"><?= htmlspecialchars($row['description']); ?></p>
                                <p class="task-due-date fw-bold">Due Date: <?= htmlspecialchars($row['due_date']); ?></p>
                                <div class="actions grid gap-3 justify-content-center">
                                    <a href="is_completed.php?task_id=<?= $row['task_id']; ?>" class="check text-decoration-none">✔</a>
                                    <a href="edit-task.php?task_id=<?= $row['task_id']; ?>" class="edit text-dark text-decoration-none">Edit</a>
                                    <a href="delete-task.php?task_id=<?= $row['task_id']; ?>" class="delete text-decoration-none">🗑️</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows === 0): ?> 
                            <div class="no-tasks-message fs-3 text-muted">
                                You don't have any tasks!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="arrow right-arrow">➡</button>
            </div>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const checkButtons = document.querySelectorAll(".check");

            checkButtons.forEach(button => {
                button.addEventListener("click", event => {
                    event.preventDefault(); // Sayfanın yeniden yüklenmesini engelle
                    const taskCard = button.closest(".task-card");
                    const taskId = taskCard.getAttribute("data-id");

                    // AJAX isteği
                    fetch("is_completed.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ task_id: taskId }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // DOM Güncelleme: Görevi sayfadan kaldır
                            taskCard.remove();

                            // Eğer hiç görev kalmadıysa mesaj göster
                            const taskContainer = document.querySelector(".task-cards");
                            if (taskContainer.children.length === 0) {
                                taskContainer.innerHTML = `
                                    <div class="no-tasks-message fs-3 text-muted">
                                        You don't have any tasks!
                                    </div>
                                `;
                            }
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
                });
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





