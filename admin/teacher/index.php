<?php 

  include_once('../../includes/admin_header.php');
  include_once('../../includes/classes/Teacher.php');

    ?>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../subject/subject.css">
        </head>
    <?php


  $teacher = new Teacher($con, null);


?>
    
<div class="col-md-12 row">

    <div class="content_subject">
        <div class="dashboard">

            <h5>Department</h3>

            <div class="form-box">
                <div class="button-box">
                    <div id="btn"></div>
                    <a href="shs_index.php">
                        <button type="button" class="btn-active toggle-btn" >
                            SHS
                        </button>
                    </a>

                    <a href="tertiary_index.php">
                        <button type="button" class="btn-inactive toggle-btn">
                            Tertiary
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
          <div class="content-header"></div>

          <div class="tabs">
            <div class="tab" id="teachers-list">
              <a href="index.php">
                    <button
                        type="button"
                        class="selection-btn"
                        id="teachers-btn"
                        style="color: black">
                        <i class="bi bi-clipboard-check icon"></i>
                        Teachers List
                    </button>
                </a>
            </div>
            <div
              class="tab"
              id="subjects-load"
              style="background-color: rgb(3, 0, 29)">
              <a href="subject_load.php">
                    <button
                        type="button"
                        class="selection-btn"
                        id="teachers-btn"
                        style="background-color: rgb(3, 0, 29)">

                        <i class="bi bi-clipboard-check icon"></i>
                        Subject Load
                    </button>
                </a>
              
              
              
            </div>
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
                    <button type="button" class="btn btn-success">+ Add new</button>
                  </a>

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
                    <?php
                    
                      $query = $con->prepare("SELECT * FROM teacher as t1
                      
                        -- INNER JOIN subject_schedule as t2 ON t1.teacher_id = t2.teacher_id
                        -- LEFT JOIN subject as t3 ON t3.subject_id = t2.subject_id
                        -- LEFT JOIN course as t4 ON t4.course_id = t3.course_id
                        ");

                      $query->execute();

                      if($query->rowCount() > 0){

                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

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
                                <a href='info.php?details=show&id=$teacher_id'>
                                  <button class='btn btn-primary'>View</button>
                                </a>
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
</div>

 
<?php include_once('../../includes/footer.php')?>


