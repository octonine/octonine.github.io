<?php
    session_start();
    include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
        .background-radial-gradient {
      height: 100vh;
      width: 100%;
      background-color: #FFF6E3; 
      background-image: 
        radial-gradient(650px circle at 0% 0%,
            #BFECFF 15%,  
            #CDC1FF 35%,  
            #FFF6E3 75%,  
            #FFF6E3 80%,  
            transparent 100%),
        radial-gradient(1250px circle at 100% 100%,
            #BFECFF 15%,  
            #CDC1FF 35%,  
            #FFF6E3 75%,  
            #FFF6E3 80%,  
            transparent 100%);
    }

    #radius-shape-1 {
      height: 220px;
      width: 220px;
      top: -60px;
      left: -130px;
      background: radial-gradient(#C6E7FF, #D4F6FF);
      overflow: hidden;
    }

    #radius-shape-2 {
      border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
      bottom: -60px;
      right: -110px;
      width: 300px;
      height: 300px;
      background: radial-gradient(#FFB38E, #FFCF9D);
      overflow: hidden;
    }

    .bg-glass {
      background-color: hsla(0, 0%, 100%, 0.9) !important;
      backdrop-filter: saturate(200%) blur(25px);
    }
    </style>
</head>
<body>
<main class=" overflow-hidden background-radial-gradient d-flex justify-content-center align-items-center vh-100">
      <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center mb-5">
          <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
              <h1 class="my-5 display-5 fw-bold ls-tight" style="color: #FC8F54">
              Stay Organised <br />
              <span style="color: #FAB12F"> Achieve More</span>
              </h1> 
              <p class="mb-4" style="color: #FC8F54">
              Manage your tasks effortlessly. 
              Your productivity is about to skyrocket!
              </p>
          </div>

          <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
            <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
            <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>
            <!--form design-->
            <div class="card bg-glass w-75 rounded">
                <div  class="card-body px-4 py-5 px-md-5">
                <form action="login.php" method="post" id="loginForm" onsubmit="return isValid()">
                    <div class="row">
                        <div data-mdb-input-init class="form-outline mb-4">
                          <input type="username" class="form-control" id="username" name="username" placeholder="name123">
                          <label class="form-label" for="username">Username</label>
                        </div>
                      </div>
                    

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <label class="form-label" for="password">Password</label>
                    </div>

                    <button name="submit" data-mdb-button-init data-mdb-ripple-init class="btn w-25 btn-info text-light btn-block mb-4 align-self-start" type="submit">Log in</button>
                    <p class="mt-2 mb-0">Don't have an account? <a href="register-page.php">Create an account</a></p>
                </div>
          </div>
        </form>
        </div>
</div>
      </div>
    </main>

    <!-- Modal for errors -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alertModalLabel">Warning</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Error message will be here -->
        <p id="modalMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        function showModal(title, message) {
          document.getElementById('alertModalLabel').innerText = title;
          document.getElementById('modalMessage').innerText = message;

          // opening modal
          const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
          alertModal.show();
        }

        function isValid() {
          var username = document.getElementById("username").value;
          var password = document.getElementById("password").value;

          if (username === "" && password === "") {
            showModal('Empty Places', 'Username and password cannot be empty.');
            return false;
          } else {
              if (username === "") {
                showModal('Empty Username', 'You cannot leave username empty.');
                return false;
              }

              if (password === "") {
                showModal('Empty Password', 'You cannot leave password empty.');
                return false;
              }
            }
          return true;
        }

      // chhecking wrong login
      document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const loginError = urlParams.get('login_error');

        if (loginError === 'true') {
          showModal('Login Error', 'Username or password is wrong!');
        }
      });

    </script>
</body>
</html>