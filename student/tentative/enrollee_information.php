<?php
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

    $program_id = $pending->GetPendingProgramId();


    $program = new Program($con, $program_id);

    $department_id = $program->GetProgramDepartmentId();

    $department = new Department($con, $department_id);

    $department_name = $department->GetDepartmentName();
 
    // var_dump($department_name);

    $type_status = $department_name === "Tertiary" ? 1 : 0;
 
    $parent = new PendingParent($con, $enrollee_id);
    $studentRequirement = new StudentRequirement($con, $enrollee_id);

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


    // Mother
    $mother_firstname = $parent->GetMotherFirstName();
    $mother_lastname = $parent->GetMotherLastName();
    $mother_middle = $parent->GetMotherMiddleName();
    $mother_suffix = $parent->GetMotherSuffix();
    $mother_contact_number = $parent->GetMotherContactNumber();
    $mother_email = $parent->GetMotherEmail();
    $mother_occupation = $parent->GetMotherOccupation();


    // $adresx = "49 Pechay st";
    
    // $address1 = "!49 Pechay St Napico Manggahan, Pasig City";
    // $address2 = "Apt 12, 123 Main St, Townsville";

    // $address = Helper::ValidateAddress($address2);
    // var_dump(Helper::$errorArray);


    if($_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['student_details_btn_' . $pending_enrollees_id])
        && isset($_POST['firstname'])
        && isset($_POST['middle_name'])
        && isset($_POST['lastname'])
        && isset($_POST['civil_status'])
        && isset($_POST['sex'])
        && isset($_POST['birthday'])
        && isset($_POST['birthplace'])
        && isset($_POST['nationality'])
        && isset($_POST['contact_number'])
        // && isset($_POST['email'])
        && isset($_POST['address'])
        ){

        $firstname = Helper::ValidateFirstname($_POST['firstname']);

        $middle_name = Helper::ValidateMiddlename($_POST['middle_name']);

        $lastname = Helper::ValidateLastname($_POST['lastname']);

        $suffix = isset($_POST['suffix']) ? Helper::ValidateSuffix($_POST['suffix']) : '';

        // echo $suffix;
        // return;


        $civil_status = Helper::ValidateCivilStatus($_POST['civil_status']);

        $sex = Helper::ValidateGender($_POST['sex']);

        $birthday = Helper::sanitizeFormString($_POST['birthday']);

        $address = Helper::ValidateAddress($_POST['address']);

        $age = $pending->CalculateAge($birthday);

        $birthplace = Helper::ValidateBirthPlace($_POST['birthplace']);

        $nationality = Helper::ValidateNationality($_POST['nationality']);

        // $religion = isset($_POST['religion']) ? Helper::ValidateReligion($_POST['religion']) : '';

        $religion = isset($_POST['religion']) ? $_POST['religion'] : '';

        $contact_number = Helper::ValidateContactNumber($_POST['contact_number']);

        // $email = Helper::ValidateEmailNewEnrollee(
        //     $pending_enrollees_id,
        //     $_POST['email'], false, $con);

        $email = "";

        $lrn = isset($_POST['lrn']) ? Helper::sanitizeFormString($_POST['lrn']) : NULL;
        // $lrn = NULL;

        // var_dump($lrn);
        // return;
        
        // echo "firstname: $firstname<br>";
        // echo "middle_name: $middle_name<br>";
        // echo "lastname: $lastname<br>";
        // echo "suffix: $suffix<br>";
        // echo "civil_status: $civil_status<br>";
        // echo "sex: $sex<br>";
        // echo "birthday: $birthday<br>";
        // echo "address: $address<br>";
        // echo "age: $age<br>";
        // echo "birthplace: $birthplace<br>";
        // echo "nationality: $nationality<br>";
        // echo "religion: $religion<br>";
        // echo "contact_number: $contact_number<br>";
        // echo "email: $email<br>";
        // return;
        // echo "lrn: $lrn<br>";


        if(empty(Helper::$errorArray)){

            // echo "empty error";

            $enrolleeSuccess = $pending->UpdateStudentInformation(
                $firstname,
                $lastname,
                $middle_name,
                $suffix,
                $civil_status,
                $nationality,
                $contact_number,
                $birthday,
                $birthplace,
                $age,
                $sex,
                $address,
                $lrn,
                $religion, $pending_enrollees_id
            );
      
            if($enrolleeSuccess){


                $initializedEnrollee = $studentRequirement->InitializedPendingEnrolleeRequirement(
                    $pending_enrollees_id, $pending_type, $school_year_id);
                
                // $url = "process.php?new_student=true&step=student_requirements";

                // $url = "process.php?new_student=true&step=enrollee_parent_information";
                // $url = "process.php?new_student=true&step=enrollee_school_history";
                
                $url = "process.php?new_student=true&step=enrollee_requirements";
                Alert::success("Student Information filled-up.",
                    $url);
                exit();

            }else{

                // $url = "process.php?new_student=true&step=student_requirements";
                
                // $url = "process.php?new_student=true&step=enrollee_parent_information";

                // echo "hey";

                // $url = "process.php?new_student=true&step=enrollee_school_history";
                $url = "process.php?new_student=true&step=enrollee_requirements";
                header("Location: $url");
                exit();
            }

        }else{
            // echo "Error";
        }
    }
