<?php

    class Section{

        private $con, $course_id, $sqlData;


        public function __construct($con, $course_id = null){
            $this->con = $con;
            $this->course_id = $course_id;

            $query = $this->con->prepare("SELECT * FROM course
                 WHERE course_id=:course_id");

            $query->bindValue(":course_id", $course_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetSectionName() {
            return isset($this->sqlData['program_section']) ? ucfirst($this->sqlData["program_section"]) : ""; 
        }

        public function GetSectionType() {
            return isset($this->sqlData['is_tertiary']) ? $this->sqlData["is_tertiary"] : NULL; 
        }

        public function GetSectionRoom() {
            return isset($this->sqlData['room']) ? $this->sqlData["room"] : 0; 
        }
        public function GetSectionCapacity() {
            return isset($this->sqlData['capacity']) ? $this->sqlData["capacity"] : 0; 
        }

        public function GetSectionAdviseryId() {
            return isset($this->sqlData['adviser_teacher_id']) ? $this->sqlData["adviser_teacher_id"] : 0; 
        }

        public function GetSectionFirstPeriodRoomId() {
            return isset($this->sqlData['first_period_room_id']) ? $this->sqlData["first_period_room_id"] : NULL; 
        }

        public function GetSectionSecondPeriodRoomId() {
            return isset($this->sqlData['second_period_room_id']) ? $this->sqlData["second_period_room_id"] : NULL; 
        }

        public function GetSectionGradeLevel($course_id = null) {

            $value = 0;

            if($course_id == null){
                return isset($this->sqlData['course_level']) ? $this->sqlData["course_level"] : ""; 
            }

            else{

                $query = $this->con->prepare("SELECT course_level FROM course
                    WHERE course_id=:course_id");

                $query->bindParam(":course_id", $course_id);
                $query->execute();

                if($query->rowCount() > 0){
                    $value = $query->fetchColumn();
                }
            }

            return $value;
        }
        public function GetSectionSY() {
            return isset($this->sqlData['school_year_term']) ? $this->sqlData["school_year_term"] : ""; 
        }

        public function GetSectionProgramId($course_id){

            $sql = $this->con->prepare("SELECT program_id FROM course
                WHERE course_id=:course_id");
                
            $sql->bindValue(":course_id", $course_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return null;
        }


        public function GetAcronymByProgramId($program_id){

            $sql = $this->con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id");
                
            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return "N/A";
        }

        public function CheckSectionIsFull($course_id){

            $sql = $this->con->prepare("SELECT is_full FROM course
                WHERE course_id=:course_id
                AND is_full='yes'");
                
            $sql->bindParam(":course_id", $course_id);
            $sql->execute();

            return $sql->rowCount() > 0;
        }
        
        public function GetCreatedStrandSectionPerTerm( 
                $program_id, $school_year_term, $course_level){

            $sql = $this->con->prepare("SELECT * FROM course
                WHERE course_level=:course_level
                AND program_id=:program_id
                AND school_year_term=:school_year_term
                ");
                
            $sql->bindParam(":course_level", $course_level);
            $sql->bindParam(":program_id", $program_id);
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->execute();

            return $sql->rowCount();

        }

        public function GetTotalNumberOfStudentInSection($course_id,
            $current_school_year_id){

            $sql = $this->con->prepare("SELECT 
                            
                    t3.program_id, t2.student_id,
                    t2.student_status,t2.firstname, t2.lastname 
                    
                    FROM enrollment as t1
            
                    LEFT JOIN student as t2 ON t2.student_id=t1.student_id
                    LEFT JOIN course as t3 ON t3.course_id=t1.course_id


                    WHERE t1.course_id=:course_id
                    AND t1.school_year_id=:school_year_id
                    AND t1.enrollment_status=:enrollment_status
            ");

            $sql->bindParam(":course_id", $course_id);
            $sql->bindParam(":school_year_id", $current_school_year_id);
            $sql->bindValue(":enrollment_status", "enrolled");
            
            $sql->execute();

            return $sql->rowCount();

        }

        public function GetStudentEnrolledInSectionSubject($course_id,
            $subject_program_id,  $current_school_year_id){

            $sql = $this->con->prepare("SELECT 
                            
                    t3.program_id, t2.student_id,
                    t2.student_status,t2.firstname, t2.lastname 
                    
                    FROM enrollment as t1
            
                    LEFT JOIN student as t2 ON t2.student_id=t1.student_id
                    LEFT JOIN course as t3 ON t3.course_id=t1.course_id


                    WHERE t1.course_id=:course_id
                    AND t1.school_year_id=:school_year_id
                    AND t1.enrollment_status=:enrollment_status
            ");

            $sql->bindParam(":course_id", $course_id);
            $sql->bindParam(":school_year_id", $current_school_year_id);
            $sql->bindValue(":enrollment_status", "enrolled");
            
            $sql->execute();

            return $sql->rowCount();

        }

        public function CreateSHSSectionLevelFirstSemesterContent($program_id, $term,
            $course_level, $enrollment){

            $output = "";
            $query = $this->con->prepare("SELECT t1.*, t2.room_number

                FROM course as t1 

                LEFT JOIN room as t2 ON t2.room_id = t1.first_period_room_id
                -- AND school_year_id=:school_year_id

                WHERE t1.program_id=:program_id
                AND t1.school_year_term=:school_year_term
                AND t1.course_level=:course_level
            ");

            $query->bindParam(":program_id", $program_id);
            $query->bindParam(":school_year_term", $term);
            $query->bindParam(":course_level", $course_level);
            $query->execute();

            if($query->rowCount() > 0){

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                    $program_section = $row['program_section'];
                    $course_id = $row['course_id'];
                    $program_id = $row['program_id'];
                    $course_level = $row['course_level'];
                    $active = $row['active'];
                    $capacity = $row['capacity'];
                    $room_number = $row['room_number'] ?? "-";

                    $active_status = ($active != "no") 
                        ? "<i style='color: green;' class='fas fa-check'></i>" 
                        : "<i style='color: orange;' class='fas fa-times'></i>";

                    // echo $course_id;
                    $students_enrolled = $enrollment->GetStudentEnrolled($course_id);

                    $removeSection=  "removeSection($course_id, $course_level)";
                    $editUrl=  "edit.php?id=$course_id&p_id=$program_id";
                    

                    // CSS BUG FIX 
                    // <div class='dropdown-menu'>
                    //     <a class='dropdown-item' href='$editUrl'>
                    //         <button class='btn btn-primary' style='width: 100%;'>
                    //             Edit
                    //         </button>
                    //     </a>
                    //     <a class='dropdown-item' href='#'>
                    //         <button onclick='$removeSection'class='btn btn-danger' style='width: 100%;'>
                    //             Remove
                    //         </button>
                    //     </a>
                    //     <a class='dropdown-item' href='show.php?id=$course_id'>
                    //         <button class='btn btn-info' style='width: 100%;'>
                    //             View Section
                    //         </button>
                    //     </a>
                    // </div>

                    $output .= "
                        <tr>
                            <td>$course_id</td>
                            <td>
                                <a style='color:white;' href='subject_list.php?id=$course_id'>
                                    $program_section
                                </a>
                            </td>
                            <td>$room_number</td>
                            <td>$students_enrolled / $capacity</td>
                            <td>$active_status</td>
                            <td>
                                
                                    <a href='$editUrl'>
                                        <button class='btn btn-sm btn-primary'     >
                                            <i class='bi bi-pencil-square'></i>
                                        </button>
                                    </a>
                                    <a href='#'>
                                        <button onclick='$removeSection'class='btn btn-sm btn-danger'  >
                                            <i class='fas fa-times-circle'></i>

                                        </button>
                                    </a>
                                    <a href='show.php?id=$course_id'>
                                        <button class='btn btn-sm btn-info'    >
                                            <i class='bi bi-eye-fill '></i>

                                        </button>
                                    </a>

                            </td>
                        </tr>
                    ";
                }

            }
            return $output;
        }
        public function CreateSectionLevelContent($program_id, $term,
            $course_level, $enrollment, $current_school_year_id = null){

            $output = "";
            $query = $this->con->prepare("SELECT t1.*, t2.room_number

                FROM course as t1 

                LEFT JOIN room as t2 ON t2.course_id = t1.course_id
                AND school_year_id=:school_year_id

                WHERE t1.program_id=:program_id
                AND t1.school_year_term=:school_year_term
                AND t1.course_level=:course_level
            ");

            $query->bindParam(":program_id", $program_id);
            $query->bindParam(":school_year_term", $term);
            $query->bindParam(":course_level", $course_level);
            $query->bindParam(":school_year_id", $current_school_year_id);
            $query->execute();

            if($query->rowCount() > 0){

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                    $program_section = $row['program_section'];
                    $course_id = $row['course_id'];
                    $program_id = $row['program_id'];
                    $course_level = $row['course_level'];
                    $active = $row['active'];
                    $capacity = $row['capacity'];
                    $room_number = $row['room_number'] ?? "-";

                    $active_status = ($active != "no") 
                        ? "<i style='color: green;' class='fas fa-check'></i>" 
                        : "<i style='color: orange;' class='fas fa-times'></i>";

                    // echo $course_id;
                    $students_enrolled = $enrollment->GetStudentEnrolled($course_id);

                    $removeSection=  "removeSection($course_id, $course_level)";
                    $editUrl=  "edit.php?id=$course_id&p_id=$program_id";
                    

                    // CSS BUG FIX 
                    // <div class='dropdown-menu'>
                    //     <a class='dropdown-item' href='$editUrl'>
                    //         <button class='btn btn-primary' style='width: 100%;'>
                    //             Edit
                    //         </button>
                    //     </a>
                    //     <a class='dropdown-item' href='#'>
                    //         <button onclick='$removeSection'class='btn btn-danger' style='width: 100%;'>
                    //             Remove
                    //         </button>
                    //     </a>
                    //     <a class='dropdown-item' href='show.php?id=$course_id'>
                    //         <button class='btn btn-info' style='width: 100%;'>
                    //             View Section
                    //         </button>
                    //     </a>
                    // </div>

                    $output .= "
                        <tr>
                            <td>$course_id</td>
                            <td>
                                <a style='color:white;' href='subject_list.php?id=$course_id'>
                                    $program_section
                                </a>
                            </td>
                            <td>$room_number</td>
                            <td>$students_enrolled / $capacity</td>
                            <td>$active_status</td>
                            <td>
                                
                                    <a href='$editUrl'>
                                        <button class='btn btn-sm btn-primary'     >
                                            <i class='bi bi-pencil-square'></i>
                                        </button>
                                    </a>
                                    <a href='#'>
                                        <button onclick='$removeSection'class='btn btn-sm btn-danger'  >
                                            <i class='fas fa-times-circle'></i>

                                        </button>
                                    </a>
                                    <a href='show.php?id=$course_id'>
                                        <button class='btn btn-sm btn-info'    >
                                            <i class='bi bi-eye-fill '></i>

                                        </button>
                                    </a>

                            </td>
                        </tr>
                    ";
                }

            }
            return $output;
        }
        

        public function createProgramSelection($program_id = null){

            $SHS_DEPARTMENT = 4;

            $query = $this->con->prepare("SELECT * FROM program
                -- WHERE department_id=:department_id
            ");

            // $query->bindValue(":department_id", $SHS_DEPARTMENT);
            $query->execute();
            
            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>Program</label>

                    <select id='program_id' class='form-control' name='program_id'>";

                $html .= "<option value='Course-Section' disabled selected>Select-Program</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $selected = "";
                    if($row['program_id'] == $program_id){
                        $selected = "selected";
                    }
                    $html .= "
                        <option value='".$row['program_id']."' $selected>".$row['program_name']."</option>
                    ";
                }
                $html .= "</select>
                        </div>";
                return $html;
            }
 
            return "";
        }

        public function CreateCourseLevelDropdownDepartmentBased(
            $department_name = null, $course_level = null){

            $html = "";
            if($department_name == "Senior High School"){

                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>Course Level</label>

                <select id='course_level' class='form-control' name='course_level'>";

                // $html .= "<option value='Course-Section' disabled selected>Select-Program</option>";
                
                $html .= "
                    <option value='11'" . ($course_level == 11 ? " selected" : "") . ">Grade 11</option>
                    <option value='12'" . ($course_level == 12 ? " selected" : "") . ">Grade 12</option>
                ";
                $html .= "</select>
                        </div>";

                return $html;

            }
            else if($department_name == "Tertiary"){
                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>Course Level</label>

                <select required id='course_level' class='form-control' name='course_level'>";

                $html .= "
                    <option value='1'>First Year</option>
                    <option value='2'>Second Year</option>
                    <option value='3'>Third Year</option>
                    <option value='4'>Fourth Year</option>
                ";
                $html .= "</select>
                        </div>";

                return $html;
            }
 
            return $html;
        }

        public function CreateSectionDropdownProgramBased(
            $program_id, $course_id, $text,
            $current_school_year_id, $section = null){

            $html = "";

            $query = $this->con->prepare("SELECT * FROM course
                WHERE program_id=:program_id
                AND course_id != :course_id
            ");

            $query->bindParam(":program_id", $program_id);
            $query->bindParam(":course_id", $course_id);
            $query->execute();
            
            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>$text</label>

                    <select id='course_id' class='form-control' name='course_id'>";

                $html .= "<option value='' disabled selected>Select-Section</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                    $updatedTotalStudent =  $section->GetTotalNumberOfStudentInSection($row['course_id'],
                        $current_school_year_id);

                    $capacity = $row['capacity'];
                    $selected = "";

                    if($row['course_id'] == $course_id){
                        $selected = "selected";
                    }

                    $html .= "
                        <option value='".$row['course_id']."' $selected>".$row['program_section']." Status: ( $updatedTotalStudent / $capacity )</option>
                    ";
                }

                $html .= "</select>
                        </div>";
                return $html;
            }
 
            return $html;
        }

        public function CreateSectionSubjectDropdownProgramBased(
            $program_id, $student_subject_course_id, $text,
            $current_school_year_id, $section = null,
            $current_school_year_period, $student_subject_program_id,
            $current_term, $student_subject_programCode = null){

                // echo $current_term;

            $html = "";

            // echo $student_subject_programCode;
                
            // echo $course_id;

            // $query = $this->con->prepare("SELECT 
            //     t1.*, t2.* 
                
            //     FROM course AS t1

            //     INNER JOIN subject_program AS t2 ON t2.program_id = t1.program_id
            //     AND t1.course_level = t2.course_level

            //     WHERE t1.program_id=:program_id


            //     AND t1.course_id != :course_id
            //     AND t2.semester = :semester
            //     AND t2.subject_program_id = :subject_program_id
            //     AND t1.school_year_term = :school_year_term

            // ");

            // PE101 STEMS
            // PE101 HUMMS
            // PE101 ICT  etc

            $query = $this->con->prepare("SELECT 
                t2.*
                , t3.program_section
                , t3.course_id

                FROM subject_program AS t2 
                
                LEFT JOIN course AS t3 ON t3.program_id = t2.program_id

                WHERE t2.semester = :semester
                AND t2.subject_code = :subject_code

            ");

            // $query->bindParam(":program_id", $program_id);
            $query->bindParam(":semester", $current_school_year_period);
            $query->bindParam(":subject_code", $student_subject_programCode);
            $query->execute();
            
            if($query->rowCount() > 0){

                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>* $text</label>
                    <select id='course_id' class='form-control' name='course_id'>";

                $html .= "<option value='' disabled selected>Select Subject-Code</option>";

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                    $enrolled_student_count =  0;

                    // $capacity = $row['capacity'];
                    $program_section = $row['program_section'];
                    $course_id = $row['course_id'];

                    $program_id = $row['program_id'];
                    $subject_code = $row['subject_code'];

                    $program = new Program($this->con, $program_id);

            
                    // Remove Current Section Subject Code
                    if($course_id == $student_subject_course_id) continue;

                    $program_acronym = $program->GetProgramAcronym();

                    $sec = new Section($this->con, $course_id);
                    $section_code = $sec->CreateSectionSubjectCode($program_section,
                        $subject_code);

                    // echo $subject_code . " is with $program_acronym";
                    // echo $section_code;
                    // echo "<br>";

                    $capacity = "";
                    $program_section = "";

                    $selected = "";

                    $section_subject_code = $section->CreateSectionSubjectCode($program_section,
                        $subject_code);


                    $html .= "<option value='".$course_id."' $selected>".$section_code." &nbsp &nbsp &nbsp &nbsp(Enrolled Student: ". $enrolled_student_count . " / " . $capacity . ")</option>";

                }

                $html .= "</select>
                        </div>";
                return $html;
            }else{
                $html .= "
                    <div class='form-group mb-2'>
                        <label class='mb-2'>No available section offered</label>
                    </div>
                ";
            }
 
            return $html;
        }


        public function CheckSetionExistsWithinCurrentSY($program_section, $school_year_term){

            $sql = $this->con->prepare("SELECT program_section FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                ");
                
            $sql->bindValue(":program_section", $program_section);
            $sql->bindValue(":school_year_term", $school_year_term);
            $sql->execute();

            return $sql->rowCount() > 0;
        }

        public function CheckIdExists($course_id) {

            $query = $this->con->prepare("SELECT * FROM course
                    WHERE course_id=:course_id");

            $query->bindParam(":course_id", $course_id);
            $query->execute();

            if($query->rowCount() == 0){
                echo "
                    <div class='col-md-12'>
                        <h4 class='text-center text-warning'>ID Doesnt Exists.</h4>
                    </div>
                ";
                exit();
            }
        }

        public function GetDepartmentIdByProgramId($program_id){

            $sql = $this->con->prepare("SELECT department_id FROM program
                WHERE program_id=:program_id");
                
            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return -1;
        }

        public function GetTrackByProgramId($program_id){

            $sql = $this->con->prepare("SELECT track FROM program
                WHERE program_id=:program_id");
                
            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchColumn();
            
            return "N/A";
        }

        public function GetStudentSubjectsByCourseId($course_id,
            $current_school_year_term){

            $sql = $this->con->prepare("SELECT t2.* FROM course as t1

                INNER JOIN subject as t2 ON t2.course_id = t1.course_id

                WHERE t2.course_id = :course_id
                AND t2.semester = :semester
                ");
                
            $sql->bindParam(":course_id", $course_id);
            $sql->bindParam(":semester", $current_school_year_term);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            
            return [];
        }

        public function GetStudentSubjectsByCourseIdCurriculumBased($course_id,
            $period, $student_course_level){

            $sql = $this->con->prepare("SELECT 

                t2.*, t1.program_section, t1.course_id
                
                FROM course as t1

                INNER JOIN subject_program as t2 ON t2.program_id = t1.program_id

                WHERE t2.semester = :semester
                AND t1.course_id = :course_id
                AND t2.course_level = :course_level
                ");
                
            $sql->bindParam(":semester", $period);
            $sql->bindParam(":course_id", $course_id);
            $sql->bindParam(":course_level", $student_course_level);
            $sql->execute();

            if($sql->rowCount() > 0)
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            
            return [];
        }


        public function SetSectionIsFull($course_id){

            $is_full = "yes";
            
            $update = $this->con->prepare("UPDATE course
                SET is_full=:is_full
                WHERE course_id=:course_id");
            
            $update->bindValue(":is_full", $is_full);
            $update->bindValue(":course_id", $course_id);

            return $update->execute();

        }

       

        public function AutoCreateAnotherSection($program_section){

            // STEM11-A -> STEM11-B
            // STEM11-C -> STEM11-D
 
            if($program_section != ""){
                $last_letter = substr($program_section, -1);
                $next_letter = chr(ord($last_letter) + 1);
                // echo $next_letter;
                // echo "<br>";
                $prefix = substr($program_section, 0, -1);
                // echo $prefix;
                // echo $prefix . $next_letter;
                return $prefix . $next_letter;
            }
        }

        public function CreateNewSection($new_section_name, 
            $program_id, $course_level, $current_school_year_term){
            
            // $sql = $this->con->prepare("INSERT INTO course
            //     (program_section, program_id, creationDate)
            //     WHERE program_id=:program_id");

            $active = "yes";
            $is_full = "no";


            $defaultGrade11StemStrand = $this->con->prepare("INSERT INTO course

                (program_section, program_id, course_level, capacity,
                    school_year_term, active, is_full)

                VALUES(:program_section, :program_id, :course_level, :capacity,
                    :school_year_term, :active, :is_full)");

            $defaultGrade11StemStrand->bindValue(":program_section", $new_section_name);

            $defaultGrade11StemStrand->bindValue(":program_id", $program_id, PDO::PARAM_INT);
            $defaultGrade11StemStrand->bindValue(":course_level", $course_level, PDO::PARAM_INT);
            $defaultGrade11StemStrand->bindValue(":capacity", 2);
            $defaultGrade11StemStrand->bindValue(":school_year_term", $current_school_year_term);
            $defaultGrade11StemStrand->bindValue(":active", $active);
            $defaultGrade11StemStrand->bindValue(":is_full", $is_full);

            if($defaultGrade11StemStrand->execute()){
                return true;
            }

            return false;
        }

        public function CreateSectionSubjectCode($section_name, $subject_code){

            return $section_name . "-" . $subject_code;
        }

        public function DeactiveCurrentActiveSections($school_year_term){

            $activeSection = $this->GetAllActiveSection($school_year_term);

            $sql = $this->con->prepare("UPDATE course
                SET active = :change_to

                WHERE course_id = :course_id
                AND active = 'yes'
            ");

            $isDone = false;

            foreach ($activeSection as $key => $value) {
                # code...

                $course_id = $value['course_id'];

                $sql->bindValue(":change_to", "no");
                $sql->bindParam(":course_id", $course_id);
                
                if($sql->execute()){
                    $isDone = true;
                }
            }

            return $isDone;
        }

        public function MovingUpCurrentActiveSections($school_year_term){

            $activeSection = $this->GetAllActiveSection($school_year_term);

            $movingUp = $this->con->prepare("INSERT INTO course
                (program_section, program_id, course_level, capacity, room,
                    school_year_term, active, is_tertiary, is_full, previous_course_id)
                VALUES (:program_section, :program_id, :course_level, :capacity, :room, 
                    :school_year_term, :active, :is_tertiary, :is_full, :previous_course_id)
            ");

            $isDone = false;

            $deactivate_section = $this->con->prepare("UPDATE course
                SET active=:active

                WHERE course_id=:course_id
                AND school_year_term=:school_year_term");


            foreach ($activeSection as $key => $value) {
                # code...

                $course_id = $value['course_id'];
                $program_section = $value['program_section'];
                $program_id = $value['program_id'];
                $course_level = $value['course_level'];
                $is_tertiary = $value['is_tertiary'];
                $school_year_term = $value['school_year_term'];

                // echo $course_id;

                // if($course_level === 12 || $course_level === 4){

                //     // Deactivate this for it was no longer useful.
                    
                //     $deactivate_section->bindValue(":active", "no");
                //     $deactivate_section->bindParam(":course_id", $course_id);
                //     $deactivate_section->bindParam(":school_year_term", $school_year_term);
                //     // echo $course_id;

                //     if($deactivate_section->execute()){
                //         $isDone = true;
                //     }
                // }
                if($course_level !== 12 || $course_level !== 4){

                    if($course_level == 12) continue;
                    if($course_level == 4) continue;

                    $movingUpName =  $this->MovingUpSectionName($program_section);
                    $next_term =  $this->getNextSchoolYear($school_year_term);

                    $movingUp->bindValue(":program_section", $movingUpName);
                    $movingUp->bindValue(":program_id", $program_id);
                    $movingUp->bindValue(":course_level", $course_level + 1);
                    $movingUp->bindValue(":capacity", 30);
                    $movingUp->bindValue(":room", 0);
                    $movingUp->bindValue(":active", "yes");
                    $movingUp->bindValue(":is_tertiary", $is_tertiary);
                    $movingUp->bindValue(":is_full", "no");
                    $movingUp->bindValue(":school_year_term", $next_term);
                    $movingUp->bindValue(":previous_course_id", $course_id);
                    
                    if($movingUp->execute()){
                        $isDone = true;
                    }
                }
            }
            return $isDone;
        }

        public function ResetCurrentActiveSections($school_year_term){

            $activeSection = $this->GetAllActiveSection($school_year_term);

            $update_course = $this->con->prepare("UPDATE course
                SET active=:active,
                    is_full=:is_full,
                    capacity=:capacity

                WHERE course_id=:course_id
                AND school_year_term=:school_year_term");

            $isDone = false;

            foreach ($activeSection as $key => $value) {
                # code...

                $course_id = $value['course_id'];

                $update_course->bindValue(":active", "yes");
                $update_course->bindValue(":is_full", "no");
                $update_course->bindValue(":capacity", 30);
                $update_course->bindParam(":course_id", $course_id);
                $update_course->bindParam(":school_year_term", $school_year_term);

                if($update_course->execute()){
                    $isDone = true;
                }
            }

            return $isDone;
        }

        public function CreateEachSectionStrandCourse($school_year_term){

            $program = new Program($this->con);

            $offeredPrograms = $program->GetAllOfferedPrograms();
            
            $sql = $this->con->prepare("INSERT INTO course
                (program_section, program_id, course_level, capacity, room,
                    school_year_term, active, is_tertiary, is_full, previous_course_id)
                VALUES (:program_section, :program_id, :course_level, :capacity, :room, 
                    :school_year_term, :active, :is_tertiary, :is_full, :previous_course_id)
            ");

            $isDone = false;

            foreach ($offeredPrograms as $key => $value) {
                # code...

                $acronym = $value['acronym'];
                $program_id = $value['program_id'];
                $department_name = $value['department_name'];

                $next_term =  $this->getNextSchoolYear($school_year_term);
                
                $level = $department_name == "Tertiary" ? 1 : 11;
                $is_tertiary = $department_name == "Tertiary" ? 1 : 0;

                $program_section = $acronym . $level . "-A";

                $sql->bindValue(":program_section", $program_section);
                $sql->bindValue(":program_id", $program_id);
                $sql->bindValue(":course_level", $level);
                $sql->bindValue(":capacity", 30);
                $sql->bindValue(":room", 0);
                $sql->bindValue(":active", "yes");
                $sql->bindValue(":is_tertiary", $is_tertiary);
                $sql->bindValue(":is_full", "no");
                $sql->bindValue(":school_year_term", $next_term);
                $sql->bindValue(":previous_course_id", NULL);
                
                if($sql->execute()){
                    $isDone = true;
                }
            }

            return $isDone; 

        }


        public function getNextSchoolYear($current_term){
            // Extract the starting year from the string
            $startingYear = intval(substr($current_term, 0, 4));

            // Calculate the next year's starting year
            $nextStartingYear = $startingYear + 1;

            // Construct the new school year string
            $newSchoolYear = $nextStartingYear . '-' . ($nextStartingYear + 1);

            return $newSchoolYear;
        }

        public function MovingUpSectionName($tertiary_program_section){
            $pattern = '/(\d+)/';
            $replacement = '${1}';

            $newString = preg_replace_callback($pattern, function($matches) {
                return intval($matches[0]) + 1;
            }, $tertiary_program_section);
        
            return $newString;
        }

        public function GetAllActiveSection($school_year_term){

            $activeSection = [];

            $sql = $this->con->prepare("SELECT *
                FROM course as t1
               
                WHERE t1.school_year_term = :school_year_term
                AND t1.active = 'yes'
                ");
                
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->execute();

            if($sql->rowCount() > 0){

                $activeSection = $sql->fetchAll(PDO::FETCH_ASSOC);

            }
           
            // print_r($course_ids);
            return $activeSection;


        }


        public function GetRegularOldSectionList($student_program_id,
            $current_school_year_term, $student_course_level){

            $activeSection = [];

            $active = "yes";

            $sql = $this->con->prepare("SELECT * FROM course

                WHERE program_id=:program_id
                AND active=:active
                AND is_remove = 0
                AND school_year_term=:school_year_term
                AND course_level=:course_level
            ");

            $sql->bindParam(":program_id", $student_program_id);
            $sql->bindParam(":active", $active);
            $sql->bindParam(":school_year_term", $current_school_year_term);
            $sql->bindParam(":course_level", $student_course_level);

            $sql->execute();
        
            if($sql->rowCount() > 0){
                $activeSection = $sql->fetchAll(PDO::FETCH_ASSOC);

            }
           
            // print_r($course_ids);
            return $activeSection;

        }

        public function GetIrregularOldSectionList($student_program_id,
            $current_school_year_term,
            $student_course_level = null
            ){

            $activeSection = [];

            $active = "yes";

            $sql = $this->con->prepare("SELECT * FROM course

                WHERE program_id=:program_id
                AND active=:active
                AND is_remove = 0
                AND school_year_term=:school_year_term
                -- AND course_level=:course_level
            ");

            $sql->bindParam(":program_id", $student_program_id);
            $sql->bindParam(":active", $active);
            $sql->bindParam(":school_year_term", $current_school_year_term);
            // $sql->bindParam(":course_level", $student_course_level);

            $sql->execute();
        
            if($sql->rowCount() > 0){
                $activeSection = $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            // print_r($course_ids);
            return $activeSection;

        }

        public function RemoveUnEnrolledCreatedSectionWithinSemester($current_school_year_term,
            $school_year_id){

            $successRemove = false;

            $enrollment = new Enrollment($this->con);

            $getEnrolledSection = $enrollment->GetAllEnrolledEnrollmentCourseIDWithinSemester($school_year_id);
            
            $semesterSectionID = [];
            $sections = $this->GetAllActiveSection($current_school_year_term);

            foreach ($sections as $key => $value) {
                array_push($semesterSectionID, $value['course_id']);
            }

            $excludedSectionSemester = array_diff($semesterSectionID, $getEnrolledSection);

            // print_r($excludedSectionSemester);
            
            $update = $this->con->prepare("UPDATE course
                SET is_remove=:is_remove
                WHERE course_id=:course_id");
            
            foreach ($excludedSectionSemester as $key => $courseIds) {
                 
                // Update this to be removed = 1.
                
                $update->bindValue(":is_remove", 1);
                $update->bindValue(":course_id", $courseIds);

                if($update->execute()){
                    $successRemove = true;
                }
            }

            return $successRemove;

        }
         
    }
?>