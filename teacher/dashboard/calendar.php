
<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');


    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $teacher_id = $_SESSION['teacherLoggedInId'];

?>

<!DOCTYPE html>
<html>

  <head>

    <title>My Calendar</title>
    <!-- *Note: You must have internet connection on your laptop or pc other wise below code is not working -->
    <!-- CSS for full calender -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css"
      rel="stylesheet"
    />
    <!-- JS for jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- JS for full calender -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <!-- bootstrap css and js -->
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  

    <style>

    .fc-content {
   width: auto !important;
    white-space: normal !important;
    overflow: visible !important;
    }
    </style>
  </head>

<body>

    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h4 style="font-weight: bold;" class="mt-2 text-primary text-center">
            My Calendar
          </h4>
          <div id="calendar"></div>
        </div>
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

    <br />
</body>

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
      url: `../../ajax/class/teacher_calendar.php?t_id=${teacherId}&sy_id=${current_school_year_id}`,

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

  // function save_event(){

  //     var event_name=$("#event_name").val();
  //     var event_start_date=$("#event_start_date").val();
  //     var event_end_date=$("#event_end_date").val();
  //     if(event_name=="" || event_start_date=="" || event_end_date=="")
  //     {
  //     alert("Please enter all required details.");
  //     return false;
  //     }
  //     $.ajax({
  //     url:"save_event.php",
  //     type:"POST",
  //     dataType: 'json',
  //     data: {event_name:event_name,event_start_date:event_start_date,event_end_date:event_end_date},
  //     success:function(response){
  //     $('#event_entry_modal').modal('hide');
  //     if(response.status == true)
  //     {
  //         alert(response.msg);
  //         location.reload();
  //     }
  //     else
  //     {
  //         alert(response.msg);
  //     }
  //     },
  //     error: function (xhr, status) {
  //     console.log('ajax error = ' + xhr.statusText);
  //     alert(response.msg);
  //     }
  //     });

  //     return false;

  // }
</script>


</html>
