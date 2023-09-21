

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
        <!--SHS-SECTIONS-->
        <div class="floating" id="shs-sections">
          <header>
            <div class="title">
              <h3>Strand Sections</h3>
              <small> *insert school year* </small>
            </div>
          </header>
          <main>
            <table>
              <thead>
                <tr>
                  <th>Strand</th>
                  <th>Grade 11</th>
                  <th>Grade 12</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>
                    <button
                      type="button"
                      class="information"
                      onclick="view('page1')"
                    >
                      View
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>

        <!--COLLEGE-SECTIONS-->
        <div class="floating" id="college-sections">
          <header>
            <div class="title">
              <h3>Course Sections</h3>
              <small> *insert school year* </small>
            </div>
          </header>
          <main>
            <table>
              <thead>
                <tr>
                  <th>Course</th>
                  <th>1st Year</th>
                  <th>2nd Year</th>
                  <th>3rd Year</th>
                  <th>4th Year</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>
                    <button
                      type="button"
                      class="information"
                      onclick="view('page2')"
                    >
                      View
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
      </main>
    </div>
    <script src="../../assets/js/sections.js"></script>