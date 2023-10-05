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


<<<<<<< HEAD
    if(isset($_GET['id']) && isset($_GET['sy_id'])){
        
        $course_id = $_GET['id'];
        $school_year_id = $_GET['sy_id'];
=======
    $pendingEnrollmentCount = 0;
    $unionEnrollmentCount = 0;
    $waitingApprovalEnrollmentCount = 0;
    $enrolledStudentsEnrollmentCount = 0;


    $unionEnrollmentCount = count($unionEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
    $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);


    $selected_student_filter = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" 
        && isset($_POST["student_filter"])) {

        $selected_student_filters = $_POST["student_filter"];

        foreach ($selected_student_filters as $selected_filter) {
            // echo $selected_filter . "<br>";

          $selected_student_filter = $selected_filter;

        }
    }

    echo $selected_student_filter;
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
        </header>
    </div>

    <div class="tabs">
        <button
        class="tab"
        id="shsEvaluation"
        style="background-color: var(--theme); color: white"
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
        style="background-color: var(--mainContentBG); color: black"
        onclick="window.location.href = 'enrolled_students.php';"
        >
        Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
        </button>
    </div>
>>>>>>> ab36c1169e9eadbb944d7c8c6d0b4ebe6a6bd812

        $school_year = new SchoolYear($con, null);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        $enrollment = new Enrollment($con, null);
        $section = new Section($con, $course_id);

        $sectionName = $section->GetSectionName();

        // O.S Irregular, Pending New Standard, New Transferee
        $unionEnrollment = $enrollment->UnionEnrollment($current_school_year_id);
        $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
        $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
        $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);

        $sectionEnrolledStudentList = $section->GetCurrentSectionWithEnrolledStudent($current_school_year_id);
        
        $studentEnrolledInSectionList = $section->GetStudentsEnrolledInSection(
            $course_id, $school_year_id);

        

        $pendingEnrollmentCount = 0;
        $unionEnrollmentCount = 0;
        $waitingApprovalEnrollmentCount = 0;
        $enrolledStudentsEnrollmentCount = 0;

        $unionEnrollmentCount = count($unionEnrollment);
        $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
        $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
        $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);


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
                    onclick="window.location.href = 'enrolled_students.php';"
                    >
                    Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                    </button>
                </div>

                <main>


                    <div class="floating ">

                        <header>
                            <div class="title">
                                <h3><?php echo $sectionName ?></h3>
                            </div>
                        </header>

                        <main>
                            <?php if(count($studentEnrolledInSectionList) > 0):?>

                                <table style="width: 100%"class="a">
                                    <thead>
                                        <tr>
                                            <th>Student No</th>  
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Enrolled Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach ($studentEnrolledInSectionList as $key => $row) {


                                                $enrollement_student_id = $row['student_id'];
                                                
                                                $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
                                                // $standing = $row['course_level'];
                                                // $course_id = $row['course_id'];
                                                // $enrollment_course_id = $row['enrollment_course_id'];

                                                $enrollment_approve = $row['enrollment_approve'];

                                                $enrollment_approvex = date("F d, Y", strtotime($enrollment_approve));

                                                $email = $row['email'];
                                                $username = $row['username'];
                                                $student_unique_id = $row['student_unique_id'];
                                                $student_id = $row['student_id'];
                                                $program_section = $row['program_section'];

                                                $enrollment_approve = $row['enrollment_approve'];
                                                $enrollment_approvex = date("F d, Y", strtotime($enrollment_approve));

                                                $student_url  = "../student/record_details.php?id=$student_id&details=show";

                                                echo "
                                                    <tr>
                                                        <td>
                                                            <a style='color: inherit;' href='$student_url'>
                                                                $student_unique_id
                                                            </a>
                                                        </td>
                                                        <td>$fullname</td>
                                                        <td>$email</td>
                                                        <td>$enrollment_approvex</td>
                                                        <td></td>
                                                    </tr>
                                                ";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            <?php endif;?>
                            


                        </main>

                    </div>
                                    
                    <div class="floating ">
                        <header>
                            <div class="title">
                                <h3>Form details</h3>
                            </div>

                            <div class="action">
                                <form style="display: flex;" method="POST" id="student_filter_form">
                                    <div style="margin-right: 15px;" class="form-group">
                                        <label for="new">New</label>
                                        <input type="checkbox" id="new" name="student_filter[]" value="New" class="form-control" onchange="handleCheckboxChange('new')" <?php if (isset($_POST["student_filter"]) && in_array("New", $_POST["student_filter"])) echo "checked"; ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="old">Old</label>
                                        <input type="checkbox" id="old" name="student_filter[]" value="Old" class="form-control" onchange="handleCheckboxChange('old')" <?php if (isset($_POST["student_filter"]) && in_array("Old", $_POST["student_filter"])) echo "checked"; ?>>
                                    </div>
                                </form>
                            </div>
                          
                        </header>

                        <main>
            
                            <table>
                                <tr>
                                <th style="border-right: 2px solid black">Search by</th>
                                <td><button>Student ID</button></td>
                                <td><button >Name</button></td>
                                <td><button>Email</button></td>
                                <td><button style="font-weight: bold;" class="btn-sm btn btn-info">Section</button></td>
                                </tr>
                            </table>

                        

                            <table style="width: 100%" id="enrolled_students_table" class="a">
                                <thead>
                                    <tr>
                                        <th>Student No</th>  
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Type</th>  
                                        <th>Section</th>
                                        <th>Enrolled Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </main>

                    </div>

                </main>
            </div>

        <?php

    }

?>  




<script>

    function submitForm() {
      document.getElementById("student_filter_form").submit();
    }

    function handleCheckboxChange(checkboxId) {
        if (checkboxId === "new") {
            if (document.getElementById("old").checked) {
                document.getElementById("old").checked = false;
            }
        } else if (checkboxId === "old") {
            if (document.getElementById("new").checked) {
                document.getElementById("new").checked = false;
            }
        }
        document.getElementById("student_filter_form").submit();
    }


    $(document).ready(function() {

        var selected_student_filter = `
            <?php echo $selected_student_filter; ?>
        `;

        selected_student_filter = selected_student_filter.trim();

        var table = $('#enrolled_students_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                // 'url': `enrolledStudentListData.php?status=${selected_student_filter}`,
                
                'url': `enrolledStudentListData.php?admission_type_filter=${selected_student_filter}`,
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
            { data: 'student_id', orderable: true },  
            { data: 'name', orderable: false },  
            { data: 'email', orderable: true },
            { data: 'type', orderable: false },
            { data: 'section_name', orderable: false },  
            { data: 'enrollment_approve', orderable: true },
            { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });
    });
    
</script>






<?php include_once('../../includes/footer.php') ?>
