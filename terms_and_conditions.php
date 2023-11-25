<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <li><a href="index.php#admission" id="admission-tab">ADMISSION</a></li>
        <li><a href="index.php#academics" id="academics-tab">ACADEMICS</a></li>
        <li><a href="index.php#about" id="about-tab">ABOUT</a></li>
        <li><a href="#" id="show-login">LOGIN</a></li>
      </ul>
    </nav>

    <div class="content">
        <main>
            <div class="slide-4">
                <header>
                    <div class="title">
                        <h2>Terms & Conditions</h2>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>1. Acceptance of Terms</h3>
                        <small>
                            By accessing or using [Your Company Name] ("the Company") 
                            services, you agree to comply with and be bound by the 
                            following Terms and Conditions. If you do not agree to 
                            these Terms and Conditions, please do not use our services.
                        </small>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>2. Changes to Terms</h3>
                        <small>
                            The Company reserves the right to modify or revise these Terms 
                            and Conditions at any time. Your continued use of the services 
                            following any changes constitutes your acceptance of such changes. 
                            It is your responsibility to review these Terms and Conditions regularly.
                        </small>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>3. User Responsibilities</h3>
                        <small>
                            You agree to use the services provided by the Company responsibly. 
                            You will not engage in any activity that interferes with or disrupts 
                            our services, networks, or systems. You also agree not to access, reproduce, 
                            or distribute any part of our services without the Company's explicit 
                            written consent.
                        </small>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>4. Privacy Policy</h3>
                        <small>
                            Your use of our services is also governed by our Privacy Policy, 
                            which outlines how we collect, use, and protect your personal information. 
                            By using our services, you consent to the terms of our Privacy Policy.
                        </small>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>5. Intellectual Property</h3>
                        <small>
                            All content, trademarks, and intellectual property on our services are 
                            owned by or licensed to the Company. You may not use, reproduce, or 
                            distribute any content from our services without the Company's express 
                            permission. 
                        </small>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>6. Limitation of Liability</h3>
                        <small>
                            The Company is not liable for any direct, indirect, incidental, special, 
                            or consequential damages that result from your use of or inability to use 
                            our services.
                        </small>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>7. Governing Law</h3>
                        <small>
                            These Terms and Conditions are governed by and construed in accordance with the 
                            laws of [Your Jurisdiction]. Any disputes arising out of these terms will be 
                            subject to the exclusive jurisdiction of the courts of [Your Jurisdiction].
                        </small>
                    </div>
                </header>
                <header>
                    <div class="title">
                        <h3>8. Contact Information</h3>
                        <small>
                            If you have any questions or concerns about these Terms and Conditions, please 
                            contact us at [Your Contact Information]. 
                        </small>
                    </div>
                </header>
            </div>

            <div class="footer">
                <div class="contact">
                    <h4>DAEHAN COLLEGE OF BUSINESS AND TECHNOLOGY</h4>
                    <p>
                        Siwang San Juan Floodway Road 20, Taytay, Philippines 
                        (0916 330 6989)
                    </p>
                    <p><a href="terms_and_conditions.php">Terms and Condition</a></p>
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
          document.querySelector(".content").classList.add("active");
          document.querySelector("#check").checked = false;
          document.body.classList.add("no-scroll");
        });

      document.querySelector("#login-form .close-btn").addEventListener("click", function () {
          document.querySelector("#login-form").classList.remove("active");
          document.querySelector("#check").checked = false;
          document.body.classList.remove("no-scroll");
        });

      document.querySelector("#admission-tab").addEventListener("click", function () {
        document.querySelector("#check").checked = false;
      });

      document.querySelector("#academics-tab").addEventListener("click", function () {
        document.querySelector("#check").checked = false;
      });

      document.querySelector("#about-tab").addEventListener("click", function () {
        document.querySelector("#check").checked = false;
      });
    </script>
</body>
</html>