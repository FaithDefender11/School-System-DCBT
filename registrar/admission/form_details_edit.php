<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/StudentParent.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/EnrollmentAudit.php');
    include_once('../../includes/classes/User.php');


    
    if($_GET['id']){


        $pendingEnroleee = new Pending($con);

        $student_id = $_GET['id'];

        $pending = new Student($con, $student_id);

        $student_email = $pending->GetEmail();
        $student_firstname = $pending->GetFirstName();
        $student_lastname = $pending->GetLastName();
        $student_middle_name = $pending->GetMiddleName();
        $student_suffix = $pending->GetSuffix();

        $student_civil_status = $pending->GetCivilStatus();
        $student_nationality = $pending->GetNationality();
        $student_religion = $pending->GetReligion();
        $student_email = $pending->GetEmail();
        $student_status_st = $pending->GetStudentStatus();
        $student_current_course_id = $pending->GetStudentCurrentCourseId();
        $student_contact_number = $pending->GetContactNumber();
        $student_birthday = $pending->GetStudentBirthdays();
        $student_birthplace = $pending->GetStudentBirthPlace();
        $student_gender = $pending->GetStudentGender();
        $student_address = $pending->GetStudentAddress();
        $type_status = $pending->GetIsTertiary();

        // var_dump($student_birthday);

        $student_lrn = $pending->GetStudentLRN();

        // var_dump($student_lrn);

        $get_student_new_pending_id = $pendingEnroleee->GetPendingAccountByStudentTable(
            $student_email, $student_firstname, $student_lastname);

        // var_dump($get_student_new_pending_id);

        // $parent = new StudentParent($con, $student_id);

        $parent = new PendingParent($con, $get_student_new_pending_id);

        $pending_enrollee = new Pending($con, $get_student_new_pending_id);

        $student_parent = new StudentParent($con, $student_id);

        $parent_id = $parent->GetParentID();

        // var_dump($parent_id);

        $parent_firstname = $parent->GetFirstName();
        $parent_lastname = $parent->GetLastName();
        $parent_middle_name = $parent->GetMiddleName();
        $parent_suffix = $parent->GetSuffix();
        $parent_contact_number = $parent->GetContactNumber();
        $parent_email = $parent->GetEmail();
        $parent_occupation = $parent->GetOccupation();
        $parent_relationship = $parent->GetGuardianRelationship();


        $school_name = $parent->GetSchoolName();
        $school_address = $parent->GetSchoolAddress();
        $year_started = $parent->GetSchoolYearStarted();
        $year_ended = $parent->GetSchoolYearEnded();

        $enrollment = new Enrollment($con);
        
        $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            $current_school_year_id);

        $user = new User($con, $registrarUserId);
        $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());


        if (
            $_SERVER['REQUEST_METHOD'] === "POST" &&
            isset($_POST['update_enrollment_' . $student_id]) &&
            isset($_POST['lastName']) &&
            isset($_POST['firstName']) &&
            isset($_POST['middleName']) &&
            isset($_POST['suffixName']) &&
            isset($_POST['status']) &&
            isset($_POST['citizenship']) &&
            isset($_POST['gender']) &&
            isset($_POST['birthdate']) &&
            isset($_POST['birthplace']) &&
            isset($_POST['religion']) &&
            isset($_POST['address']) &&
            isset($_POST['phone']) &&
            isset($_POST['email']) &&
            isset($_POST['guardian_firstname']) &&
            isset($_POST['guardian_lastname']) &&
            isset($_POST['guardian_middle_name']) &&
            isset($_POST['guardian_suffix']) &&
            isset($_POST['guardian_contact']) &&
            isset($_POST['guardian_relationship']) &&
            isset($_POST['guardian_occupation'])
        ) {

            // $suffixName = $_POST['suffixName'];

            // var_dump($suffixName);
            // return;
            // Retrieve old values

            $stud = $con->prepare("SELECT 
                lastname, firstname, middle_name, suffix, civil_status, nationality,
                sex, birthday, birthplace, religion, address, contact_number, email

                FROM student WHERE student_id = :student_id"
            );

            $stud->bindParam(':student_id', $student_id);
            $stud->execute();
            $oldStudent = $stud->fetch(PDO::FETCH_ASSOC);



            // Collect changes
            $changes = [];
            if ($oldStudent['lastname'] !== $_POST['lastName']) {
                $changes['lastName'] = $oldStudent['lastname'];
            }
            if ($oldStudent['firstname'] !== $_POST['firstName']) {
                $changes['firstName'] = $oldStudent['firstname'];
            }

            if ($oldStudent['middle_name'] !== $_POST['middleName']) {
                $changes['middleName'] = $oldStudent['middle_name'];
            }
            if ($oldStudent['suffix'] !== $_POST['suffixName']) {
                $changes['suffixName'] = $oldStudent['suffix'];
            }

            if ($oldStudent['civil_status'] !== $_POST['status']) {
                $changes['status'] = $oldStudent['civil_status'];
            }
            if ($oldStudent['nationality'] !== $_POST['citizenship']) {
                $changes['citizenship'] = $oldStudent['nationality'];
            }
            if ($oldStudent['sex'] !== $_POST['gender']) {
                $changes['gender'] = $oldStudent['sex'];
            }
            if ($oldStudent['birthday'] !== $_POST['birthdate']) {
                $changes['birthdate'] = $oldStudent['birthday'];
            }
            if ($oldStudent['birthplace'] !== $_POST['birthplace']) {
                $changes['birthplace'] = $oldStudent['birthplace'];
            }
            if ($oldStudent['religion'] !== $_POST['religion']) {
                $changes['religion'] = $oldStudent['religion'];
            }
            if ($oldStudent['address'] !== $_POST['address']) {
                $changes['address'] = $oldStudent['address'];
            }
            if ($oldStudent['contact_number'] !== $_POST['phone']) {
                $changes['phone'] = $oldStudent['contact_number'];
            }
            if ($oldStudent['email'] !== $_POST['email']) {
                $changes['email'] = $oldStudent['email'];
            }

            # School

            $studentDetailsQuery = $con->prepare("SELECT 

                lastname, firstname, middle_name, suffix, occupation, relationship, contact_number,
                school_name, school_address, year_started, year_ended

                FROM parent WHERE pending_enrollees_id = :pending_enrollees_id"
            );
            $studentDetailsQuery->bindParam(':pending_enrollees_id', $get_student_new_pending_id);
            $studentDetailsQuery->execute();

            $studentOther = $studentDetailsQuery->fetch(PDO::FETCH_ASSOC);

            # School History
            if ($studentOther['school_name'] !== $_POST['school_name']) {
                $changes['school_name'] = $studentOther['school_name'];
            }
            if ($studentOther['school_address'] !== $_POST['school_address']) {
                $changes['school_address'] = $studentOther['school_address'];
            }
            if ($studentOther['year_started'] !== $_POST['year_started']) {
                $changes['year_started'] = $studentOther['year_started'];
            }
            if ($studentOther['year_ended'] !== $_POST['year_ended']) {
                $changes['year_ended'] = $studentOther['year_ended'];
            }

            # Guardian
            if ($studentOther['lastname'] !== $_POST['guardian_lastname']) {
                $changes['guardian_lastname'] = $studentOther['lastname'];
            }
            if ($studentOther['firstname'] !== $_POST['guardian_firstname']) {
                $changes['guardian_firstname'] = $studentOther['firstname'];
            }

            if ($studentOther['middle_name'] !== $_POST['guardian_middle_name']) {
                $changes['guardian_middle_name'] = $studentOther['middle_name'];
            }
            if ($studentOther['suffix'] !== $_POST['guardian_suffix']) {
                $changes['guardian_suffix'] = $studentOther['suffix'];
            }

            if ($studentOther['contact_number'] !== $_POST['guardian_contact']) {
                $changes['guardian_contact'] = $studentOther['contact_number'];
            }
            if ($studentOther['occupation'] !== $_POST['guardian_occupation']) {
                $changes['guardian_occupation'] = $studentOther['occupation'];
            }
            if ($studentOther['relationship'] !== $_POST['guardian_relationship']) {
                $changes['guardian_relationship'] = $studentOther['relationship'];
            }
             
            

            $enrollmentAudit= new EnrollmentAudit($con);

            $firstNameFormat = "";

            foreach ($changes as $field => $oldValue) {

                echo "<br>";
                // echo "oldValue: $oldValue";
                // echo "field: $field";
                // echo "<br>";

                $formatValue = "";

                if($field == "lastName"){
                    $formatValue = "Last name";
                }
                if($field == "firstName"){
                    $formatValue = "First name";
                }

                if($field == "middleName"){
                    $formatValue = "Middle name";
                }

                if($field == "suffixName"){
                    $formatValue = "Suffix";
                }

                if($field == "civil_status"){
                    $formatValue = "Civil Status";
                }
                
                if($field == "citizenship"){
                    $formatValue = "Citizenship";
                }

                if($field == "sex"){
                    $formatValue = "Gender";
                }


                 if($field == "birthday"){
                    $formatValue = "Birth day";
                }
                
                if($field == "birthplace"){
                    $formatValue = "Birth place";
                }

                if($field == "religion"){
                    $formatValue = "Religion";
                }

                if($field == "address"){
                    $formatValue = "Suffix";
                }
                if($field == "contact_number"){
                    $formatValue = "Phone";
                }

                if($field == "email"){
                    $formatValue = "Email";
                }

                if($field == "school_name"){
                    $formatValue = "School Name";
                }
                
                if($field == "school_address"){
                    $formatValue = "School Address";
                }


                if($field == "guardian_lastname"){
                    $formatValue = "Guardian lastname";
                }
                
                if($field == "guardian_firstname"){
                    $formatValue = "Guardian firstname";
                }

                if($field == "guardian_middle_name"){
                    $formatValue = "Guardian Middlename";
                }
                
                if($field == "guardian_suffix"){
                    $formatValue = "Guardian suffix";
                }

                if($field == "guardian_contact"){
                    $formatValue = "Guardian contact";
                }
                
                if($field == "guardian_occupation"){
                    $formatValue = "Guardian occupation";
                }

                if($field == "guardian_relationship"){
                    $formatValue = "Guardian relationship";
                }

                $newValue = $_POST[$field];


                $description = "Registrar '$registrarName' has been edited the $formatValue input, From '$oldValue' changed into '$newValue'";
                // echo $description;
                // echo "<br>";

                // $stmt = $con->prepare("INSERT INTO enrollment_audit 

                //     (enrollment_id, description, school_year_id, registrar_id) 
                //     VALUES (:enrollment_id, :description, :school_year_id, :registrar_id)
                // ");
                // $stmt->bindParam(':enrollment_id', $student_enrollment_id);
                // $stmt->bindParam(':description', $description);
                // $stmt->bindParam(':school_year_id', $current_school_year_id);
                // $stmt->bindParam(':registrar_id', $registrarUserId);
                // $stmt->execute();

                $enrollmentAudit=   new EnrollmentAudit($con);

                $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                    $student_enrollment_id,
                    $description, $current_school_year_id, $registrarUserId
                );



            }

            // return;

            // $student_lrn = isset($_POST['lrn']) ? $_POST['lrn'] : NULL;

            $student_lrn = isset($_POST['student_lrn']) ? $_POST['student_lrn'] : NULL;

            // var_dump($student_lrn);
            // return;

            $lastName = $_POST['lastName'];
            $firstName = $_POST['firstName'];
            $middleName = $_POST['middleName'];
            $suffixName = $_POST['suffixName'];
            $status = $_POST['status'];
            $citizenship = $_POST['citizenship'];
            $gender = $_POST['gender'];
            $student_birthday = $_POST['birthdate'];
            $birthplace = $_POST['birthplace'];
            $religion = $_POST['religion'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];

            $guardianFirstName = $_POST['guardian_firstname'];
            $guardianLastName = $_POST['guardian_lastname'];
            $guardianMiddleName = $_POST['guardian_middle_name'];
            $guardianSuffix = $_POST['guardian_suffix'];
            $guardianContact = $_POST['guardian_contact'];
            $guardianRelationship = $_POST['guardian_relationship'];
            $guardianOccupation = $_POST['guardian_occupation'];
            

            // var_dump($email);
            // return;

            // echo "LRN: " . $student_lrn . "<br>";

            // echo "Last Name: " . $_POST['lastName'] . "<br>";
            // echo "First Name: " . $_POST['firstName'] . "<br>";
            // echo "Middle Name: " . $_POST['middleName'] . "<br>";
            // echo "Suffix Name: " . $_POST['suffixName'] . "<br>";
            // echo "Status: " . $_POST['status'] . "<br>";
            // echo "Citizenship: " . $_POST['citizenship'] . "<br>";
            // echo "Gender: " . $_POST['gender'] . "<br>";
            // echo "Birthdate: " . $_POST['birthdate'] . "<br>";
            // echo "Birthplace: " . $_POST['birthplace'] . "<br>";
            // echo "Religion: " . $_POST['religion'] . "<br>";
            // echo "Address: " . $_POST['address'] . "<br>";
            // echo "Phone: " . $_POST['phone'] . "<br>";
            // echo "Email: " . $_POST['email'] . "<br>";

            // echo "Guardian First Name: " . $_POST['guardian_firstname'] . "<br>";
            // echo "Guardian Last Name: " . $_POST['guardian_lastname'] . "<br>";
            // echo "Guardian Middle Name: " . $_POST['guardian_middle_name'] . "<br>";
            // echo "Guardian Suffix: " . $_POST['guardian_suffix'] . "<br>";
            // echo "Guardian Contact: " . $_POST['guardian_contact'] . "<br>";
            // echo "Guardian Relationship: " . $_POST['guardian_relationship'] . "<br>";
            // echo "Guardian Occupation: " . $_POST['guardian_occupation'] . "<br>";
            
            // return;

            # If student_unique_id is NULL AND username is NULL = not enrolled
            # together with pending enrollees id.

            // var_dump($editParentExec);
            // return;

            # Update For Pending Enrollee Table.
            $pendingEditingSuccess = $pending_enrollee->UpdateStudentInformation(
                $firstName, $lastName, $middleName, $suffixName,
                $status, $citizenship, $phone,
                $student_birthday, $birthplace, null, $gender, $address, $student_lrn,
                $religion, $get_student_new_pending_id, $email);


            # Update For Student Table.
            $editStudentExec = $pending->UpdateStudentDetails(
                $student_id, $firstName, $lastName,
                $middleName, $suffixName, $status, $citizenship, $gender,
                $student_birthday, $birthplace, $religion, $address, $phone,
                $email, $student_lrn
            );


            $editParentExec = $student_parent->UpdateNewEnrolleeStudentParent(
                $get_student_new_pending_id, $parent_id, $guardianFirstName, $guardianLastName,
                $guardianMiddleName, $guardianSuffix,
                $guardianContact, $guardianOccupation,
                $guardianRelationship
            );

            if($pendingEditingSuccess || 
                $editStudentExec || 
                $editParentExec
            ){

                Alert::success("Successfully update student details.", "");
                exit();
            }else{
                # No changes made.
            }

            # TODO. FIX THE student/details (The Father and mother fields is not  backup yet)
            # TODO. Only the pending enrollees enrollee is updatable but the student table
            # which is the reflection of the form is not yet updatable.

            // $studentEditingSuccess = $pending->UpdateStudentDetails(
            //     $student_id, $student_firstname, $student_lastname,
            //     $student_middle_name, $student_suffix, $student_civil_status, $student_nationality, $student_gender,
            //     $student_birthday, $student_birthplace, $student_religion, $student_address, $student_contact_number,
            //     $student_email
            // );
            
            
        }


        ?>

            <div class="content">
                <nav>
                    <a href="process_enrollment.php?details=show&st_id=<?php echo $student_id; ?>"
                    ><i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                    </a>
                </nav>

                <main>

                    <div class="floating">
                        <header>

                            <div class="title">
                                <h4 style="font-weight: bold;">Student Information</h4>
                                
                            </div>

                        </header>

                        <main>
                            <form method="POST" >

                                <?php if($type_status == 0):?>

                                    <div class="row">
                                        <span style="margin-left: 470px;">

                                            <label for="student_lrn">LRN</label>
                                            <input class="form-control" placeholder="" style="width: 250px;" id="student_lrn" type="text" name="student_lrn" value="<?php echo $student_lrn;?>">
                                        </span>
                                    </div>

                                <?php endif;?>


                                <div class="row">
                                    <span>
                                        <label for="name">Name</label>
                                        <div>
                                            <input type="text" name="lastName" id="lastName" value="<?php echo $student_lastname; ?>" class="form-control" />
                                            <small></small>
                                        </div>
                                        <div>
                                            <input type="text" name="firstName" id="firstName" value="<?php echo $student_firstname; ?>" class="form-control" />
                                            <small>First name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="middleName" id="middleName" value="<?php echo $student_middle_name; ?>" class="form-control" />
                                            <small>Middle name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="suffixName" id="suffixName" value="<?php echo $student_suffix; ?>" class="form-control" />
                                            <small>Suffix name</small>
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="status">Status</label>
                                        <div>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Single"<?php echo ($student_civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                <option value="Married"<?php echo ($student_civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                <!-- <option value="Divorced"<?php echo ($student_civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                <option value="Widowed"<?php echo ($student_civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option> -->
                                            </select>
                                        </div>
                                    </span>
                                    <span>
                                        <label for="citizenship">Citizenship</label>
                                        <div>
                                            <input type="text" name="citizenship" id="citizenship" value="<?php echo $student_nationality; ?>" class="form-control" />
                                        </div>
                                    </span>
                                    <span>
                                        <label for="gender">Gender</label>
                                        <div>
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="Male"<?php echo ($student_gender == "Male") ? " selected" : ""; ?>>Male</option>
                                                <option value="Female"<?php echo ($student_gender == "Female") ? " selected" : ""; ?>>Female</option>
                                            </select>
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="birthdate">Birthdate</label>
                                        <div>
                                            <input type="date" name="birthdate" id="birthdate" value="<?php echo $student_birthday; ?>" class="form-control" />
                                        </div>
                                    </span>
                                    <span>
                                        <label for="birthplace">Birthplace</label>
                                        <div>
                                            <input type="text" name="birthplace" id="birthplace" value="<?php echo $student_birthplace; ?>" class="form-control" />
                                        </div>
                                    </span>
                                    <span>
                                        <label for="religion">Religion</label>
                                        <div>
                                            <input type="text" name="religion" id="religion" value="<?php echo $student_religion; ?>" class="form-control" />
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="address">Address</label>
                                        <div>
                                            <input type="text" name="address" id="address" value="<?php echo $student_address; ?>" class="form-control" />
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="phoneNo">Phone no.</label>
                                        <div>
                                            <input type="text" name="phone" id="phone" value="<?php echo $student_contact_number; ?>" class="form-control" />
                                        </div>
                                    </span>
                                    <span>
                                        <label for="email">Email</label>
                                        <div>
                                            <input type="email" name="email" id="email" value="<?php echo $student_email; ?>" class="form-control" />
                                        </div>
                                    </span>
                                </div>




                                <br>
                                <div id="previous_school">
                                    <header>
                                        <div class="title">
                                            <h4 style="font-weight: bold;">Previous School Information</h4>
                                        </div>
                                    </header>

                                    <div class="row">
                                        <span>
                                            <label for="school_name">School Name</label>
                                            <div>
                                                <input required type="text" id="school_name" name="school_name" class="read_only form-control" 
                                                value="<?php echo $school_name; ?>">
                                            </div>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span>
                                            <label for="school_address">Address</label>
                                            <div>
                                                <input required type="text" id="school_address" name="school_address"
                                                class="read_only form-control" value="<?php echo $school_address; ?>">
                                            </div>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span>
                                            <label for="year_started">Admission Year</label>
                                            <div>
                                                <input required type="text" id="year_started" name="year_started"
                                                class="read_only form-control" value="<?php echo $year_started;?>">
                                            </div>
                                        </span>

                                        <span>
                                            <label for="year_ended">Graduation Year</label>
                                            <div>
                                                <input  required type="text" id="year_ended" name="year_ended" 
                                                class="read_only form-control" value="<?php echo $year_ended;?>">
                                            </div>
                                        </span>
                                    </div>

                                </div>

                                <div id="guardian_info">

                                    <header>
                                        <div class="title mb-2 mt-2">
                                            <h4 style="font-weight: bold;">Guardian Information</h4>
                                        </div>
                                    </header>

                                    <main>
                                        <div class="row">
                                            <span>
                                                <label for="name">Name</label>
                                                <div>
                                                <input type="text" name="guardian_lastname" id="guardian_lastname" value="<?php echo $parent_lastname;?>" class="form-control" />
                                                <small>Last name</small>
                                                </div>
                                                <div>
                                                <input type="text" name="guardian_firstname" id="guardian_firstname" value="<?php echo $parent_firstname;?>" class="form-control" />
                                                <small>First name</small>
                                                </div>
                                                <div>
                                                <input type="text" name="guardian_middle_name" id="guardian_middle_name" value="<?php echo $parent_middle_name;?>" class="form-control" />
                                                <small>Middle name</small>
                                                </div>
                                                <div>
                                                <input type="text" name="guardian_suffix" id="guardian_suffix" value="<?php echo $parent_suffix;?>" class="form-control" />
                                                <small>Suffix</small>
                                                </div>
                                            </span>
                                            </div>

                                            <div class="row">
                                            <span>
                                                <label for="phoneNo">Phone no.</label>
                                                <div>
                                                <input type="text" name="guardian_contact" id="guardian_contact" value="<?php echo $parent_contact_number;?>" class="form-control" />
                                                </div>
                                            </span>
                                            </div>
                                            <div class="row">
                                                <span>
                                                    <label for="relationship">Relationship</label>
                                                    <div>
                                                    <input type="text" name="guardian_relationship" id="guardian_relationship" value="<?php echo $parent_relationship;?>" class="form-control" />
                                                    </div>
                                                </span>
                                                <span>
                                                    <label for="occupation">Occupation</label>
                                                    <div>
                                                    <input type="text" name="guardian_occupation" id="guardian_occupation" value="<?php echo $parent_occupation;?>" class="form-control" />
                                                    </div>
                                                </span>
                                            </div>
                                    </main>
                                

                                </div>

                                <div class="action">
                                    
                                    <button name="update_enrollment_<?php echo $student_id; ?>" type="submit" class="default success large">
                                        Update & Save
                                    </button>
                                </div>

                            </form>

                        </main>

                    </div>

                   

                </main>
            </div>

        <?php
    }

?>

