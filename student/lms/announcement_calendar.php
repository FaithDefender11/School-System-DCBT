<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/StudentSubject.php');
    require_once("../../includes/classes/Announcement.php");

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $student_id = $_SESSION['studentLoggedInId'];


    $enrollment = new Enrollment($con);
    $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
        $current_school_year_id);

    
    $announcement = new Announcement($con);

    $studentSubject = new StudentSubject($con);

    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
        ($studentLoggedInId, $current_school_year_id, $enrollment_id);

    $enrolledSubjectList = [];

    foreach ($allEnrolledSubjectCode as $key => $value) {
        # code...
        $subject_code = $value['student_subject_code'];
        array_push($enrolledSubjectList, $subject_code);
    }

    $getAllAnnouncementOnMyEnrolledSubjects = $announcement->GetAllTeacherAnnouncementUnderEnrolledSubjects(
        $current_school_year_id, $enrolledSubjectList);

    $getAllAnnouncementFromAdmin = $announcement->GetAllAnnouncementFromAdmin(
        $current_school_year_id);

    $mergeAnnouncement = array_merge($getAllAnnouncementFromAdmin,
        $getAllAnnouncementOnMyEnrolledSubjects);

    # List of all Enrolled Subject subject_period_code_topic_id(s)
?>

            <nav>
              <a href="student_dashboard.php"
                ><i class="bi bi-arrow-return-left"></i>Back</a
              >
            </nav>

            <main>
              <div class="floating">
                <header>
                  <div class="title">
                    <h3>Announcement Calendar</h3>
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

              let studentId = `
                <?php echo $student_id; ?>
              `;

              let current_school_year_id = `
                <?php echo $current_school_year_id; ?>
              `;

              let enrollment_id = `
                <?php echo $enrollment_id; ?>
              `;

              studentId = studentId.trim();
              current_school_year_id = current_school_year_id.trim();
              enrollment_id = enrollment_id.trim();

              display_events(studentId, current_school_year_id, enrollment_id);

            }); //end document.ready block

            function display_events(studentId, current_school_year_id, enrollment_id) {

              var events = []; // Initialize an empty array to store events

              $.ajax({
                url: `../../ajax/class/announcement_calendar.php?st_id=${studentId}&sy_id=${current_school_year_id}&e_id=${enrollment_id}`,

                dataType: 'json',

                success: function (response) {

                  var result = response.data;

                  console.log(response);
                  // console.log(result);

                  $.each(result, function (i, item) {

                      events.push({
                          announcement_id: result[i].announcement_id,
                          title: result[i].title,
                          start: result[i].start,
                          end: result[i].end,
                          // color: result[i].color,
                          url: result[i].url
                          // url: ''
                      });
                  });

                  var calendar = $('#calendar').fullCalendar({
                  
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
                  }); 

                },
                error: function (xhr, status, error) {

                  console.error('Error:', error);
                  console.log('Status:', status);
                  console.log('Response Text:', xhr.responseText);
                  console.log('Response Code:', xhr.status);

                },
              });
            }
          </script>
        </body>
      </html>