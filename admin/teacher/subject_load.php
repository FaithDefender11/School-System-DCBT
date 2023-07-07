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
              Tertiary
            </button>
          </div>
        </div>
      </nav>

      <div class="content-header"></div>
      
      <div class="tabs">

          <a class="tab" href="index.php">
            <button id="teachers-list">
                <i class="bi bi-clipboard-check icon"></i>
                  Teachers List
            </button>
          </a>
          <a style="background-color: #d6cdcd;" class="tab" href="subject_load.php">
            <button style="background-color: #d6cdcd;" id="teachers-list">
                <i class="bi bi-collection icon"></i>
                  Subjects Load
            </button>
          </a>
      </div>

      <main>
        <div class="floating" id="shs-teachers">
          <header>
            <div class="title">
              <h3>Subject Loader</h3>
            </div>
            <div class="action">
              <a href="../schedule/create.php">
                <button type="button" class="default large">
                  <i style="color: white;" class="fas fa-plus-circle"></i> Schedule</button>
              </a>
            </div>
          </header>
          <main>
            <table
              id="subject_loader_view_table"
              class="ws-table-all cw3-striped cw3-bordered"
              style="margin: 0"
            >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Subject ID</th>
                  <th>Subject Title</th>
                  <th>Section</th>
                  <th>S.Y - Semester</th>
                  <th>Schedule</th>
                  <th style="width: 115px">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                
                  $query = $con->prepare("SELECT 
                    t1.*, t2.*, 
                    t3.subject_title, t3.subject_id AS subjectId,

                    t3.course_id,

                    t4.program_section,
                    t4.course_level,
                    t5.term,
                    t5.period


                    FROM teacher AS t1
                  
                    INNER JOIN subject_schedule AS t2 ON t1.teacher_id = t2.teacher_id

                    INNER JOIN subject AS t3 ON t3.subject_id = t2.subject_id
                    INNER JOIN course AS t4 ON t4.course_id = t3.course_id
                    INNER JOIN school_year AS t5 ON t5.school_year_id = t2.school_year_id

                    ");

                  $query->execute();

                  if($query->rowCount() > 0){

                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                      $teacher_id = $row['teacher_id'];
                      $subject_schedule_id = $row['subject_schedule_id'];

                      $fullname = $row['firstname'] . " " . $row['lastname'];
                      $teacher_status = $row['teacher_status'];
                      $date_creation = $row['date_creation'];

                      $schedule_time = $row['schedule_time'];
                      $subject_title = $row['subject_title'];
                      $subjectId = $row['subjectId'];
                      $program_section = $row['program_section'];
                      $course_level = $row['course_level'];
                      $course_id = $row['course_id'];
                      $term = $row['term'];
                      $period = $row['period'];

                    //   $program_section = "";
                    //   $course_level = "";


                      $date_creation = $date_creation !== NULL ? date('Y-m-d', strtotime($date_creation)) : 'Not Set';

                      $fullname = $row['firstname'] . " " . $row['lastname'];

                      $subject_load_count = $teacher->GetTeacherSubjectLoad($teacher_id);

                      $teacher_schedule_url = "../schedule/assign.php?id=$teacher_id";

                      $removeSubjectLoadBtn = "removeSubjectLoadBtn($subject_schedule_id)";

                      echo "
                        <tr>
                          <td>$fullname</td>
                          <td>$subjectId</td>
                          <td>$subject_title</td>
                          <td>$program_section</td>
                          <td>$term - $period</td>
                          <td>$schedule_time</td>
                          <td>
                            <div class='dropdown'>
                              <button class='btn btn-info dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                Actions
                              </button>

                              <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' href='info.php?details=show&id=$teacher_id'>
                                  <button style='width: 100%' class='btn btn btn-primary'>
                                    View
                                  </button>

                                </a>
                                <a class='dropdown-item' href='$teacher_schedule_url'>
                                  <button style='width: 100%'  class='btn btn btn-success'>
                                    Add Schedule
                                  </button>
                                </a>
                                <a class='dropdown-item' href='#'>
                                   <button style='width: 100%' onclick='$removeSubjectLoadBtn'
                                      type='button'
                                        class='btn btn-danger btn'>
                                        Remove
                                    </button>
                                </a>
                              </div>

                            </div>
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
 
<?php include_once('../../includes/footer.php')?>

<script>

    function removeSubjectLoadBtn(subject_schedule_id){
        Swal.fire({
                icon: 'question',
                title: `Do you want to remove Schedule ID: #${subject_schedule_id}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                // console.log('qwe');

                if (result.isConfirmed) {
                    $.ajax({
                        url: "../../ajax/schedule/remove_schedule.php",
                        type: 'POST',
                        data: {
                            subject_schedule_id
                        },
                        success: function(response) {
                          response = response.trim();

                            // console.log(response);
                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Removed`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                $('#subject_loader_view_table').load(
                                    location.href + ' #subject_loader_view_table'
                                );
                            });
                          }

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
</script>



