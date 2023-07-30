
<?php 

    $pending = new Pending($con, $pending_enrollees_id);
    $parent = new PendingParent($con, $pending_enrollees_id);

    // Check if already enrollee has a parent -> CREATE.
    if($parent->CheckEnrolleeHasParent($pending_enrollees_id)){
        # UPDATE.
        // echo "has";
        # CREATE.
    }else{
        // echo "not";
    }

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

    // $pending_enrollees_id = $pending->

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

    if(
        $_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['parent_details_btn_' . $pending_enrollees_id])
        && isset($_POST['father_firstname'])
        && isset($_POST['father_lastname'])
        ){


        $father_lastname = Helper::ValidateLastname($_POST['father_lastname']);
        $father_firstname = Helper::ValidateFirstname($_POST['father_firstname']);
        $father_middle_name = Helper::ValidateMiddlename($_POST['father_middle']);
        $father_suffix = Helper::ValidateSuffix($_POST['father_suffix']);
        $father_contact_number = Helper::ValidateContactNumber($_POST['father_contact_number']);
        $father_occupation = Helper::ValidateOccupation($_POST['father_occupation']);
        $father_email = isset($_POST['father_email']) ?  Helper::ValidateEmail($_POST['father_email'], true) : '';
        

        $mother_firstname = Helper::ValidateMotherLastname($_POST['mother_firstname']);
        $mother_lastname = Helper::ValidateMotherFirstname($_POST['mother_lastname']);
        $mother_middle_name = Helper::ValidateMotherMiddlename($_POST['mother_middle']);

        // $mother_suffix = Helper::ValidateSuffix($_POST['mother_suffix']);

        $mother_contact_number = Helper::ValidateContactNumber($_POST['mother_contact_number']);
        $mother_occupation = Helper::ValidateOccupation($_POST['mother_occupation']);
        $mother_email = isset($_POST['mother_email']) ?  Helper::ValidateEmail($_POST['mother_email'], true) : '';

        // $mother_firstname = $_POST['mother_firstname'];
        // $mother_lastname = $_POST['mother_lastname'];
        // $mother_middle_name = $_POST['mother_middle'];
        // $mother_suffix = $_POST['mother_suffix'];
        // $mother_contact_number = $_POST['mother_contact_number'];
        // $mother_occupation = isset($_POST['mother_occupation']) ? $_POST['mother_occupation'] : '';
        // $mother_email = isset($_POST['mother_email']) ? $_POST['mother_email'] : '';

        $parent_firstname = $_POST['parent_firstname'];
        $parent_middle_name = $_POST['parent_middle_name'];
        $parent_lastname = $_POST['parent_lastname'];
        $parent_suffix = isset($_POST['parent_suffix']) ? $_POST['parent_suffix'] : '';

        $parent_contact_number = $_POST['parent_contact_number'];
        $parent_email = isset($_POST['parent_email']) ? $_POST['parent_email'] : '';
        $parent_suffix = isset($_POST['parent_suffix']) ? $_POST['parent_suffix'] : '';
        $parent_occupation = isset($_POST['parent_occupation']) ? $_POST['parent_occupation'] : '';
        $parent_relationship = isset($_POST['parent_relationship']) ? $_POST['parent_relationship'] : '';

        $defaultRedirect = true;

        if(empty(Helper::$errorArray)){
            echo "empty error";
            if($parent->CheckEnrolleeHasParent($pending_enrollees_id)){


                $mother_suffix = "";

                // UPDATE

                // $updateEnroleeParent = $parent->UpdatePendingParent(
                //     $pending_enrollees_id, $parent_id, $parent_firstname, $parent_lastname,
                //     $parent_middle_name, $parent_suffix, $parent_contact_number,
                //     $parent_email, $parent_occupation, $parent_relationship,
                    
                //     $father_firstname,
                //     $father_lastname,
                //     $father_middle_name,
                //     $father_suffix,
                //     $father_contact_number,
                //     $father_email,
                //     $father_occupation,
                //     $mother_firstname,
                //     $mother_lastname,
                //     $mother_middle,
                //     $mother_suffix,
                //     $mother_contact_number,
                //     $mother_email,
                //     $mother_occupation
                // );

                // if($updateEnroleeParent){
                //     $defaultRedirect = false;
                    
                //     Alert::success("Success Update", "process.php?new_student=true&step=enrollee_summary_details");
                //     // Alert::success("Success Update", "enrollee_summary_details.php?id=$pending_enrollees_id&details=show");
                //     exit();

                // }else{
                // }

            }else if($parent->CheckEnrolleeHasParent($pending_enrollees_id) == false){
                
                $mother_suffix = "";
                
                // Create
                // $createEnroleeParent = $parent->InsertParentInformation(
                //     $pending_enrollees_id,
                //     $parent_firstname,
                //     $parent_lastname,
                //     $parent_middle_name,
                //     $parent_suffix,
                //     $parent_contact_number,
                //     $parent_email,
                //     $parent_occupation,
                //     $parent_relationship,

                //     $father_firstname,
                //     $father_lastname,
                //     $father_middle_name,
                //     $father_suffix,
                //     $father_contact_number,
                //     $father_email,
                //     $father_occupation,

                //     $mother_firstname,
                //     $mother_lastname,
                //     $mother_middle_name,
                //     $mother_suffix,
                //     $mother_contact_number,
                //     $mother_email,
                //     $mother_occupation);

                // if($createEnroleeParent){

                //     $defaultRedirect = false;
                //     // echo "success create";
                //     Alert::success("Success Creation", "process.php?new_student=true&step=enrollee_summary_details");
                //     exit();
                // }
            } 
        }else{
            echo "has error";
        }

        // if($defaultRedirect == true){
        //     // header("Location: enrollee_summary_details.php?id=$pending_enrollees_id&details=show");
        //     header("Location: process.php?new_student=true&step=enrollee_summary_details");
        //     exit(); 
        // }

        // Echo each variable with its value

        // echo "Father's First Name: $father_firstname <br>";
        // echo "Father's Last Name: $father_lastname <br>";
        // echo "Father's Middle Name: $father_middle_name <br>";
        // echo "Father's Suffix: $father_suffix <br>";
        // echo "Father's Contact Number: $father_contact_number <br>";
        // echo "Father's Occupation: $father_occupation <br>";
        // echo "Father's Email: $father_email <br>";
        // echo "Mother's First Name: $mother_firstname <br>";
        // echo "Mother's Middle Name: $mother_middle  <br>";
        // echo "Mother's Last Name: $mother_lastname <br>";
        // echo "Mother's Suffix: $mother_suffix <br>";
        // echo "Mother's Contact Number: $mother_contact_number <br>";
        // echo "Mother's Occupation: $mother_occupation <br>";
        // echo "Mother's Email: $mother_email <br>";
        // echo "Parent's First Name: $parent_firstname <br>";
        // echo "Parent's Middle Name: $parent_middle_name <br>";
        // echo "Parent's Last Name: $parent_lastname <br>";
        // echo "Parent's Suffix: $parent_suffix <br>";
        // echo "Parent's Contact Number: $parent_contact_number <br>";
        // echo "Parent's Email: $parent_email <br>";
        // echo "Parent's Occupation: $parent_occupation <br>";
        // echo "Parent's Relationship: $parent_relationship <br>";
    }

