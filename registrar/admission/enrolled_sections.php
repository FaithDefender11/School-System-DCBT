<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');


?>  
    <head>

        <style>
            .show_search{
                position: relative;
                /* margin-top: -38px;
                margin-left: 215px; */
            }
            div.dataTables_length {
                display: none;
            }

            #enrolled_students_table_filter{
            margin-top: 12px;
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: start;
            }

            #enrolled_students_table_filter input{
            width: 250px;
            }
        </style>

        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    </head>

<?php

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $enrollment = new Enrollment($con, null);
    $section = new Section($con, null);

    // O.S Irregular, Pending New Standard, New Transferee
    $unionEnrollment = $enrollment->UnionEnrollment($current_school_year_id);
    $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
    $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);

    $sectionEnrolledStudentList = $section->GetCurrentSectionWithEnrolledStudent($current_school_year_id);

    // var_dump($sectionEnrolledStudentList);

    $pendingEnrollmentCount = 0;
    $unionEnrollmentCount = 0;
    $waitingApprovalEnrollmentCount = 0;
    $enrolledStudentsEnrollmentCount = 0;


    $unionEnrollmentCount = count($unionEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
    $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);
    $sectionEnrolledStudentListCount = count($sectionEnrolledStudentList);


    $selected_student_filter = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" 
        && isset($_POST["student_filter"])) {

        $selected_student_filters = $_POST["student_filter"];

        foreach ($selected_student_filters as $selected_filter) {
            // echo $selected_filter . "<br>";

          $selected_student_filter = $selected_filter;

        }
    }

    // echo $selected_student_filter;
    $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

?>

<div class="content">
     
    <div class="content-header">
        <header>
        <div class="title">
            <h1>Enrollment form <em>SHS</em></h1>
            <small
            >Note: Numbers on tabs only count current school year and
            semester</small
            >
        </div>
        <h5><?php echo $current_school_year_term; ?> <span><?php echo $period_short; ?></span></h5>

        </header>
    </div>

    <div class="tabs">
        <button
        class="tab"
        id="shsEvaluation"
        style="background-color: var(--them)"
        onclick="window.location.href = 'evaluation.php';"
        >
        Evaluation (<?php echo $unionEnrollmentCount;?>)
        </button>
        
        <button
            class="tab" id="shsPayment"
            style="background-color: var(--them); color: white"
            onclick="window.location.href = 'waiting_payment.php';">
            Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
        </button>

        <button
            class="tab"
            id="shsApproval"
            style="background-color: var(--them); color: white"
            onclick="window.location.href = 'waiting_approval.php';">
            Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
        </button>
        <button
        class="tab"
        id="shsEnrolled"
        style="background-color: var(--mainContentBG); color: white"
        onclick="window.location.href = 'enrolled_sections.php';"
        >
        Enrolled (<?php echo $sectionEnrolledStudentListCount;?>)
        </button>
    </div>

    <main>


       <div class="floating ">
            <header>

                <div class="title">
                    <h3>Section details</h3>
                </div>

            </header>

            <main>
                <?php if(count($sectionEnrolledStudentList) > 0):?>

                    <table style="width: 100%" id="enrolled_section_list" class="a" >
                        
                        <thead>
                            <tr>
                                <th>Section</th>  
                                <th>Student</th>
                                <th>Capacity</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 

                                foreach ($sectionEnrolledStudentList as $key => $value) {

                                    $program_section = $value['program_section'];
                                    $course_id = $value['course_id'];
                                    $capacity = $value['capacity'];
                                    $school_year_id = $value['school_year_id'];

                                    $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);
                                    
                                    echo "
                                        <tr>
                                            <td>
                                                <a href='enrolled_students.php?id=$course_id&sy_id=$school_year_id' style='color: inherit'>
                                                    $program_section
                                                </a>
                                            </td>
                                            <td>$totalStudent</td>
                                            <td>$capacity</td>
                                        </tr>
                                    ";
                                }
                            ?>

                        </tbody>
                    </table>
                <?php endif;?>

            </main>

        </div>
    </main>
</div>

<script>

                                
    $(document).ready(function() {
     
        var table = $('#enrolled_section_list').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                
                'url': 'enrolledSectionListData.php',
                
                // 'success': function(data) {
                //     // Handle success response here
                //     console.log('Success:', data);
                // },
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },
            // 'pageLength': 2,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data for enrolled students."
            },
            'columns': [
            { data: 'program_section', orderable: true },  
            { data: 'student_count', orderable: false },  
            { data: 'capacity', orderable: true }
            ],
            'ordering': true
        });
    });
    
</script>






<?php include_once('../../includes/footer.php') ?>
