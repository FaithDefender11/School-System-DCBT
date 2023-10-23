

<?php  

        ?>
            <style>
                .read_only{
                    pointer-events: none;
                }
            </style>
        <?php

        $pending = new Pending($con, $pending_enrollees_id);
        $parent = new PendingParent($con, $pending_enrollees_id);

        // $school_history = $pending->GetEnrolleeSchoolHistory($pending_enrollees_id);
        // $student_school_history_id = $school_name = $school_address = $year_started = $year_ended = "";
        // if ($school_history !== NULL) {

            // $student_school_history_id = $school_history['student_school_history_id'];

            // $school_name = $school_history['school_name'];
            // $school_address = $school_history['address'];
            // $year_started = $school_history['year_started'];
            // $year_ended = $school_history['year_ended'];
        // }


        $school_name = $parent->GetSchoolName();
        $school_address = $parent->GetSchoolAddress();
        $year_started = $parent->GetSchoolYearStarted();
        $year_ended = $parent->GetSchoolYearEnded();


        $lrn = $pending->GetPendingLRN();
        $firstname = $pending->GetPendingFirstName();
        $lastname = $pending->GetPendingLastName();
        $middle_name = $pending->GetPendingMiddleName();
        $suffix = $pending->GetPendingSuffix();
        $civil_status = $pending->GetPendingCivilStatus();
        $nationality = $pending->GetPendingNationality();
        $sex = $pending->GetPendingGender();
        $birthday = $pending->GetPendingBirthday();
        $birthplace = $pending->GetPendingBirthplace();
        $religion = $pending->GetPendingReligion();
        $address = $pending->GetPendingAddress();
        $email = $pending->GetPendingEmail();
        $contact_number = $pending->GetPendingContactNumber();
        $admission_status = $pending->GetPendingAdmissionStatus();


        // Guardian
        $parent_id = $parent->GetParentID();
        $parent_firstname = $parent->GetFirstName();
        $parent_lastname = $parent->GetLastName();
        $parent_middle_name = $parent->GetMiddleName();
        $parent_suffix = $parent->GetSuffix();
        $parent_contact_number = $parent->GetContactNumber();
        $parent_email = $parent->GetEmail();
        $parent_occupation = $parent->GetOccupation();
        $parent_relationship = $parent->GetGuardianRelationship();
        // 

        // echo $parent_firstname;

        // Father
        $father_firstname = $parent->GetFatherFirstName();
        $father_lastname = $parent->GetFatherLastName();
        $father_middle = $parent->GetFatherMiddleName();
        $father_suffix = $parent->GetFatherSuffix();
        $father_contact_number = $parent->GetFatherContactNumber();
        $father_email = $parent->GetFatherEmail();
        $father_occupation = $parent->GetFatherOccupation();

        // Father
        $mother_firstname = $parent->GetMotherFirstName();
        $mother_lastname = $parent->GetMotherLastName();
        $mother_middle = $parent->GetMotherMiddleName();
        $mother_suffix = $parent->GetMotherSuffix();
        $mother_contact_number = $parent->GetMotherContactNumber();
        $mother_email = $parent->GetMotherEmail();
        $mother_occupation = $parent->GetMotherOccupation();
        // echo $mother_middle;

        // $check = $pending->PendingProgramLevelSectionAvailable(
        //     3, 11, $current_term, $current_semester
        // );
        // if($check){
        //     echo "yey";
        // }else{
        //     echo "not";
        // }

        ?>

        <div class="content">
            <nav>
                <a href="<?php echo $logout_url;?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <h3>Logout</h3>
                </a>
            </nav>   
            <main>
                <div class="floating noBorder">
                    <header>
                        <div class="title">
                            <h2 style="color: var(--titleTheme)">New enrollment form</h2>
                            <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester; ?> Semester </small>
                        </div>
                    </header>

                    <div class="progress">
                        <span class="dot active"><p>Preferred Course/Strand</p></span>
                        <span class="line active"></span>
                        <span class="dot active"> <p>Personal Information</p></span>
                        <span class="line active"></span>
                        <span class="dot active"> <p>Validate Details</p></span>
                        <span class="line inactive"></span>
                        <span class="dot inactive"> <p>Finished</p></span>
                    </div>

                    <form method="POST">
                        <main>
                            <header>
                                <div class="title">
                                    <h4 style="font-weight: bold;">Enrollee Information</h4>
                                    <div class="row">
                                        <span style="margin-left: 660px;">
                                            <label for="lrn">LRN</label>
                                            <input class="read_only form-control" style="width: 250px;" id="lrn" type="text" name="lrn" 
                                            value="<?php echo ($lrn != "") ? $lrn : ''; ?>"id="lrn">
                                        </span>
                                    </div>
                                </div>
                            </header>

                            <div class="row">
                                <span>
                                <label for="name">Name</label>
                                <div>
                                    <input class="read_only form-control" type="text" required name="lastname" id="lastName" required value="<?php echo ($lastname != "") ? $lastname : ''; ?>" placeholder="Last name">
                                    <small>Last name</small>
                                </div>
                                <div>
                                    <input class="read_only form-control" type="text" required name="firstname" id="firstName" value="<?php echo ($firstname != "") ? $firstname : ''; ?>" placeholder="First name">
                                    <small>First name</small>
                                </div>
                                <div>
                                    <input class="read_only form-control" type="text" name="middle_name" id="middleName" value="<?php echo ($middle_name != "") ? $middle_name : ''; ?>" placeholder="Middle name">
                                    <small>Middle name</small>
                                </div>
                                <div>
                                    <input class="read_only form-control" type="text" name="suffix" id="suffixName" value="<?php echo ($suffix != "") ? $suffix : ''; ?>" placeholder="">

                                    <small>Suffix name</small>
                                </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                <label for="status">Status</label>
                                <div>
                                    <select class="read_only form-control" id="status" name="civil_status" class="form-control" required>
                                        <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                        <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                        <option value="Divorced"<?php echo ($civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                        <option value="Widowed"<?php echo ($civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                    </select>
                                </div>
                                </span>
                                <span>
                                    <label for="citizenship">Citizenship</label>
                                    <div>
                                        <input class="read_only form-control" style="width: 220px;" type="text" name="nationality" 
                                            required value="<?php echo ($nationality != "") ? $nationality : ''; ?>"id="nationality">
                                    </div>
                                </span>
                                <span>
                                <label for="gender">Gender</label>
                                <div>
                                    <select class="read_only form-control" required name="sex" id="sex">
                                        <option value="Male"<?php echo ($sex == "Male") ? " selected" : ""; ?>>Male</option>
                                        <option value="Female"<?php echo ($sex == "Female") ? " selected" : ""; ?>>Female</option>
                                    </select>
                                </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                <label for="birthdate">Birthdate</label>
                                <div>
                                <input type="date" id="birthday" name="birthday" class="read_only form-control" required value="<?php echo ($birthday != "") ? $birthday : "2023-06-17"; ?>">

                                </div>
                                </span>
                                <span>
                                <label for="religion">Religion</label>
                                <div>
                                        <input type="text" id="religion" name="religion" class="read_only form-control" value="<?php echo ($religion != "") ? $religion : "None"; ?>">

                                </div>
                                </span>
                                <span>
                                <label for="birthplace">Birthplace</label>
                                <div>
                                        <input type="text" id="birthplace" name="birthplace" class="read_only form-control" required value="<?php echo ($birthplace != "") ? $birthplace : "Taguigarao"; ?>">

                                </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                <label for="address">Address</label>
                                <div>
                                        <input  style="text-align: start;" type="text" id="address" name="address" class="read_only form-control" required value="<?php echo ($address != "") ? $address : "None"; ?>">

                                </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                <label for="phone">Phone no.</label>
                                <div>
                                        <input type="tel" id="contact_number" name="contact_number" class="read_only form-control" required value="<?php echo ($contact_number != "") ? $contact_number : "09151515123"; ?>">

                                </div>
                                </span>
                                <span>
                                <label for="email">Email</label>
                                <div>
                                    <input  type="email" id="email" name="email" class="read_only form-control" required value="<?php echo ($email != "") ? $email : ''; ?>">
                                </div>
                                </span>
                            </div>

                            <?php 

                                // include_once('./enrollee_parent_info.php');
                            ?>

                        </main>

                        <hr>

                        <main>
                            <header>
                                <div class="title">
                                    <h4 style="font-weight: bold;">Previous School Information</h4>
                                </div>
                            </header>
                            <br>
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
                        </main>

                        <hr>
                       
                        <main>
                            <header>
                                <div class="title">
                                    <h4 style="font-weight: bold;">Enrollee Parent Information</h4>
                                </div>
                            </header>
                            <hr>

                            <!-- FATHER DD -->
                            <div id="father_info">
                                <header>
                                    <div class="title">
                                    <h3>Father's Information</h3>
                                    </div>
                                </header>

                                <div class="row">
                                    <span>
                                        <label for="name">Name</label>
                                        <div>
                                            <input type="text" name="father_lastname" class="read_only form-control" required value="<?php echo htmlspecialchars($father_lastname); ?>">
                                            <small>Last name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="father_firstname" class="read_only form-control" required value="<?php echo htmlspecialchars($father_firstname); ?>">
                                            <small>First name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="father_middle" class="read_only form-control" required value="<?php echo htmlspecialchars($father_middle); ?>">
                                            <small>Middle name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="father_suffix" class="read_only form-control" value="<?php echo htmlspecialchars($father_suffix); ?>">
                                            <small>Suffix name</small>
                                        </div>
                                    </span>
                                </div>

                                <div class="row">
                                    <span>
                                        <label for="phone">Phone no.</label>
                                        <div>
                                            <input type="tel" id="father_contact_number" name="father_contact_number" class="form-control" required value="<?php echo ($father_contact_number != "") ? $father_contact_number : ''; ?>">
                                        </div>
                                    </span>
                                    <!-- <span>
                                        <label for="email">Email</label>
                                        <div>
                                            <input type="text" id="father_email" name="father_email" class="read_only form-control" value="<?php echo ($father_email != "") ? $father_email : ''; ?>">

                                        </div>
                                    </span> -->
                                    <span>
                                    <label for="occupation">Occupation</label>
                                    <div>
                                        <input type="text" id="father_occupation" name="father_occupation" class="read_only form-control" value="<?php echo ($father_occupation != "") ? $father_occupation : ''; ?>">
                                    </div>
                                    </span>
                                </div>
                            </div>

                            <!-- MOTHER DD -->
                            <div id="mother_info">
                                <header>
                                    <div class="title">
                                    <h3>Mother's Information</h3>
                                    </div>
                                </header>

                                <div class="row">
                                    <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <input type="text" name="mother_lastname" class="read_only form-control" required value="<?php echo $mother_lastname; ?>">

                                        <small>Last name</small>
                                    </div>
                                    <div>
                                        <input type="text" name="mother_firstname" class="read_only form-control" required value="<?php echo $mother_firstname; ?>">
                                        <small>First name</small>
                                    </div>
                                    <div>
                                        <input type="text" name="mother_middle" class="read_only read_only form-control" required value="<?php echo $mother_middle; ?>">
                                        
                                        <small>Middle name</small>
                                    </div>
                                    <!-- <div>
                                        <input type="text" name="mother_suffix" class="read_only read_only form-control" value="<?php echo $mother_suffix; ?>">
                                        
                                        <small>Suffix name</small>
                                    </div> -->
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="phone">Phone no.</label>
                                        <div>
                                            <input type="tel" id="mother_contact_number" name="mother_contact_number" class="read_only form-control"
                                                value="<?php echo $mother_contact_number; ?>">
                                        </div>

                                    </span>
                                    <!-- <span>
                                        <label for="email">Email</label>
                                        <div>
                                            <input type="text" id="mother_email" name="mother_email" class="read_only form-control" value="<?php echo ($mother_email != "") ? $mother_email : ''; ?>">
                                        </div>
                                    </span> -->

                                    <span>
                                        <label for="occupation">Occupation</label>
                                        <div>
                                            <input type="text" id="mother_occupation" name="mother_occupation" class="read_only form-control" value="<?php echo ($mother_occupation != "") ? $mother_occupation : ''; ?>">
                                        </div>
                                    </span>
                                </div>
                            </div>


                            <div class="guardian_info">
                                <header>
                                    <div class="title">
                                    <h3>Guardian's Information</h3>
                                    </div>
                                </header>

                                <div class="row">
                                    <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <input type="text" name="parent_lastname" class="read_only form-control" required value="<?php echo $parent_lastname; ?>">
                                        <small>Last name</small>
                                    </div>
                                    <div>
                                        <input type="text" name="parent_firstname" class="read_only form-control" required value="<?php echo $parent_firstname; ?>">

                                        <small>First name</small>
                                    </div>
                                    <div>
                                        <input type="text" name="parent_middle_name" class="read_only form-control" required value="<?php echo $parent_middle_name; ?>">
                                        <small>Middle name</small>
                                    </div>
                                    <div>
                                        <input type="text" name="parent_suffix" class="read_only form-control" value="<?php echo $parent_suffix; ?>">
                                        <small>Suffix name</small>
                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="phone">Contact no.</label>
                                        <div>
                                            <input type="tel" id="parent_contact_number" name="parent_contact_number" class="read_only form-control" required value="<?php echo ($parent_contact_number != "") ? $parent_contact_number : ''; ?>">
                                        </div>

                                        <!-- <label for="email">Email</label>
                                        <div>
                                            <input type="text" id="parent_email" name="parent_email" class="read_only form-control" value="<?php echo ($parent_email != "") ? $parent_email : ''; ?>">
                                        </div> -->

                                        <label for="occupation">Occupation</label>
                                        <div>
                                            <input type="text" id="parent_occupation" name="parent_occupation" class="read_only form-control" value="<?php echo ($parent_occupation != "") ? $parent_occupation : ''; ?>">
                                        </div>

                                        <label for="relationship">Relationship</label>
                                        <div>
                                            <input class="read_only form-control"
                                            type="text"
                                            name="parent_relationship"
                                            id="parent_relationship"
                                            value="<?php echo $parent_relationship;?>"
                                            />
                                        </div>
                                    <span>
                                </div>
                            </div>

                        </main>

                        <div class="action">
                            <button style="margin-right: 9px;"
                            type="button"
                                class="default large"
                                onclick="window.location.href = 'process.php?new_student=true&step=enrollee_parent_information'"
                                >
                                Return
                            </button>

                            <?php if($does_enrollee_finished_input !== 1): ?>
                                <button
                                    class="default success large"
                                    onclick="MarkAsValidated(<?php echo $pending_enrollees_id; ?>, <?php echo $school_year_id; ?>, '<?php echo $current_term; ?>', '<?php echo $current_semester; ?>')"
                                    type="button">
                                    Validate
                                </button>
                            <?php endif;?>
                            
                        </div>
                    </form>
                                <!-- onclick="window.location.href ='process.php?new_student=true&step=4'" -->
                </div>
            </main>
        </div>

        <?php
?>

<script>
    function MarkAsValidated(pending_enrollees_id,
        current_school_year_id, current_term, current_period){

        // pending_enrollees_id = parseInt(pending_enrollees_id);

        Swal.fire({
            icon: 'info',
            title: 'I hereby declare that all provided information are credible.',
            text: 'Please note that this cant be undone.',
            showCancelButton: true,
            confirmButtonText: 'Ok',

          }).then((result) => {

            if(result.isConfirmed){
                $.ajax({
                url: '../../ajax/tentative/markAsValidated.php',
                type: 'POST',
                data: {
                    pending_enrollees_id,
                    current_school_year_id,
                    current_term,
                    current_period,
                },
            // dataType: 'json',
                success: function (response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Request has made.',
                        timer: 1200,
                        showCancelButton: false,
                        confirmButtonText: 'Wait',
                    }).then(() => {

                        var url = 'process.php?new_student=true&step=4';
                        window.location.href = url;

                    });
                },

                });
            }   
        });
    }
</script>