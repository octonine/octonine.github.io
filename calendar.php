<?php
session_start();

include "connection.php"; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php");
    exit;
}

// Get tasks from database
$sql = "SELECT task_id, title, description, due_date, is_completed FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        'id' => $row['task_id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'due_date' => $row['due_date'],
        'is_completed' => (int) $row['is_completed'],
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link rel="stylesheet" href="calendar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <style>
        a {
            text-decoration: none;
            color: black;
        }

        .task.completed {
            text-decoration: line-through;
            background-color: #D3D3D3;
            color: black;
            padding: 2px;
            border-radius: 4px;
        }

        /* Popup CSS */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            width: 300px;
        }

        .popup.show {
            display: block;
        }

        .popup .popup-header {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .popup .popup-close {
            cursor: pointer;
            color: red;
            font-size: 20px;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .wavy{
            text-decoration-style: wavy;
            text-decoration-line: underline;
            text-decoration-color: #FFCCEA;
        }

        .sidebar{
            background: #BFECFF;
        }

        .task {
            background-color: #E0F9B5;
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
    <!-- Sidebar -->
    <aside class="sidebar d-flex justify-content-center">
        <ul class="container">
            <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link active" href="calendar.php">Calendar</a></li>
            <li class="nav-item"><a class="nav-link active" href="completed-tasks.php">Completed Tasks</a></li>
            <li class="nav-item"><a class="nav-link active" href="analysis.php">My Analysis</a></li>
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
        </header>

        <section class="task-panel container">
            <div class="task-header container">
                <h2 class="wavy fw-bold">Calendar</h2>
                <div class="add-task">
                    <button type="button" class="btn btn-light"><a class="add-task-page text-decoration-none text-dark" href="add_task_page.php">Add Task</a></button>
                </div>
            </div>

            <div class="calendar-container mt-4">
                <div class="calendar-header container">
                    <button class="prev-btn" onclick="prevMonth()">&lt;</button>
                    <h2 id="month-year"></h2>
                    <button class="next-btn" onclick="nextMonth()">&gt;</button>
                </div>
                <div class="calendar-days">
                    <div class="day">Sun</div>
                    <div class="day">Mon</div>
                    <div class="day">Tue</div>
                    <div class="day">Wed</div>
                    <div class="day">Thu</div>
                    <div class="day">Fri</div>
                    <div class="day">Sat</div>
                </div>
                <div class="calendar-grid" id="calendar-grid">
                    <?php
                    $currentDate = new DateTime();
                    $month = $currentDate->format('m');
                    $year = $currentDate->format('Y');
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $firstDayOfMonth = (new DateTime("$year-$month-01"))->format('w');

                    // Empty cells before the start of the month
                    for ($i = 0; $i < $firstDayOfMonth; $i++) {
                        echo '<div class="calendar-cell empty"></div>';
                    }

                    // Loop through the days of the month and add tasks
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $currentDay = sprintf("%04d-%02d-%02d", $year, $month, $day);
                        echo '<div class="calendar-cell" data-day="' . $day . '" onclick="openPopup(' . $day . ')">';
                        echo $day;

                        // Add tasks for each day
                        $dayTasks = array_filter($tasks, function($task) use ($currentDay) {
                            return $task['due_date'] === $currentDay;
                        });

                        foreach ($dayTasks as $task) {
                            $class = $task['is_completed'] === 1 ? 'completed' : '';
                            echo '<div class="task ' . $class . '">' . htmlspecialchars($task['title']) . '</div>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>

    <!-- Popup -->
    <div id="taskPopup" class="popup">
        <span class="popup-close" id="popup-close">&times;</span>
        <div class="popup-header" id="popup-title"></div>
        <div id="popup-description"></div>
    </div>

    <script src="calendar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthYearElement = document.getElementById('month-year');

            // get the date
            const date = new Date();
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            // updating month and year
            monthYearElement.textContent = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
        });

        // Open the popup and display task details
        function openPopup(day) {
            const tasks = <?php echo json_encode($tasks); ?>;
            const dayTasks = tasks.filter(task => {
            const taskDate = new Date(task.due_date);
            return taskDate.getDate() === day;  // Check if task matches the clicked day
        });

        let popupTitle = document.getElementById('popup-title');
        let popupDescription = document.getElementById('popup-description');

        //checking info about tasks on the calendar
        if (dayTasks.length > 0) {
            popupTitle.innerHTML = "Tasks for the day " + day;
            popupDescription.innerHTML = dayTasks.map(task => {
            //check whether the task is completed or not 
                const completionStatus = task.is_completed === 1 
                ? "<span style='color: green;'><strong>Completed</strong></span>" 
                : "<span style='color: red;'><strong>Not Completed</strong></span>";

            // inside Popup
            return `
                <div><strong>Task title: </strong>${task.title} <br></div>
                <div><strong>Task description: </strong>${task.description} <br></div>
                <div><strong>Status: </strong>${completionStatus} <br><br></div>
            `;
            }).join('');
        } else {
            popupTitle.innerHTML = "No Tasks";
            popupDescription.innerHTML = "No tasks available for this day.";
        }
            document.getElementById('taskPopup').classList.add('show');
        }

        // Close the popup
        document.getElementById('popup-close').addEventListener('click', function () {
            document.getElementById('taskPopup').classList.remove('show');
        });

        // Close the popup when clicking outside the popup content
        window.addEventListener('click', function (event) {
            if (event.target === document.getElementById('taskPopup')) {
                document.getElementById('taskPopup').classList.remove('show');
            }
        });
    </script>
</body>
</html>
