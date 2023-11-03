<?php

    include('includes/config.php');
    include('includes/classes/Department.php');

    // require_once('./classes/StudentEnroll.php');
    // require_once('enrollment/classes/StudentEnroll.php');
    // require_once('includes/classes/form-helper/Constants.php');

    // $enroll = new StudentEnroll($con);

    $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
    $baseURL = dirname($currentURL);

    $shsTrackArr = [];
    $tertiaryTrackArr = [];

    $shsTrack = $con->prepare("SELECT t1.track 
    
      FROM program  as t1
      INNER JOIN department as t2 ON t2.department_id=t1.department_id
      WHERE t2.department_name=:department_name

      GROUP BY t1.track
    ");

    $shsTrack->bindValue(":department_name", "Senior High School");
    $shsTrack->execute();

    if($shsTrack->rowCount() > 0){
      $shsTrackArr = $shsTrack->fetchAll(PDO::FETCH_ASSOC);
    }

    $tertiaryTrack = $con->prepare("SELECT t1.track 
    
      FROM program  as t1
      INNER JOIN department as t2 ON t2.department_id=t1.department_id
      WHERE t2.department_name=:department_name

      GROUP BY t1.track
    ");

    $tertiaryTrack->bindValue(":department_name", "Tertiary");
    $tertiaryTrack->execute();

    if($tertiaryTrack->rowCount() > 0){
      $tertiaryTrackArr = $tertiaryTrack->fetchAll(PDO::FETCH_ASSOC);
    }

    // var_dump($tertiaryTrackArr);

?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, inital-scale=1, shrink-to-fit=no"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,700&family=Lato:wght@100;300;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
      integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="assets/css/home.css" />

    <link rel="icon" href="assets/images/icons/DCBT-Logo.jpg" type="image/png">

    <title>Daehan College of Business and Technology</title>
  </head>
    
  <body>
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="<?php echo domainName . "index.php" ?>">
        <img
          src="assets/images/home/DCBT-Logo.jpg"
          alt="DCBT-Logo"
        />
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="#"><span>ADMISSIONS</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><span>ACADEMICS</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><span>ABOUT</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="show-login"><span>LOGIN</span></a>
            <!-- <a href="enrollment/student_login.php" class="nav-link"><span>LOGIN</span></a> -->
          </li>
        </ul>
      </div>
    </nav>

    <div class="header">
      <img
        src="assets/images/home/DCBT-Logo.jpg"
        alt="DCBT-Logo"
      />
      <h2>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h2>
    </div>

    <div class="slide-1">
      <div class="content-1">
        <div class="content-img1">
          <img
            src="assets/images/home/DCBT-Cover.jpg"
            alt="DCBT-Cover"
          />
        </div>
        <div class="content-text1">
          <h3>Be a DAEHAN student TODAY!</h3>
          <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati
            dignissimos sapiente, officiis iusto saepe itaque delectus beatae
            quo, vitae sint veniam quos deleniti rem veritatis quasi, eveniet
            laudantium distinctio ipsa?
          </p>
          <button class="enroll" onclick="enroll()">Enroll now!</button>
        </div>
      </div>
    </div>

    <div class="slide-2">
      <h3>Courses Offered</h3>

      <div class="container">
        <div class="row">
          <div class="col">
            <div class="course-header">
              <img
                src="assets/images/home/DCBT-SHS-Logo.jpg"
              />
              <h3>Senior High</h3>
            </div>

            <div class="shs-courses">

              <?php 
              
                if(count($shsTrackArr) > 0){

                  foreach ($shsTrackArr as $key => $value) {
                    $track_shs = $value['track'];

                    # code...
                    echo "
                      <h4>○ $track_shs Track</h4>
                    ";

                      $query = $con->prepare("SELECT * FROM program 
                      
                        WHERE track=:track");

                      $query->bindValue(":track", $track_shs);
                      $query->execute();

                      if($query->rowCount() > 0){
                        $result = $query->fetchAll(PDO::FETCH_ASSOC);
                      }

                      if(count($result) > 0){

                          foreach ($result as $key => $value) {

                            $track = $value['track'];
                            $department_id = $value['department_id'];
                            $program_name = $value['program_name'];
                            $acronym = $value['acronym'];

                            $department = new Department($con, $department_id);
                            $name = $department->GetDepartmentName();
                            
                              # code...
                              echo "
                                <p>$acronym ($program_name) </p>
                              ";

                          }
                      }

                  }
                }
              ?>
              
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="course-header">
              <img
                src="assets/images/home/DCBT-SHS-Logo.jpg"
              />
              <h3>Tertiary</h3>
            </div>

            <div class="college-courses">
                <h4>BACHELOR'S DEGREE PROGRAMS</h4>
                
               <?php 
                  $query = $con->prepare("SELECT * FROM program as t1

                    INNER JOIN department as t2 ON t2.department_id=t1.department_id
                    WHERE t2.department_name=:department_name

                  ");

                  $query->bindValue(":department_name", "Tertiary");
                  $query->execute();

                  if($query->rowCount() > 0){
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                  }

                  if(count($result) > 0){

                      foreach ($result as $key => $value) {

                        $track = $value['track'];
                        $department_id = $value['department_id'];
                        $program_name = $value['program_name'];
                        $acronym = $value['acronym'];

                        $department = new Department($con, $department_id);
                        $name = $department->GetDepartmentName();
                        
                          # code...
                          echo "
                            <p>$acronym ($program_name)</p>
                          ";

                      }
                  }

                ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="slide-3">
      <h3>News and Events!</h3>

      <div class="slideshow-container">
        <div class="slide-img fade">
          <img
            src="assets/images/home/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img fade">
          <img
            src="assets/images/home/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img fade">
          <img
            src="assets/images/home/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img fade">
          <img
            src="assets/images/home/DCBT-Building.jpg"
            style="width: 100%"
          />
        </div>

        <a class="prev" onclick="plusSlides(-1, 0)">&#10094;</a>
        <a class="next" onclick="plusSlides(1, 0)">&#10095;</a>
      </div>
    </div>

    <div class="slide-4">
      <h3>About</h3>

      <div class="container">
        <div class="row">
          <div class="col">
            <div class="mission">
              <h3>Mission</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic,
                odio impedit, excepturi quod a molestiae accusamus saepe ab
                quisquam fuga autem similique nisi aspernatur perspiciatis
                tenetur eum, nesciunt ratione et.
              </p>
            </div>
          </div>
          <div class="col">
            <div class="vision">
              <h3>Vision</h3>
              <p>
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. In
                rerum facilis sunt eum consequatur quasi recusandae repellendus
                accusamus, voluptate illo nihil minima qui. Laborum eaque,
                dolore error ab possimus quas.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="slide-5">
      <h3>Facilities</h3>

      <div class="slideshow-container">
        <div class="slide-img2 fade">
          <img
            src="assets/images/home/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img2 fade">
          <img
            src="assets/images/home/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img2 fade">
          <img
            src="assets/images/home/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>
        <div class="slide-img2 fade">
          <img
            src="assets/images/home/DCBT-Cover.jpg"
            style="width: 100%"
          />
        </div>

        <a class="prev" onclick="plusSlides(-1, 1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1, 1)">&#10095;</a>
      </div>
    </div>

    

    <footer>
      <div class="contact">
        <h4>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h4>
        <p>Nicanor Reyes Street, Sampaloc, Manila</p>
        <p>Tel No: +63(2)-87777-338</p>
        <p>Terms and condition | Privacy Policy</p>
      </div>
      <div class="copyright">
        <h4>Copyright © 2019. All Rights Reserved</h4>
      </div>
    </footer>




    <div class="popup" id="login-form">
      <div class="close-btn">&times;</div>

      <div class="form">

        <h2>Choose section log-in</h2>
      

        <div class="row col-md-12">

          <div class="col-md-6">
            <div class="form-element">
              <button type="button" onclick="window.location.href ='lms_login.php'" name="samp_btn">LMS</button>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-element">
              <button  type="button" onclick="window.location.href ='online_application.php'" name="samp_btn">Enrollment</button>
            </div>
          </div>
        </div>
        
      </div>

    </div>


    <script>
      let slideIndex = [1, 1];
      let slideId = ["slide-img", "slide-img2"];
      showSlides(1, 0);
      showSlides(1, 1);

      function plusSlides(n, no) {
        showSlides((slideIndex[no] += n), no);
      }

      function showSlides(n, no) {
        let i;
        let x = document.getElementsByClassName(slideId[no]);
        if (n > x.length) {
          slideIndex[no] = 1;
        }
        if (n < 1) {
          slideIndex[no] = x.length;
        }
        for (i = 0; i < x.length; i++) {
          x[i].style.display = "none";
        }
        x[slideIndex[no] - 1].style.display = "block";
      }
    </script>

    <script>
      document.querySelector("#show-login").addEventListener("click",function(){
        document.querySelector("#login-form").classList.add("active");
      });
      document.querySelector(".popup .close-btn").addEventListener("click",function(){
        document.querySelector("#login-form").classList.remove("active");
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

    <script
      src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
      integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
      crossorigin="anonymous"
    ></script>
  </body>
</html>