?>

    <div class="content">

        <nav>
            <a href="">
                <i class="bi bi-arrow-return-left fa-10x"></i>
                <h3>Logout</h3>
            </a>
        </nav>

        <main>
            <div class="floating noBorder">
                <header>
                    <div class="title">
                    <h2 style="color: var(--titleTheme)">New Enrollment Form</h2>
                        <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester; ?> Semester </small>

                    </div>
                </header>

                <div class="progress">
                    <span class="dot active"><p>Preferred Course/Strand</p></span>
                    <span class="line active"></span>
                    <span class="dot active"> <p>Student Information</p></span>
                    <span class="line inactive"></span>
                    <span class="dot inactive"> <p>Validate Details</p></span>
                    <span class="line inactive"></span>
                    <span class="dot inactive"> <p>Finished</p></span>
                </div>

                <form method="POST">
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
                                        <?php 
                                            Helper::EchoErrorField(
                                                Constants::$lastNameRequired,
                                                Constants::$invalidLastNameCharacters,
                                                Constants::$lastNameIsTooShort,
                                                Constants::$lastNameIsTooLong
                                            );
                                        ?>
                                        <input type="text" name="father_lastname" class="form-control" 
                                            value="<?php 
                                                    echo Helper::DisplayText('father_lastname', $father_lastname);  
                                                ?>">
                                        <small>Last name</small>
                                    </div>
                                    <div>
                                        <?php 
                                            Helper::EchoErrorField(
                                                Constants::$firstNameRequired,
                                                Constants::$invalidFirstNameCharacters,
                                                Constants::$firstNameIsTooShort,
                                                Constants::$firstNameIsTooLong
                                            );
                                        ?>
                                        <input type="text" name="father_firstname" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('father_firstname', $father_firstname);  
                                            ?>">
                                        <small>First name</small>
                                    </div>
                                    <div>
                                        <?php 
                                            Helper::EchoErrorField(
                                                Constants::$middleNameRequired,
                                                Constants::$invalidMiddleNameCharacters,
                                                Constants::$middleNameIsTooShort,
                                                Constants::$middleNameIsTooLong
                                            );
                                        ?>
                                        <input type="text" name="father_middle" class="form-control" 
                                            value="<?php 
                                                echo Helper::DisplayText('father_middle', $father_middle);  
                                            ?>">
                                        <small>Middle name</small>
                                    </div>
                                    <div>
                                        <?php
                                            echo Helper::getError(Constants::$invalidSuffixNameCharacters);
                                        ?>
                                        <input type="text" name="father_suffix" maxlength="3" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('father_suffix', $father_suffix);;
                                            ?>">
                                        <small>Suffix name</small>
                                    </div>
                                </span>
                            </div>

                            <div class="row">
                                <span>
                                    <?php
                                            echo Helper::getError(Constants::$ContactNumberRequired);
                                            echo Helper::getError(Constants::$invalidContactNumberCharacters);
                                            echo Helper::getError(Constants::$invalidContactNumber2Characters);
                                        ?>
                                    <label for="phone">Phone no.</label>
                                    <div>
                                        <input type="tel" id="father_contact_number" name="father_contact_number" class="form-control" 
                                            value="<?php 
                                                echo Helper::DisplayText('father_contact_number', $father_contact_number);;
                                            ?>">
                                    </div>
                                </span>
                                <span>
                                    <?php
                                        echo Helper::getError(Constants::$EmailRequired);
                                        echo Helper::getError(Constants::$invalidEmailCharacters);
                                    ?>
                                    <label for="email">Email</label>
                                    <div>
                                        <input type="text" id="father_email" name="father_email" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('father_email', $father_email);;
                                            ?>">
                                    </div>
                                </span>
                                <span>
                                    <?php 
                                        echo Helper::getError(Constants::$fatherOccupationRequired);
                                        echo Helper::getError(Constants::$invalidFatherOccupationCharacters);
                                    ?>
                                    <label for="occupation">Occupation</label>
                                    <div>
                                        <input type="text" id="father_occupation" name="father_occupation" class="form-control" 
                                            value="<?php 
                                                echo Helper::DisplayText('father_occupation', $father_occupation);;
                                            ?>">
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

                                    <?php 
                                        Helper::EchoErrorField(
                                            Constants::$motherLastNameRequired,
                                            Constants::$invalidMotherLastNameCharacters,
                                            Constants::$motherLastNameIsTooShort,
                                            Constants::$motherLastNameIsTooLong
                                        );
                                    ?>

                                    <input type="text" name="mother_lastname" class="form-control"\
                                        value="<?php 
                                            echo Helper::DisplayText('mother_lastname', $mother_lastname); 
                                        ?>">

                                    <small>Last name</small>
                                </div>

                                <div>
                                    <?php 
                                        Helper::EchoErrorField(
                                            Constants::$motherFirstNameRequired,
                                            Constants::$invalidMotherFirstNameCharacters,
                                            Constants::$motherFirstNameIsTooShort,
                                            Constants::$motherFirstNameIsTooLong
                                        );
                                    ?>
                                    <input type="text" name="mother_firstname" class="form-control"
                                        value="<?php 
                                            echo Helper::DisplayText('mother_firstname', $mother_firstname); 
                                        ?>">
                                    <small>First name</small>
                                </div>

                                <div>
                                    <input type="text" name="mother_middle" class="form-control"  value="<?php echo $mother_middle; ?>">
                                    
                                    <small>Middle name</small>
                                </div>
                                <!-- <div>
                                    <input type="text" name="mother_suffix" class="form-control" maxlength="3" value="<?php echo $mother_suffix; ?>">
                                    
                                    <small>Suffix name</small>
                                </div> -->
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                <label for="phone">Phone no.</label>
                                <div>
                                    <input type="tel" id="mother_contact_number" name="mother_contact_number" class="form-control"  value="<?php echo ($mother_contact_number != "") ? $mother_contact_number : '0915151515123'; ?>">
                                    
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
                                    <input type="text" name="parent_lastname" class="form-control"  value="<?php echo $parent_lastname; ?>">
                                    <small>Last name</small>
                                </div>
                                <div>
                                    <input type="text" name="parent_firstname" class="form-control"  value="<?php echo $parent_firstname; ?>">

                                    <small>First name</small>
                                </div>
                                <div>
                                    <input type="text" name="parent_middle_name" class="form-control"  value="<?php echo $parent_middle_name; ?>">
                                    <small>Middle name</small>
                                </div>
                                <div>
                                    <input type="text" name="parent_suffix" class="form-control" maxlength="3" value="<?php echo $parent_suffix; ?>">
                                    <small>Suffix name</small>
                                </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="phone">Phone no.</label>
                                    <div>
                                        <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control"  value="<?php echo ($parent_contact_number != "") ? $parent_contact_number : ''; ?>">

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
                                </span>
                            </div>
                        </div>

                    </main>

                    <div class="action">
                        <button style="margin-right: 9px;"
                            type="button"
                            class="default large"
                            onclick="window.location.href = 'process.php?new_student=true&step=enrollee_information';"
                            >Return
                        </button>
                        <button
                            class="default success large"
                            name="parent_details_btn_<?php echo $pending_enrollees_id ?>" 
                            type="submit">
                            Proceed
                        </button>
                    </div>

                </form>

            </div>
        </main>
    </div>
<?php

?>






