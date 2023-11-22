<?php

    include('includes/config.php');
    include('includes/classes/Department.php');
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

    }

    $shsTrackArr = [];
    $tertiaryTrackArr = [];

    $shsTrack = $con->prepare("SELECT t1.track 
    
      FROM program  as t1
      INNER JOIN department as t2 ON t2.department_id=t1.department_id
      WHERE t2.department_name=:department_name

      AND t1.status = 1

      GROUP BY t1.track
    ");

    $shsTrack->bindValue(":department_name", "Senior High School");
    $shsTrack->execute();

    if($shsTrack->rowCount() > 0){
      $shsTrackArr = $shsTrack->fetchAll(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, inital-scale=1" />
    <link rel="icon" href="assets/images/icons/DCBT-Logo.png" type="image/png">
    <title>Daehan College of Business & Technology</title>
    <!--Link JavaScript-->
    <script src="assets/js/image-slider.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
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
  <div id="fb-root"></div>
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0" nonce="VpV93QD8"></script>
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
        <li><a href="#admission" id="admission-tab">ADMISSION</a></li>
        <li><a href="#academics">ACADEMICS</a></li>
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#" id="show-login">LOGIN</a></li>
      </ul>
    </nav>

    <div class="content">
      <div class="content-header">
        <div class="logo-title">
          <img src="assets/images/icons/DCBT-Logo.png" alt="DCBT" />
        </div>
        <div class="title">
          <h2>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h2>
        </div>
      </div>
      <main>
        <div class="slide-1" id="admission">
          <div class="carousel">
            <img src="assets/images/home/DCBT-Cover.jpg" alt="Cover" />
          </div>
          <div class="carousel">
            <h2>Be a DAEHAN student TODAY!</h2>
            <p>
              Welcome to Daehan College of Business & Technology, your gateway to a transformative educational 
              journey! Explore our diverse array of senior high school and college courses, 
              and embark on a path to knowledge, growth, and success. Start your registration now 
              to unlock a world of opportunities!
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
            <?php
              // Function to display programs
              function displayPrograms($programs) {
                foreach ($programs as $program) {
                  echo "<p>{$program['program_name']} ({$program['acronym']})</p>";
                }
              }
            ?>
            <div class="item">
              <?php
                if(count($shsTrackArr) > 0){
                  foreach ($shsTrackArr as $key => $value) {
                    $track_shs = $value['track'];
                    # code...
                    // ○
                    echo "
                      <h3>$track_shs Track</h3>
                    ";

                      $query = $con->prepare("SELECT * FROM program 
                      
                        WHERE track=:track
                        AND status = 1
                        ");

                      $query->bindValue(":track", $track_shs);
                      $query->execute();

                      if($query->rowCount() > 0){
                        $result = $query->fetchAll(PDO::FETCH_ASSOC);
                      }

                      if(count($result) > 0){
                          echo "<div>";

                          foreach ($result as $key => $value) {

                            $track = $value['track'];
                            $department_id = $value['department_id'];
                            $program_name = $value['program_name'];
                            $acronym = $value['acronym'];

                            $department = new Department($con, $department_id);
                            $name = $department->GetDepartmentName();
                            
                              # code...
                              echo "
                                <p>$program_name ($acronym)</p>
                              ";
                          }
                          echo "</div>";
                      }
                  }
                }
              ?>
            </div>
            <div class="item">
              <h3>Bachelor's Degree Program</h3>
              <?php
                $query = $con->prepare("SELECT * FROM program as t1

                INNER JOIN department as t2 ON t2.department_id=t1.department_id
                WHERE t2.department_name=:department_name
                AND t1.status = 1

              ");

              $query->bindValue(":department_name", "Tertiary");
              $query->execute();

              if($query->rowCount() > 0){
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
              }

              if(count($result) > 0){
                  echo "
                    <div>
                  ";

                  foreach ($result as $key => $value) {

                    $track = $value['track'];
                    $department_id = $value['department_id'];
                    $program_name = $value['program_name'];
                    $acronym = $value['acronym'];

                    $department = new Department($con, $department_id);
                    $name = $department->GetDepartmentName();
                    
                      # code...
                      echo "
                        <p> $program_name ($acronym) </p>
                      ";
                  }
                  echo "
                    </div>
                  ";
              }
              ?>
            </div>
          </main>
        </div>

        <div class="slide-2 news" style="background-color: var(--theme1)">
          <header>
            <div class="title">
              <h2>News and Events!</h2>
            </div>
          </header>
          <main>
            <div class="wrapper">
            <i id="news-left" class="fa-solid fa-angle-left"></i>
              <ul class="carousel">
                <div class="card">
                  <div class="img">
                    <div class="fb-post" data-href="https://www.facebook.com/daehanedu/posts/pfbid02WjTuSCkDyqszAym3WwBs2bk86ZjCBWxsz4sEj5vy778CEadZ7YZyHZdHLouWECgEl" data-width="350px" data-show-text="false" draggable="false">
                      <blockquote cite="https://www.facebook.com/daehanedu/posts/776227644507072" class="fb-xfbml-parse-ignore">
                      <p>Happy Wednesday and Happy 1st Day of Academics! 
                        Enjoy your class everyone!
                        Thank you!
                        #daehan &#064;dcbt
                      </p>Posted by <a href="https://www.facebook.com/daehanedu">Daehan College of Business &amp; Technology - DCBT</a> on&nbsp;<a href="https://www.facebook.com/daehanedu/posts/776227644507072">Tuesday, August 8, 2023</a>
                      </blockquote>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="img">
                    <div class="fb-post" data-href="https://www.facebook.com/daehanedu/posts/pfbid02zDc2EYcTPdqWop8smjHaPDNzEoKPGfeV5Y5i7QRpQf61JZQw9aCad9RYV6GaZYDml" data-width="350px" data-show-text="false" draggable="false">
                      <blockquote cite="https://www.facebook.com/daehanedu/posts/775584574571379" class="fb-xfbml-parse-ignore">
                      <p>Senior High School and College 
                        Enrollment is open until August 25, 2023 
                        Please PM us for details.
                        Thank you! 
                        #daehan #dcbt #enrollment #admission
                      </p>Posted by <a href="https://www.facebook.com/daehanedu">Daehan College of Business &amp; Technology - DCBT</a> on&nbsp;<a href="https://www.facebook.com/daehanedu/posts/775584574571379">Tuesday, August 8, 2023</a>
                      </blockquote>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="img">
                    <div class="fb-post" data-href="https://www.facebook.com/daehanedu/posts/pfbid02CVDe4MN65Yx2y9Pvjrtsim7ytM3G7grDhqJRfdiA1uL8sKG3cKTEebQ577WNb759l" data-width="350px" data-show-text="false" draggable="false">
                      <blockquote cite="https://www.facebook.com/daehanedu/posts/775566544573182" class="fb-xfbml-parse-ignore">
                      <p>First day of School Year 2023-2024 
                        Orientation 5th batch!
                        *feel free tag yourselves and tag our school Daehan College of Business &amp; Technology - DCBT
                        #dcbt #daehan #orientation #SSG
                      </p>Posted by <a href="https://www.facebook.com/daehanedu">Daehan College of Business &amp; Technology - DCBT</a> on&nbsp;<a href="https://www.facebook.com/daehanedu/posts/775566544573182">Tuesday, August 8, 2023</a>
                      </blockquote>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="img">
                    <div class="fb-post" data-href="https://www.facebook.com/daehanedu/posts/pfbid0CjXUg88j8L1dibexg1zssRJVHtkwSD8pJLyp3pXKWr7H9ux91ZF9LmdYAfvqtHRZl" data-width="350px" data-show-text="false" draggable="false">
                      <blockquote cite="https://www.facebook.com/daehanedu/posts/775531921243311" class="fb-xfbml-parse-ignore">
                      <p>First day of School Year 2023-2024 
                        Orientation 5th batch!
                        *feel free tag yourselves and tag our school Daehan College of Business &amp; Technology - DCBT
                        #dcbt #daehan #orientation
                      </p>Posted by <a href="https://www.facebook.com/daehanedu">Daehan College of Business &amp; Technology - DCBT</a> on&nbsp;<a href="https://www.facebook.com/daehanedu/posts/775531921243311">Monday, August 7, 2023</a>
                      </blockquote>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="img">
                    <div class="fb-post" data-href="https://www.facebook.com/daehanedu/posts/pfbid02ScBgGvUv5peCfHhFXQUhV8qmmwcxRRCJLgpSxm8dF5gLoCLLe6T3AdcXxTixAL3wl" data-width="350px" data-show-text="false" draggable="false">
                      <blockquote cite="https://www.facebook.com/daehanedu/posts/775458454583991" class="fb-xfbml-parse-ignore">
                      <p>First day of School Year 2023-2024 
                        Orientation 4th batch!
                        *feel free tag yourselves and tag our school Daehan College of Business &amp; Technology - DCBT
                        #dcbt #daehan #orientation
                      </p>Posted by <a href="https://www.facebook.com/daehanedu">Daehan College of Business &amp; Technology - DCBT</a> on&nbsp;<a href="https://www.facebook.com/daehanedu/posts/775458454583991">Monday, August 7, 2023</a>
                      </blockquote>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="img">
                    <div class="fb-post" data-href="https://www.facebook.com/daehanedu/posts/pfbid0sQhe3gkTzrw5E2Lwj4Pw3UCMLqwWGGjrQfRjGtCq4xouJBPiZTL6ju2Fgf5yNZ5Rl" data-width="350px" data-show-text="false" draggable="false">
                      <blockquote cite="https://www.facebook.com/daehanedu/posts/774848394644997" class="fb-xfbml-parse-ignore">
                      <p>First day of School Year 2023-2024 
                        Orientation 3rd batch!
                        *feel free tag yourselves and tag our school Daehan College of Business &amp; Technology - DCBT
                        #dcbt #daehan #orientation
                      </p>Posted by <a href="https://www.facebook.com/daehanedu">Daehan College of Business &amp; Technology - DCBT</a> on&nbsp;<a href="https://www.facebook.com/daehanedu/posts/774848394644997">Sunday, August 6, 2023</a>
                      </blockquote>
                    </div>
                  </div>
                </div>
              </ul>
            <i id="news-right" class="fa-solid fa-angle-right"></i>
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
              <div>
                <p>
                To promote, establish, and operate training centers 
                and or institution in the field of Science, Technology, Vocational, 
                and other apprentice able trade and occupations in which qualified 
                and deserving persons may be taught, developed and trained in a well 
                rounded, theoretical and practical method including on the job training 
                so as to instill in them the right sense of professionalism in the performance 
                of their work; to conduct and sponsor other allied cultural and vocational 
                human development courses as may be best suited to the current situation and 
                needs of the Philippines, finally to acquire by construction, purchase, exchange 
                and other means, thereafter to won, lease, maintain and carry on, and to sell, 
                transfer, convey or otherwise dispose of training center facilities and establishment
                 suitable or proper for the operation or conduct of a training institute or institutes 
                 devoted to training and instructions of technical and vocational learning.
                </p>
              </div>
            </div>
            <div class="item" style="display: none">
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

        <div class="slide-2 facilities" id="img-slides" style="background-color: var(--theme1)">
          <header>
            <div class="title">
              <h2>Facilities</h2>
            </div>
          </header>
          <main>
            <div class="img-wrapper">
              <div class="img-carousel">
                <img src="assets/images/home/slide-1.jpg" alt="" draggable="false" />
                <img src="assets/images/home/slide-2.jpg" alt="" draggable="false" />
                <img src="assets/images/home/slide-3.jpg" alt="" draggable="false" />
                <img src="assets/images/home/slide-4.jpg" alt="" draggable="false" />
                <img src="assets/images/home/slide-5.jpg" alt="" draggable="false" />
                <img src="assets/images/home/slide-6.jpg" alt="" draggable="false" />
              </div>
            </div>
          </main>
        </div>
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
              <button type="button" class="default" onclick="window.location.href ='enrollment_login.php'">Enrollment</button>
            </div>
          </main>
        </div>
      </div>
    </div>

    <script>
      document.querySelector("#show-login").addEventListener("click", function () {
          document.querySelector("#login-form").classList.add("active");
          document.querySelector("nav > ul").classList.add("active");
          document.body.classList.add("no-scroll");
        });

      document.querySelector("#login-form .close-btn").addEventListener("click", function () {
          document.querySelector("#login-form").classList.remove("active");
          document.querySelector("nav > ul").classList.remove("active");
          document.body.classList.remove("no-scroll");
        });

      document.querySelector("#admission-tab").addEventListener("click", function () {
        document.querySelector("nav > ul").classList.add("active");
        document.querySelector("nav > ul").classList.remove("active");
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
