<?php

  include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, inital-scale=1" />
    <link rel="icon" href="assets/images/icons/DCBT-Logo.png" type="image/png">
    <title>Daehan College of Business & Technology</title>
    <!--Link stylesheets-->
    <link rel="stylesheet" href="assets/css/home.css" />
    <link rel="stylesheet" href="assets/css/fonts.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />
    <!--Link fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=IM+Fell+Double+Pica&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        background-color: #efefef;
      }
    </style>
  </head>
  <body>
    <nav>
      <input type="checkbox" id="check" />
      <label for="check" class="check-btn">
        <i class="bi bi-list"></i>
      </label>
      <label class="logo">
        <a href="index.php">
          <img src="assets/images/icons/DCBT-Logo.png" alt="DCBT" />
        </a>
      </label>
      <ul>
        <li><a href="index.php#admissions">ADMISSIONS</a></li>
        <li><a href="index.php#academics">ACADEMICS</a></li>
        <li><a href="index.php#about">ABOUT</a></li>
        <li><a href="#" id="show-login">LOGIN</a></li>
      </ul>
    </nav>

    <div class="content">
      <div class="slide-3">
        <header>
          <div class="title">
            <h2>Online Application</h2>
            <small>
              Welcome to Daehan College of Business and Technology, where junior high school completers, 
              senior high school graduates, and college transferees embark on a journey 
              of learning and growth, surrounded by a warm and inclusive community 
              dedicated to their success.
            </small>
          </div>
        </header>
        <header>
          <div class="title">
            <h3>Choose enrollment type</h3>
          </div>
        </header>
        <main>
          
          <div class="action">
            <a href="#" onclick="newStudentURL()">New register</a>
          </div>
          <div class="action">
            <a href="#" onclick="enrolledStudentURL()">Sign in</a>
          </div>

        </main>
      </div>
      <main>
        <div class="footer">
          <div class="contact">
            <h4>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h4>
            <p>
              Siwang San Juan Floodway Road 20, Taytay, Philippines 
              (0916 330 6989)
            </p>
            <p>Terms and condition | Privacy Policy</p>
          </div>
        </div>
      </main>

      <div class="login-element" id="login-form">
        <div class="floating">
          <div class="close-btn">
            <button><a href="#">&times;</a></button>
          </div>
          <header>
            <div class="title">
              <h2>Choose section log-in</h2>
            </div>
          </header>
          <main>
            <div class="action">
              <button type="button" class="default" onclick="window.location.href ='lms_login.php'">ELMS</button>
              <button type="button" class="default" onclick="window.location.href ='online_application.php'">Enrollment</button>
            </div>
          </main>
        </div>
      </div>
    </div>

    <script>
      document
        .querySelector("#show-login")
        .addEventListener("click", function () {
          document.querySelector("#login-form").classList.add("active");
          document.querySelector("nav > ul").classList.add("active");
          document.body.classList.add("no-scroll");
        });
      document
        .querySelector("#login-form .close-btn")
        .addEventListener("click", function () {
          document.querySelector("#login-form").classList.remove("active");
          document.querySelector("nav > ul").classList.remove("active");
          document.body.classList.remove("no-scroll");
        });
    </script>
    <script>
      function newStudentURL(){
          var root = `<?php
                  echo domainName;
              ?>`;
          window.location.href = `${root}pre_enrollment_register.php`
      }

      function enrolledStudentURL(){
          var root = `<?php
                  echo domainName;
              ?>`;
          // window.location.href = `${root}/student_enrollment.php`
          window.location.href = `${root}enrollment_login.php`
      }
    </script>
  </body>
</html>
