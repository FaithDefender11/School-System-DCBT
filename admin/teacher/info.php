<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
 
    ?>
        <head>
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

        </head>
    <?php


    if(isset($_GET['id'])){

        $teacher_id = $_GET['id'];

        $teacher = new Teacher($con, $teacher_id);


        $school_year = new SchoolYear($con, null);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        ?>

        <div class="content">

            <nav>
                <a href="teachers.html">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <span>Back</span>
                </a>
            </nav>

            <div class="content-header">
                <header>
                    <div class="title">
                        <h4><?php echo $teacher->GetTeacherFullName();?></h4>

                    </div>
                    <!-- <div class="action">
                        <div class="dropdown">
                        <button class="icon" id="dropdownMenuButton">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a href="" class="dropdown-item">
                            <i class="bi bi-pen"></i>
                            Edit teacher information
                            </a>
                            <a href="" class="dropdown-item">
                            <i class="bi bi-printer"></i>
                            Print schedule
                            </a>
                            <a href="" class="dropdown-item">
                            <i class="bi bi-person-x"></i>
                            Delete teacher
                            </a>
                        </div>
                        </div>
                    </div> -->

                </header>
                <div class="cards">

                    <div class="card">
                        <span>Teacher ID</span>
                        <span><?php echo $teacher->GetTeacherId();?></span>
                    </div>

                    <div class="card">
                        <span>Department</span>
                        <span><?php echo $teacher->GetDepartmentName();?></span>
                    </div>

                    <div class="card">
                        <span>Status</span>
                        <span><?php echo $teacher->GetStatus();?></span>
                    </div>

                    <div class="card">
                        <span>Added on</span>
                        <span><?php echo $teacher->GetCreation();?></span>
                    </div>

                </div>
            </div>

            <?php echo Helper::CreateTwoTabs("info.php?details=show&id=$teacher_id","Details",
                "info.php?teaching_load=show&id=$teacher_id", "Subject Load");?>
 
            <?php 
            
                if(isset($_GET['details']) && $_GET['details'] == "show"){

                    $lastname = $teacher->GetTeacherLastName();
                    $firstname = $teacher->GetTeacherFirstName();
                    $middle_name = $teacher->GetTeacherMiddleName();
                    $status = $teacher->GetStatus();
                    $birthday = $teacher->GetTeacherBirthday();
                    $religion = $teacher->GetTeacherReligion();
                    $address = $teacher->GetTeacherAddress();
                    $contact_number = $teacher->GetTeacherContactNumber();
                    $email = $teacher->GetTeacherEmail();
                    $suffix = $teacher->GetTeacherSuffix();
                    $citizenship = $teacher->GetTeacherCitizenship();
                    $birthplace = $teacher->GetTeacherBirthday();
                    
                    ?>
                        <div class="row col-md-12">

                           
                            <div class="card">
                                <div class="card-body">
                                    <div class="bg-content">
                                        <div class="step-content">
                                        <div class="content-box">
                                        <div class="student-info">
                                            <h4>Teacher information</h4>
                                            <form action="" class="info-box">
                                                <div class="info-1">
                                                <label for="name"> Name </label>
                                                <input
                                                    type="text"
                                                    name="lastname"
                                                    id="lastname"
                                                    value="<?php echo $lastname;?>"
                                                    placeholder="Last name"
                                                />
                                                <input
                                                    type="text"
                                                    name="firstname"
                                                    id="firstname"
                                                    value="<?php echo $firstname;?>"

                                                    placeholder="First name"
                                                />
                                                <input
                                                    type="text"
                                                    name="middle_name"
                                                    id="middle_name"
                                                    value="<?php echo $middle_name;?>"
                                                    placeholder="Middle name"
                                                />
                                                <input
                                                    type="text"
                                                    name="suffix"
                                                    id="suffix"
                                                    value="<?php echo $suffix;?>"

                                                    placeholder="Suffix name"
                                                />
                                                </div>
                                                <div class="info-2">
                                                <label for="status">Status</label>
                                                <div class="selection-box-1">
                                                    <select name="status" id="status">
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Divorced">Divorced</option>
                                                    <option value="Widowed">Widowed</option>
                                                    </select>
                                                </div>
                                                <label for="citizenship"> Citizenship </label>
                                                <input type="text" name="citizenship" id="citizenship" 
                                                value="<?php echo $citizenship;?>"
                                                />
                                                <label for="gender"> Gender </label>
                                                <div class="selection-box-1">
                                                    <select name="gender" id="gender">
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                                </div>
                                                <div class="info-3">
                                                <label for="birthdate"> Birthdate </label>
                                                <input type="date" name="birthdate" 
                                                id="birthdate" value="<?php echo $birthday?>" />
                                                <label for="birthplade"> Birthplace </label>
                                                <input
                                                    type="text"
                                                    name="birthplace"
                                                    id="birthplace"
                                                    value="<?php echo $birthplace; ?>"
                                                />
                                                <label for="religion"> Religion </label>
                                                <input type="text" name="religion" id="religion" 
                                                value="<?php echo $religion;?>" />
                                                </div>
                                                <div class="info-4">
                                                <label for="address"> Address </label>
                                                <input type="text" value="<?php echo $address?>" name="address" id="address" />
                                                </div>
                                                <div class="info-5">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input type="text" value="<?php echo $contact_number?>" name="phoneNo" id="phoneNo" />
                                                <label for="email"> Email </label>
                                                <input type="text" value="<?php echo $email?>" name="email" id="email" value="<?php echo $email;?>" />
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                    
                    <?php

                }
            
                if(isset($_GET['teaching_load']) && $_GET['teaching_load'] == "show"){

                    ?>
                        <div class="row col-md-12">
                            <div class="col-md-12">
                                                            <main>
                                <div class="floating">
                                <header>
                                    <div class="title">
                                    <h3><?php echo $current_school_year_term;?></h3>
                                    </div>
                                </header>
                                <main>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Section</th>
                                                <th>Schedule</th>
                                                <th>Status</th>
                                                <th>Hrs/Week</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            
                                                $query = $con->prepare("SELECT t1.*,
                                                    t3.subject_code, 
                                                    t3.subject_title AS subjectTitle, 
                                                    t4.program_section
                                                    
                                                    FROM subject_schedule as t1
                                                    INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
                                                    
                                                    LEFT JOIN subject as t3 ON t3.subject_id = t1.subject_id
                                                    LEFT JOIN course as t4 ON t4.course_id = t3.course_id

                                                    WHERE t1.teacher_id = :teacher_id
                                                    ");

                                                $query->bindParam(":teacher_id", $teacher_id);
                                                $query->execute();

                                                if($query->rowCount() > 0){

                                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subjectTitle'];
                                                        $program_section = $row['program_section'];
                                                        $schedule = $row['schedule_time'];;
                                                        $status = "";
                                                        $hrs_per_week = "";


                                                        echo "
                                                            <tr>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$program_section</td>
                                                                <td>$schedule</td>
                                                                <td>$status</td>
                                                                <td>$hrs_per_week</td>
                                                            </tr>
                                                        ";

                                                    }
                                                }else{
                                                    echo "
                                                        <div class='col-md-12'>
                                                            <h4 class='text-info text-center'>No Subject Load</h4>
                                                        </div>
                                                    ";
                                                }
                                            
                                            ?>
                                        </tbody>
                                    </table>

                            
                                </main>
                                </div>
                            </main>
                            </div>

                        </div>
                    <?php
                }
            ?>

        </div>

            <div class="col-md-12 row">

                <!-- TOP -->
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo $teacher->GetTeacherFullName();?></h4>
                            <br>
                            <div class="row col-md-12">
                                <div class="col-md-3">
                                    <p>Teacher Id</p>
                                    <span><?php echo $teacher->GetTeacherId();?></span>
                                </div>
                                <div class="col-md-3">
                                    <p>Department</p>
                                    <span><?php echo $teacher->GetDepartmentName();?></span>
                                </div>
                                <div class="col-md-3">
                                    <p>Status</p>
                                    <span><?php echo $teacher->GetStatus();?></span>
                                </div>
                                <div class="col-md-3">
                                    <p>Added On</p>
                                    <span><?php echo $teacher->GetCreation();?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                
                <?php
                    // CONTENT
                    if(isset($_GET['details']) && $_GET['details'] == "show"){

                        $lastname = $teacher->GetTeacherLastName();
                        $firstname = $teacher->GetTeacherFirstName();
                        $middle_name = $teacher->GetTeacherMiddleName();
                        $status = $teacher->GetStatus();
                        $birthday = $teacher->GetTeacherBirthday();
                        $religion = $teacher->GetTeacherReligion();
                        $address = $teacher->GetTeacherAddress();
                        $contact_number = $teacher->GetTeacherContactNumber();
                        $email = $teacher->GetTeacherEmail();
                        $suffix = $teacher->GetTeacherSuffix();
                        $citizenship = $teacher->GetTeacherCitizenship();
                        $birthplace = $teacher->GetTeacherBirthday();
                        
                        ?>
                            <div class="row col-md-12">

                                <!-- BUTTONS -->
                                <div class="row col-md-12">
                                    <div class="col-md-6">
                                        <a href="info.php?details=show&id=<?php echo $teacher_id?>">
                                            <button class="btn btn-large btn-primary">
                                                Details
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="info.php?teaching_load=show&id=<?php echo $teacher_id?>">
                                            <button class="btn btn-large btn-outline-primary">
                                                Subject Load
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="bg-content">
                                            <div class="step-content">
                                            <div class="content-box">
                                            <div class="student-info">
                                                <h4>Teacher information</h4>
                                                <form action="" class="info-box">
                                                    <div class="info-1">
                                                    <label for="name"> Name </label>
                                                    <input
                                                        type="text"
                                                        name="lastname"
                                                        id="lastname"
                                                        value="<?php echo $lastname;?>"
                                                        placeholder="Last name"
                                                    />
                                                    <input
                                                        type="text"
                                                        name="firstname"
                                                        id="firstname"
                                                        value="<?php echo $firstname;?>"

                                                        placeholder="First name"
                                                    />
                                                    <input
                                                        type="text"
                                                        name="middle_name"
                                                        id="middle_name"
                                                        value="<?php echo $middle_name;?>"
                                                        placeholder="Middle name"
                                                    />
                                                    <input
                                                        type="text"
                                                        name="suffix"
                                                        id="suffix"
                                                        value="<?php echo $suffix;?>"

                                                        placeholder="Suffix name"
                                                    />
                                                    </div>
                                                    <div class="info-2">
                                                    <label for="status">Status</label>
                                                    <div class="selection-box-1">
                                                        <select name="status" id="status">
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Divorced">Divorced</option>
                                                        <option value="Widowed">Widowed</option>
                                                        </select>
                                                    </div>
                                                    <label for="citizenship"> Citizenship </label>
                                                    <input type="text" name="citizenship" id="citizenship" 
                                                    value="<?php echo $citizenship;?>"
                                                    />
                                                    <label for="gender"> Gender </label>
                                                    <div class="selection-box-1">
                                                        <select name="gender" id="gender">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                    </div>
                                                    <div class="info-3">
                                                    <label for="birthdate"> Birthdate </label>
                                                    <input type="date" name="birthdate" 
                                                    id="birthdate" value="<?php echo $birthday?>" />
                                                    <label for="birthplade"> Birthplace </label>
                                                    <input
                                                        type="text"
                                                        name="birthplace"
                                                        id="birthplace"
                                                        value="<?php echo $birthplace; ?>"
                                                    />
                                                    <label for="religion"> Religion </label>
                                                    <input type="text" name="religion" id="religion" 
                                                    value="<?php echo $religion;?>" />
                                                    </div>
                                                    <div class="info-4">
                                                    <label for="address"> Address </label>
                                                    <input type="text" value="<?php echo $address?>" name="address" id="address" />
                                                    </div>
                                                    <div class="info-5">
                                                    <label for="phoneNo"> Phone no. </label>
                                                    <input type="text" value="<?php echo $contact_number?>" name="phoneNo" id="phoneNo" />
                                                    <label for="email"> Email </label>
                                                    <input type="text" value="<?php echo $email?>" name="email" id="email" value="<?php echo $email;?>" />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- TOP Last DIV  -->
                        </div>
                        
                        <?php

                    }

                    if(isset($_GET['teaching_load']) && $_GET['teaching_load'] == "show"){

                        ?>
                            <div class="row col-md-12">

                                <div class="row col-md-12">
                                    <div class="col-md-6">
                                        <a href="info.php?details=show&id=<?php echo $teacher_id?>">
                                            <button class="btn btn-large btn-outline-primary">
                                                Details
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="info.php?teaching_load=show&id=<?php echo $teacher_id?>">
                                            <button class="btn btn-large btn-primary">
                                                Subject Load
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                
                                <main>
                                    <div class="floating">
                                    <header>
                                        <div class="title">
                                        <h3><?php echo $current_school_year_term;?></h3>
                                        </div>
                                    </header>
                                    <main>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Section</th>
                                                    <th>Schedule</th>
                                                    <th>Status</th>
                                                    <th>Hrs/Week</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                
                                                    $query = $con->prepare("SELECT t1.*,
                                                        t3.subject_code, 
                                                        t3.subject_title AS subjectTitle, 
                                                        t4.program_section
                                                        
                                                        FROM subject_schedule as t1
                                                        INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
                                                        
                                                        LEFT JOIN subject as t3 ON t3.subject_id = t1.subject_id
                                                        LEFT JOIN course as t4 ON t4.course_id = t3.course_id

                                                        WHERE t1.teacher_id = :teacher_id
                                                        ");

                                                    $query->bindParam(":teacher_id", $teacher_id);
                                                    $query->execute();

                                                    if($query->rowCount() > 0){

                                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                            $subject_code = $row['subject_code'];
                                                            $subject_title = $row['subjectTitle'];
                                                            $program_section = $row['program_section'];
                                                            $schedule = $row['schedule_time'];;
                                                            $status = "";
                                                            $hrs_per_week = "";


                                                            echo "
                                                                <tr>
                                                                    <td>$subject_code</td>
                                                                    <td>$subject_title</td>
                                                                    <td>$program_section</td>
                                                                    <td>$schedule</td>
                                                                    <td>$status</td>
                                                                    <td>$hrs_per_week</td>
                                                                </tr>
                                                            ";

                                                        }
                                                    }else{
                                                        echo "
                                                            <div class='col-md-12'>
                                                                <h4 class='text-info text-center'>No Subject Load</h4>
                                                            </div>
                                                        ";
                                                    }
                                                
                                                ?>
                                            </tbody>
                                        </table>

                                
                                    </main>
                                    </div>
                                </main>

                
                            </div>

                            </div>
                        <?php
                    }

         
            

    }
?>


    

 
<?php include_once('../../includes/footer.php')?>


