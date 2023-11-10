<div class="content">

    <!-- <nav>
        <a href="<?php echo $back_url; ?>"
        ><i class="bi bi-arrow-return-left fa-1x"></i>
        <span>Back</span>
        </a>
    </nav> -->
    
    <div class="content-header">

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
                        <a  href="#" class="dropdown-item" style="color: red">
                            <i class="bi bi-file-earmark-x"></i>
                            Delete form
                        </a>
                    </div>
                </div>
            </div>

        </header>

        <div class="cards">
            <div class="card">
                <sup>Form ID</sup>
                <sub><?php echo $student_enrollment_form_id;?></sub>
            </div>
            <div class="card">
                <sup>Admission type</sup>
                <sub><?php echo $student_status;?></sub>
            </div>
            <div class="card">
                <sup>Student no.</sup>
                <sub><?php echo $student_unique_id;?></sub>
            </div>
            <div class="card">
                <sup>Status</sup>
                <sub>For Approval</sub>
            </div>
            <div class="card">
                <sup>Submitted on</sup>
                <sub>
                    <?php
                        $date = new DateTime($date_creation);
                        $formattedDate = $date->format('m/d/Y H:i');
                        echo $formattedDate;
                    ?>
                </sub>
            </div>
        </div>
    </div>

    <div class="tabs">

        <?php
            echo "
                <button class='tab' 
                    style='background-color: var(--mainContentBG)'
                    onclick=\"window.location.href = 'subject_insertion_summary.php?id=$enrollment_id&student_details=show';\">
                    Student Details
                </button>
            ";

            echo "
                <button class='tab' 
                    id='shsPayment'
                    style='background-color: var(--them); color: white'
                    onclick=\"window.location.href = 'subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show';\">
                    Enrolled Subjects
                </button>
            ";
        ?>
    </div>

    <main>
        <div class="floating">
            <header class=" ">
                <div class="title">
                    <h4>Student Information</h4>
                </div>
            </header>

            <form method="POST">

                <main>
                    <div class="row">
                        <span>
                            <label for="name">Name</label>
                            <div>
                            <input type="text" name="lastname" id="lastname" value="<?php echo $student_lastname;?>" class="form-control" />
                            <small>Last name</small>
                            </div>
                            <div>
                            <input type="text" name="firstname" id="firstname" value="<?php echo $student_firstname;?>" class="form-control" />
                            <small>First name</small>
                            </div>
                            <div>
                            <input type="text" name="middle_name" id="middle_name" value="<?php echo $student_middle_name;?>" class="form-control" />
                            <small>Middle name</small>
                            </div>
                            <div>
                            <input type="text" name="suffix" id="suffix" value="<?php echo $student_suffix;?>" class="form-control" />
                            <small>Suffix name</small>
                            </div>
                        </span>
                    </div>

                    <div class="row">
                    <span>
                        <label for="status">Status</label>
                        <div>
                        <select name="civil_status" id="civil_status" class="form-control">
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
                        <input type="text" name="nationality" id="nationality" value="<?php echo $student_citizenship;?>" class="form-control" />
                        </div>
                    </span>

                    <span>
                        <label for="sex">Gender</label>
                        <div>
                        <select name="sex" id="sex" class="form-control">
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
                        <input type="date" name="birthday" id="birthday" value="<?php echo $student_birthday;?>" class="form-control" />
                        </div>
                    </span>
                    <span>
                        <label for="birthplace">Birthplace</label>
                        <div>
                        <input type="text" name="birthplace" id="birthplace" value="<?php echo $student_birthplace;?>" class="form-control" />
                        </div>
                    </span>
                    <span>
                        <label for="religion">Religion</label>
                        <div>
                        <input type="text" name="religion" id="religion" value="<?php echo $student_religion;?>" class="form-control" />
                        </div>
                    </span>
                    </div>

                    <div class="row">
                    <span>
                        <label for="address">Address</label>
                        <div>
                        <input type="text" name="address" id="address" value="<?php echo $student_address;?>" class="form-control" />
                        </div>
                    </span>
                    </div>

                    <div class="row">
                    <span>
                        <label for="phoneNo">Phone no.</label>
                        <div>
                        <input type="text" name="contact_number" id="contact_number" value="<?php echo $student_contact;?>" class="form-control" />
                        </div>
                    </span>
                    <span>
                        <label for="email">Email</label>
                        <div>
                        <input type="email" name="email" id="email" value="<?php echo $student_email;?>" class="form-control" />
                        </div>
                    </span>
                    </div>
                </main>
        
            </form>
        </div>
    </main>

</div>