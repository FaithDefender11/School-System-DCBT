
<?php 

    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Alert.php');
    include_once('../../includes/classes/Department.php');

    echo Helper::RemoveSidebar();
 
    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];


    if(isset($_GET['id'])
        && isset($_GET['details'])
        && $_GET['details'] == "show"){


        $pending_enrollees_id = $_GET['id'];

        $pending = new Pending($con, $pending_enrollees_id);

        $isFinished = $pending->GetPendingIsFinished();

        $parent = new PendingParent($con, $pending_enrollees_id);

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


        ?>

            <div class="content">
                    <?php
                        if($isFinished == 1){
                            ?>
                                <nav>
                                    <a href="profile.php?fill_up_state=finished">
                                        <i class="bi bi-arrow-return-left"></i>
                                        <h3>Back</h3>
                                    </a>
                                </nav>
                            <?php
                        }
                    ?>
                <main>
                    <div class="floating noBorder">
                        <header>
                            <div class="title">
                                <h2 style="color: var(--titleTheme)">New Enrollment Form</h2>
                                <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester; ?> Semester </small>
                            </div>
                        </header>

                        <div class="progress">
                            <span class="dot active"><p></p></span>
                            <span class="line active"></span>
                            <span class="dot active"> <p></p></span>
                            <span class="line active"></span>
                            <span class="dot active"> <p></p></span>
                            <span class="line active"></span>
                            <span class="dot active"> <p>Finished</p></span>
                        </div>

                        <form method="POST">

                            <main>
                                <header>
                                    <div class="title">
                                        <h4 style="font-weight: bold;">Student Information</h4>
                                        <div class="row">
                                            <span style="margin-left: 660px;">
                                                <label for="lrn">LRN</label>
                                                <input class="form-control" style="width: 250px;" id="lrn" type="text" name="lrn" 
                                                value="<?php echo ($lrn != "") ? $lrn : ''; ?>"id="lrn">
                                            </span>
                                        </div>
                                    </div>
                                </header>

                                <div class="row">
                                    <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <input class="form-control" type="text" required name="lastname" id="lastName" required value="<?php echo ($lastname != "") ? $lastname : ''; ?>" placeholder="Last name">
                                        <small>Last name</small>
                                    </div>
                                    <div>
                                        <input class="form-control" type="text" required name="firstname" id="firstName" value="<?php echo ($firstname != "") ? $firstname : ''; ?>" placeholder="First name">
                                        <small>First name</small>
                                    </div>
                                    <div>
                                        <input class="form-control" type="text" name="middle_name" id="middleName" value="<?php echo ($middle_name != "") ? $middle_name : ''; ?>" placeholder="Middle name">
                                        <small>Middle name</small>
                                    </div>
                                    <div>
                                        <input class="form-control" type="text" name="suffix" id="suffixName" value="<?php echo ($suffix != "") ? $suffix : ''; ?>" placeholder="Suffix name">

                                        <small>Suffix name</small>
                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="status">Status</label>
                                    <div>
                                        <select class="form-control" id="status" name="civil_status" class="form-control" required>
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
                                            <input class="form-control" style="width: 220px;" type="text" name="nationality" 
                                                required value="<?php echo ($nationality != "") ? $nationality : ''; ?>"id="nationality">
                                        </div>
                                    </span>
                                    <span>
                                    <label for="gender">Gender</label>
                                    <div>
                                        <select class="form-control" required name="sex" id="sex">
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
                                    <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($birthday != "") ? $birthday : "2023-06-17"; ?>">

                                    </div>
                                    </span>
                                    <span>
                                    <label for="religion">Religion</label>
                                    <div>
                                            <input type="text" id="religion" name="religion" class="form-control" value="<?php echo ($religion != "") ? $religion : "None"; ?>">

                                    </div>
                                    </span>
                                    <span>
                                    <label for="birthplace">Birthplace</label>
                                    <div>
                                            <input type="text" id="birthplace" name="birthplace" class="form-control" required value="<?php echo ($birthplace != "") ? $birthplace : "Taguigarao"; ?>">

                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="address">Address</label>
                                    <div>
                                            <input  style="text-align: start;" type="text" id="address" name="address" class="form-control" required value="<?php echo ($address != "") ? $address : "None"; ?>">

                                    </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                    <label for="phone">Phone no.</label>
                                    <div>
                                            <input type="tel" id="contact_number" name="contact_number" class="form-control" required value="<?php echo ($contact_number != "") ? $contact_number : "09151515123"; ?>">

                                    </div>
                                    </span>
                                    <span>
                                    <label for="email">Email</label>
                                    <div>
                                        <input readonly type="email" id="email" name="email" class="form-control" required value="<?php echo ($email != "") ? $email : ''; ?>">
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
                                                <input type="text" name="father_lastname" class="form-control" required value="<?php echo htmlspecialchars($father_lastname); ?>">
                                                <small>Last name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="father_firstname" class="form-control" required value="<?php echo htmlspecialchars($father_firstname); ?>">
                                                <small>First name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="father_middle" class="form-control" required value="<?php echo htmlspecialchars($father_middle); ?>">
                                                <small>Middle name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="father_suffix" class="form-control" value="<?php echo htmlspecialchars($father_suffix); ?>">
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
                                        <span>
                                        <label for="email">Email</label>
                                        <div>
                                            <input type="text" id="father_email" name="father_email" class="form-control" value="<?php echo ($father_email != "") ? $father_email : ''; ?>">

                                        </div>
                                        </span>
                                        <span>
                                        <label for="occupation">Occupation</label>
                                        <div>
                                            <input type="text" id="father_occupation" name="father_occupation" class="form-control" value="<?php echo ($father_occupation != "") ? $father_occupation : ''; ?>">
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
                                            <input type="text" name="mother_lastname" class="form-control" required value="<?php echo $mother_lastname; ?>">

                                            <small>Last name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="mother_firstname" class="form-control" required value="<?php echo $mother_firstname; ?>">
                                            <small>First name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="mother_middle" class="form-control" required value="<?php echo $mother_middle; ?>">
                                            
                                            <small>Middle name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="mother_suffix" class="form-control" value="<?php echo $mother_suffix; ?>">
                                            
                                            <small>Suffix name</small>
                                        </div>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span>
                                        <label for="phone">Phone no.</label>
                                        <div>
                                            <input type="tel" id="mother_contact_number" name="mother_contact_number" class="form-control" required value="<?php echo ($mother_contact_number != "") ? $mother_contact_number : '0915151515123'; ?>">
                                            
                                        </div>
                                        </span>
                                        <span>
                                        <label for="email">Email</label>
                                        <div>
                                            <input type="text" id="mother_email" name="mother_email" class="form-control" value="<?php echo ($mother_email != "") ? $mother_email : ''; ?>">

                                        </div>
                                        </span>
                                        <span>
                                        <label for="occupation">Occupation</label>
                                        <div>
                                            <input type="text" id="mother_occupation" name="mother_occupation" class="form-control" value="<?php echo ($mother_occupation != "") ? $mother_occupation : ''; ?>">
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
                                            <input type="text" name="parent_lastname" class="form-control" required value="<?php echo $parent_lastname; ?>">
                                            <small>Last name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="parent_firstname" class="form-control" required value="<?php echo $parent_firstname; ?>">

                                            <small>First name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="parent_middle_name" class="form-control" required value="<?php echo $parent_middle_name; ?>">
                                            <small>Middle name</small>
                                        </div>
                                        <div>
                                            <input type="text" name="parent_suffix" class="form-control" value="<?php echo $parent_suffix; ?>">
                                            <small>Suffix name</small>
                                        </div>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <span>
                                            <label for="phone">Contact no.</label>
                                            <div>
                                                <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control" required value="<?php echo ($parent_contact_number != "") ? $parent_contact_number : ''; ?>">
                                            </div>

                                            <label for="email">Email</label>
                                            <div>
                                                <input type="text" id="parent_email" name="parent_email" class="form-control" value="<?php echo ($parent_email != "") ? $parent_email : ''; ?>">
                                            </div>

                                            <label for="occupation">Occupation</label>
                                            <div>
                                                <input type="text" id="parent_occupation" name="parent_occupation" class="form-control" value="<?php echo ($parent_occupation != "") ? $parent_occupation : ''; ?>">
                                            </div>

                                            <label for="relationship">Relationship</label>
                                            <div>
                                                <input class="form-control"
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

                            <?php 
                                if($isFinished == 0){
                                    ?>
                                        <div class="action">
                                            <button style="margin-right: 9px;"
                                            type="button"
                                                class="default large"
                                                onclick="window.location.href = 'process.php?new_student=true&step=enrollee_parent_information'"
                                                >
                                                Return
                                            </button>
                                            <button
                                                class="default success large"
                                                onclick="<?php echo "MarkAsValidated($pending_enrollees_id)" ?>"
                                                
                                                type="button"
                                            >
                                                Validate
                                            </button>
                                        </div>
                                    <?php
                                }
                            ?>
                            

                        </form>
                                    <!-- onclick="window.location.href ='process.php?new_student=true&step=4'" -->


                        
                    </div>
                </main>
            </div>

        <?php
    }
?>

<script>
    function MarkAsValidated(pending_enrollees_id){

        // pending_enrollees_id = parseInt(pending_enrollees_id);

        Swal.fire({
            icon: 'info',
            title: 'I hereby declare that all provided information are credible.',
            text: 'Please note that this cant be undone.',
            showCancelButton: true,
            confirmButtonText: 'Ok',

          }).then(() => {

            $.ajax({
                url: '../../ajax/tentative/markAsValidated.php',
                type: 'POST',
                data: {
                    pending_enrollees_id
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
        });
    }
</script>