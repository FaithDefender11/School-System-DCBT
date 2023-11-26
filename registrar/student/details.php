
<?php 


    $pending = new Pending($con);
    
    $pending_enrollee_id = $pending->GetPendingAccountByStudentTable(
        $email, $firstname, $lastname);



    $student = new Student($con, $student_id);

    // $student_email = $student->GetEmail();
    // $student_firstname = $student->GetFirstName();
    // $student_lastname = $student->GetLastName();
    // $student_middle_name = $student->GetMiddleName();

    // $get_student_new_pending_id = $pending->GetPendingAccountByStudentTable(
    //     $student_email, $student_firstname, $student_lastname);

    // $pendingParent = new PendingParent($con, $pending_enrollee_id);

    $school_name = $parent->GetSchoolName();
    $school_address = $parent->GetSchoolAddress();
    $year_started = $parent->GetSchoolYearStarted();
    $year_ended = $parent->GetSchoolYearEnded();

    if(isset($_POST['student_details_btn'])){


        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middle_name = $_POST['middle_name'];
        $suffix = $_POST['suffix'];

        $civil_status = $_POST['civil_status'];
        $nationality = $_POST['nationality'];
        $sex = $_POST['sex'];
        $birthday = $_POST['birthday'];
        $birthplace = $_POST['birthplace'];
        $religion = $_POST['religion'];
        $address = $_POST['address'];

        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];

        $student_lrn = $_POST['student_lrn'];

        // var_dump($student_lrn);
        // return;


        $guardian_firstname = $_POST['guardian_firstname'];
        $guardian_lastname = $_POST['guardian_lastname'];
        $guardian_middle_name = $_POST['guardian_middle_name'];
        $guardian_suffix = $_POST['guardian_suffix'];

        $guardian_contact = $_POST['guardian_contact'];
        // $guardian_email = $_POST['guardian_email'];
        $guardian_email = "";
        $guardian_occupation = $_POST['guardian_occupation'];
        $guardian_relationship = $_POST['guardian_relationship'];


        $school_name = $_POST['school_name'];
        $school_address = $_POST['school_address'];
        $year_started = $_POST['year_started'];
        $year_ended = $_POST['year_ended'];

        // echo "school_name: $school_name";
        // echo "<br>";


        // echo "school_address: $school_address";
        // echo "<br>";

        // echo "year_started: $year_started";
        // echo "<br>";


        // echo "year_ended: $year_ended";
        // echo "<br>";

        // return;

        // Father
        // $father_firstname = $_POST['father_firstname'];
        // $father_lastname = $_POST['father_lastname'];
        // $father_middle = $_POST['father_middle'];
        // $father_suffix = $_POST['father_suffix'];
        // $father_contact_number = $_POST['father_contact_number'];
        // $father_email = $_POST['father_email'];
        // $father_occupation = $_POST['father_occupation'];

        // $mother_firstname = $_POST['mother_firstname'];
        // $mother_lastname = $_POST['mother_lastname'];
        // $mother_middle = $_POST['mother_middle'];
        // $mother_suffix = $_POST['mother_suffix'];
        // $mother_contact_number = $_POST['mother_contact_number'];
        // $mother_email = $_POST['mother_email'];
        // $mother_occupation = $_POST['mother_occupation'];
    
        # Update
        $editStudentExec = $student->UpdateStudentDetails(
            $student_id, $firstname, $lastname,
            $middle_name, $suffix, $civil_status, $nationality, $sex,
            $birthday, $birthplace, $religion, $address, $contact_number,
            $email, $student_lrn
        );


        $editParentExec = $parent->UpdateStudentParent(
        $student_id, $parent_id, $guardian_firstname, $guardian_lastname,
        $guardian_middle_name, $guardian_suffix, $guardian_contact,
            $guardian_email, $guardian_occupation, $guardian_relationship,


            // $father_firstname,
            // $father_lastname,
            // $father_middle,
            // $father_suffix,
            // $father_contact_number,
            // $father_email,
            // $father_occupation,
            // $mother_firstname,
            // $mother_lastname,
            // $mother_middle,
            // $mother_suffix,
            // $mother_contact_number,
            // $mother_email,
            // $mother_occupation

            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",

            $school_name, $school_address, $year_started, $year_ended

        );

        if($editStudentExec || $editParentExec){
            Alert::success("Successfully save Changes", "");
            exit();
        }
    }


    $firstname = ucwords($firstname);
?>

