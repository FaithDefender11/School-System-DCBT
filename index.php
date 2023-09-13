<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, inital-scale=1" />
  <title>Daehan College of Business & Technology</title>
  <!--Link stylesheets-->
  <link rel="stylesheet" href="assets/css/DCBT-landing-page.css" />
  <link rel="stylesheet" href="assets/css/fonts.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <!--Link fonts-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=IM+Fell+Double+Pica&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap" rel="stylesheet" />
</head>

<body>
  <div><?php
        $servername = "localhost";
        $database = "u544299924_main";
        $username = "u544299924_master";
        $password = "xTVeEU~k=O8:";

        // Create connection

        $conn = mysqli_connect($servername, $username, $password, $database);

        // Check connection

        if (!$conn) {

          die("Connection failed: " . mysqli_connect_error());
        }
        echo "Connected successfully";
        mysqli_close($conn);
        ?></div>
  <nav>
    <input type="checkbox" id="check" />
    <label for="check" class="check-btn">
      <i class="bi bi-list"></i>
    </label>
    <label class="logo">
      <a href="index.php">
        <img src="assets/images/DCBT-Logo.jpg" alt="DCBT" />
      </a>
    </label>
    <ul>
      <li><a href="#">ADMISSIONS</a></li>
      <li><a href="#">ACADEMICS</a></li>
      <li><a href="#">ABOUT</a></li>
      <li><a href="login.php">LOGIN</a></li>
    </ul>
  </nav>

  <div class="content">
    <div class="content-header">
      <div class="logo-title">
        <img src="assets/images/DCBT-Logo.jpg" alt="DCBT" />
      </div>
      <div class="title">
        <h2>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h2>
      </div>
    </div>
    <main>
      <div class="slide-1">
        <div class="carousel">
          <img src="assets/images/DCBT-Cover.jpg" alt="Cover" />
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
            <button class="enroll" onclick="window.location.href = 'Online-enrollment-page.html';">
              Enroll now!
            </button>
          </div>
        </div>
      </div>

      <div class="slide-2">
        <header>
          <div class="title">
            <h2>Courses Offered</h2>
          </div>
        </header>
      </div>
      <div class="slide-2">
        <header>
          <div class="title">
            <img src="assets/images/DCBT-SHS-Logo.jpg" alt="" />
            <h3 style="font-weight: 400; font-style: italic">Senior High</h3>
          </div>
          <div class="title">
            <img src="assets/images/DCBT-Logo.jpg" alt="" />
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
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0" nonce="iAWvvjaF"></script>
            <div class="fb-post" data-href="https://www.facebook.com/daehanedu/posts/pfbid02VafgciUkhGSbuzg4NJPMYDKjVSeJ4k3PWXjD2dt5GyjdNSiP5BbHAtzZzVyQqpKpl" data-width="750" data-show-text="true">
              <blockquote cite="https://www.facebook.com/daehanedu/posts/759221502874353" class="fb-xfbml-parse-ignore">
                <p>
                  Congratulations to all graduates and awardees! We will love
                  to see you again! #DCBT #GRADUATION
                </p>
                Posted by
                <a href="https://www.facebook.com/daehanedu">Daehan College of Business &amp; Technology - DCBT</a>
                on&nbsp;<a href="https://www.facebook.com/daehanedu/posts/759221502874353">Friday, July 14, 2023</a>
              </blockquote>
            </div>
          </div>
        </main>
      </div>

      <div class="slide-2">
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
                <img src="assets/images/slide-1.jpg" alt="" />
              </div>
              <div>
                <img src="assets/images/slide-2.jpg" alt="" />
              </div>
              <div>
                <img src="assets/images/slide-3.jpg" alt="" />
              </div>
              <div>
                <img src="assets/images/slide-4.jpg" alt="" />
              </div>
              <div>
                <img src="assets/images/slide-5.jpg" alt="" />
              </div>
              <div>
                <img src="assets/images/slide-6.jpg" alt="" />
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
  </div>
</body>

</html>