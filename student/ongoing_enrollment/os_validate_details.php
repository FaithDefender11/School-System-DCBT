                    <div class="content">

                        <main>
                            <div class="floating noBorder">

                                <header>
                                    <div class="title">
                                    <h2 style="color: var(--titleTheme)">Existing Student Form</h2>
                                    <p class="text-right mt-0">Generated Form ID: <?php echo $enrollment_form_id;?></p>
                                    </div>
                                </header>

                                <div class="progress">
                                    <span class="dot active"><p>Update Information</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Enrollment Details</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Validate Details</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Finished</p></span>
                                </div>
                                <hr>

                                <form method="POST">
                                    <main>
                
                                        <header>
                                            <div class="title">
                                                <h5>Student type</h5>
                                            </div>
                                        </header>
                                        
                                        <div class="row">
                                            <span>
                                            <div class="form-element">
                                                <label for="college">College</label>
                                                <div>
                                            <input required  type="radio" name="student_type"
                                                    value="Tertiary" <?php echo ($type == "Tertiary") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="shs">Senior High</label>
                                                <div>
                                                    <input required  type="radio" name="student_type"
                                                        value="SHS" <?php echo ($type == "SHS") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        
                                        <header>
                                            <div class="title">
                                                <h5>Taken <?php echo $type == "Tertiary" ? "Course" : ($type == "SHS" ? "Strand" : "");?></h5>
                                            </div>
                                        </header>

                                        <div class="row">
                                            <span>
                                                <div class="form-element courseStrand">
                                                    <div>
                                                        <?php echo $student->CreateRegisterStrand($student_program_id, true);?>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>

                                        <header>
                                            <div class="title" id="current-details">
                                                <h5 style="color: var(--titleTheme)">Current Level</h5>
                                            </div>
                                        </header>

                                        <div class="row" id="current-checkbox">

                                            <?php 
                                                if($type == "Tertiary"){
                                                    ?>
                                                        <span>
                                                            <div class="form-element">
                                                                <label for="1st_year">1</label>
                                                                <div>
                                                                    <input type="radio" name="yearLevel" id="1st_year" value="1" <?php echo $current_student_level == 1 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                            <div class="form-element">
                                                                <label for="2nd_year">2</label>
                                                                <div>
                                                                    <input type="radio" name="yearLevel" id="2nd_year" value="2" <?php echo $current_student_level == 2 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                                <div class="form-element">
                                                                <label for="3rd_year">3</label>
                                                                <div>
                                                                    <input type="radio" name="yearLevel" id="3rd_year" value="3" <?php echo $current_student_level == 3 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                                <div class="form-element">
                                                                <label for="4th_year">4</label>
                                                                <div>
                                                                    <input type="radio" name="yearLevel" id="4th_year" value="4" <?php echo $current_student_level == 4 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                        </span>
                                                    <?php
                                                }
                                                if($type == "SHS"){
                                                    ?>
                                                    <span>
                                                        <div class="form-element">
                                                            <label for="">11 </label>
                                                            <div>
                                                                <input disabled type="radio" name="currentLevel" id="" value="11" <?php echo $current_student_level == 11 ? 'checked' : ''; ?> />
                                                            </div>
                                                        </div>
                                                        <div class="form-element">
                                                            <label for="">12</label>
                                                            <div>
                                                                <input  disabled type="radio" name="currentLevel" id="" value="12" <?php echo $current_student_level == 12 ? 'checked' : ''; ?> />
                                                            </div>
                                                        </div>
                                                    </span>
                                                    <?php
                                                }
                                            ?>
                                        </div>

                                        <header>
                                            <div class="title" id="transferee-details">
                                                <h5 style="color: var(--titleTheme)">Applying for Grade Level</h5>
                                            </div>
                                        </header>

                                        <div class="row" id="shs-checkbox">

                                            <?php 
                                                if($type == "Tertiary"){
                                                    ?>
                                                        <span>
                                                            <div class="form-element">
                                                                <label for="1st_year">1</label>
                                                                <div>
                                                                    <input  disabled type="radio" name="yearLevel" id="1st_year" value="1" <?php echo $apply_to_level == 1 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                            <div class="form-element">
                                                                <label for="2nd_year">2</label>
                                                                <div>
                                                                    <input  disabled type="radio" name="yearLevel" id="2nd_year" value="2" <?php echo $apply_to_level == 2 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                                <div class="form-element">
                                                                <label for="3rd_year">3</label>
                                                                <div>
                                                                    <input  disabled type="radio" name="yearLevel" id="3rd_year" value="3" <?php echo $apply_to_level == 3 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                                <div class="form-element">
                                                                <label for="4th_year">4</label>
                                                                <div>
                                                                    <input  disabled type="radio" name="yearLevel" id="4th_year" value="4" <?php echo $apply_to_level == 4 ? 'checked' : ''; ?> />
                                                                </div>
                                                            </div>
                                                        </span>
                                                    <?php
                                                }
                                                if($type == "SHS"){
                                                    ?>
                                                    <span>
                                                        <div class="form-element">
                                                            <label for="11">11</label>
                                                            <div>
                                                                <input  disabled type="radio" name="yearLevel" id="11" value="11" <?php echo $apply_to_level == 11 ? 'checked' : ''; ?> />
                                                            </div>
                                                        </div>
                                                        <div class="form-element">
                                                            <label for="12">12</label>
                                                            <div>
                                                                <input  disabled type="radio" name="yearLevel" id="11" value="12" <?php echo $apply_to_level == 12 ? 'checked' : ''; ?> />
                                                            </div>
                                                        </div>
                                                    </span>
                                                    <?php
                                                }
                                            ?>
                                        </div>




                                        <hr>

                                        <!--  -->

                                        <header>
                                            <div class="title">
                                                <h3>Update Student Information</h3>
                                                <div class="row">
                                                    <span style="margin-left: 500px;">
                                                        <small>LRN</small>
                                                        <input  class="form-control" readonly style="width: 150px;" type="text" name="lrn" 
                                                            value="<?php echo ($student_lrn != "") ? $student_lrn : ''; ?>"id="lrn">
                                                    </span>
                                                </div>
                                            </div>
                                        </header>

                                        <div class="row">

                                            <span>
                                            <label for="name">Name</label>
                                            <div>
                                                <input class="form-control" type="text" required name="lastname" id="lastName" required value="<?php echo ($student_lastname != "") ? $student_lastname : ''; ?>" placeholder="Last name">
                                                <small>Last name</small>
                                            </div>

                                            <div>
                                                <input  class="form-control" type="text" required name="firstname" id="firstName" value="<?php echo ($student_firstname != "") ? $student_firstname : ''; ?>" placeholder="First name">

                                                <small>First name</small>
                                            </div>
                                            <div>
                                                <input  class="form-control" type="text" name="middle_name" id="middleName" value="<?php echo ($student_middle_name != "") ? $student_middle_name : ''; ?>" placeholder="Middle name">
                                                <small>Middle name</small>
                                            </div>
                                            <div>
                                                <input  class="form-control" type="text" name="suffix" id="suffixName" value="<?php echo ($student_suffix != "") ? $student_suffix : ''; ?>" placeholder="Suffix name">

                                                <small>Suffix name</small>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="status">Status</label>
                                            <div>
                                                <select  class="form-control" id="status" name="civil_status" class="form-control" required>
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
                                                <input  class="form-control" style="width: 220px;" type="text" name="nationality" 
                                                    required value="<?php echo ($student_nationality != "") ? $student_nationality : ''; ?>"id="nationality">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="gender">Gender</label>
                                            <div>
                                                <select  class="form-control" required name="sex" id="sex">
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
                                            <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($student_birthday != "") ? $student_birthday : "2023-06-17"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="religion">Religion</label>
                                            <div>
                                                    <input type="text" id="religion" name="religion" class="form-control" required value="<?php echo ($student_religion != "") ? $student_religion : "None"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="birthplace">Birthplace</label>
                                            <div>
                                                    <input type="text" id="birthplace" name="birthplace" class="form-control" required value="<?php echo ($student_birthplace != "") ? $student_birthplace : "Taguigarao"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="address">Address</label>
                                            <div>
                                                    <input  style="text-align: start;" type="text" id="address" name="address" class="form-control" required value="<?php echo ($student_address != "") ? $student_address : "None"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="phone">Phone no.</label>
                                            <div>
                                                <input type="tel" id="contact_number" name="contact_number" class="form-control" required value="<?php echo ($student_contact != "") ? $student_contact : "09151515123"; ?>">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="email">Email</label>
                                            <div>
                                                <input  class="form-control" readonly type="email" id="email" name="email" class="form-control" required value="<?php echo ($student_email != "") ? $student_email : ''; ?>">
                                            </div>
                                            </span>
                                        </div>
                                    </main>

                                    <div class="action">


                                        <button type="button" name="" class="mt-2 default large"
                                                onclick="window.location.href = 'procedure.php?enrollment_details=show'"
                                                >Return
                                        </button>
                                        <button type="button" name="" class="mt-2 default success large"
                                                onclick="window.location.href = 'procedure.php?subject_summary=show'"
                                                >Proceed
                                        </button>
                                    </div>

                                </form>


                            </div>
                        </main>

                    </div>