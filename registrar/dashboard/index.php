<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');
    
    $school_year = new SchoolYear($con);
    $section = new Section($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];
 
    $processEnrolled = true;
    $current_enrollment_course_id = 1281;

    $originalValueIsFalse = false;


    $course_fulled_ids = $section->GetSectionWhoReachedTheMaximumCapacityOnEnrollment($current_school_year_id);
    
    // $course_fulled_ids = [1281, 1289];
    // print_r($course_fulled_ids);

    // Create an array of placeholders for the course_ids

    $placeholders = array_map(function ($id) {
        return ":course_id_$id";
    }, $course_fulled_ids);

    // print_r($placeholders);

    $query = $con->prepare("SELECT course_id FROM course

        WHERE program_id = :program_id
        AND course_id NOT IN (" . implode(',', $placeholders) . ")

        AND program_section != :program_section
        AND course_level = :course_level
        AND active = 'yes'
        AND is_full = 'no'
        AND school_year_term = :school_year_term
    ");

    $query->bindValue(":program_id", 25);
    $query->bindValue(":program_section", "ABE1-A");
    $query->bindValue(":course_level", 1);
    $query->bindValue(":school_year_term", $current_school_year_term);

    // Bind each course_id from the array

    foreach ($course_fulled_ids as $course_id) {
        $paramName = ":course_id_$course_id";
        $query->bindValue($paramName, $course_id);
    }

    $query->execute();

    if ($query->rowCount() > 0) {
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        print_r($res);
    }






    $reachedMaxEnrollmentArr = [];

    $query = $con->prepare("SELECT t1.* FROM enrollment as t1 


        WHERE t1.registrar_id=:registrar_id
        AND t1.enrollment_status=:enrollment_status
        AND t1.school_year_id = :school_year_id

    ");

    $query->bindValue(":registrar_id", $registrarUserId);
    $query->bindValue(":enrollment_status", "tentative");
    $query->bindValue(":school_year_id", $current_school_year_id);
    $query->execute();

    if($query->rowCount() > 0){

        while($row = $query->fetch(PDO::FETCH_ASSOC)){

            $course_id = $row['course_id'];
            $enrollment_id = $row['enrollment_id'];
            $school_year_id = $row['school_year_id'];
            $school_year_id = $row['school_year_id'];


            $section = new Section($con, $course_id);
            $sectionCapacity = $section->GetSectionCapacity();


            $count = $section->GetEnrollmentCourseIdEnrolledCount($course_id, $school_year_id);

            if($sectionCapacity == $count){

                array_push($reachedMaxEnrollmentArr, $row);

            }
        }
    }

    // print_r($reachedMaxEnrollmentArr);

    # You placed the student to te ABE1-A Capacity: 3
    # ABE1-A has already 3 enrolled student

    # Show that student which you have been processed.




 

?>

<div class="content">

    <?php echo Helper::RegistrarDepartmentSection(false,
        "shs_index", "tertiary_index");?>

    <main>
        <div class="floating" id="shs-sy">

            <header>

                <div class="title">
                    <h3 style="font-weight: bold;">My processed enrollment form</h3>
                </div>
            </header>

            <main>
                <table id="shs_program_table"
                    class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Applying for</th>
                            <th>Maximum</th>
                            <th>Enrolled</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                            foreach ($reachedMaxEnrollmentArr as $key => $row) {

                                $db_student_id = $row['student_id'];

                                $student = new Student($con, $db_student_id);

                                $db_course_id = $row['course_id'];
                                $db_enrollment_id = $row['enrollment_id'];
                                $db_enrollment_form_id= $row['enrollment_form_id'];
                                $db_school_year_id= $row['school_year_id'];

                                $section = new Section($con, $db_course_id);

                                $programSection = $section->GetSectionName();
                                $capacity = $section->GetSectionCapacity();

                                $studentName = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());
                                $studentUniqueId = $student->GetStudentUniqueId();


                                // $removeProgramBtn = "removeProgramBtn($program_id)";

                                $url = "../admission/subject_insertion_summary.php?id=$db_enrollment_id&enrolled_subject=show";
                                
                                $count = $section->GetTotalNumberOfStudentInSection($db_course_id, $db_school_year_id);
                                
                                echo "

                                    <tr>
                                        
                                        <td>$db_enrollment_form_id</td>
                                        <td>$studentName</td>
                                        <td>$programSection</td>
                                        <td>$capacity</td>
                                        <td>$count</td>
                                        
                                        <td>
                                            <a href='$url'>
                                                <button class='btn-sm btn btn-primary'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                
                                ";
                            }

                        ?>
                    </tbody>
                </table>
            </main>
        </div>
    </main>

</div>
<?php include_once('../../includes/footer.php') ?>
