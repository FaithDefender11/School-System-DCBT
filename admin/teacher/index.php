<?php

include_once('../../includes/admin_header.php');
include_once('../../includes/classes/Teacher.php');

$teacher = new Teacher($con, null);

?>


<div class="content">


  <div class="tabs">
    <?php
    echo "
            <button class='tab' 
                style='background-color: var(--them)'
                onclick=\"window.location.href = 'index.php';\">
                <i class='bi bi-clipboard-check'></i>
                Teacher List
            </button>
        ";

    echo "
            <button class='tab' 
                id='shsPayment'
                style='background-color: var(--mainContentBG); color: white'
                onclick=\"window.location.href = 'subject_schedule.php';\">
                <i class='bi bi-book'></i>
                Teacher Schedule
            </button>
        ";
    ?>
  </div>




  <!--SHS-TEACHERS-->
  <main>
    <div class="floating" id="shs-teachers">
      <header>
        <div class="title">
          <h3>Teachers</h3>
        </div>
        <div class="action">
          <a href="create.php">
            <button type="button" class="default large">+ Add new</button>
          </a>
        </div>
      </header>
      <main>
        <table class="a" style="margin: 0">
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
            <?php
            $query = $con->prepare("SELECT * FROM teacher as t1
                
                ");

            $query->execute();
            if ($query->rowCount() > 0) {

              while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                $teacher_id = $row['teacher_id'];
                $teacher_status = $row['teacher_status'];
                $date_creation = $row['date_creation'];

                $date_creation = $date_creation !== NULL ? date('Y-m-d', strtotime($date_creation)) : 'Not Set';

                $fullname = $row['firstname'] . " " . $row['lastname'];

                $subject_load_count = $teacher->GetTeacherSubjectLoad($teacher_id);

                echo "
                    <tr>
                      <td>$teacher_id</td>
                      <td>
                        <a style='color: white;' href='edit.php?id=$teacher_id'>
                          $fullname
                        </a>

                      </td>
                      <td>$subject_load_count</td>
                      <td></td>
                      <td>$teacher_status</td>
                      <td>$date_creation</td>
                      <td>
                          <button type='button' 
                            onclick=\"window.location.href='info.php?details=show&id=$teacher_id'\"
                            class='information'>View</button>
                      </td>
                    </tr>
                  ";
              }
            }

            ?>
          </tbody>
        </table>
      </main>
    </div>
  </main>

</div>

<?php include_once('../../includes/footer.php') ?>

<!--SHS-TEACHERS-->
<main>
  <div class="floating" id="shs-teachers">
    <header>
      <div class="title">
        <h3>Teachers</h3>
      </div>
      <div class="action">
        <button type="button" class="default large" onclick="addTeacher('shs')">
          + Add new
        </button>
      </div>
    </header>
    <main>
      <table class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
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
              <button type="button" class="information" id="view" onclick="view()">
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
        <button type="button" class="default large" onclick="addTeacher('college')">
          + Add new
        </button>
      </div>
    </header>
    <main>
      <table class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
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
              <button type="button" class="information" id="view" onclick="view()">
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
          <th rowspan="2" class="cell1" style="border-right: 2px solid black">
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
            <button type="button" class="default" id="edit" onclick="edit()">
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
          <th rowspan="2" class="cell1" style="border-right: 2px solid black">
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
            <button type="button" class="default" id="edit" onclick="edit()">
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