<div class="content">
     <nav>
        <a href="index.php"><i class="bi bi-arrow-return-left fa-1x"></i>
            <h3>Back</h3>
        </a>
    </nav>
    <div class="content-header">
        <?php echo Helper::RevealStudentTypePending($type); ?>

        <header>
            <div class="title">
                <h2><?php echo $lastname;?>, <?php echo $firstname;?> <?php echo $middle_name;?> <?php echo $suffix;?></h2>
            </div>
            <div class="action">
                <div class="dropdown">
                <button class="icon">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                    <div class="dropdown-menu">

                        <?php
                            if($student_active_status == 0) {
                                ?>
                                <a 
                                    onclick="setAsActive(<?php echo $student_id; ?>)"
                                    class="dropdown-item" 
                                    style="cursor: pointer;
                                    color: green">
                                        <i class="bi bi-file-earmark-x"></i>
                                    Set as active
                                </a>
                                <?php
                            }

                            // Has student table but doesnt have a previous form
                            if($studentHasForm) {
                                ?>
                                <a 
                                    href="../admission/process_enrollment.php?enrollee_find_section=true&id=<?php echo $pending_enrollee_id;?>'" 
                                    class="dropdown-item" style="color: blue">
                                        <i class="bi bi-file-earmark-x"></i>
                                    Create Form
                                </a>
                                <?php
                            }
                            if($student_admission_status == "withdraw" 
                                && $student_active_status == 0
                                ){
                                    ?>
                                        <a 
                                            onclick="window.location.href='../admission/process_enrollment.php?enrollee_find_section=true&id=<?php echo $pending_enrollee_id;?>'"
                                            class="dropdown-item" style="cursor:pointer;color: yellow">
                                            <i class="bi bi-file-earmark-x"></i>
                                            Create Form
                                        </a>
                                    <?php
                                }
                        ?>


                    </div>
                </div>
            </div>
        </header>

        <?php 
            echo Helper::CreateStudentTabs($student_unique_id, $student_level,
                $type, $section_acronym, $student_active_status,
                $enrollment_date);
            
        ?>

            
    </div>

    <div class="tabs">
        <?php
            echo "
                <button class='tab' 
                    style='background-color: var(--mainContentBG)'
                    onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\">
                    <i class='bi bi-clipboard-check'></i>
                    Student Details
                </button>
            ";

            echo "
                <button class='tab' 
                    id='shsPayment'
                    style='background-color: var(--them); color: white'
                    onclick=\"window.location.href = 'record_details.php?id=$student_id&grade_records=show';\">
                    <i class='bi bi-book'></i>
                    Grade Records
                </button>
            ";

            echo "
                <button class='tab' 
                    id='shsPayment'
                    style='background-color: var(--them); color: white'
                    onclick=\"window.location.href = 'record_details.php?id=$student_id&enrolled_subject=show';\">
                    <i class='bi bi-collection icon'></i>
                    Enrolled Subjects
                </button>
            ";
        ?>
    </div>

    <main>
        <div class="floating">
                
             
            <header class="mt-4">
                <div class="title">
                    <h4 style="font-weight: bold;">Student Information</h4>
                </div>
            </header>
                                <br>
            <form method="POST">

                                

                <?php if($type_status == 0):?>
                    
                    <div class="row">
                        <span style="margin-left: 460px;">

                            <label for="student_lrn">LRN *</label>
                            <input class="form-control" placeholder="" style="width: 250px;" id="student_lrn" type="text" name="student_lrn" value="<?php echo $student_lrn;?>">
                        </span>
                    </div>

                <?php endif;?>

                <main>
                    <div class="row">


                        <span>
                            <label for="name">Name</label>
                            <div>
                                <input autocomplete="off"  type="text" name="lastname" id="lastname" value="<?php echo $lastname;?>" class="form-control" />
                                <small>Last name</small>
                            </div>
                            <div>
                            <input autocomplete="off"  type="text" name="firstname" id="firstname" value="<?php echo $firstname;?>" class="form-control" />
                            <small>First name</small>
                            </div>
                            <div>
                            <input autocomplete="off"  type="text" name="middle_name" id="middle_name" value="<?php echo $middle_name;?>" class="form-control" />
                            <small>Middle name</small>
                            </div>
                            <div>
                            <input autocomplete="off"  type="text" name="suffix" id="suffix" value="<?php echo $suffix;?>" class="form-control" />
                            <small>Suffix name</small>
                            </div>
                        </span>
                    </div>

                    <div class="row">
                      <span>
                          <label for="status">Status</label>
                          <div>
                          <select name="civil_status" id="civil_status" class="form-control">
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
                          <input type="text" name="nationality" id="nationality" value="<?php echo $nationality;?>" class="form-control" />
                          </div>
                      </span>

                      <span>
                          <label for="sex">Gender</label>
                          <div>
                          <select name="sex" id="sex" class="form-control">
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
                          <input type="date" name="birthday" id="birthday" value="<?php echo $birthday;?>" class="form-control" />
                          </div>
                      </span>
                      <span>
                          <label for="birthplace">Birthplace</label>
                          <div>
                          <input type="text" name="birthplace" id="birthplace" value="<?php echo $birthplace;?>" class="form-control" />
                          </div>
                      </span>
                      <span>
                          <label for="religion">Religion</label>
                          <div>
                          <input type="text" name="religion" id="religion" value="<?php echo $religion;?>" class="form-control" />
                          </div>
                      </span>
                      </div>

                      <div class="row">
                      <span>
                          <label for="address">Address</label>
                          <div>
                          <input type="text" name="address" id="address" value="<?php echo $address;?>" class="form-control" />
                          </div>
                      </span>
                      </div>

                      <div class="row">
                      <span>
                          <label for="phoneNo">Phone no.</label>
                          <div>
                          <input type="text" name="contact_number" id="contact_number" value="<?php echo $contact_number;?>" class="form-control" />
                          </div>
                      </span>
                      <span>
                          <label for="email">Email</label>
                          <div>
                          <input style="pointer-events: none;" type="email" name="email" id="email" value="<?php echo $email;?>" class="form-control" />
                          </div>
                      </span>
                    </div>
                </main>

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

                <br>
                <div id="guardian_information">

                    <header>
                    <div class="mb-1 title">
                        <h4 style="font-weight: bold;">Guardian's Information</h4>
                    </div>
                    </header>

                    <main>
                        <div class="row">
                        <span>
                            <label for="name">Name</label>
                            <div>
                            <input  type="text" name="guardian_firstname" id="guardian_firstname" value="<?php echo $parent_firstname;?>" class="form-control" />
                            <small>Last name</small>
                            </div>
                            <div>
                            <input autocomplete="off" type="text" name="guardian_lastname" id="guardian_lastname" value="<?php echo $parent_lastname;?>" class="form-control" />
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
                        
                    </main>

                </div>


                <div class="action modal-footer">
                    <button type="submit"
                        name="student_details_btn"
                        class="default large clean" >
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </main>
</div>

