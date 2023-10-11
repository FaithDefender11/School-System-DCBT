<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SchoolYear.php");

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];
    
     if (isset($_POST['type']) &&
        isset($_POST['firstname']) && isset($_POST['lastname']) 
        && isset($_POST['middle_name']) && isset($_POST['password']) 
        && isset($_POST['program_id']) && isset($_POST['civil_status']) && isset($_POST['nationality']) && isset($_POST['contact_number']) && isset($_POST['birthday']) && isset($_POST['age']) && isset($_POST['guardian_name']) && isset($_POST['guardian_contact_number']) && isset($_POST['sex']) && isset($_POST['student_status'])
        && isset($_POST['pending_enrollees_id'])
        && isset($_POST['address'])
        && isset($_POST['lrn'])
        && isset($_POST['enrollment_form_id'])
        && isset($_POST['selected_course_id'])
        && isset($_POST['religion'])
        && isset($_POST['birthplace'])
        && isset($_POST['email'])){

        $type = $_POST['type'];
        $type = strtolower($type);

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middle_name = $_POST['middle_name'];
        $password = $_POST['password'];
        $program_id = $_POST['program_id'];
        $civil_status = $_POST['civil_status'];
        $nationality = $_POST['nationality'];
        $contact_number = $_POST['contact_number'];
        $birthday = $_POST['birthday'];
        $age = $_POST['age'];
        $guardian_name = $_POST['guardian_name'];
        // $guardian_contact_number = $_POST['guardian_contact_number'];
        $sex = $_POST['sex'];
        $student_status = $_POST['student_status'];
        $pending_enrollees_id = $_POST['pending_enrollees_id']; 
        $address = $_POST['address']; 
        $lrn = $_POST['lrn']; 
        $selected_course_id = $_POST['selected_course_id']; 
        $enrollment_form_id = $_POST['enrollment_form_id']; 

        $religion = $_POST['religion']; 
        $birthplace = $_POST['birthplace']; 
        $email = $_POST['email']; 

        $default_course_level = 11;

        $student = new Student($con);

        $generateStudentUniqueId = $student->GenerateUniqueStudentNumber();
        $username = strtolower($lastname) . '.' . $generateStudentUniqueId . '@dcbt.ph';


        // echo "Type: " . $type . '<br>';
        // echo "First Name: " . $firstname . '<br>';
        // echo "Last Name: " . $lastname . '<br>';
        // echo "Middle Name: " . $middle_name . '<br>';
        // echo "Password: " . $password . '<br>';
        // echo "Program ID: " . $program_id . '<br>';
        // echo "Civil Status: " . $civil_status . '<br>';
        // echo "Nationality: " . $nationality . '<br>';
        // echo "Contact Number: " . $contact_number . '<br>';
        // echo "Birthday: " . $birthday . '<br>';
        // echo "Age: " . $age . '<br>';
        // echo "Guardian Name: " . $guardian_name . '<br>';
        // echo "Guardian Contact Number: " . $guardian_contact_number . '<br>';
        // echo "Sex: " . $sex . '<br>';
        // echo "Student Status: " . $student_status . '<br>';
        // echo "Pending Enrollees ID: " . $pending_enrollees_id . '<br>';
        // echo "Address: " . $address . '<br>';
        // echo "LRN: " . $lrn . '<br>';
        // echo "Selected Course ID: " . $selected_course_id . '<br>';
        // echo "Enrollment Form ID: " . $enrollment_form_id . '<br>';
        // echo "Religion: " . $religion . '<br>';
        // echo "Birthplace: " . $birthplace . '<br>';
        // echo "Email: " . $email . '<br>';

        // Check is student with the same 
        // first name, lastname and date of birth and email should not be the same.

        if($student->CheckEnrolleeExists($firstname, $lastname,
            $middle_name, $birthday, $email) == true){

            $data = array(
                'status' => "student_account_exist"
            );

            $jsonData = json_encode($data);
            echo $jsonData;

            return;
        }else{
            
        }

        $sql = "INSERT INTO student (firstname, lastname, middle_name, password, civil_status, nationality, contact_number, birthday, guardian_name, guardian_contact_number, sex, student_status,
                    course_id, student_unique_id, 
                    course_level, username, address, lrn, religion, birthplace, email,
                    admission_status, is_tertiary) 

                    VALUES (:firstname, :lastname, :middle_name, :password, :civil_status, :nationality, :contact_number, :birthday, :guardian_name, :guardian_contact_number, :sex, :student_status,
                    :course_id, :student_unique_id,
                    :course_level, :username, :address, :lrn, :religion, :birthplace, :email,
                    :admission_status, :is_tertiary)";
            
        $stmt_insert = $con->prepare($sql);
        
        $stmt_insert->bindParam(':firstname', $firstname);
        $stmt_insert->bindParam(':lastname', $lastname);
        $stmt_insert->bindParam(':middle_name', $middle_name);
        $stmt_insert->bindParam(':password', $password);
        // $stmt_insert->bindParam(':program_id', $program_id);
        $stmt_insert->bindParam(':civil_status', $civil_status);
        $stmt_insert->bindParam(':nationality', $nationality);
        $stmt_insert->bindParam(':contact_number', $contact_number);
        $stmt_insert->bindParam(':birthday', $birthday);
        // $stmt_insert->bindParam(':age', $age);
        $stmt_insert->bindParam(':guardian_name', $guardian_name);
        // $stmt_insert->bindParam(':guardian_contact_number', $guardian_contact_number);
        $stmt_insert->bindParam(':sex', $sex);
        $stmt_insert->bindParam(':student_status', $student_status);
        $stmt_insert->bindParam(':course_id', $selected_course_id);
        $stmt_insert->bindParam(':student_unique_id', $generateStudentUniqueId);
        $stmt_insert->bindParam(':course_level', $default_course_level);
        $stmt_insert->bindParam(':username', $username);
        $stmt_insert->bindParam(':address', $address);
        $stmt_insert->bindParam(':lrn', $lrn);
        $stmt_insert->bindParam(':religion', $religion);
        $stmt_insert->bindParam(':birthplace', $birthplace);
        $stmt_insert->bindParam(':email', $email);
        $stmt_insert->bindValue(':admission_status', "Standard");

        $stmt_insert->bindValue(':is_tertiary', $type === "shs" ? 0 : 1);

        # Student ID created, Enrollment ID created, Tightly Coupled.
        # If 1 is failed, then 2 should be failed
        # If 1 is success, then 2 SHOULD NOT be FAILED.

        if($stmt_insert->execute()){

            // echo "student create";
            
            $student_id = $con->lastInsertId();

            $student_exec = new Student($con, $student_id);
            $pending = new Pending($con);

            $enrollment_status = "tentative";
            $is_new_enrollee = 1;
            $registrar_evaluated = "yes";

            $get_student_username = $student_exec->GetUsername();

            if($student_id){


                $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
                    $student_id);

                $section = new Section($con, $selected_course_id);
                 

                $insert_enrollment = $con->prepare("INSERT INTO enrollment
                    (student_id, course_id, school_year_id, enrollment_status,
                        is_new_enrollee, registrar_evaluated, enrollment_form_id, is_tertiary)
                    VALUES (:student_id, :course_id, :school_year_id, :enrollment_status,
                        :is_new_enrollee, :registrar_evaluated, :enrollment_form_id, :is_tertiary)");

                $insert_enrollment->bindParam(':student_id', $student_id);
                $insert_enrollment->bindParam(':course_id', $selected_course_id);
                $insert_enrollment->bindParam(':school_year_id', $current_school_year_id);
                $insert_enrollment->bindParam(':enrollment_status', $enrollment_status);
                $insert_enrollment->bindParam(':is_new_enrollee', $is_new_enrollee);
                $insert_enrollment->bindParam(':registrar_evaluated', $registrar_evaluated);
                $insert_enrollment->bindParam(':enrollment_form_id', $enrollment_form_id);
                $insert_enrollment->bindValue(':is_tertiary', $type == "tertiary" ? 1 : 0);
              
                if($insert_enrollment->execute()){

                    $studentNumberInSection = $section->
                        GetTotalNumberOfStudentInSection($selected_course_id,
                            $current_school_year_id);

                    $capacity = $section->GetSectionCapacity();
                    $course_program_id = $section->GetSectionProgramId($selected_course_id);
                    $course_level = $section->GetSectionGradeLevel();
                    $program_section = $section->GetSectionName();

                    // if($studentNumberInSection >= $capacity){

                    //     # Update Previous Section into Is FULL.
                    //     $update_isfull = $section->SetSectionIsFull($selected_course_id);
                    //     # Create New Section

                    //     $new_program_section = $section->AutoCreateAnotherSection($program_section);

                    //     $createNewSection = $section->CreateNewSection($new_program_section, 
                    //         $course_program_id, $course_level,
                    //         $current_school_year_term);

                    //     if($createNewSection == true){

                    //         $createNewSection_Id = $con->lastInsertId();

                    //         $get_subject_program = $con->prepare("SELECT * 
                            
                    //             FROM subject_program

                    //             WHERE program_id=:program_id
                    //             AND course_level=:course_level
                    //             ");

                    //         $get_subject_program->bindValue(":program_id", $course_program_id);
                    //         $get_subject_program->bindValue(":course_level", $course_level);
                    //         $get_subject_program->execute();

                    //         if($get_subject_program->rowCount() > 0){

                    //             $isSubjectCreated = false;

                    //             $insert_section_subject = $con->prepare("INSERT INTO subject
                    //                 (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code)
                    //                 VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code)");

                    //             while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

                    //                 $program_program_id = $row['subject_program_id'];
                    //                 $program_course_level = $row['course_level'];
                    //                 $program_semester = $row['semester'];
                    //                 $program_subject_type = $row['subject_type'];
                    //                 $program_subject_title = $row['subject_title'];
                    //                 $program_subject_description = $row['description'];
                    //                 $program_subject_unit = $row['unit'];

                    //                 // $program_subject_code = $row['subject_code'] . $program_section; 
                    //                 $program_subject_code = $row['subject_code'];

                    //                 $insert_section_subject->bindValue(":subject_title", $program_subject_title);
                    //                 $insert_section_subject->bindValue(":description", $program_subject_description);
                    //                 $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
                    //                 $insert_section_subject->bindValue(":unit", $program_subject_unit);
                    //                 $insert_section_subject->bindValue(":semester", $program_semester);
                    //                 $insert_section_subject->bindValue(":program_id", $program_id);
                    //                 $insert_section_subject->bindValue(":course_level", $program_course_level);
                    //                 $insert_section_subject->bindValue(":course_id", $createNewSection_Id);
                    //                 $insert_section_subject->bindValue(":subject_type", $program_subject_type);
                    //                 $insert_section_subject->bindValue(":subject_code", $program_subject_code);

                    //                 // $insert_section_subject->execute();
                    //                 if($insert_section_subject->execute()){
                    //                     $isSubjectCreated = true;
                    //                     // echo "New Section $new_program_section is created and student has confirmed.";

                    //                 }
                    //             }
                    //             // if($isSubjectCreated == true){
                    //             //     // echo "Successfully populated subjects in course_id $course_id";
                    //             // }
                    //         }
                    //     }

                    // }

                    // Check enrollment course_id number with course_id capacity

                    // echo "pending student transfered to student table and insert in enrollment.";
                    // echo "New Student Pre-Enrollment Successfully Confirmed.";

                    $date_now = date('Y-m-d H:i:s');

                    $update_pending = $con->prepare("UPDATE pending_enrollees
                        SET student_status=:student_status,
                            date_approved=:date_approved
                        WHERE pending_enrollees_id=:pending_enrollees_id
                        ");
                    
                    $update_pending->bindValue(":student_status", "APPROVED");
                    $update_pending->bindValue(":date_approved", $date_now);
                    $update_pending->bindValue(":pending_enrollees_id", $pending_enrollees_id);
                    
                    if($update_pending->execute()){

                        // echo "Pending Approved";

                        $data = array(
                            'student_username' => $get_student_username,
                            'student_id' => $student_id
                        );
                        $jsonData = json_encode($data);

                        echo $jsonData;
                    }
                }
            }
        }

        // if(false){
        // if($stmt_insert->execute()){

        //     echo "student create";

        //     $student_id = $con->lastInsertId();

        //     $get_student_username = $enroll->GetStudentUsername($student_id);

        //     $student = new Student($con, $get_student_username);

        //     $enrollment_status = "tentative";
        //     $is_new_enrollee = 1;
        //     $registrar_evaluated = "yes";
        //     // $username = "generate";


        //     // echo $pending_enrollees_id;
        //     // echo "<br>";
        //     // echo $student_id;
        //     $update_parent = $pending->GetParentMatchPendingStudentId($pending_enrollees_id,
        //         $student_id);

        //     if($student_id){

        //         $insert_enrollment = $con->prepare("INSERT INTO enrollment
        //             (student_id, course_id, school_year_id, enrollment_status,
        //                 is_new_enrollee, registrar_evaluated, enrollment_form_id, is_tertiary)
        //             VALUES (:student_id, :course_id, :school_year_id, :enrollment_status,
        //                 :is_new_enrollee, :registrar_evaluated, :enrollment_form_id, :is_tertiary)");
                                    
        //         if($type == "tertiary"){

        //             $insert_enrollment->bindValue(':student_id', $student_id);
        //             $insert_enrollment->bindValue(':course_id', $selected_course_id);
        //             $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
        //             $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
        //             $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);
        //             $insert_enrollment->bindValue(':registrar_evaluated', $registrar_evaluated);
        //             $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
        //             $insert_enrollment->bindValue(':is_tertiary', $type == "tertiary" ? 1 : 0);
        //             //
        //         }
        //         // else{
                        
        //         //     $insert_enrollment->bindValue(':student_id', $student_id);
        //         //     $insert_enrollment->bindValue(':course_id', $selected_course_id);
        //         //     $insert_enrollment->bindValue(':school_year_id', $current_school_year_id);
        //         //     $insert_enrollment->bindValue(':enrollment_status', $enrollment_status);
        //         //     $insert_enrollment->bindValue(':is_new_enrollee', $is_new_enrollee);
        //         //     $insert_enrollment->bindValue(':registrar_evaluated', $registrar_evaluated);
        //         //     $insert_enrollment->bindValue(':enrollment_form_id', $enrollment_form_id);
        //         //     $insert_enrollment->bindValue(':is_tertiary', 0);

        //         // }

        //         if($insert_enrollment->execute()){

        //             // echo "iosert";
        //             // echo "insert_enrollment";

        //             $studentNumberInSection = $section->
        //                 GetTotalNumberOfStudentInSection($selected_course_id,
        //                     $current_school_year_id);

        //             $section_obj = $section->GetSectionObj($selected_course_id);

        //             $capacity = $section_obj['capacity'];
        //             $course_program_id = $section_obj['program_id'];
        //             $course_level = $section_obj['course_level'];
        //             $program_section = $section_obj['program_section'];

        //             if($studentNumberInSection >= $capacity){

        //                 # Update Previous Section into Is FULL.
        //                 $update_isfull = $section->SetSectionIsFull($selected_course_id);
        //                 # Create New Section

        //                 $new_program_section = $section->AutoCreateAnotherSection($program_section);

        //                 $createNewSection = $section->CreateNewSection($new_program_section, 
        //                     $course_program_id, $course_level,
        //                     $current_school_year_term);

        //                 if($createNewSection == true){

        //                     $createNewSection_Id = $con->lastInsertId();

        //                     $get_subject_program = $con->prepare("SELECT * 
                            
        //                         FROM subject_program

        //                         WHERE program_id=:program_id
        //                         AND course_level=:course_level
        //                         ");

        //                     $get_subject_program->bindValue(":program_id", $course_program_id);
        //                     $get_subject_program->bindValue(":course_level", $course_level);
        //                     $get_subject_program->execute();

        //                     if($get_subject_program->rowCount() > 0){

        //                         $isSubjectCreated = false;

        //                         $insert_section_subject = $con->prepare("INSERT INTO subject
        //                             (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code)
        //                             VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code)");

        //                         while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

        //                             $program_program_id = $row['subject_program_id'];
        //                             $program_course_level = $row['course_level'];
        //                             $program_semester = $row['semester'];
        //                             $program_subject_type = $row['subject_type'];
        //                             $program_subject_title = $row['subject_title'];
        //                             $program_subject_description = $row['description'];
        //                             $program_subject_unit = $row['unit'];

        //                             // $program_subject_code = $row['subject_code'] . $program_section; 
        //                             $program_subject_code = $row['subject_code'];

        //                             $insert_section_subject->bindValue(":subject_title", $program_subject_title);
        //                             $insert_section_subject->bindValue(":description", $program_subject_description);
        //                             $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
        //                             $insert_section_subject->bindValue(":unit", $program_subject_unit);
        //                             $insert_section_subject->bindValue(":semester", $program_semester);
        //                             $insert_section_subject->bindValue(":program_id", $program_id);
        //                             $insert_section_subject->bindValue(":course_level", $program_course_level);
        //                             $insert_section_subject->bindValue(":course_id", $createNewSection_Id);
        //                             $insert_section_subject->bindValue(":subject_type", $program_subject_type);
        //                             $insert_section_subject->bindValue(":subject_code", $program_subject_code);

        //                             // $insert_section_subject->execute();
        //                             if($insert_section_subject->execute()){
        //                                 $isSubjectCreated = true;
        //                                 // echo "New Section $new_program_section is created and student has confirmed.";

        //                             }
        //                         }
        //                         // if($isSubjectCreated == true){
        //                         //     // echo "Successfully populated subjects in course_id $course_id";
        //                         // }
        //                     }
        //                 }

        //             }

        //             // Check enrollment course_id number with course_id capacity

        //             // echo "pending student transfered to student table and insert in enrollment.";
        //             // echo "New Student Pre-Enrollment Successfully Confirmed.";

        //             $date_now = date('Y-m-d H:i:s');

        //             $update_pending = $con->prepare("UPDATE pending_enrollees
        //                 SET student_status=:student_status,
        //                     date_approved=:date_approved
        //                 WHERE pending_enrollees_id=:pending_enrollees_id
        //                 ");
        //             $update_pending->bindValue(":student_status", "APPROVED");
        //             $update_pending->bindValue(":date_approved", $date_now);
        //             $update_pending->bindValue(":pending_enrollees_id", $pending_enrollees_id);


        //             if($update_pending->execute()){

        //                 // echo "Pending to Approved";

        //                 $data = array(
        //                     'student_username' => $get_student_username,
        //                     'student_id' => $student_id
        //                 );
        //                 $jsonData = json_encode($data);

        //                 echo $jsonData;
        //             }
        //         }

        //     }
        // }


    }
?>