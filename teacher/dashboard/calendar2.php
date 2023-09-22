<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');

    ?>
        <head>
       
            <!-- <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js'></script>
            <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js'></script>
            <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script> -->
            

            <!-- CSS for full calender -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
            <!-- JS for jQuery -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <!-- JS for full calender -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
            <!-- bootstrap css and js -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        
        </head>
    
    <?php
        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];
?>


 <div id='calendar'></div>

<script>
    $(document).ready(function() {
        display_events();
    }); //end document.ready block
    // document.addEventListener('DOMContentLoaded', function() {

    //     var calendarEl = document.getElementById('calendar');

    //     var calendar = new FullCalendar.Calendar(calendarEl, {
    //         plugins: ['dayGrid'], // Include the dayGrid plugin
    //         initialView: 'dayGridMonth'
    //     });
    //     calendar.render();
    // });

    function display_events() {
    var events = new Array();

    $.ajax({
        url: 'calendar.php',  
        dataType: 'json',
        success: function (response) {
            
        var result=response.data;
        $.each(result, function (i, item) {
            events.push({
                event_id: result[i].event_id,
                title: result[i].title,
                start: result[i].start,
                end: result[i].end,
                color: result[i].color,
                url: result[i].url
            }); 	
        })
        var calendar = $('#calendar').fullCalendar({
            defaultView: 'month',
            timeZone: 'local',
            editable: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end) {
                    alert(start);
                    alert(end);
                    $('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
                    $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
                    $('#event_entry_modal').modal('show');
                },
            events: events,
            eventRender: function(event, element, view) { 
                element.bind('click', function() {
                        alert(event.event_id);
                    });
            }
            }); //end fullCalendar block	
        },//end success block
        error: function (xhr, status) {
        alert(response.msg);
        }
        });//end ajax block	
    }
</script>