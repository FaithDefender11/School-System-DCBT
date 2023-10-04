
<?php

    $parent = new StudentParent($con, $student_id);

    $parent_firstname = $parent->GetFirstName();
    $parent_lastname = $parent->GetLastName();
    $parent_middle_name = $parent->GetMiddleName();
    $parent_suffix = $parent->GetSuffix();
    $parent_contact_number = $parent->GetContactNumber();
    $parent_email = $parent->GetEmail();
    $parent_occupation = $parent->GetOccupation();
    $parent_relationship = $parent->GetGuardianRelationship();
    // 

    // Father
    $father_firstname = $parent->GetFatherFirstName();
    $father_lastname = $parent->GetFatherLastName();
    $father_middle = $parent->GetFatherMiddleName();
    $father_suffix = $parent->GetFatherSuffix();
    $father_contact_number = $parent->GetFatherContactNumber();
    $father_email = $parent->GetFatherEmail();
    $father_occupation = $parent->GetFatherOccupation();


    // Mother
    $mother_firstname = $parent->GetMotherFirstName();
    $mother_lastname = $parent->GetMotherLastName();
    $mother_middle = $parent->GetMotherMiddleName();
    $mother_suffix = $parent->GetMotherSuffix();
    $mother_contact_number = $parent->GetMotherContactNumber();
    $mother_email = $parent->GetMotherEmail();
    $mother_occupation = $parent->GetMotherOccupation();


?>
<!-- STEP 1 -->

<div class="content">
    <nav>
        <a href="SHS-find-form-evaluation.html"
        ><i class="bi bi-arrow-return-left fa-1x"></i>
        <h3>Back</h3>
        </a>
    </nav>
    <div class="content-header">
        <?php echo Helper::RevealStudentTypePending($type); ?>
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
                <div class="title">
                <h3>Student form details</h3>
                <small
                    >Assure every student information in this section. This will be
                    the student data.</small
                >
                </div>
            </header>
            
            <header>
                <div class="title">
                <h4>Student Information</h4>
                </div>
            </header>

            <main>
                <form action="">
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
                </form>
            </main>

            <div id="school_attended">
                <header>
                    <div class="title">
                    <h4>Last School Attended</h4>
                    </div>
                </header>

                <main>
                    <form action="">
                        <div class="row">
                            <span>
                                <label for="schoolType">School type</label>
                                <div>
                                    <select name="schoolType" id="schoolType" class="form-control">
                                        <option value="">SHS</option>
                                        <option value="">College</option>
                                    </select>
                                </div>
                            </span>
                            <span>
                                <label for="schoolName">School name</label>
                                <div>
                                    <input type="text" name="schoolName" id="schoolName" value="" class="form-control" />
                                </div>
                            </span>
                        </div>
                        <div class="row">
                            <span>
                                <label for="graduationDate">Graduation date</label>
                                <div>
                                    <input type="date" name="graduationDate" id="graduationDate" value="" class="form-control" />
                                </div>
                            </span>
                            <span>
                                <label for="schoolYear">School year</label>
                                <div>
                                    <input type="text" name="schoolYear" id="schoolYear" placeholder="2022-2023" value="" class="form-control" />
                                </div>
                            </span>
                            <span>
                                <label for="term">Term</label>
                                <div>
                                    <select name="term" id="term" class="form-control">
                                        <option value="">First term</option>
                                        <option value="">Second term</option>
                                    </select>
                                </div>
                            </span>
                        </div>
                        <div class="row">
                            <span>
                                <label for="schoolAddress">School address</label>
                                <div>
                                    <input type="text" name="schoolAddress" id="schoolAddress" value="" class="form-control" />
                                </div>
                            </span>
                        </div>
                    </form>
                </main>

            </div>


            <div id="father_info">
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

            <div id="mother_info">
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
                    <div class="title">
                    <h4>Guardian's Information</h4>
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
                        <span>
                            <label for="email">Email</label>
                            <div>
                            <input type="email" name="guardian_email" id="guardian_email" value="<?php echo $parent_email;?>" class="form-control" />
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
            <button class="default success large" 
                onclick="window.location.href = 'process_enrollment.php?find_section=show&st_id=<?php echo $student_id; ?>&c_id=<?php echo $student_enrollment_course_id;?>'">
                Proceed
            </button>
        </div>
    </main>
</div>