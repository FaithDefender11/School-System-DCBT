<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/StudentSubject.php');


    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $student_id = $_SESSION['studentLoggedInId'];


    $enrollment = new Enrollment($con);
    $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
        $current_school_year_id);

    # List of all Enrolled Subject subject_period_code_topic_id(s)
?>

            <nav>
              <a href="student_dashboard.php"
                ><i class="bi bi-arrow-return-left"></i>Back</a
              >
            </nav>

            <main>
              <div class="bars">
                <div class="floating">
                  <main>
                    <div id="calendar"></div>
                  </main>
                </div>
              </div>

              <!-- Start popup dialog box -->
              <!-- <div
                class="modal fade"
                id="event_entry_modal"
                tabindex="-1"
                role="dialog"
                aria-labelledby="modalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalLabel">Add New Event</h5>
                      <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>

                    <div class="modal-body">
                      <div class="img-container">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label for="event_name">Event name</label>
                              <input
                                type="text"
                                name="event_name"
                                id="event_name"
                                class="form-control"
                                placeholder="Enter your event name"
                              />
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="event_start_date">Event start</label>
                              <input
                                type="date"
                                name="event_start_date"
                                id="event_start_date"
                                class="form-control onlydatepicker"
                                placeholder="Event start date"
                              />
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                              <label for="event_end_date">Event end</label>
                              <input
                                type="date"
                                name="event_end_date"
                                id="event_end_date"
                                class="form-control"
                                placeholder="Event end date"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button
                        type="button"
                        class="btn btn-primary"
                        onclick="save_event()"
                      >
                        Save Event
                      </button>
                    </div>
                  </div>
                </div>

              </div> -->

              <!-- End popup dialog box -->
            </main>
          </div>
          <script>

            $(document).ready(function () {

              var studentId = `
                <?php echo $student_id; ?>
              `;

              var current_school_year_id = `
                <?php echo $current_school_year_id; ?>
              `;

              var enrollment_id = `
                <?php echo $enrollment_id; ?>
              `;


              display_events(studentId, current_school_year_id, enrollment_id);

            }); //end document.ready block

            function display_events(studentId, current_school_year_id, enrollment_id) {

              var events = []; // Initialize an empty array to store events

              $.ajax({
                url: `../../ajax/class/student_calendar.php?st_id=${studentId}&sy_id=${current_school_year_id}&e_id=${enrollment_id}`,

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
                          // color: result[i].color,
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
