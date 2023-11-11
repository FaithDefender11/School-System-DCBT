<main>
                <div class="floating noBorder">
                    <header>
                        <div class="title">
                        <h2 style="color: var(--titleTheme)">Existing Student Form</h2>
                        <p class="text-right mt-0">Generated Form ID: <?php echo $enrollment_form_id;?></p>
                        </div>
                    </header>
                    <div class="progress">
                        <span class="dot active"><p>View Information</p></span>
                        <span class="line active"></span>
                        <span class="dot active"> <p>Enrollment Details</p></span>
                        <span class="line inactive"></span>
                        <span class="dot inactive"> <p>Validate Details</p></span>
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
                                <div class="title" id="transferee-details">
                                    <h5 style="color: var(--titleTheme)">Grade Level</h5>
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
                                                        <input type="radio" name="yearLevel" id="1st_year" value="1" <?php echo $student_level == 1 ? 'checked' : ''; ?> />
                                                    </div>
                                                </div>
                                                <div class="form-element">
                                                    <label for="2nd_year">2</label>
                                                    <div>
                                                        <input type="radio" name="yearLevel" id="2nd_year" value="2" <?php echo $student_level == 2 ? 'checked' : ''; ?> />
                                                    </div>
                                                </div>
                                                <div class="form-element">
                                                    <label for="3rd_year">3</label>
                                                    <div>
                                                        <input type="radio" name="yearLevel" id="3rd_year" value="3" <?php echo $student_level == 3 ? 'checked' : ''; ?> />
                                                    </div>
                                                </div>
                                                <div class="form-element">
                                                    <label for="4th_year">4</label>
                                                    <div>
                                                        <input type="radio" name="yearLevel" id="4th_year" value="4" <?php echo $student_level == 4 ? 'checked' : ''; ?> />
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
                                                    <input type="radio" name="yearLevel" id="11" value="11" <?php echo $student_level == 11 ? 'checked' : ''; ?> />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="12">12</label>
                                                <div>
                                                    <input type="radio" name="yearLevel" id="11" value="12" <?php echo $student_level == 12 ? 'checked' : ''; ?> />
                                                </div>
                                            </div>
                                        </span>
                                        <?php
                                    }
                                ?>
                            </div>

                            <div style="display: none;" class="row">
                                <span>
                                <div class="form-element">
                                    <label for="school year">School Year</label>
                                    <div>
                                <input required class="form-control" type="text" name="student_type"
                                    value="<?php echo $current_term;?>">
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for="semester">Semester</label>
                                    <div>
                                    <input class="form-control" required  type="text" name="student_type"
                                        value="<?php echo $current_semester;?>" <?php echo ($type == "SHS") ? ' checked' : ''; ?>>
                                    </div>
                                </div>
                                </span>
                            </div>
                        </main>

                            <div class="action">
                                <button type="button" name="" class="mt-2 default large"

                                onclick="window.location.href = 'procedure.php?information=show'"
                                >
                                Return
                                <button type="button" name="" class="mt-2 default success large"
                                onclick="window.location.href = 'procedure.php?validate_details=show'"
                                >
                                Proceed
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </body>
</html>
