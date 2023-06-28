

<?php include_once('../../includes/admin_header.php')?>

    <div class="content">
      <nav>
        <h3>Department</h3>
        <div class="form-box">
          <div class="button-box">
            <div id="btn"></div>
            <button type="button" class="toggle-btn" onclick="shs()">
              SHS
            </button>
            <button type="button" class="toggle-btn" onclick="college()">
              College
            </button>
          </div>
        </div>
      </nav>
      <main>
        <!--SHS-->
        <div class="floating" id="shs-menu">
          <header>
            <div class="title">
              <h3>Menu</h3>
            </div>
          </header>
          <main>
            <div class="menu">
              <div class="item">
                <span>View Subjects</span>
                <span
                  ><a href="SHS-View-Subjects.html"
                    ><i class="bi bi-arrow-right-circle"></i></a
                ></span>
              </div>
              <div class="item">
                <span>View Strand Subjects</span>
                <span
                  ><a href="SHS-View-Strand-Subjects.html"
                    ><i class="bi bi-arrow-right-circle"></i></a
                ></span>
              </div>
            </div>
          </main>
        </div>
        <div class="floating" id="shs-options">
          <header>
            <div class="title">
              <h3>Options</h3>
            </div>
          </header>
          <main>
            <div class="menu">
              <div class="item">
                <span>Automatic Subject Population</span>
                <span
                  ><i class="bi bi-info-circle"></i
                  ><input
                    type="checkbox"
                    name="SHSAutoPopulation"
                    id="shs-autopopulation"
                /></span>
              </div>
              <div class="item">
                <span>Manually Populate Sections</span>
                <span><button class="default">Populate now</button></span>
              </div>
            </div>
          </main>
        </div>

        <!--COLLEGE-->
        <div class="floating" id="college-menu">
          <header>
            <div class="title">
              <h3>Menu</h3>
            </div>
          </header>
          <main>
            <div class="menu">
              <div class="item">
                <span>View Subjects</span>
                <span
                  ><a href="College-View-Subjects.html"
                    ><i class="bi bi-arrow-right-circle"></i></a
                ></span>
              </div>
              <div class="item">
                <span>View Course Subjects</span>
                <span
                  ><a href="College-View-Course-Subjects.html"
                    ><i class="bi bi-arrow-right-circle"></i></a
                ></span>
              </div>
            </div>
          </main>
        </div>
        <div class="floating" id="college-options">
          <header>
            <div class="title">
              <h3>Options</h3>
            </div>
          </header>
          <main>
            <div class="menu">
              <div class="item">
                <span>Automatic Subject Population</span>
                <span
                  ><i class="bi bi-info-circle"></i
                  ><input
                    type="checkbox"
                    name="CollegeAutoPopulation"
                    id="college-autopopulation"
                /></span>
              </div>
              <div class="item">
                <span>Manually Populate Sections</span>
                <span><button class="default">Populate now</button></span>
              </div>
            </div>
          </main>
        </div>
      </main>
    </div>
    <script src="../../assets/js/subjects.js"></script>