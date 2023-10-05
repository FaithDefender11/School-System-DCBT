<?php 
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/StudentParent.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/PendingParent.php');

    if(isset($_GET['id'])){

        $pending_enrollees_id = $_GET['id'];

        $pending = new Pending($con, $pending_enrollees_id);
        $parent = new PendingParent($con, $pending_enrollees_id);

        $student_parent = new StudentParent($con, $pending_enrollees_id);



        $parent_id = $parent->GetParentID();

        
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

        // Guardian
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


        // Father
        $mother_firstname = $parent->GetMotherFirstName();
        $mother_lastname = $parent->GetMotherLastName();
        $mother_middle = $parent->GetMotherMiddleName();
        $mother_suffix = $parent->GetMotherSuffix();
        $mother_contact_number = $parent->GetMotherContactNumber();
        $mother_email = $parent->GetMotherEmail();
        $mother_occupation = $parent->GetMotherOccupation();


        if(isset($_POST['update_pending_btn'])){

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


            $guardian_firstname = $_POST['guardian_firstname'];
            $guardian_lastname = $_POST['guardian_lastname'];
            $guardian_middle_name = $_POST['guardian_middle_name'];
            $guardian_suffix = $_POST['guardian_suffix'];
            $guardian_contact = $_POST['guardian_contact'];
            $guardian_email = $_POST['guardian_email'];
            $guardian_occupation = $_POST['guardian_occupation'];
            $guardian_relationship = $_POST['guardian_relationship'];

          

            // Father
            $father_firstname = $_POST['father_firstname'];
            $father_lastname = $_POST['father_lastname'];
            $father_middle = $_POST['father_middle'];
            $father_suffix = $_POST['father_suffix'];
            $father_contact_number = $_POST['father_contact_number'];
            $father_email = $_POST['father_email'];
            $father_occupation = $_POST['father_occupation'];

          

            // Mother
            $mother_firstname = $_POST['mother_firstname'];
            $mother_lastname = $_POST['mother_lastname'];
            $mother_middle = $_POST['mother_middle'];
            $mother_suffix = $_POST['mother_suffix'];
            $mother_contact_number = $_POST['mother_contact_number'];
            $mother_email = $_POST['mother_email'];
            $mother_occupation = $_POST['mother_occupation'];

            

            $editPendingtExec = $pending->UpdatePendingEnrolleeDetails(
                $pending_enrollees_id,
                $firstname,
                $lastname,
                $middle_name,
                $suffix,
                $civil_status,
                $nationality,
                $sex,
                $birthday,
                $birthplace,
                $religion,
                $address,
                $contact_number,
                $email);

            $editParentExec = $parent->UpdatePendingParent(
                $pending_enrollees_id, $parent_id, $guardian_firstname, $guardian_lastname,
                $guardian_middle_name, $guardian_suffix, $guardian_contact,
                $guardian_email, $guardian_occupation, $guardian_relationship,

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

            if($editPendingtExec || $editParentExec){
                Alert::success("Successfully save Changes", "");
                exit();
            }
            
        }

        

        ?>
            <div class="content">
                <nav>
                    <a href="process_enrollment.php?enrollee_details=true&id=<?php echo $pending_enrollees_id;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                
                <main>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <h3 class="text-primary text-center">Enrollee Form Details</h3>
                            </div>
                        </header>
                            
                        
                        <header class="mt-4">
                            <div class="title">
                            <h3>Pending Enrollee Information</h3>
                            </div>
                        </header>

                        <form method="POST">

                            <main>
                                <div class="row">
                                <span>
                                    <label for="name">Name</label>
                                    <div>
                                    <input type="text" name="lastname" id="lastname" value="<?php echo $lastname;?>" class="form-control" />
                                    <small>Last name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="firstname" id="firstname" value="<?php echo $firstname;?>" class="form-control" />
                                    <small>First name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="middle_name" id="middle_name" value="<?php echo $middle_name;?>" class="form-control" />
                                    <small>Middle name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="suffix" id="suffix" value="<?php echo $suffix;?>" class="form-control" />
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
                                    <input type="email" name="email" id="email" value="<?php echo $email;?>" class="form-control" />
                                    </div>
                                </span>
                                </div>
                            </main>

                            <hr>
                            <header>
                                <div class="title">
                                    <h3>Father's Information</h3>
                                </div>
                            </header>

                            <div class="row">
                                <span>
                                    <label for="name">Name</label>
                                    <div>
                                        <input value="<?php echo $father_lastname?>" type="text" name="father_lastname" class="form-control">
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


                            <hr>
                            <header>
                                <div class="title">
                                    <h3>Mother's Information</h3>
                                </div>
                            </header>

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

                            <hr>
                            <header>
                            <div class="title">
                                <h4>Guardian's Information</h4>
                            </div>
                            </header>

                            <main>
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
                            </main>

                            <div class="action modal-footer">
                                <button name="update_pending_btn"
                                    type="submit" class="default large success">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        <?php
    }

?>



