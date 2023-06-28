<?php include_once('../../includes/admin_header.php')?>


    <div class="content">
      <nav>
        <h3>Department</h3>
        <div class="form-box">
          <div class="button-box">
            <div id="btn"></div>
            <button
              type="button"
              class="toggle-btn"
              id="shs-btn"
              onclick="shs()"
            >
              SHS
            </button>
            <button
              type="button"
              class="toggle-btn"
              id="college-btn"
              onclick="college()"
            >
              College
            </button>
          </div>
        </div>
      </nav>
      <div class="content-header"></div>
      <div class="tabs">
        <button class="tab" id="teachers-list" onclick="teachers_list()">
          <i class="bi bi-clipboard-check icon"></i>
          Teachers List
        </button>
        <button class="tab" id="subjects-load" onclick="subject_load()">
          <i class="bi bi-collection icon"></i>
          Subjects Load
        </button>
      </div>

      <!--SHS-TEACHERS-->
      <main>
        <div class="floating" id="shs-teachers">
          <header>
            <div class="title">
              <h3>Teachers</h3>
            </div>
            <div class="action">
              <button type="button" class="default large">+ Add new</button>
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
                  <th>Name</th>
                  <th>Subject load</th>
                  <th>Hours per week</th>
                  <th>Status</th>
                  <th>Date added</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>021</td>
                  <td>Rhandyl Tapuroc</td>
                  <td>4</td>
                  <td>4</td>
                  <td>Active</td>
                  <td>04/27/2023</td>
                  <td>
                    <button
                      type="button"
                      class="information"
                      id="view"
                      onclick="view()"
                    >
                      View
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>

        <!--COLLEGE-TEACHERS-->
        <div class="floating" id="college-teachers">
          <header>
            <div class="title">
              <h3>Teachers</h3>
            </div>
            <div class="action">
              <button type="button" class="default large">+ Add new</button>
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
                  <th>Name</th>
                  <th>Subject load</th>
                  <th>Hours per week</th>
                  <th>Status</th>
                  <th>Date added</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>021</td>
                  <td>Rhandyl Tapuroc</td>
                  <td>4</td>
                  <td>4</td>
                  <td>Active</td>
                  <td>04/27/2023</td>
                  <td>
                    <button
                      type="button"
                      class="information"
                      id="view"
                      onclick="view()"
                    >
                      View
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>

        <!--SHS-SUBJECTS-LOAD-->
        <div class="floating" id="shs-subject-load" style="display: none">
          <header>
            <div class="title">
              <h3>Subject Loader</h3>
              <small>Find subject</small>
            </div>
          </header>
          <div class="filters">
            <table>
              <tr>
                <th
                  rowspan="2"
                  class="cell1"
                  style="border-right: 2px solid black"
                >
                  Filters
                </th>
                <th>School year</th>
                <td>
                  <select name="school-year">
                    <option value="2022-2023">2022-2023</option>
                  </select>
                </td>
                <th>Semester</th>
                <td>
                  <select name="semester">
                    <option value="1">1</option>
                  </select>
                </td>
              </tr>
              <tr>
                <th>Strand</th>
                <td>
                  <select name="strand">
                    <option name="ICT">ICT</option>
                  </select>
                </td>
                <th>Level</th>
                <td>
                  <select name="level">
                    <option name="1st year">1st year</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="action">
            <button type="button" class="default">
              <i class="bi bi-search"></i>Search
            </button>
          </div>
          <table>
            <thead>
              <tr>
                <th>School year</th>
                <th>Subject ID</th>
                <th>Section</th>
                <th>Level</th>
                <th>Schedule</th>
                <th>Hrs/week</th>
                <th>Subject status</th>
                <th>Teacher</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2022-2023</td>
                <td>021</td>
                <td>ICT-101</td>
                <td>11</td>
                <td>1:00am-2:00pm</td>
                <td>1hr</td>
                <td>Active</td>
                <td>Jeriko Coz</td>
                <td>
                  <button
                    type="button"
                    class="default"
                    id="edit"
                    onclick="edit()"
                  >
                    Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!--COLLEGE-SUBJECTS-LOAD-->
        <div class="floating" id="college-subject-load" style="display: none">
          <header>
            <div class="title">
              <h3>Subject Loader</h3>
              <small>Find subject</small>
            </div>
          </header>
          <div class="filters">
            <table>
              <tr>
                <th
                  rowspan="2"
                  class="cell1"
                  style="border-right: 2px solid black"
                >
                  Filters
                </th>
                <th>School year</th>
                <td>
                  <select name="school-year">
                    <option value="2022-2023">2022-2023</option>
                  </select>
                </td>
                <th>Semester</th>
                <td>
                  <select name="semester">
                    <option value="1">1</option>
                  </select>
                </td>
              </tr>
              <tr>
                <th>Strand</th>
                <td>
                  <select name="strand">
                    <option name="ICT">ICT</option>
                  </select>
                </td>
                <th>Level</th>
                <td>
                  <select name="level">
                    <option name="1st year">1st year</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="action">
            <button type="button" class="default">
              <i class="bi bi-search"></i>Search
            </button>
          </div>
          <table>
            <thead>
              <tr>
                <th>School year</th>
                <th>Subject ID</th>
                <th>Section</th>
                <th>Level</th>
                <th>Schedule</th>
                <th>Hrs/week</th>
                <th>Subject status</th>
                <th>Teacher</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2022-2023</td>
                <td>021</td>
                <td>ICT-101</td>
                <td>11</td>
                <td>1:00am-2:00pm</td>
                <td>1hr</td>
                <td>Active</td>
                <td>Jeriko Coz</td>
                <td>
                  <button
                    type="button"
                    class="default"
                    id="edit"
                    onclick="edit()"
                  >
                    Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
    <script src="../../assets/js/teachers.js"></script>