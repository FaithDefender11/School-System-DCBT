<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $teacher_id = $_SESSION['teacherLoggedInId'];

    $back_url = "index.php";

    $subjectCodeAssignment = new SubjectCodeAssignment($con);

    $teachingSubjectCodeAnnouncement = $subjectCodeAssignment->GetTeacherTeachingSubjectsWithAnnouncement(
      $teacher_id,
      $current_school_year_id);

    // var_dump($teachingSubjectCodeAnnouncement);

?>

      <nav>
        <a href="<?php echo $back_url;?>">
          <i class="bi bi-arrow-return-left"></i>
          Back
        </a>
      </nav>

      <main>
        <div class="floating">
          <header>

            <div class="title">
                <div style="
                      display: flex;
                      flex-direction: row;
                      /* justify-content: end; */
                      justify-content: space-between;
                  " class="action">
                  <h3>Announcements Calendar</h3>
                  <!-- <button class='text-right btn btn-primary'>Add announcement</button> -->
                </div>
            </div>

          </header>
          <main>
            <div id="calendar"></div>
          </main>
        </div>
 
      </main>
    </div>
  <script>
    $(document).ready(function () {

    var teacherId = `
      <?php echo $teacher_id; ?>
    `;

    var current_school_year_id = `
      <?php echo $current_school_year_id; ?>
    `;

    display_events(teacherId, current_school_year_id);

    }); //end document.ready block

    function display_events(teacherId, current_school_year_id) {

    var events = []; // Initialize an empty array to store events

    $.ajax({
      url: `../../ajax/class/teacher_announcement_calendar.php?t_id=${teacherId}&sy_id=${current_school_year_id}`,

      dataType: 'json',

      success: function (response) {
        var result = response.data;

        console.log(response);
        // console.log(result);

        $.each(result, function (i, item) {

            events.push({
                subject_code_assignment_id: result[i].subject_code_assignment_id,
                title: result[i].title,
                start: result[i].start,
                end: result[i].end,
                color: result[i].color,
                url: result[i].url
                // url: ''
            });
        });

        var calendar = $('#calendar').fullCalendar({
          // eventLimit: false,
          // defaultView: 'list', 
          defaultView: 'month',
          timeZone: 'local',
          editable: true,
          selectable: true,
          selectHelper: true,

          select: function (startDate, endDate) {

            // console.log(startDate)
            // $('#event_start_date').val(moment(startDate).format('YYYY-MM-DD'));
            // $('#event_end_date').val(moment(endDate).format('YYYY-MM-DD'));
            // $('#event_entry_modal').modal('show');

          },

          events: events,

          eventRender: function (event, element, view) {

            element.bind('click', function () {

              // alert(event.subject_code_assignment_id);

            });

          },
          eventAfterRender: function (event, element, view) {
            // Modify the event title to display only the end date
            // element.find('.fc-title').text(moment(event.end).format('YYYY-MM-DD'));
          }
        }); // end fullCalendar block

      },
      error: function (xhr, status, error) {

        console.error('Error:', error);
        console.log('Status:', status);
        console.log('Response Text:', xhr.responseText);
        console.log('Response Code:', xhr.status);

      },
    }); // end ajax block
    }
  </script>
  </body>
</html>