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

        $subjectCodeAssignment = new SubjectCodeAssignment($con);
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);




    // print_r($allTeachingTopicIds);

    // echo $current_school_year_id;

    $teachingSubjectCode = $subjectCodeAssignment->GetTeacherTeachingSubjects($teacher_id,
        $current_school_year_id);

    $teachingTopicIdsArr = [];

    foreach ($teachingSubjectCode as $key => $value) {

        $teachingCode = $value['subject_code'];

        $allTeachingTopicIds = $subjectPeriodCodeTopic->GetAllsubjectPeriodCodeTopics(
            $teachingCode,
            $current_school_year_id);

        if(count($allTeachingTopicIds) > 0){
            
            foreach ($allTeachingTopicIds as $key => $topicIds) {
                array_push($teachingTopicIdsArr, $topicIds);

            }
        }
        // echo $teachingCode;
        // print_r($allTeachingTopicIds);
        // echo "<br>";
    }

    // print_r($teachingTopicIdsArr);
    // echo "<br>";

    $subjectCodeAssignmentIdsArr = [];

    foreach ($teachingTopicIdsArr as $key => $topicIds) {
        # code...
        // $assignmentsBasedFromSubjectTopic = $subjectCodeAssignment->GetAllAssignmentsBasedFromSubjectTopic($topicIds);
        $assignmentsBasedFromSubjectTopicList = $subjectCodeAssignment->GetAllAssignmentsBasedFromSubjectTopic($topicIds);
        // $assignmentsBasedFromSubjectTopicList = $subjectCodeAssignment->GetAllAssignmentOnTopicBased($topicIds);

        if(count($assignmentsBasedFromSubjectTopicList) > 0){

            foreach ($assignmentsBasedFromSubjectTopicList as $key => $assignmentList) {
                
                $subject_code_assignment_ids = $assignmentList['subject_code_assignment_id'];
                // echo $topicIds;
                // echo "<br>";
                array_push($subjectCodeAssignmentIdsArr,
                    $subject_code_assignment_ids);

                // echo "hey";
                // echo "<br>";
            }
        }

        // $subject_code_assignment_ids = $assignmentsBasedFromSubjectTopicList['subject_code_assignment_id'];
        // var_dump($subject_code_assignment_ids);
    }

    // print_r($subjectCodeAssignmentIdsArr);
    // echo "<br>";


    $ungradedSubmissionArr = [];

    if(count($subjectCodeAssignmentIdsArr) > 0){

        foreach ($subjectCodeAssignmentIdsArr as $key => $codeAssignmentId) {
            
            // echo $codeAssignmentId;
            // echo "<br>";
            $submissionList = $subjectAssignmentSubmission->GetSubmittedUngradedSubmission($codeAssignmentId);
            foreach ($submissionList as $key => $submissions) {
                # code...
                $subject_assignment_submission_id = $submissions['subject_assignment_submission_id'];
                array_push($ungradedSubmissionArr,
                    $subject_assignment_submission_id);
            }
        }

        // print_r($ungradedSubmissionArr);
    }

    // $ungradedSubmissionArr = [];
?>


<div class="content">

    <div class="row col-md-12">

        <div class="col-md-9">
            <br>
            <main>
                <div class="floating" id="shs-sy">
                    <header>
                        <div class="title">
                            <h4 style="font-weight: bold;" class="text-primary">Teaching Subject(s)</h4>
                        </div>
                    </header>
                    <main>
                        <?php if(count($teachingSubjectCode) > 0):?>
                            <table id="department_table" class="a" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>  
                                        <th>Code</th>
                                        <th>Section</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                        foreach ($teachingSubjectCode as $key => $row) {

                                            $subject_code = $row['subject_code'];
                                            $subject_title = $row['subject_title'];
                                            $course_id = $row['course_id'];
                                            $program_section = $row['program_section'];

                                            $class_url = "../class/index.php?c_id=$course_id&c=$subject_code";
                                        
                                            echo "
                                                <tr class='text-center'>
                                                    <td>
                                                        <a style='color: inherit' href='$class_url'>
                                                            $subject_title
                                                        </a>
                                                    </td>
                                                    <td>
                                                        $subject_code
                                                    </td>
                                                    <td>
                                                        <a style='all:unset; cursor: pointer' href=''>
                                                            $program_section
                                                        </a>
                                                    </td>
                                                </tr>
                                            ";
                                        }

                                    ?>
                                </tbody>
                            </table>
                        <?php else:?>
                            <div class="col-md-12">
                                <h4 class="text-center">No teaching subject within semester</h4>
                            </div>
                        <?php endif; ?>
                    </main>
                </div>
            </main>
        </div>
        <div class="col-md-3">

            <?php if(count($ungradedSubmissionArr)>0): ?>
                <div style="max-width: 100%;" class="mt-4">
                    <div id="todoAccordion">
                        <!-- Bootstrap Accordion Item -->
                        <div class="card">
                            <div class="card-header" id="todoAccordionHeading">
                                <h5 class="mb-0">

                                    <button class="btn btn-link" data-toggle="collapse"
                                            data-target="#todoAccordionCollapse" aria-expanded="true"
                                            aria-controls="todoAccordionCollapse">
                                        <?php echo "<h5 style='margin-bottom: 7px;'>(".count($ungradedSubmissionArr).") To check(s)</h5>"; ?>
                                    </button>

                                </h5>
                            </div>
                            <div id="todoAccordionCollapse" class="collapse" aria-labelledby="todoAccordionHeading"
                                data-parent="#todoAccordion">
                                <div class="card-body">
                                    <?php
                                    foreach ($ungradedSubmissionArr as $key => $submission_id) {

                                        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $submission_id);
                                        $subjectCodeAssignmentId = $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();
                                        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);
                                        $assignment_title = $subjectCodeAssignment->GetAssignmentName();
                                        $topicId = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
                                        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $topicId);
                                        $topicName = $subjectPeriodCodeTopic->GetTopic();
                                        $getSubjectCode = $subjectPeriodCodeTopic->GetSubjectCode();
                                        $courseId = $subjectPeriodCodeTopic->GetCourseId();

                                        if (!isset($topicCodeCount[$getSubjectCode])) {
                                            $topicCodeCount[$getSubjectCode] = [
                                                'count' => 1,
                                                'courseId' => $courseId,
                                                'teaching_code' => $getSubjectCode,
                                            ];
                                        } else {
                                            $topicCodeCount[$getSubjectCode]['count']++;
                                        }
                                    }

                                    foreach ($topicCodeCount as $getSubjectCode => $data) {
                                        $count = $data['count'];
                                        $teaching_code = $data['teaching_code'];
                                        $courseId = $data['courseId'];
                                    
                                        // $class_subject_url = "section_topic_grading.php?ct_id=$subject_period_code_topic_id";
                                        $class_subject_url = "../class/index.php?c_id=$courseId&c=$teaching_code";
                                        echo "
                                            <p style='margin:0'>Code:
                                                <a style='color:inherit' href='$class_subject_url'
                                                class='m-0 text-right'> $teaching_code ($count)</a>
                                            </p>
                                        ";
                                        echo "<br>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else:?>
                <div style="max-width: 100%;" class="mt-4">
                    <div id="todoAccordion">
                        <!-- Bootstrap Accordion Item -->
                        <div class="card">
                            <div class="card-header" id="todoAccordionHeading">
                                <h5 style="margin-bottom: 7px;">No to check(s)</h5>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endif;?>


          
        </div>
    </div>

</div>