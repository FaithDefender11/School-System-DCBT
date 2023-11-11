<?php

    include('includes/config.php');

    // require_once('./classes/StudentEnroll.php');
    // require_once('enrollment/classes/StudentEnroll.php');
    // require_once('includes/classes/form-helper/Constants.php');

    // $enroll = new StudentEnroll($con);

    $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
    $baseURL = dirname($currentURL);

        
    if(isset($_POST['samp_btn'])
      && isset($_POST['username'])
      && isset($_POST['password'])
      ){

      $username =  $_POST['username'];
      $password =  $_POST['password'];

    //   $wasSuccess = $enroll->loginStudentUser($username, $password);

    //   // if(sizeof($object) > 0 && $object[1] == true){
    //   if(sizeof($wasSuccess) > 0 && $wasSuccess[1] == true && $wasSuccess[2] == "enrolled"){

    //       $_SESSION['username'] = $wasSuccess[0];
    //       $_SESSION['status'] = "enrolled";

    //       // $current_url = "http://" . $_SERVER['HTTP_HOST'] ;
    //       $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    //       $url = "http://localhost/dcbt/enrollment/profile.php";
    //       header("Location: " . $url . "");
    //       // echo $current_url;
    //       // header("Location: " . $current_url . "profile.php");
    //   }

    //   if(sizeof($wasSuccess) > 0 && $wasSuccess[1] == true && $wasSuccess[2] != "enrolled"){
    //       $_SESSION['username'] = $wasSuccess[0];
    //       $_SESSION['status'] = "pending";

    //       // $current_url = "http://" . $_SERVER['HTTP_HOST'] ;
    //       $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    //       $url = "http://localhost/dcbt/enrollment/profile.php?fill_up_state=finished";
    //       header("Location: " . $url . "");
    //   }

    }


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, inital-scale=1" />
    <link rel="icon" href="assets/images/icons/DCBT-Logo.jpg" type="image/png">
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
  </head>
  <body>
    <nav>
      <input type="checkbox" id="check" />
      <label for="check" class="check-btn">
        <i class="bi bi-list"></i>
      </label>
      <label class="logo">
        <a href="index.php">
          <img src="assets/images/home/DCBT-Logo.jpg" alt="DCBT" />
        </a>
      </label>
      <ul>
        <li><a href="#admissions">ADMISSIONS</a></li>
        <li><a href="#academics">ACADEMICS</a></li>
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#" id="show-login">LOGIN</a></li>
      </ul>
    </nav>

    <div class="content">
      <div class="content-header">
        <div class="logo-title">
          <img src="assets/images/home/DCBT-Logo.jpg" alt="DCBT" />
        </div>
        <div class="title">
          <h2>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h2>
        </div>
      </div>
      <main>
        <div class="slide-1" id="admissions">
          <div class="carousel">
            <img src="assets/images/home/DCBT-Cover.jpg" alt="Cover" />
          </div>
          <div class="carousel">
            <h2>Be a DAEHAN student TODAY!</h2>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam
              maiores beatae at vel ut minima praesentium. Totam accusamus
              laboriosam consequatur enim, animi veniam quibusdam adipisci autem
              sit obcaecati ducimus ratione?
            </p>
            <div class="action">
              <button
                class="enroll"
                onclick="enroll()"
              >
                Enroll now!
              </button>
            </div>
          </div>
        </div>

        <div class="slide-2" id="academics">
          <header>
            <div class="title">
              <h2>Courses Offered</h2>
            </div>
          </header>
        </div>
        <div class="slide-2">
          <header>
            <div class="title">
              <img src="assets/images/home/DCBT-SHS-Logo.jpg" alt="" />
              <h3 style="font-weight: 400; font-style: italic">Senior High</h3>
            </div>
            <div class="title">
              <img src="assets/images/home/DCBT-Logo.jpg" alt="" />
              <h3 style="font-weight: 400; font-style: italic">College</h3>
            </div>
          </header>
          <main>
            <div class="item">
              <h3>ACADEMIC TRACK</h3>
              <div>
                <p>Accountancy, Business and Management (ABM)</p>
                <p>Humanities and Social Science (HUMMS)</p>
                <p>General Academic Strand (GAS)</p>
              </div>
              <h3>TECH-VOCATIONAL TRACK</h3>
              <div>
                <p>Information and Communication Technology (ICT)</p>
                <p>
                  Industrial Arts - Consumer Electronics/Electrical Installation
                  Maintenance (IA)
                </p>
              </div>
              <h3>ARTS & DESIGN TRACK</h3>
            </div>
            <div class="item">
              <h3>BACHELOR'S DEGREE PROGRAMS</h3>
              <div>
                <p>Bachelor of Christian Ministries (BCM)</p>
                <p>Bachelor of Arts in English (ABE)</p>
                <p>Bachelor of Science in Entrepreneurship (BSENTREP)</p>
                <p>Bachelor of Science in Teachers Education (BTTE)</p>
                <p>Bachelor of Physical Education (BPE)</p>
              </div>
            </div>
          </main>
        </div>

        <div class="slide-2" style="background-color: var(--theme1)">
          <header>
            <div class="title">
              <h2>News and Events!</h2>
            </div>
          </header>
          <main>
            <div class="item">
              <div id="fb-root"></div>
              <script
                async
                defer
                crossorigin="anonymous"
                src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0"
                nonce="zL2X8O3d"
              ></script>
              <div
                class="fb-post custom-fb-post"
                data-href="https://www.facebook.com/daehanedu/posts/pfbid0JbyAYoEjVhk4n2oshdMmiz2xuC3vFxouhm8Zir5Kudj1XyGTK5PUkpDgKCM1Q4pEl"
                data-width="auto"
                data-show-text="true"
              >
                <blockquote
                  cite="https://www.facebook.com/daehanedu/posts/740629674733536"
                  class="fb-xfbml-parse-ignore"
                >
                  <p>Happy Father&#039;s Day! 🫶</p>
                  Posted by
                  <a href="https://www.facebook.com/daehanedu"
                    >Daehan College of Business &amp; Technology - DCBT</a
                  >
                  on&nbsp;<a
                    href="https://www.facebook.com/daehanedu/posts/740629674733536"
                    >Saturday, June 17, 2023</a
                  >
                </blockquote>
              </div>
            </div>
          </main>
        </div>

        <div class="slide-2" id="about">
          <header>
            <div class="title">
              <h2>About</h2>
            </div>
          </header>
          <main>
            <div class="item">
              <h3>Mission</h3>
              <div>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod
                  corrupti nam, dolores aliquam maxime accusantium quia?
                  Laboriosam, quisquam. Laborum ea facilis similique tempore
                  repudiandae necessitatibus fugit temporibus nesciunt incidunt
                  labore!
                </p>
              </div>
            </div>
            <div class="item">
              <h3>Vision</h3>
              <div>
                <p>
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Architecto, laudantium dicta laboriosam sit delectus ipsa
                  explicabo porro placeat quam tenetur, labore rem debitis
                  consequuntur dolore tempora necessitatibus earum. Sunt, ad?
                </p>
              </div>
            </div>
          </main>
        </div>

        <div class="slide-2" style="background-color: var(--theme1)">
          <header>
            <div class="title">
              <h2>Facilities</h2>
            </div>
          </header>
          <main>
            <div class="slideshow-container">
              <s id="s1"></s>
              <s id="s2"></s>
              <s id="s3"></s>
              <s id="s4"></s>
              <s id="s5"></s>
              <s id="s6"></s>

              <div class="slider">
                <div>
                  <img src="assets/images/home/slide-1.jpg" alt="" />
                </div>
                <div>
                  <img src="assets/images/home/slide-2.jpg" alt="" />
                </div>
                <div>
                  <img src="assets/images/home/slide-3.jpg" alt="" />
                </div>
                <div>
                  <img src="assets/images/home/slide-4.jpg" alt="" />
                </div>
                <div>
                  <img src="assets/images/home/slide-5.jpg" alt="" />
                </div>
                <div>
                  <img src="assets/images/home/slide-6.jpg" alt="" />
                </div>
              </div>

              <div class="prevNext">
                <div><a href="#s6"></a><a href="#s4"></a></div>
                <div><a href="#s1"></a><a href="#s3"></a></div>
                <div><a href="#s2"></a><a href="#s4"></a></div>
                <div><a href="#s1"></a><a href="#s1"></a></div>
                <div><a href="#s3"></a><a href="#s5"></a></div>
              </div>

              <div class="bullets">
                <a href="#s1">1</a>
                <a href="#s2">2</a>
                <a href="#s3">3</a>
                <a href="#s4">4</a>
                <a href="#s5">5</a>
                <a href="#s6">6</a>
              </div>
            </div>
          </main>
        </div>
        <div class="footer">
          <div class="contact">
            <h4>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h4>
            <p>
              Nicanor Reyes Street, Sampaloc, Manila Tel No: +63 (2)-87777-338
              (trunkline)
            </p>
            <p>Terms and condition | Privacy Policy</p>
          </div>
          <div class="copyright">
            <h4>Copyright © 2019. All Rights Reserved</h4>
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
      function enroll(){
        var root = `<?php
                echo $baseURL;
            ?>`;
        // REFF
        window.location.href = `${root}/online_application.php`
      }
    </script>
  </body>
</html>
