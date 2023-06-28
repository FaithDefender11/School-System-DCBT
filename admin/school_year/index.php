

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
        <!--SHS SY-->
        <div class="floating" id="shs-sy">
          <header>
            <div class="title">
              <h3>School Year <em>SHS</em></h3>
            </div>
          </header>
          <main>
            <div class="menu">
              <div class="item">
                <span>*insert school year*</span>
                <span class="status">*insert "Current"*</span>
                <span><button class="default fixed">View</button></span>
              </div>
            </div>
          </main>
        </div>

        <!--COLLEGE SY-->
        <div class="floating" id="college-sy" style="display: none">
          <header>
            <div class="title">
              <h3>School Year <em>College</em></h3>
            </div>
          </header>
          <main>
            <div class="menu">
              <div class="item">
                <span>*insert school year*</span>
                <span class="status">*insert "Current"*</span>
                <span><button class="default fixed">View</button></span>
              </div>
            </div>
          </main>
        </div>
      </main>
    </div>
    <script src="../../assets/js/school-year.js"></script>