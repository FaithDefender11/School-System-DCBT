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
                    style='background-color: var(--theme); color: white'
                    onclick=\"window.location.href = 'index.php';\">
                    <i class='bi bi-clipboard-check'></i>
                    Teacher List
                </button>
            ";

            echo "
                <button class='tab' 
                    id='shsPayment'
                    style='background-color: var(--mainContentBG); color: black'
                    onclick=\"window.location.href = 'subject_schedule.php';\">
                    <i class='bi bi-book'></i>
                    Teacher Schedule
                </button>
            ";
        ?>
      </div>

      <main>
        <div class="floating">
          <header>
            <div class="title">
              <h3>Subject Loader</h3>
            </div>
            <div class="action">
              <a href="../schedule/create.php">
                <button type="button" class="default large">
                  Add Schedule</button>
              </a>
            </div>
          </header>
          <main>
            <table
              id="subject_loader_view_table"
              class="a"
              style="margin: 0"
            >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Subject</th>
                  <!-- <th>Code</th> -->
                  <th>Section</th>
                  <th>Days</th>
                  <th>Schedule</th>
                  <th>S.Y - Semester</th>
                  <th>Room</th>
                  <th style="width: 115px">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = $con->prepare("SELECT 
                    t1.subject_schedule_id,
                    t1.course_id AS subject_schedule_course_id,
                    t1.subject_program_id AS subject_subject_program_id,
                    t1.time_from,
                    t1.time_to,
                    t1.schedule_day,
                    t1.schedule_time,
                    t1.teacher_id,
                    t1.course_id, t1.subject_code,

                    t2.firstname,
                    t2.lastname,
                    t2.teacher_status,
                    t2.date_creation,

                    t4.program_section,
                    t4.course_level,
                    t4.course_id as courseCourseId,

                    t3.subject_title,
                    t3.subject_program_id,
                    t3.subject_code AS sp_subject_code,

                    t5.period,
                    t5.term,

                    t6.room_number,
                    t6.room_name

                    
                    FROM subject_schedule as t1
                    INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
                    
                    LEFT JOIN subject_program as t3 ON t3.subject_program_id = t1.subject_program_id
                    LEFT JOIN course as t4 ON t4.course_id = t1.course_id
                    LEFT JOIN school_year AS t5 ON t5.school_year_id = t1.school_year_id
                    LEFT JOIN room AS t6 ON t6.room_id = t1.room_id

                    ORDER BY t1.day_count ASC

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
                        $schedule_day = $row['schedule_day'];
                        $program_section = $row['program_section'];
                        $course_level = $row['course_level'];
                        $course_id = $row['course_id'];
                        $term = $row['term'];
                        $period = $row['period'];
                        $subject_code = $row['subject_code'];

                        $room_number = $row['room_number'];
                        if($room_number === NULL){
                          $room_number = "TBA";
                        }
                        $room_name = $row['room_name'];

                         

                        //   $program_section = "";
                        //   $course_level = "";

                        $edit = "subject_schedule_edit.php?s_id=$subject_schedule_id";

                        $info = "info.php?details=show&id=$teacher_id";

                        $period = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "" );

                        echo "
                          <tr>
                            <td>
                              <a href='$info' style='color: inherit'>
                                $fullname
                              </a>
                            </td>
                            <td>$subject_title</td>
                            <td>$program_section</td>
                            <td>$schedule_day</td>
                            <td>$schedule_time</td>
                            <td>$term - $period</td>
                            <td>$room_number</td>
                            <td>
                              <button class='btn btn-sm btn-primary'
                              onclick='window.location.href =  \"$edit\" '
                              >
                                
                                <i class='fas fa-marker'></i>
                              </button>
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



