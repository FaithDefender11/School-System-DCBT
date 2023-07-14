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
                    <a href="#" class="dropdown-item" style="color: red"
                    ><i class="bi bi-file-earmark-x"></i>Delete form</a
                    >
                </div>
                </div>
            </div>
        </header>
        <?php echo Helper::ProcessPendingCards($enrollment_form_id,
            $date_creation, $admission_status); ?>
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
                <h3>Student Information</h3>
                </div>
            </header>

            <main>
                <form action="">
                <div class="row">
                    <span>
                    <label for="name">Name</label>
                    <div>
                        <input type="text" name="lastName" 
                            id="lastName" value="<?php echo $lastname;?>" />
                        <small></small>
                    </div>
                    <div>
                        <input
                        type="text"
                        name="firstName"
                        id="firstName"
                        value="<?php echo $firstname;?>"
                        />
                        <small>First name</small>
                    </div>
                    <div>
                        <input
                        type="text"
                        name="middleName"
                        id="middleName"
                        value="<?php echo $middle_name;?>"
                        />
                        <small>Middle name</small>
                    </div>
                    <div>
                        <input
                        type="text"
                        name="suffixName"
                        id="suffixName"
                        value="<?php echo $suffix;?>"
                        />
                        <small>Suffix name</small>
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="status">Status</label>
                    <div>
                        <select name="status" id="status">
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
                        <input
                        type="text"
                        name="citizenship"
                        id="citizenship"
                        value="<?php echo $nationality;?>"
                        />
                    </div>
                    </span>
                    <span>
                    <label for="gender">Gender</label>
                    <div>
                        <select name="gender" id="gender">
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
                        <input
                        type="date"
                        name="birthdate"
                        id="birthdate"
                        value="<?php echo $birthday;?>"
                        />
                    </div>
                    </span>
                    <span>
                    <label for="birthplace">Birthplace</label>
                    <div>
                        <input
                        type="text"
                        name="birthplace"
                        id="birthplace"
                        value="<?php echo $birthplace;?>"
                        />
                    </div>
                    </span>
                    <span>
                    <label for="religion">Religion</label>
                    <div>
                        <input type="text" name="religion" id="religion" value="<?php echo $religion;?>" />
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="address">Address</label>
                    <div>
                        <input type="text" name="address" id="address" value="<?php echo $address;?>" />
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="phoneNo">Phone no.</label>
                    <div>
                        <input type="text" name="phone" id="phone" value="<?php echo $contact_number;?>" />
                    </div>
                    </span>
                    <span>
                    <label for="email">Email</label>
                    <div>
                        <input type="email" name="email" id="email" value="<?php echo $email;?>" />
                    </div>
                    </span>
                </div>
                </form>
            </main>

            <header>
                <div class="title">
                <h3>Current/Last School Attended</h3>
                </div>
            </header>

            <main>
                <form action="">
                <div class="row">
                    <span>
                    <label for="schoolType">School type</label>
                    <div>
                        <select name="schoolType" id="schoolType">
                        <option value="">SHS</option>
                        <option value="">College</option>
                        </select>
                    </div>
                    </span>
                    <span>
                    <label for="schoolName">School name</label>
                    <div>
                        <input
                        type="text"
                        name="schoolName"
                        id="schoolName"
                        value=""
                        />
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="graduationDate">Graduation date</label>
                    <div>
                        <input
                        type="date"
                        name="graduationDate"
                        id="graduationDate"
                        value=""
                        />
                    </div>
                    </span>
                    <span>
                    <label for="schoolYear">School year</label>
                    <div>
                        <input
                        type="text"
                        name="schoolYear"
                        id="schoolYear"
                        placeholder="2022-2023"
                        value=""
                        />
                    </div>
                    </span>
                    <span>
                    <label for="term">Term</label>
                    <div>
                        <select name="term" id="term">
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
                        <input
                        type="text"
                        name="schoolAddress"
                        id="schoolAddress"
                        value=""
                        />
                    </div>
                    </span>
                </div>
                </form>
            </main>

            <header>
                <div class="title">
                <h3>Parent/Guardian's Information</h3>
                <h4>Father's Information</h4>
                </div>
            </header>

            <main>
                <form action="">
                <div class="row">
                    <span>
                    <label for="name">Name</label>
                    <div>
                        <input type="text" name="fatherLN" id="fatherLN" value="" />
                        <small>Last name</small>
                    </div>
                    <div>
                        <input type="text" name="fatherFN" id="fatherFN" value="" />
                        <small>First name</small>
                    </div>
                    <div>
                        <input type="text" name="fatherMN" id="fatherMN" value="" />
                        <small>Middle name</small>
                    </div>
                    <div>
                        <input type="text" name="fatherSN" id="fatherSN" value="" />
                        <small>Suffix name</small>
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="phoneNo">Phone no.</label>
                    <div>
                        <input
                        type="text"
                        name="fatherPhone"
                        id="fatherPhone"
                        value=""
                        />
                    </div>
                    </span>
                    <span>
                    <label for="email">Email</label>
                    <div>
                        <input
                        type="email"
                        name="fatherEmail"
                        id="fatherEmal"
                        value=""
                        />
                    </div>
                    </span>
                    <span>
                    <label for="occupation">Occupation</label>
                    <div>
                        <input
                        type="text"
                        name="fatherOccupation"
                        id="fatherOccupation"
                        value=""
                        />
                    </div>
                    </span>
                </div>
                </form>
            </main>

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
                        <input type="text" name="motherLN" id="motherLN" value="" />
                        <small>Last name</small>
                    </div>
                    <div>
                        <input type="text" name="motherFN" id="motherFN" value="" />
                        <small>First name</small>
                    </div>
                    <div>
                        <input type="text" name="motherMN" id="motherMN" value="" />
                        <small>Middle name</small>
                    </div>
                    <div>
                        <input type="text" name="motherSN" id="motherSN" value="" />
                        <small>Suffix name</small>
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="phoneNo">Phone no.</label>
                    <div>
                        <input
                        type="text"
                        name="motherPhone"
                        id="motherPhone"
                        value=""
                        />
                    </div>
                    </span>
                    <span>
                    <label for="email">Email</label>
                    <div>
                        <input
                        type="email"
                        name="motherEmail"
                        id="motherEmal"
                        value=""
                        />
                    </div>
                    </span>
                    <span>
                    <label for="occupation">Occupation</label>
                    <div>
                        <input
                        type="text"
                        name="motherOccupation"
                        id="motherOccupation"
                        value=""
                        />
                    </div>
                    </span>
                </div>
                </form>
            </main>

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
                        <input
                        type="text"
                        name="guardianLN"
                        id="guardianLN"
                        value="<?php echo $parent_lastname;?>"
                        />
                        <small>Last name</small>
                    </div>
                    <div>
                        <input
                        type="text"
                        name="guardianFN"
                        id="guardianFN"
                        value="<?php echo $parent_firstname;?>"
                        />
                        <small>First name</small>
                    </div>
                    <div>
                        <input
                        type="text"
                        name="guardianMN"
                        id="guardianMN"
                        value="<?php echo $parent_middle_name;?>"
                        />
                        <small>Middle name</small>
                    </div>
                    <div>
                        <input
                        type="text"
                        name="guardianSN"
                        id="guardianSN"
                        value="<?php echo $parent_suffix;?>"
                        />
                        <small>Suffix name</small>
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="phoneNo">Phone no.</label>
                    <div>
                        <input
                        type="text"
                        name="guardianPhone"
                        id="guardianPhone"
                        value="<?php echo $parent_contact_number;?>"
                        />
                    </div>
                    </span>
                    <span>
                    <label for="email">Email</label>
                    <div>
                        <input
                        type="email"
                        name="guardianEmail"
                        id="guardianEmal"
                        value="<?php echo $parent_email;?>"
                        />
                    </div>
                    </span>
                </div>
                <div class="row">
                    <span>
                    <label for="relationship">Relationship</label>
                    <div>
                        <input
                        type="text"
                        name="guardianRelation"
                        id="guardianRelation"
                        value=""
                        />
                    </div>
                    </span>
                    <span>
                    <label for="occupation">Occupation</label>
                    <div>
                        <input
                        type="text"
                        name="guardianOccupation"
                        id="guardianOccupation"
                        value="<?php echo $parent_occupation;?>"
                        />
                    </div>
                    </span>
                </div>
                </form>
            </main>

        </div>

        <div class="action">
            <button class="default success large" 
                onclick="window.location.href = 'process_enrollment.php?step2=true&id=<?php echo $pending_enrollees_id; ?>'">
                Proceed
            </button>
        </div>
    </main>
</div>