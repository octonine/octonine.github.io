<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <style>
        body {
          font-family: "Quicksand", sans-serif;
          font-optical-sizing: auto;
          font-weight: 400;
          font-style: normal;
        }
        .hero {
            position: relative;
            height: 100vh;
            background: url('https://img.freepik.com/free-photo/workplace-items-arrangement_23-2148975823.jpg?t=st=1733763367~exp=1733766967~hmac=8c07b2e648c3527faccced60c6cf909d0c9d0fbdde317f9b7c6bf78eed4016b9&w=1380') no-repeat center center/cover;
            color: white;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay for readability */
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);
        }
        .hero-content h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .btn-custom {
            padding: 0.8rem 2rem;
            font-size: 1rem;
        }

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">TaskManager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-warning me-2" href="login-page.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-white" href="register-page.php">Sign Up</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1>Stay Organized and Achieve More</h1>
        <p>Your ultimate task management solution. Simplify your life and get things done effortlessly.</p>
        <div>
            <a href="#features" class="btn btn-warning btn-custom">Learn More</a>
            <a href="register-page.php" class="btn btn-light btn-custom">Sign Up</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container text-center">
        <h2 class="mb-4 fw-bold">Why Choose Task Manager?</h2>
        <div class="row">
            <div class="col-md-4">
              <object data="svgs/todo.svg" width="100" height="100"> </object>
                <h3> Powerful Task Management </h3>
                <p>Easily add, delete, complete, or undo tasks with a seamless workflow. 
                  All tasks are automatically sorted by due date from the moment they are created, 
                  ensuring you stay organized and focused.

                </p>
            </div>
            <div class="col-md-4">
                <object data="svgs/calendar-find-search-svgrepo-com.svg" width="100" height="100"> </object>
                <h3> Intuitive Calendar Integration</h3>
                <p>Visualize your tasks with an integrated calendar view. 
                  Track daily, weekly, or monthly plans effortlessly and never miss a deadline again.</p>
            </div>
            <div class="col-md-4">
            <object data="svgs/page-analysis-svgrepo-com.svg" width="100" height="100"> </object>
            
                <h3>Task Analysis</h3>
                <p>Understand your productivity with analysis! 
                  View completed, pending, and total tasks along with their percentages. 
                  Review your previously completed tasks to celebrate your progress and 
                  improve your workflow.</p>
            </div>
        </div>
    </div>
</section>

<!--- What is task manager? -->
<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      <div class="col-10 col-sm-8 col-lg-6">
        <img src="assets/calendar.png" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
      </div>
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">What is task manager?</h1>
        <p class="lead"> A task manager is your digital assistant to help you stay organized and productive. 
        It allows you to manage your daily to-dos, track deadlines, and analyze your progress.</p>
      </div>
    </div>
  </div>

  <!--- How to use?-->
<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5"><div class="col-lg-6">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Organize Your Tasks by Deadlines</h1>
        <p class="lead">Stay ahead of your schedule by prioritizing tasks based on their due dates. 
          Our task manager automatically sorts your tasks, ensuring that the most urgent ones are always at the top. 
          Never miss a deadline and manage your time efficiently with ease.</p>
      </div>
      

      <div class="col-10 col-sm-8 col-lg-6">
        <img src="assets\main.png" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
      </div>
      
    </div>
  </div>

  <!--Analysis ---> 
  <div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      <div class="col-10 col-sm-8 col-lg-6">
        <img src="assets/analysis1.png" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
      </div>
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Gain Insights with Task Analysis</h1>
        <p class="lead">Understand your productivity like never before. 
          Our task analysis feature provides a clear overview of your completed, pending, 
          and total tasks. Visualize your progress with intuitive charts and percentages, 
          celebrate milestones, and identify areas for improvement to enhance your workflow.</p>
      </div>
    </div>
  </div>

      <!-- jumbotron-->
      <div class="container my-5">
  <div class="p-5 text-center bg-body-tertiary rounded-3">
    <svg class="bi mt-4 mb-3" style="color: var(--bs-indigo);" width="100" height="100"><use xlink:href="#bootstrap"></use></svg>
    <h1 class="text-body-emphasis">Jumbotron with icon</h1>
    <p class="col-lg-8 mx-auto fs-5 text-muted">
      This is a custom jumbotron featuring an SVG image at the top, some longer text that wraps early thanks to a responsive <code>.col-*</code> class, and a customized call to action.
    </p>
    <div class="d-inline-flex gap-2 mb-5">
      <button class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" type="button">
        Call to action
        <svg class="bi ms-2" width="24" height="24"><use xlink:href="#arrow-right-short"></use></svg>
      </button>
      <button class="btn btn-outline-secondary btn-lg px-4 rounded-pill" type="button">
        Secondary link
      </button>
    </div>
  </div>
</div>

<!-- Footer Section -->
<footer class="text-center py-3 bg-light">
    <p>© 2024 Task Manager. All rights reserved.</p>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
