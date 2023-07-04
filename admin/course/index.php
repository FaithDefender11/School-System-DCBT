

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
        <!--SHS-STRAND-->
        <div class="floating" id="shs-strand">
          <header>
            <div class="title">
              <h3>Strand</h3>
              <small>
                Strand(s) with previously enrolled student cannot be deleted
              </small>
            </div>
            <div class="action">
              <button type="button" class="default large" onclick="add('shs')">
                + Add New
              </button>
            </div>
          </header>
          <main>
            <table
              class="ws-table-all cw3-striped cw3-bordered"
              style="margin: 0"
            >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Track</th>
                  <th>Strand</th>
                  <th>Total enrolled</th>
                  <th>Offered</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>

        <!--COLLEGE-COURSES-->
        <div class="floating" id="college-courses">
          <header>
            <div class="title">
              <h3>Course</h3>
              <small>
                Course(s) with previously enrolled student cannot be deleted
              </small>
            </div>
            <div class="action">
              <button
                type="button"
                class="default large"
                onclick="add('college')"
              >
                + Add New
              </button>
            </div>
          </header>
          <main>
            <table
              class="ws-table-all cw3-striped cw3-bordered"
              style="margin: 0"
            >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Track</th>
                  <th>Course</th>
                  <th>Total enrolled</th>
                  <th>Offered</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
      </main>
    </div>
    <script src="../../assets/js/courses.js"></script>