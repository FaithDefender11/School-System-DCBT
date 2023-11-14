
<?php

    // $parent = new StudentParent($con, $student_id);

    // $parent_firstname = $parent->GetFirstName();
    // $parent_lastname = $parent->GetLastName();
    // $parent_middle_name = $parent->GetMiddleName();
    // $parent_suffix = $parent->GetSuffix();
    // $parent_contact_number = $parent->GetContactNumber();
    // $parent_email = $parent->GetEmail();
    // $parent_occupation = $parent->GetOccupation();
    // $parent_relationship = $parent->GetGuardianRelationship();
    // 

    $pendingEnroleee = new Pending($con);




    

    $pending = new Student($con, $student_id);

    $student_lrn = $pending->GetStudentLRN();


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


    $get_student_new_pending_id = $pendingEnroleee->GetPendingAccountByStudentTable(
        $student_email, $student_firstname, $student_lastname);

    $parent = new PendingParent($con, $get_student_new_pending_id);

    $get_student_new_pending_id = $pendingEnroleee->GetPendingAccountByStudentTable(
        $student_email, $student_firstname, $student_lastname);

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

    $school_name = $parent->GetSchoolName();
    $school_address = $parent->GetSchoolAddress();
    $year_started = $parent->GetSchoolYearStarted();
    $year_ended = $parent->GetSchoolYearEnded();


?>
<!-- STEP 1 -->