?>

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
                            <h2 style="color: var(--titleTheme)">New Enrollment Form</h2>
                            <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester; ?> Semester </small>
                        </div>
                    </header>

                    <div class="progress">
                        <span class="dot active"><p>Preferred Course/Strand</p></span>
                        <span class="line active"></span>
                        <span class="dot active"> <p>Personal Information</p></span>
                        <span class="line inactive"></span>
                        <span class="dot inactive"> <p>Validate Details</p></span>
                        <span class="line inactive"></span>
                        <span class="dot inactive"> <p>Finished</p></span>
                    </div>

                    <main>
                        <form method="POST">
                            <?php if($type_status  == 0): ?>
                                <header>
                                    <div class="title">
                                        <h3>Student Information</h3>
                                    </div>
                                </header>
                                    <div class="row">
                                        <span>
                                            <label for="lrn">LRN</label>
                                            <div>
                                                <input 
                                                    class="form-control"
                                                    id="lrn" 
                                                    type="text" 
                                                    name="lrn" 
                                                    style="width: 200px" 
                                                    placeholder="e.g. 236-736-050-357"
                                                    value="<?php  
                                                        echo Helper::DisplayText('lrn', $lrn);
                                                    ?>">
                                            </div>
                                        </span>
                                    </div>
                            <?php else: ?>
                                <header>
                                    <div class="title">
                                        <h3>Student Information</h3>
                                    </div>
                                </header>
                            <?php endif; ?>
                            
                            <div class="row">
                                <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <?php 
                                            Helper::EchoErrorField(Constants::$lastNameRequired,
                                                Constants::$invalidLastNameCharacters,
                                                Constants::$lastNameIsTooShort, Constants::$lastNameIsTooLong
                                            );
                                        ?>
                                        <input class=" form-control" type="text"
                                            name="lastname" id="lastName"  placeholder="Last name" 
                                            value="<?php  
                                                echo Helper::DisplayText('lastname', $lastname);
                                            ?>">
                                        <small>Last name <span class="red">*</span></small>
                                    </div>
                                <div>
                                    <?php 
                                        Helper::EchoErrorField(Constants::$firstNameRequired, Constants::$invalidFirstNameCharacters,
                                            Constants::$firstNameIsTooShort, Constants::$firstNameIsTooLong);
                                    ?>
                                    <input class=" form-control"
                                        type="text" name="firstname" 
                                        placeholder="First name"
                                        id="firstName" 
                                        value="<?php
                                            echo Helper::DisplayText('firstname', $firstname);
                                        ?>"
                                    >
                                    <small>First name <span class="red">*</span></small>
                                </div>

                                <div>
                                    <?php
                                        Helper::EchoErrorField(
                                            Constants::$middleNameRequired,
                                            Constants::$invalidMiddleNameCharacters,
                                            Constants::$middleNameIsTooShort,
                                            Constants::$middleNameIsTooLong);
                                    ?>
                                    <input class=" form-control" type="text" name="middle_name" id="middleName" 
                                        placeholder="Middle name"
                                        value="<?php
                                            echo Helper::DisplayText('middle_name', $middle_name);
                                        ?>">
                                    <small>Middle name</small>
                                </div>
                                <div>
                                    <?php
                                        echo Helper::getError(Constants::$invalidSuffixNameCharacters);
                                    ?>
                                    <input maxlength="3" class="form-control" 
                                    type="text" name="suffix" id="suffixName" placeholder="e.g. Jr, Sr, II"
                                    value="<?php 
                                        echo Helper::DisplayText('suffix', $suffix);;
                                    ?>">
                                    <small>Suffix name</small>
                                </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <?php
                                        echo Helper::getError(Constants::$civilStatusRequired);
                                        echo Helper::getError(Constants::$invalidCivilStatusCharacters);
                                    ?>
                                    <label for="status">Status <span class="red">*</span></label>
                                    <div>
                                        <select class="form-control" id="status" name="civil_status" class="form-control">
                                            <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                            <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                        </select>
                                    </div>
                                </span>

                                <span>
                                    <!-- <p style="color:orange; font-size: 11px;">Input error</p> -->
                                    <?php
                                        echo Helper::getError(Constants::$nationalityRequired);
                                        echo Helper::getError(Constants::$invalidNationalityCharacters);
                                    ?>
                                    
                                    <label for="citizenship">Citizenship <span class="red">*</span></label>
                                    <div>
                                        <input class="form-control"
                                            type="text" name="nationality" 
                                            id="nationality"
                                            value="<?php 
                                                echo Helper::DisplayText('nationality', $nationality);
                                                // if(count(Helper::$errorArray) > 0){
                                                //     Helper::getInputValue('nationality');
                                                // }else{
                                                //     echo $nationality;
                                                // }
                                            ?>"
                                            >
                                    </div>
                                </span>
                                <span>
                                    <?php
                                        echo Helper::getError(Constants::$genderRequired);
                                        echo Helper::getError(Constants::$invalidGenderCharacters);
                                    ?>
                                    <label for="gender">Gender <span class="red">*</span></label>
                                    <div>
                                        <select class="form-control" name="sex" id="sex">
                                            <option value="Male"<?php echo ($sex == "Male") ? " selected" : ""; ?>>Male</option>
                                            <option value="Female"<?php echo ($sex == "Female") ? " selected" : ""; ?>>Female</option>
                                        </select>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="birthdate">Birthdate <span class="red">*</span></label>
                                    <div>
                                        <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($birthday != "") ? $birthday : "2023-06-17"; ?>">
                                    </div>
                                </span>

                                <span>
                                    <?php
                                        // echo Helper::getError(Constants::$religionRequired);
                                        // echo Helper::getError(Constants::$invalidReligionCharacters);
                                    ?>
                                <label for="religion">Religion</label>
                                <div>
                                    <!-- <select class="form-control" name="religion" id="religion">
                                        <option value="Catholic"<?php echo ($religion == "Catholic") ? " selected" : ""; ?>>Catholic</option>
                                        <option value="Christian"<?php echo ($religion == "Christian") ? " selected" : ""; ?>>Christian</option>
                                        <option value="Other"<?php echo ($religion == "Other") ? " selected" : ""; ?>>Other</option>
                                    </select> -->
                                    <input placeholder="Catholic, Christian" type="text" id="religion" name="religion" class="form-control" value="<?php echo ($religion != "") ? $religion : ""; ?>">
                                </div>
                                </span>
                                <span>
                                    <?php
                                        Helper::EchoErrorField(
                                            Constants::$birthPlaceRequired,
                                            Constants::$invalidBirthPlaceCharacters,
                                            Constants::$birthPlaceIsTooShort,
                                            Constants::$birthPlaceIsTooLong
                                        );
                                    ?>
                                    <label for="birthplace">Birthplace <span class="red">*</span></label>
                                    <div>
                                        <input type="text" id="birthplace" name="birthplace" 
                                            class="form-control"  placeholder="City/Municipality"
                                            value="<?php 
                                                echo Helper::DisplayText('birthplace', $birthplace);
                                            ?>">

                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <?php
                                        Helper::EchoErrorField(
                                            Constants::$addressRequired,
                                            Constants::$invalidAddressCharacters,
                                            Constants::$addressIsTooShort,
                                            Constants::$addressIsTooLong);
                                        ?>
                                    <label for="address">Address <span class="red">*</span></label>
                                    <div>
                                        <input autocomplete="offpro" style="text-align: start;" type="text" 
                                        id="address" name="address" placeholder="House No Street Name Barangay City/Municipality"
                                        class="form-control" 
                                        value="<?php
                                            echo Helper::DisplayText('address', $address);
                                        ?>">
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
                                    <label for="phone">Contact no. <span class="red">*</span></label>
                                    <div>
                                        <input type="tel" id="contact_number"
                                            name="contact_number" class="form-control"
                                            placeholder="09151516123"
                                            value="<?php
                                                echo Helper::DisplayText('contact_number', $contact_number);
                                            ?>">
                                    </div>
                                </span>
                                <span>
                                    <?php
                                        echo Helper::getError(Constants::$EmailRequired);
                                        echo Helper::getError(Constants::$invalidEmailCharacters);
                                    ?>
                                    <label for="email">Email <span class="red">*</span></label>
                                    <div>
                                        <input disabled autocomplete="off" type="email" id="email" name="email" 
                                        class=" form-control" 
                                        value="<?php 
                                            echo Helper::DisplayText('email', $email);
                                        ?>">
                                    </div>
                                </span>
                            </div>
                            <div class="action">
                                <button
                                    type="button"
                                    class="default large"
                                    onclick="window.location.href = 'process.php?new_student=true&step=preferred_course';"
                                >
                                    Return
                                </button>
                                <button
                                    class="clean large"
                                    name="student_details_btn_<?php echo $pending_enrollees_id ?>" 
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
    </body>
</html>