<script>
    var dropBtns = document.querySelectorAll(".icon");

    dropBtns.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const dropMenu = e.currentTarget.nextElementSibling;
            if (dropMenu.classList.contains("show")) {
                dropMenu.classList.toggle("show");
            } else {
                document.querySelectorAll(".dropdown-menu").forEach(item => item.classList.remove("show"));
                dropMenu.classList.add("show");
            }
        });
    });
    function setAsActive(student_id, enrollment_id, school_year_id){

        var student_id = parseInt(student_id);
         
        Swal.fire({
            icon: 'question',
            title: `Are you sure to activate student account?`,
            text: 'Note: This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/student/setAsActive.php',
                    type: 'POST',
                    data: {
                        student_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        Swal.fire({
                            icon: 'success',
                            title: `Student is now activated`,
                        });

                        setTimeout(() => {
                            Swal.close();
                            location.reload();
                            // window.location.href = "evaluation.php";
                        }, 1000);
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }
    // function studentRemoveForm(student_id, enrollment_id, school_year_id){

    //     var student_id = parseInt(student_id);
    //     var enrollment_id = parseInt(enrollment_id);
    //     var school_year_id = parseInt(school_year_id);

    //     Swal.fire({
    //         icon: 'question',
    //         title: `Are you sure to un-enroll this enrollment form?`,
    //         text: 'Note: This action cannot be undone.',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'Cancel'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // REFX
    //             $.ajax({
    //                 url: '../../ajax/admission/unEnrollEnrolledForm.php',
    //                 type: 'POST',
    //                 data: {
    //                     student_id, enrollment_id, school_year_id
    //                 },
    //                 success: function(response) {

    //                     response = response.trim();

    //                     console.log(response);

    //                     // Swal.fire({
    //                     //     icon: 'success',
    //                     //     title: `Enrollment Form has been removed..`,
    //                     // });

    //                     // setTimeout(() => {
    //                     //     Swal.close();
    //                     //     // location.reload();
    //                     //     window.location.href = "evaluation.php";
    //                     // }, 1000);
    //                 },

    //                 error: function(jqXHR, textStatus, errorThrown) {
    //                     console.log('AJAX Error:', textStatus, errorThrown);
    //                 }
    //             });
    //         }
    //     });
    // }

</script>