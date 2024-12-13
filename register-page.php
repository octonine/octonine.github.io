<?php
  include("connection.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            background: linear-gradient(135deg, #A8D8EA, #AA96DA);
            overflow: hidden;
            position: relative;
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            z-index: 1;
        }

        .circle:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 50px;
            left: 50px;
            background: #FCBAD3;
            opacity: 50%;
        }

        .circle:nth-child(2) {
            width: 300px;
            height: 300px;
            bottom: 100px;
            right: 50px;
            background: #FFFFD2;
            opacity: 50%;
        }

        .circle:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 300px;
            right: 150px;
            background: #FCBAD3;
            opacity: 50%;
        }

        .register-container {
            z-index: 2;
            position: relative;
        }

        .register-box {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        .left-text {
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .sign-up{
          background-color: #BFECFF;
          color: black;
          border: none;
        }

        .sign-up:hover{
          background-color: #A8D8EA;
        }
    </style>
</head>
<body>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>

    <div class="container d-flex h-100 align-items-center register-container">
        <div class="row w-100">
            <!-- Sol Taraf: Yazı Alanı -->
            <div class="col-lg-6 text-center d-flex align-items-center justify-content-center">
                <div class="left-text">
                    <h1>Organize, Prioritize, Succeed</h1>
                    <p>Your next adventure begins here. Register now and make it extraordinary. <br>
                      Transform the way you manage tasks and hit your goals with confidence!</p>
                </div>
            </div>
            <!-- Sağ Taraf: Kayıt Formu -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="register-box bg-light w-75">
                    <h2 class="text-center mb-4 fw-bold">Sign Up</h2>
                    <form action="register.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label register-username fw-bold">Username</label>
                            <input name="register-username" type="text" class="form-control" id="register-username" placeholder="name123">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label register-email fw-bold">E-mail</label>
                            <input name="register-email" type="email" class="form-control" id="register-email" placeholder="example123@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label register-password fw-bold">Password</label>
                            <input name="register-password" type="password" class="form-control" id="register-password" placeholder="Please enter password">
                        </div>
                        <button type="submit" class="btn sign-up w-100"> Sign Up </button>
                    </form>
                    <p class="mt-3">Already have an account? <a href="login-page.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Username Error -->
<div class="modal fade" id="usernameErrorModal" tabindex="-1" aria-labelledby="usernameErrorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usernameErrorModalLabel"> Username Error </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        This username is already taken. Please try another username. 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Email Error -->
<div class="modal fade" id="emailErrorModal" tabindex="-1" aria-labelledby="emailErrorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailErrorModalLabel"> E-mail Error </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        This e-mail is already in use. Please sign up with another e-mail. 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Successful Registration -->
<div class="modal fade" id="registrationSuccessModal" tabindex="-1" aria-labelledby="registrationSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registrationSuccessModalLabel">Sign up is successful!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Sign up is successful. Please log in. 
      </div>
      <div class="modal-footer">
        <a href="login-page.php" class="btn btn-primary"> Log in </a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
      </div>
    </div>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault(); // Sayfanın yenilenmesini engelle

        let username = document.getElementById("register-username").value;
        let email = document.getElementById("register-email").value;
        let password = document.getElementById("register-password").value;

        // Form verilerini JSON formatında göndereceğiz
        let formData = new FormData();
        formData.append('register-username', username);
        formData.append('register-email', email);
        formData.append('register-password', password);

        // AJAX isteği
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "register.php", true);
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Log the raw response

        try {
            let response = JSON.parse(xhr.responseText); // parsing as JSON
            if (response.status === "error") {
                if (response.error === "username") {
                    var myModal = new bootstrap.Modal(document.getElementById("usernameErrorModal"));
                    myModal.show();
                } else if (response.error === "email") {
                    var myModal = new bootstrap.Modal(document.getElementById("emailErrorModal"));
                    myModal.show();
                }
            } else if (response.status === "success") {
                var myModal = new bootstrap.Modal(document.getElementById("registrationSuccessModal"));
                myModal.show();
            }
            } catch (e) {
            console.error("Error parsing JSON:", e);
          }
        }
      };
        xhr.send(formData);
    });
  </script>
</body>
</html>
