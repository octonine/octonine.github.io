<?php
session_start();
include "connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the current user ID

// Get total tasks
$sql_total = "SELECT COUNT(*) AS total_tasks FROM tasks WHERE user_id = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("i", $user_id);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_tasks = $result_total->fetch_assoc()['total_tasks'];

// Get completed tasks
$sql_completed = "SELECT COUNT(*) AS completed_tasks FROM tasks WHERE user_id = ? AND is_completed = 1";
$stmt_completed = $conn->prepare($sql_completed);
$stmt_completed->bind_param("i", $user_id);
$stmt_completed->execute();
$result_completed = $stmt_completed->get_result();
$completed_tasks = $result_completed->fetch_assoc()['completed_tasks'];

// Get pending tasks
$pending_tasks = $total_tasks - $completed_tasks;

// Calculate completion rate
$completion_rate = $total_tasks > 0 ? ($completed_tasks / $total_tasks) * 100 : 0;

// Get completed tasks by due date
$sql_completed_dates = "SELECT due_date, COUNT(*) AS task_count FROM tasks WHERE user_id = ? AND is_completed = 1 GROUP BY due_date ORDER BY due_date ASC";
$stmt_completed_dates = $conn->prepare($sql_completed_dates);
$stmt_completed_dates->bind_param("i", $user_id);
$stmt_completed_dates->execute();
$result_completed_dates = $stmt_completed_dates->get_result();

$completed_tasks_by_date = [];
while ($row = $result_completed_dates->fetch_assoc()) {
    $completed_tasks_by_date[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <style>
        .analysis-panel {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f1f3f5;
        }
        .chart-container {
            margin-top: 30px;
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
            /*background-image: radial-gradient(circle, #EEF1FF, #F6F5F5, #FFF6E3);*/
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

<!-- Main content -->
<div class="main-content container">
<header class="container-fluid">
        <h1 class="my-3 fw-bold">Task Management</h1>
        <h2 class="wavy fw-bold mt-3">My Task Analysis</h2>
    </header>

    <!-- Card Layout -->
    <section class="row gx-4 gy-3">
        <!-- Overview Card -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center" style="background-color: #E0F9B5;">
                    <h4 class="fw-bold">Overview</h4>
                </div>
                <div class="card-body">
                    <p><strong>Total Tasks:</strong> <?= $total_tasks; ?></p>
                    <p><strong>Completed Tasks:</strong> <?= $completed_tasks; ?></p>
                    <p><strong>Pending Tasks:</strong> <?= $pending_tasks; ?></p>
                    <p><strong>Completion Rate:</strong> <?= round($completion_rate, 2); ?>%</p>
                </div>
            </div>
        </div>

        <!-- Tasks by Due Date Card -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center" style="background-color: #E0F9B5;">
                    <h4 class="fw-bold">Tasks by Due Date</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Due Date</th>
                                <th>Number of Completed Tasks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($completed_tasks_by_date as $date): ?>
                                <tr>
                                    <td><?= htmlspecialchars($date['due_date']); ?></td>
                                    <td><?= $date['task_count']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart Section -->
    <div class="card shadow-sm mt-4">
        <div class="card-header text-center" style="background-color: #E0F9B5;">
            <h4 class="fw-bold">Completed Tasks by Due Date</h4>
        </div>
        <div class="card-body">
            <canvas id="completedTasksChart" width="200" height="100"></canvas>
        </div>
    </div>
</div>

<script>
    // Prepare data for the bar chart
    const labels = <?= json_encode(array_column($completed_tasks_by_date, 'due_date')); ?>;
    const data = <?= json_encode(array_column($completed_tasks_by_date, 'task_count')); ?>;

    // Configure and render the bar chart
    const ctx = document.getElementById('completedTasksChart').getContext('2d');
    const completedTasksChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Completed Tasks',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Tasks'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Due Date'
                    }
                }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>