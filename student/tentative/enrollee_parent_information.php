
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


    // No father and mother 
    // - No guardian -> Error
    // - Yes guardian -> Correct 

    // Yes father and no mother -> Valid
    // Nofather and Yes mother -> Valid

    // Yes father and yes mother 
    // - No guardian -> Correct
    // - Yes guardian -> Correct

    if($_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['parent_details_btn_' . $pending_enrollees_id])
        // && isset($_POST['father_firstname'])
        // && isset($_POST['father_lastname'])
        // && isset($_POST['father_middle'])
        // && isset($_POST['father_contact_number'])
        // && isset($_POST['father_occupation'])
        // && isset($_POST['father_email'])

        // && isset($_POST['mother_firstname'])
        // && isset($_POST['mother_lastname'])
        // && isset($_POST['mother_middle'])
        // && isset($_POST['mother_contact_number'])
        // && isset($_POST['mother_occupation'])
        // && isset($_POST['mother_email'])

        ){
            
        $father_lastname = $parent->ValidateFatherLastName($_POST['father_lastname']);

        
        $father_firstname_bool = $father_lastname !== "" 
            ? true : false;

        $father_firstname = $parent->ValidateFatherFirstName(
            $_POST['father_firstname'], $father_firstname_bool);

        if($father_firstname !== "" && $father_lastname === ""){
            $father_lastname = $parent->ValidateFatherLastName(
                $_POST['father_lastname'], true);
        }

        // FATHER MIDDLENAME
        $father_middle = $parent->ValidateFatherMiddlename($_POST['father_middle']);

        if($father_middle !== ""){

            if($father_contact_number === NULL){

                $father_contact_number = $parent->ValidateFatherContactNumber(
                    $_POST['father_contact_number'], true);
            }

            if($father_firstname === ""){

                $father_firstname = $parent->ValidateFatherFirstname(
                    $_POST['father_firstname'], true);
            }

            if($father_lastname === ""){

                $father_lastname = $parent->ValidateFatherLastname(
                    $_POST['father_lastname'], true);
            }
        }

        // FATHER SUFFIX
        $father_suffix = $parent->ValidateFatherSuffix($_POST['father_suffix']);

        // var_dump($father_suffix);

        if($father_suffix !== ""){

            if($father_contact_number === NULL){

                $father_contact_number = $parent->ValidateFatherContactNumber(
                    $_POST['father_contact_number'], true);
            }

            if($father_firstname === ""){

                $father_firstname = $parent->ValidateFatherFirstname(
                    $_POST['father_firstname'], true);
            }

            if($father_lastname === ""){

                $father_lastname = $parent->ValidateFatherLastname(
                    $_POST['father_lastname'], true);
            }

        }


        $father_contact_bool = $father_lastname !== ""
            || $father_firstname !== "" ? true : false;

        $father_contact_number = $parent->ValidateFatherContactNumber(
            $_POST['father_contact_number'], $father_contact_bool);


        if($father_contact_number !== NULL){

            if($father_lastname === ""
                ){
                $father_lastname = $parent->ValidateFatherLastName(
                    $_POST['father_lastname'], true);
            }
            if($father_firstname === ""
                ){
                $father_firstname = $parent->ValidateFatherFirstName(
                    $_POST['father_firstname'], true);
            }
        }


        // FATHER MIDDLE NAME 
        if($father_middle !== ""){

            if($father_contact_number === NULL){
                $father_contact_number = $parent->ValidateFatherContactNumber(
                    $_POST['father_contact_number'], true);
            }

            if($father_firstname === ""){
                $father_firstname = $parent->ValidateFatherFirstname(
                    $_POST['father_firstname'], true);
            }

            if($father_lastname === ""){
                $father_lastname = $parent->ValidateFatherLastname(
                    $_POST['father_lastname'], true);
            }

        }


        # FATHER OCCUPATION.
        $father_occupation = $parent->ValidateFatherOccupation($_POST['father_occupation']);

        if($father_occupation !== ""){

            if($father_contact_number === NULL){

                $father_contact_number = $parent->ValidateFatherContactNumber(
                    $_POST['father_contact_number'], true);
            }

            if($father_firstname === ""){

                $father_firstname = $parent->ValidateFatherFirstname(
                    $_POST['father_firstname'], true);
            }

            if($father_lastname === ""){

                $father_lastname = $parent->ValidateFatherLastname(
                    $_POST['father_lastname'], true);
            }

        }
         
        // $father_email = isset($_POST['father_email']) ?
        //     $parent->ValidateFatherEmail($_POST['father_email'], true) : '';


        $mother_lastname = $parent->ValidateMotherLastName(
            $_POST['mother_lastname']);

       
        $mother_firstname_bool = $mother_lastname !== "" ? true : false;

        $mother_firstname = $parent->ValidateMotherFirstname(
            $_POST['mother_firstname'], $mother_firstname_bool);

        if($mother_firstname !== "" && $mother_lastname === ""){
            $mother_lastname = $parent->ValidateMotherLastName(
                $_POST['mother_lastname'], true);
        }
   
        $mother_contact_bool = $mother_lastname !== ""
            || $mother_firstname !== "" ? true : false;


        $mother_contact_number = $parent->ValidateMotherContactNumber(
            $_POST['mother_contact_number'], $mother_contact_bool);
        

        if($mother_contact_number !== NULL){
            if($mother_lastname === ""){

                $mother_lastname = $parent->ValidateMotherLastName(
                    $_POST['mother_lastname'], true);
            }
            if($mother_firstname === ""){

                $mother_firstname = $parent->ValidateMotherFirstName(
                    $_POST['mother_firstname'], true);
            }
        }
        
        $mother_middle = $parent->ValidateMotherMiddlename($_POST['mother_middle']);

        # If mother middle has user input, all mother required field should be provided.
        if($mother_middle !== ""){

            if($mother_contact_number === NULL){

                $mother_contact_number = $parent->ValidateMotherContactNumber(
                    $_POST['mother_contact_number'], true);
            }

            if($mother_firstname === ""){

                $mother_firstname = $parent->ValidateMotherFirstname(
                    $_POST['mother_firstname'], true);
            }

            if($mother_lastname === ""){

                $mother_lastname = $parent->ValidateMotherLastname(
                    $_POST['mother_lastname'], true);
            }
        }
        

        $mother_occupation = $parent->ValidateMotherOccupation($_POST['mother_occupation']);

        # If mother occupation has user input, all mother required field should be provided.
        
        if($mother_occupation !== ""){

            if($mother_contact_number === NULL){
                    
                $mother_contact_number = $parent->ValidateMotherContactNumber(
                    $_POST['mother_contact_number'], true);
            }
            if($mother_firstname === ""){

                $mother_firstname = $parent->ValidateMotherFirstname(
                    $_POST['mother_firstname'], true);
            }
            if($mother_lastname === ""){

                $mother_lastname = $parent->ValidateMotherLastname(
                    $_POST['mother_lastname'], true);
            }
        }
        
        # GUARDIAN SIDE.
        $parent_lastname = $parent->ValidateGuardianLastName($_POST['parent_lastname'],
            false);

        $guardian_lastname_bool = $parent_lastname !== "" ? true : false;

        $parent_firstname = $parent->ValidateGuardianFirstname($_POST['parent_firstname'],
            $guardian_lastname_bool);

        $parent_middle_name = $parent->ValidateGuardianMiddlename($_POST['parent_middle_name']);

        if($parent_middle_name !== ""){

            if($parent_contact_number === NULL){

                $parent_contact_number = $parent->ValidateGuardianContactNumber(
                    $_POST['parent_contact_number'], true);
            }
            
            if($parent_firstname === ""){

                $parent_firstname = $parent->ValidateGuardianFirstname(
                    $_POST['parent_firstname'], true);
            }

            if($parent_lastname === ""){

                $parent_lastname = $parent->ValidateGuardianLastname(
                    $_POST['parent_lastname'], true);
            }
        }

        $guardian_contact_bool = $parent_lastname !== ""
            || $parent_firstname !== "" ? true : false;

        $parent_contact_number = $parent->ValidateGuardianContactNumber(
            $_POST['parent_contact_number'], $guardian_contact_bool);

        # GUARDIAN SUFFIX.
        $parent_suffix = isset($_POST['parent_suffix']) ? $parent->ValidateGuardianSuffix($_POST['parent_suffix']
            ,false) : '';

        if($parent_suffix !== ""){

            if($parent_contact_number === NULL){

                $parent_contact_number = $parent->ValidateGuardianContactNumber(
                    $_POST['parent_contact_number'], true);
            }
            
            if($parent_firstname === ""){

                $parent_firstname = $parent->ValidateGuardianFirstname(
                    $_POST['parent_firstname'], true);
            }

            if($parent_lastname === ""){

                $parent_lastname = $parent->ValidateGuardianLastname(
                    $_POST['parent_lastname'], true);
            }
        }


        $parent_email = isset($_POST['parent_email']) 
            ? $parent->ValidateGuardianEmail($_POST['parent_email'],
            false) : '';

        $parent_occupation = isset($_POST['parent_occupation']) 
            ? $parent->ValidateGuardianOccupation($_POST['parent_occupation'],
            false) : '';

        if($parent_occupation !== ""){

            if($parent_contact_number === NULL){

                $parent_contact_number = $parent->ValidateGuardianContactNumber(
                    $_POST['parent_contact_number'], true);
            }
            
            if($parent_firstname === ""){

                $parent_firstname = $parent->ValidateGuardianFirstname(
                    $_POST['parent_firstname'], true);
            }

            if($parent_lastname === ""){

                $parent_lastname = $parent->ValidateGuardianLastname(
                    $_POST['parent_lastname'], true);
            }
        }

        $relationship_bool = $parent_lastname !== ""
            || $parent_firstname !== ""
            || $parent_occupation !== ""
             ? true : false;
 

        $parent_relationship = isset($_POST['parent_relationship']) ?
            $parent->ValidateGuardianRelationship($_POST['parent_relationship']
            , $relationship_bool) : '';


        $hasErrorInGuardian = false;
        $guardianEmptyError = false;
 

        $guardianError = false;
        // if($hasErrorInGuardian == false && $guardianEmptyError == true){
        if(empty(Helper::$errorArray)){
            
            if($father_lastname == "" && $father_firstname == ""

                && $mother_lastname == "" && $mother_firstname == ""

                && $parent_firstname == "" && $parent_lastname == ""
                && $parent_relationship == ""){

                # Student Should fill-up the guardian.

                $guardianError = true;
                Alert::errorNoRedirect("If you dont have father or mother. Please kindly fill-up guardian required input fields.",
                    "");
                // exit();

            }
        }
        
        $defaultRedirect = true;
        # If error above had arised, the add/updating function will not work.
        if(empty(Helper::$errorArray) && $guardianError == false){

            if($parent->CheckEnrolleeHasParent($pending_enrollees_id)){

                $mother_suffix = "";
                $mother_email = "";
                $father_email = "";
                
                // UPDATE
                $updateEnroleeParent = $parent->UpdatePendingParent(
                    $pending_enrollees_id, $parent_id, $parent_firstname, $parent_lastname,
                    $parent_middle_name, $parent_suffix, $parent_contact_number,
                    $parent_email, $parent_occupation, $parent_relationship,
                    
                    $father_firstname,
                    $father_lastname,
                    $father_middle,
                    $father_suffix,
                    $father_contact_number,
                    $father_email,
                    $father_occupation,
                    $mother_firstname,

                    $mother_lastname,
                    $mother_middle,
                    $mother_suffix,
                    $mother_contact_number,
                    $mother_email,
                    $mother_occupation
                );

                if($updateEnroleeParent){
                    $defaultRedirect = false;
                    Alert::success("Parent data has been successfully changed", "process.php?new_student=true&step=enrollee_summary_details");
                    // Alert::success("Success Update", "enrollee_summary_details.php?id=$pending_enrollees_id&details=show");
                    exit();

                }else{
                }

            }
            else if($parent->CheckEnrolleeHasParent($pending_enrollees_id) 
                == false){
                
                $mother_suffix = "";
                $mother_email = "";
                $father_email = "";
                
                // Create
                $createEnroleeParent = $parent->InsertParentInformationNewOnlineForm(
                    $pending_enrollees_id,
                    $parent_firstname,
                    $parent_lastname,
                    $parent_middle_name,
                    $parent_suffix,
                    $parent_contact_number,
                    $parent_email,
                    $parent_occupation,
                    $parent_relationship,

                    $father_firstname,
                    $father_lastname,
                    $father_middle,
                    $father_suffix,
                    $father_contact_number,
                    $father_email,
                    $father_occupation,

                    $mother_firstname,
                    $mother_lastname,
                    $mother_middle,
                    $mother_suffix,
                    $mother_contact_number,
                    $mother_email,
                    $mother_occupation);

                if($createEnroleeParent){

                    $defaultRedirect = false;
                    // echo "success create";
                    Alert::success("Parent data has been successfully created", "process.php?new_student=true&step=enrollee_summary_details");
                    exit();
                }
            } 
        }else{
            $defaultRedirect = false;
        }
        
        if($defaultRedirect == true){

            // header("Location: enrollee_summary_details.php?id=$pending_enrollees_id&details=show");
            header("Location: process.php?new_student=true&step=enrollee_summary_details");
            exit(); 
        }

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
            <a href="<?php echo $logout_url;?>">
                <i class="fas fa-sign-out-alt"></i>
                <h3>Logout</h3>
            </a>
        </nav>
        <main>
            <div class="floating noBorder">

                <header>
                    <div class="title">
                    <h2 style="color: var(--titleTheme)">Enrollee Enrollment Form</h2>
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

                <main>
                    <form method="POST">
                        <!-- <header >
                            <div class="title">
                                <h4 style="font-weight: bold;">Enrollee Parent Information</h4>
                            </div>
                        </header> -->
                        <!-- FATHER DD -->
                        <div style="display: none;" id="father_info">
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
                                                Constants::$fatherLastNameRequired,
                                                Constants::$invalidFatherLastNameCharacters,
                                                Constants::$fatherLastNameIsTooShort,
                                                Constants::$fatherLastNameIsTooLong
                                            );
                                        ?>
                                        <input type="text" name="father_lastname" class="form-control" 
                                            value="<?php 
                                                    echo Helper::DisplayText('father_lastname', $father_lastname);  
                                                ?>">
                                        <small>Last name <span class="red">*</span></small>
                                    </div>
                                    <div>
                                        <?php 
                                            Helper::EchoErrorField(
                                                Constants::$fatherFirstNameRequired,
                                                Constants::$invalidFatherFirstNameCharacters,
                                                Constants::$fatherFirstNameIsTooShort,
                                                Constants::$fatherFirstNameIsTooLong
                                            );
                                        ?>
                                        <input type="text" name="father_firstname" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('father_firstname', $father_firstname);  
                                            ?>">
                                        <small>First name <span class="red">*</span></small>
                                    </div>
                                    <div>
                                        <?php 
                                            Helper::EchoErrorField(
                                                Constants::$fatherMiddleNameRequired,
                                                Constants::$invalidFatherMiddleNameCharacters,
                                                Constants::$fatherMiddleNameIsTooShort,
                                                Constants::$fatherMiddleNameIsTooLong
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
                                            echo Helper::getError(Constants::$invalidFatherSuffixNameCharacters);
                                        ?>
                                        <input type="text" placeholder="e.g. Jr, Sr, II" name="father_suffix" maxlength="3" class="form-control"
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
                                        echo Helper::getError(Constants::$fatherContactNumberRequired);
                                        echo Helper::getError(Constants::$invalidFatherContactNumberCharacters);
                                        echo Helper::getError(Constants::$invalidFatherContactNumber2Characters);
                                    ?>
                                    <label for="phone">Phone no  <span class="red">*</span></label>
                                    <div>
                                        <input type="tel" id="father_contact_number" name="father_contact_number" class="form-control" 
                                            value="<?php 
                                                echo Helper::DisplayText('father_contact_number', $father_contact_number);;
                                            ?>">
                                    </div>
                                </span>
                                <span>
                                    <!-- <?php
                                        echo Helper::getError(Constants::$fatherEmailRequired);
                                        echo Helper::getError(Constants::$invalidFatherEmailCharacters);
                                    ?>
                                    <label for="email">Email</label>
                                    <div>
                                        <input type="text" id="father_email" name="father_email" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('father_email', $father_email);;
                                            ?>">
                                    </div> -->
                                </span>
                                <span>
                                    <?php 
                                        echo Helper::getError(Constants::$fatherOccupationRequired);
                                        echo Helper::getError(Constants::$invalidFatherOccupationCharacters);
                                        echo Helper::getError(Constants::$fatherOccupationIsTooShort);
                                        echo Helper::getError(Constants::$fatherOccupationIsTooLong);
                                    ?>
                                    <label for="occupation">Occupation</label>
                                    <div>
                                        <input type="text" id="father_occupation" name="father_occupation"
                                            class="form-control" 
                                            value="<?php 
                                                echo Helper::DisplayText('father_occupation', $father_occupation);;
                                            ?>">
                                    </div>
                                </span>
                            </div>
                        </div>
                        <!-- MOTHER DD -->
                        <div style="display: none;" id="mother_info">
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
                                            echo Helper::getError(Constants::$motherLastNameRequired);
                                            echo Helper::getError(Constants::$invalidMotherLastNameCharacters);
                                            echo Helper::getError(Constants::$motherLastNameIsTooShort);
                                            echo Helper::getError(Constants::$motherLastNameIsTooLong);
                                        ?>

                                        <input type="text" name="mother_lastname" class="form-control"\
                                            value="<?php 
                                                echo Helper::DisplayText('mother_lastname', $mother_lastname); 
                                            ?>">

                                        <small>Last name <span class="red">*</span></small>
                                    </div>

                                    <div>
                                        <?php 
                                            echo Helper::getError(Constants::$motherFirstNameRequired);
                                            echo Helper::getError(Constants::$invalidMotherFirstNameCharacters);
                                            echo Helper::getError(Constants::$motherFirstNameIsTooShort);
                                            echo Helper::getError(Constants::$motherFirstNameIsTooLong);
                                        ?>
                                        <input type="text" name="mother_firstname" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('mother_firstname', $mother_firstname); 
                                            ?>">
                                        <small>First name <span class="red">*</span></small>
                                    </div>

                                    <div>
                                        <?php 
                                            echo Helper::getError(Constants::$motherMiddleNameRequired);
                                            echo Helper::getError(Constants::$invalidMotherMiddleNameCharacters);
                                            echo Helper::getError(Constants::$motherMiddleNameIsTooShort);
                                            echo Helper::getError(Constants::$motherMiddleNameIsTooLong);
                                        ?>
                                        <input type="text" name="mother_middle" class="form-control" 
                                            value="<?php 
                                                echo Helper::DisplayText('mother_middle', $mother_middle); 
                                            ?>">
                                        
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
                                    <?php 
                                        echo Helper::getError(Constants::$motherContactNumberRequired);
                                        echo Helper::getError(Constants::$invalidMotherContactNumberCharacters);
                                        echo Helper::getError(Constants::$invalidMotherContactNumber2Characters);
                                    ?>
                                    <label for="phone">Phone no  <span class="red">*</span></label>
                                    <div>
                                        <input type="tel" id="mother_contact_number" name="mother_contact_number" class="form-control" 
                                            value="<?php
                                                echo Helper::DisplayText('mother_contact_number', $mother_contact_number); 
                                            ?>">
                                    </div>
                                </span>
                                <span>

                                    <!-- <?php 
                                        echo Helper::getError(Constants::$motherEmailRequired);
                                        echo Helper::getError(Constants::$invalidMotherEmailCharacters);
                                    ?>
                                    <label for="email">Email</label>
                                    <div>
                                        <input type="text" id="mother_email" name="mother_email" class="form-control" 
                                            value="<?php 
                                                echo Helper::DisplayText('mother_email', $mother_email); 
                                            ?>">
                                    </div> -->
                                </span>

                                <span>
                                    <?php 
                                        echo Helper::getError(Constants::$motherOccupationRequired);
                                        echo Helper::getError(Constants::$invalidMotherOccupationCharacters);
                                        echo Helper::getError(Constants::$motherOccupationIsTooShort);
                                        echo Helper::getError(Constants::$motherOccupationIsTooLong);
                                    ?>
                                    <label for="occupation">Occupation</label>
                                    <div>
                                        <input type="text" id="mother_occupation" name="mother_occupation" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('mother_occupation', $mother_occupation); 
                                            ?>">
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="guardian_info">
                            <header class="mb-2">
                                <div class="title">
                                    <h4 style="font-weight: bold;">Guardian Information</h4>
                                </div>
                            </header>

                            <div class="row">
                                <span>
                                <label for="name">Name</label>
                                <div>
                                    <?php 
                                        echo Helper::getError(Constants::$guardianLastNameRequired);
                                        echo Helper::getError(Constants::$invalidGuardianLastNameCharacters);
                                        echo Helper::getError(Constants::$guardianLastNameIsTooShort);
                                        echo Helper::getError(Constants::$guardianLastNameIsTooLong);
                                    ?>
                                    <input type="text" name="parent_lastname" class="form-control"
                                        value="<?php 
                                            echo Helper::DisplayText('parent_lastname', $parent_lastname); 
                                        ?>">
                                    <small>Last name <span class="red">*</span></small>
                                </div>
                                <div>
                                    <?php 
                                        echo Helper::getError(Constants::$guardianFirstNameRequired);
                                        echo Helper::getError(Constants::$invalidGuardianFirstNameCharacters);
                                        echo Helper::getError(Constants::$guardianFirstNameIsTooShort);
                                        echo Helper::getError(Constants::$guardianFirstNameIsTooLong);
                                    ?>
                                    <input type="text" name="parent_firstname" class="form-control" 
                                        value="<?php 
                                            echo Helper::DisplayText('parent_firstname', $parent_firstname); 
                                        ?>">

                                    <small>First name <span class="red">*</span></small>
                                </div>
                                <div>
                                    <?php 
                                        echo Helper::getError(Constants::$guardianMiddleNameRequired);
                                        echo Helper::getError(Constants::$invalidGuardianMiddleNameCharacters);
                                        echo Helper::getError(Constants::$guardianMiddleNameIsTooShort);
                                        echo Helper::getError(Constants::$guardianMiddleNameIsTooLong);
                                    ?>
                                    <input type="text" name="parent_middle_name" class="form-control" 
                                        value="<?php 
                                            echo Helper::DisplayText('parent_middle_name', $parent_middle_name); 
                                        ?>">
                                    <small>Middle name</small>
                                </div>
                                <div>
                                    <?php
                                        echo Helper::getError(Constants::$invalidGuardianSuffixNameCharacters);
                                    ?>
                                    <input type="text" name="parent_suffix" class="form-control"
                                        maxlength="3"  placeholder="e.g. Jr, Sr, II" 
                                        value="<?php 
                                            echo Helper::DisplayText('parent_suffix', $parent_suffix); 
                                        ?>">
                                    <small>Suffix name</small>
                                </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="phone">Phone no <span class="red">*</span></label>
                                    <?php
                                        echo Helper::getError(Constants::$guardianContactNumberRequired);
                                        echo Helper::getError(Constants::$invalidGuardianContactNumberCharacters);
                                        echo Helper::getError(Constants::$invalidGuardianContactNumber2Characters);
                                    ?>
                                    
                                    <div>
                                        <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('parent_contact_number', $parent_contact_number); 
                                            ?>">
                                    </div>

                                    <!-- <label for="email">Email</label>
                                    <?php
                                        echo Helper::getError(Constants::$guardianEmailRequired);
                                        echo Helper::getError(Constants::$invalidGuardianEmailCharacters);
                                    ?>
                                    <div>
                                        <input type="text" id="parent_email" name="parent_email" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('parent_email', $parent_email); 
                                            ?>">

                                    </div> -->

                                    <!-- <input type="text" name="father_firstname" class="form-control">
                                    <input type="text" name="father_lastname" class="form-control">

                                    <input type="text" name="mother_firstname" class="form-control">
                                    <input type="text" name="mother_lastname" class="form-control"> -->

                                    <label for="occupation">Occupation</label>

                                       <?php 
                                            echo Helper::getError(Constants::$guardianOccupationRequired);
                                            echo Helper::getError(Constants::$invalidGuardianOccupationCharacters);
                                            echo Helper::getError(Constants::$guardianOccupationIsTooShort);
                                            echo Helper::getError(Constants::$guardianOccupationIsTooLong);
                                        ?>
                                    <div>
                                        <input type="text" id="parent_occupation" name="parent_occupation" class="form-control"
                                            value="<?php 
                                                echo Helper::DisplayText('parent_occupation', $parent_occupation); 

                                            ?>">
                                    </div>
                                    <label for="relationship">Relationship <span class="red">*</span></label>
                                    <div>
                                        <?php 
                                            echo Helper::EchoErrorField(
                                                Constants::$guardianRelationshipRequired,
                                                Constants::$invalidGuardianRelationshipCharacters,
                                                Constants::$guardianRelationshipIsTooShort,
                                                Constants::$guardianRelationshipIsTooLong
                                            );
                                        ?>
                                        <input class="form-control"
                                        type="text"
                                        name="parent_relationship"
                                        id="parent_relationship"
                                        placeholder="e.g. Auntie, Uncle"
                                        value="<?php echo $parent_relationship;?>"
                                        />
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="action">
                            <button style="margin-right: 9px;"
                                type="button"
                                class="default large"
                                onclick="window.location.href = 'process.php?new_student=true&step=enrollee_school_history';"

                                >Return
                            </button>
                            <button
                                class="default success large"
                                name="parent_details_btn_<?php echo $pending_enrollees_id ?>" 
                                type="submit"
                                >
                                Proceed
                            </button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
    
<?php

?>






