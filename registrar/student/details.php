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
                <h2><?php echo $lastname;?>, <?php echo $firstname;?>, <?php echo $middle_name;?>, <?php echo $suffix;?></h2>
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

        <!-- <div class="cards">
            <div class="card">
                <p class="text-center mb-0">Student No.</p>
                <p class="text-center"><?php echo $student_unique_id;?></p>
            </div>
            <div class="card">
                <p class="text-center mb-0">Level</p>
                <p class="text-center"><?php echo $student_level; ?></p>
            </div>
            <div class="card">
                <p class="text-center mb-0"><?php echo $type == "Tertiary" ? "Course" : ($type == "Senior High School" ? "Strand" : "");?></p>
                <p class="text-center"><?php echo $section_acronym; ?></p>
            </div>
            <div class="card">
                <p class="text-center mb-0">Status</p>
                <p class="text-center"><?php echo $payment_status;?></p>
            </div>
            <div class="card">
                <p class="text-center mb-0">Added on</p>
                <p class="text-center">
                    <?php
                        $date = new DateTime($enrollment_date);
                        $formattedDate = $date->format('m/d/Y');
                        echo $formattedDate;
                    ?>
                </p>
            </div>
        </div> -->

        <?php echo Helper::CreateStudentTabs($student_unique_id, $student_level,
            $type, $section_acronym, $payment_status,
            $enrollment_date);?>
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
                            id="lastName" value="<?php echo $firstname;?>" />
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
    </main>
</div>