<div class="content">

    <div class="content-header">
        
        <?php echo Helper::RevealStudentTypePending($type,
                $student_enrollment_student_status); ?>

        <header>

            <div class="title">
                <h1>Enrollment form</h1>
            </div>
            <div class="action">
                <div class="dropdown">

                    <button class="icon">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu">

                        <a href="form_details_edit.php?id=<?php echo $student_id; ?>" class='text-primary dropdown-item'>
                            <i class='bi bi-pencil'></i>Edit form
                        </a>
                    </div>
                </div>

            </div>

        </header>

        <?php echo Helper::ProcessStudentCards($student_id, $student_enrollment_form_id,
            $student_unique_id, $enrollment_creation, $student_new_enrollee,
            $enrollment_is_new_enrollee, $enrollment_is_transferee, $student_status_st); ?>

    </div>
    
    <main>
        <div class="progress">
            <span class="dot active"><p>Check form details</p></span>
            <span class="line inactive"></span>
            <span class="dot inactive"><p>Find section</p></span>
            <span class="line inactive"></span>
            <span class="dot inactive"><p>Subject confirmation</p></span>
        </div>

        <div class="floating">
            <header>
                <div class="mb-2 title">
                    <h4 style="font-weight: bold;">Student Information</h4>
                </div>

                <div>

                    <a href="enrollment_audit.php?id=<?= $student_enrollment_id;?>" style="text-decoration: none; color: inherit">
                        <button class="btn btn-sm btn-info">
                            <i class="fas fa-clock"></i> History
                        </button>
                    </a>
                    

                    <button onclick="window.location.href='enrollee_file_submission.php?id=<?= $pending_enrollees_id;?>'" class="btn btn-sm btn-primary">
                        <i class="fas fa-file"></i> Files
                    </button>

                </div>
            </header>

            <main>

                <form action="">

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
                                    <option value="Divorced"<?php echo ($student_civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                    <option value="Widowed"<?php echo ($student_civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
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



                </form>


            </main>

 




            <div style="display: none;" id="father_info">
                <header>
                    <div class="title">
                        <h4>Father's Information</h4>
                    </div>
                </header>
                
                <main>
                    <form action="">
                        <div class="row">
                            <span>
                                <label for="name">Name</label>
                                <div>
                                    <input  value="<?php echo $father_lastname?>" type="text" name="father_lastname" class="form-control">
                                    <small>Last name</small>
                                </div>
                                <div>
                                    <input value="<?php echo $father_firstname?>" type="text" name="father_firstname" class="form-control">
                                    <small>First name</small>
                                </div>
                                <div>
                                    <input value="<?php echo $father_middle?>" type="text" name="father_middle" class="form-control">
                                    <small>Middle name</small>
                                </div>
                                <div>
                                    <input value="<?php echo $father_suffix?>" type="text" name="father_suffix" class="form-control">
                                    <small>Father suffix</small>
                                </div>
                            </span>
                        </div>

                        <div class="row">
                            <span>
                                <label for="phone">Phone no.</label>
                                <div>
                                    <input value="<?php echo $father_contact_number?>" type="tel" id="father_contact_number" name="father_contact_number" class="form-control">
                                </div>
                            </span>
                            <span>
                                <label for="email">Email</label>
                                <div>
                                    <input value="<?php echo $father_email?>" type="text" id="father_email" name="father_email" class="form-control">
                                </div>
                            </span>
                            <span>
                                <label for="occupation">Occupation</label>
                                <div>
                                    <input value="<?php echo $father_occupation?>" type="text" id="father_occupation" name="father_occupation" class="form-control">
                                </div>
                            </span>
                        </div>
                    </form>
                </main>
            </div>

            <div style="display: none;" id="mother_info">
                <header>
                    <div class="title">
                    <h4>Mother's Information</h4>
                    </div>
                </header>

                <main>
                    <form action="">
                        <div class="row">
                            <span>
                                <label for="name">Name</label>
                                <div>
                                    <input value="<?php echo $mother_lastname;?>" type="text" name="mother_lastname" class="form-control">
                                    <small>Last name</small>
                                </div>
                                <div>
                                    <input value="<?php echo $mother_firstname;?>" type="text" name="mother_firstname" class="form-control">
                                    <small>First name</small>
                                </div>
                                <div>
                                    <input value="<?php echo $mother_middle;?>" type="text" name="mother_middle" class="form-control">
                                    <small>Middle name</small>
                                </div>
                                <div>
                                    <input value="<?php echo $mother_suffix;?>" type="text" name="mother_suffix" class="form-control">
                                    <small>Mother suffix</small>
                                </div>
                            </span>
                        </div>
                        
                        <div class="row">
                            <span>
                                <label for="phone">Phone no.</label>
                                <div>
                                    <input value="<?php echo $mother_contact_number;?>" type="tel" id="mother_contact_number" name="mother_contact_number" class="form-control">
                                </div>
                            </span>
                            <span>
                                <label for="email">Email</label>
                                <div>
                                    <input value="<?php echo $mother_email;?>" type="text" id="mother_email" name="mother_email" class="form-control">
                                </div>
                            </span>
                            <span>
                                <label for="occupation">Occupation</label>
                                <div>
                                    <input value="<?php echo $mother_occupation;?>" type="text" id="mother_occupation" name="mother_occupation" class="form-control">
                                </div>
                            </span>
                        </div>
                    </form>
                </main>

            </div>

            <div id="guardian_info">

                <header>
                    <div class="mb-2 title">
                        <h4 style="font-weight: bold">Guardian's Information</h4>
                    </div>
                </header>

                <main>
                    <form action="">
                        <div class="row">
                        <span>
                            <label for="name">Name</label>
                            <div>
                            <input type="text" name="guardian_firstname" id="guardian_firstname" value="<?php echo $parent_lastname;?>" class="form-control" />
                            <small>Last name</small>
                            </div>
                            <div>
                            <input type="text" name="guardian_lastname" id="guardian_lastname" value="<?php echo $parent_firstname;?>" class="form-control" />
                            <small>First name</small>
                            </div>
                            <div>
                            <input type="text" name="guardian_middle_name" id="guardian_middle_name" value="<?php echo $parent_middle_name;?>" class="form-control" />
                            <small>Middle name</small>
                            </div>
                            <div>
                            <input type="text" name="guardian_suffix" id="guardian_suffix" value="<?php echo $parent_suffix;?>" class="form-control" />
                            <small>Suffix name</small>
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
                    </form>
                </main>
            

            </div>


        </div>

        <div class="action">
            <button class="default large" 
                onclick="window.location.href = 'process_enrollment.php?find_section=show&st_id=<?php echo $student_id; ?>&c_id=<?php echo $student_enrollment_course_id;?>'">
                Proceed
            </button>
        </div>
    </main>
</div>