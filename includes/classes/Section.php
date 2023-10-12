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
         public function GetSectionMinimumCapacity() {
            return isset($this->sqlData['min_student']) ? $this->sqlData["min_student"] : 0; 
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

        public function GetSectionIsFull() {
            return isset($this->sqlData['is_full']) ? $this->sqlData["is_full"] : NULL; 
        }
        public function GetSectionIsActive() {
            return isset($this->sqlData['active']) ? $this->sqlData["active"] : NULL; 
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
            
            return "";
        }

        public function GetSectionRoomNumberBySemester($period, $course_id,
            $school_year_term){

            if($period == "First"){

                $sql = $this->con->prepare("SELECT 

                    t2.room_number FROM course as t1

                    INNER JOIN room as t2 ON t2.room_id = t1.first_period_room_id

                    WHERE t1.course_id=:course_id
                    AND t1.school_year_term=:school_year_term

                ");
                    
                $sql->bindValue(":course_id", $course_id);
                $sql->bindValue(":school_year_term", $school_year_term);
                $sql->execute();

                if($sql->rowCount() > 0){
                    return $sql->fetchColumn();
                }
                
            }
            if($period == "Second"){

                $sql = $this->con->prepare("SELECT 

                    t2.room_number FROM course as t1

                    INNER JOIN room as t2 ON t2.room_id = t1.second_period_room_id

                    WHERE t1.course_id=:course_id
                    AND t1.school_year_term=:school_year_term

                ");
                    
                $sql->bindValue(":course_id", $course_id);
                $sql->bindValue(":school_year_term", $school_year_term);
                $sql->execute();

                if($sql->rowCount() > 0){
                    return $sql->fetchColumn();
                }
                
            }

            
            return NULL;
        }

        public function CheckSectionIsFull($course_id){

            $enrollment = new Enrollment($this->con);

            $current_section_capacity = $this->GetSectionCapacity();
            $students_enrolled = $enrollment->GetStudentEnrolled($course_id);
              
            $doesCurrentSectionIsFull = false;
            
            if($students_enrolled >= $current_section_capacity){
                $doesCurrentSectionIsFull = true;
            }

            $sql = $this->con->prepare("SELECT is_full FROM course
                WHERE course_id=:course_id
                AND is_full='yes'");
                
            $sql->bindParam(":course_id", $course_id);
            $sql->execute();


            if($sql->rowCount() > 0 && $doesCurrentSectionIsFull){
                return true;
            }

            return false;
        }


        public function CheckNextActiveSectionIfExistNotFull(
            $current_program_section, $school_year_term){

            $nextSection = $this->transformString(ucwords($current_program_section));

            $sql = $this->con->prepare("SELECT course_id FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                AND active=:active
                AND is_full= 'no'
                ");
                
            $sql->bindParam(":program_section", $nextSection);
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "yes");
            $sql->execute();

            // $checknextisFull = $this->CheckNextActiveSectionIsFull()
            if($sql->rowCount() > 0 ){
                // echo "Exists not full Section : $nextSection";
                return true;
            }

            return false;
        }


        public function CheckNextActiveSectionIfExist(
            $current_program_section, $school_year_term){

            $nextSection = $this->transformString(ucwords($current_program_section));

            $sql = $this->con->prepare("SELECT course_id FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                AND active=:active
                ");
                
            $sql->bindParam(":program_section", $nextSection);
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "yes");
            $sql->execute();

            // $checknextisFull = $this->CheckNextActiveSectionIsFull()
            if($sql->rowCount() > 0 ){
                // echo "Exists Section : $nextSection";
                return true;
            }

            return false;
        }


        public function CheckNextInActiveSectionIfExistAndUpdateToActive(
            $current_program_section, $school_year_term){

            $update = $this->con->prepare("UPDATE course

                SET active=:set_active
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                AND active=:active");

            $nextSection = $this->transformString(ucwords($current_program_section));

            $sql = $this->con->prepare("SELECT course_id FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                AND active=:active
                ");
                
            $sql->bindParam(":program_section", $nextSection);
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "no");
            $sql->execute();

            // $checknextisFull = $this->CheckNextActiveSectionIsFull()
            if($sql->rowCount() > 0){

                // echo "Not active existing Section : $nextSection";

                $update->bindValue(":set_active", "yes");
                $update->bindParam(":program_section", $nextSection);
                $update->bindParam(":school_year_term", $school_year_term);
                $update->bindValue(":active", "no");
                $update->execute();

                return true;
            }

            return false;
        }


        private function transformString($inputString) {
            preg_match('/^(.*?)(\d+)-([A-Za-z])$/', $inputString, $matches);
            
            if (count($matches) !== 4) {
                // Invalid input format
                return $inputString;
            }
            
            $prefix = $matches[1];
            $numericPart = $matches[2];
            $currentLetter = $matches[3];
            
            // Convert the letter to uppercase and get the next letter
            $nextLetter = strtoupper(chr(ord($currentLetter) + 1));
            
            $newString = "{$prefix}{$numericPart}-{$nextLetter}";
            return $newString;
        }

        public function SectionHasRoomTransfer($school_year_term, $period){

            if($period == "First"){

                $update = $this->con->prepare("UPDATE course

                    SET second_period_room_id=:second_period_room_id
                    WHERE course_id=:course_id");

                $sql = $this->con->prepare("SELECT * FROM course
                    WHERE school_year_term=:school_year_term
                    AND active=:active
                    AND first_period_room_id IS NOT NULL
                    ");
                    
                $sql->bindParam(":school_year_term", $school_year_term);
                $sql->bindValue(":active", "yes");
                $sql->execute();

                $hasFinish = false;

                if($sql->rowCount() > 0 ){
                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                        $course_id = $row['course_id'];
                        $first_period_room_id = $row['first_period_room_id'];

                        $update->bindParam(":second_period_room_id", $first_period_room_id);
                        $update->bindParam(":course_id", $course_id);

                        $update->execute();
                    }

                    $hasFinish = true;
                }

            }

            return $hasFinish;
        }
        

        public function CheckNextSectionIfFull($course_id){

            $sql = $this->con->prepare("SELECT is_full FROM course
                WHERE course_id=:course_id
                AND is_full='yes'");
                
            $sql->bindParam(":course_id", $course_id);
            $sql->execute();

            if($sql->rowCount() > 0 ){
                return true;
            }

            return false;
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

        public function GetTotalNumberOfStudentInSection(
            $course_id,
            $current_school_year_id){

            $sql = $this->con->prepare("SELECT 
                            
                t3.program_id, t2.student_id,
                t2.student_status,t2.firstname, t2.lastname 
                
                FROM enrollment as t1
        
                INNER JOIN student as t2 ON t2.student_id = t1.student_id
                INNER JOIN course as t3 ON t3.course_id = t1.course_id

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

        public function CreateSHSSectionLevelSemesterContent($program_id,
            $term, $period_room_id, $course_level, $enrollment,
            $school_year_period, $school_year_term, $current_school_year_id =  null,
            $type = null){

            $output = "";
            
            $query = $this->con->prepare("SELECT t1.*, t2.room_number

                FROM course as t1 

                LEFT JOIN room as t2 ON t2.room_id = t1.$period_room_id
                -- LEFT JOIN room as t2 ON t2.room_id = t1.second_period_room_id

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

                    // echo "heyllo $type";

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
                        
                    $students_enrolled = $enrollment->GetStudentEnrolled(
                        $course_id, $current_school_year_id);

                    
                    $totalStudent = $this->GetTotalNumberOfStudentInSection($course_id, 
                        $current_school_year_id);

                    $removeSection=  "removeSection($course_id, $course_level)";

                    
                    $editUrl=  "edit.php?id=$course_id&p_id=$program_id";

                    $show_url = "show.php?id=$course_id&per_semester=$school_year_period&term=$school_year_term";

                    //  <td>".$room_number."</td>

                    $removeButton = "";
                    
                    if($type == "admin"){
                        $removeButton = "
                            <button onclick='$removeSection'class='btn btn-sm btn-danger'  >
                                <i class='fas fa-times-circle'></i>
                            </button>
                        ";
                    }

                    // echo $type;
                    // echo "<br>";

                    $output .= "
                        <tr>
                            <td>$course_id</td>
                            <td>
                                $program_section
                            </td>
                           
                            <td>$students_enrolled / $capacity</td>
                            <td>$active_status</td>
                            <td>

                                <button onclick=\"window.location.href='$editUrl'\" 
                                    class='btn btn-sm btn-primary'>
                                    <i class='bi bi-pencil-square'></i>
                                </button>

                                $removeButton

                                <button onclick=\"window.location.href='$show_url'\"  class='btn btn-sm btn-info'    >
                                    <i class='bi bi-eye-fill '></i>
                                </button>

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

                $html = "<label for='program_id'>Program</label>
                    <div>
                    <select id='program_id' name='program_id'>";

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

                $html = "
                    <label for='course_level'>* Course Level</label>
                    <div>
                    <select class='form-control' required id='course_level' name='course_level'>";

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
                $html = "
                    <label for='course_level'>* Course Level</label>
                    <div>
                    <select class='form-control' required id='course_level' name='course_level'>
                        <option value='' disabled selected>Choose Level</option>";

                $html .= "
                    <option value='1'" . ($course_level == 1 ? " selected" : "") . ">1st Year</option>
                    <option value='2'" . ($course_level == 2 ? " selected" : "") . ">2nd Year</option>
                    <option value='3'" . ($course_level == 3 ? " selected" : "") . ">3rd Year</option>
                    <option value='4'" . ($course_level == 4 ? " selected" : "") . ">4th Year</option>
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

                $html = "
                    <label for='course_id'>$text</label>
                    <div>
                    <select id='course_id' name='course_id'>";

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

                $html = "
                    <label for='course_id'>* $text</label>
                    <div>
                    <select id='course_id' name='course_id'>";

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

        public function GetAllCreatedSectionWithinSYSemester(
            $school_year_term){

            $query = $this->con->prepare("SELECT *

                FROM course AS t2 

                WHERE t2.school_year_term = :school_year_term
                AND t2.active = :active

                ORDER BY t2.program_id
            ");

            $query->bindValue(":school_year_term", $school_year_term);
            $query->bindValue(":active", "yes");
            $query->execute();
            
            if($query->rowCount() > 0){
                return $query->fetchAll(PDO::FETCH_ASSOC);
            } 
 
            return [];
        }

        public function CheckSetionExistsWithinCurrentSY($program_section,
            $school_year_term){

            $sql = $this->con->prepare("SELECT program_section FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                ");
                
            $sql->bindValue(":program_section", $program_section);
            $sql->bindValue(":school_year_term", $school_year_term);
            $sql->execute();

            return $sql->rowCount() > 0;
        }

        public function CheckSectionInActiveExistsAndCorrect($program_section,
            $school_year_term){

            $sql = $this->con->prepare("SELECT program_section FROM course
                WHERE program_section=:program_section
                AND school_year_term=:school_year_term
                AND active=:active
                ");

            $sql->bindValue(":program_section", $program_section);
            $sql->bindValue(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "no");
            $sql->execute();

            if($sql->rowCount() > 0){
                
                $update = $this->con->prepare("UPDATE course
                    SET active=:set_active

                    WHERE program_section=:program_section
                    AND school_year_term=:school_year_term");
                
                $update->bindValue(":set_active", "yes");
                $update->bindValue(":program_section", $program_section);
                $update->bindValue(":school_year_term", $school_year_term);
                $update->execute();

                if($sql->rowCount() > 0){
                    return true;
                }
            }

            return false;
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

            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }
            return false;
        }

        public function SetSectionToNonFull($course_id){

            $non_full = "no";
            
            $update = $this->con->prepare("UPDATE course
                SET is_full=:is_full
                WHERE course_id=:course_id");
            
            $update->bindValue(":is_full", $non_full);
            $update->bindValue(":course_id", $course_id);

            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }
            return false;
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
            $defaultGrade11StemStrand->bindValue(":capacity", 30);
            $defaultGrade11StemStrand->bindValue(":school_year_term", $current_school_year_term);
            $defaultGrade11StemStrand->bindValue(":active", $active);
            $defaultGrade11StemStrand->bindValue(":is_full", $is_full);

            if($defaultGrade11StemStrand->execute()){
                return true;
            }

            return false;
        }

        public function CreateSectionSubjectCode($section_name, $program_code){

            return $section_name . "-" . $program_code;
        }

        public function DeactiveCurrentActiveSections($school_year_term){

            $activeSection = $this->GetAllActiveSectionWithinYear(
                $school_year_term);

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
                $sql->execute();

                if($sql->rowCount() > 0){
                    $isDone = true;
                }
            }

            return $isDone;
        }

        public function MovingUpCurrentActiveSections($school_year_term){

            $activeSectionIn2ndSem = $this->GetAllActiveSectionRoomIn2ndSem(
                $school_year_term);

            // print_r($activeSectionIn2ndSem);
            // $activeSection = $this->GetAllActiveSection($school_year_term);

            // Check first if GetAllActiveSectionRoomIn2ndSem already exists.

            $movingUp = $this->con->prepare("INSERT INTO course
                (program_section, program_id, course_level, capacity,
                    school_year_term, active, is_tertiary, is_full, previous_course_id, min_student)
                VALUES (:program_section, :program_id, :course_level, :capacity, 
                    :school_year_term, :active, :is_tertiary, :is_full, :previous_course_id, :min_student)
            ");

            $isDone = false;

            foreach ($activeSectionIn2ndSem as $key => $value) {
                # code...

                $course_id = $value['course_id'];
                $program_section = $value['program_section'];
                $program_id = $value['program_id'];
                $course_level = $value['course_level'];
                $is_tertiary = $value['is_tertiary'];
                $school_year_term = $value['school_year_term'];
                $capacity = $value['capacity'];
                $min_student = $value['min_student'];
            
                if($course_level !== 12 || $course_level !== 4){

                    if($course_level == 12) continue;
                    if($course_level == 4) continue;

                    $movingUpName =  $this->MovingUpSectionName($program_section);
                    $next_term =  $this->getNextSchoolYear($school_year_term);

                    $movingUp->bindValue(":program_section", $movingUpName);
                    $movingUp->bindValue(":program_id", $program_id);
                    $movingUp->bindValue(":course_level", $course_level + 1);
                    $movingUp->bindValue(":capacity", $capacity);
                    $movingUp->bindValue(":min_student", $min_student);
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
                SET is_full=:is_full
                    -- active=:active

                WHERE course_id=:course_id
                AND school_year_term=:school_year_term");

            $isDone = false;

            foreach ($activeSection as $key => $value) {
                # code...
                $course_id = $value['course_id'];

                // $update_course->bindValue(":active", "yes");
                $update_course->bindValue(":is_full", "no");
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
                (program_section, program_id, course_level, capacity,
                    school_year_term, active, is_tertiary, is_full, previous_course_id, min_student)
                VALUES (:program_section, :program_id, :course_level, :capacity,
                    :school_year_term, :active, :is_tertiary, :is_full, :previous_course_id, :min_student)
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
                $sql->bindValue(":min_student", 15);
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
                AND t1.active = :active
                AND t1.is_remove = :section_remove
                ");
                
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "yes");
            $sql->bindValue(":section_remove", 0);
            $sql->execute();

            if($sql->rowCount() > 0){

                $activeSection = $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            // print_r($course_ids);
            return $activeSection;
        }

        public function GetAllActiveSectionRoomIn2ndSem($school_year_term){

            $activeSection = [];

            $sql = $this->con->prepare("SELECT *
                FROM course as t1
               
                WHERE t1.school_year_term = :school_year_term
                -- AND t1.second_period_room_id IS NOT NULL
                AND t1.active = :active
                ");
                
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "yes");
            $sql->execute();

            if($sql->rowCount() > 0){

                $activeSection = $sql->fetchAll(PDO::FETCH_ASSOC);
                // print_r($activeSection);

            }
            // print_r($activeSection);
            return $activeSection;
        }

        public function GetAllActiveSectionWithinYear($school_year_term){

            $activeSection = [];

            $sql = $this->con->prepare("SELECT t1.*

                FROM course as t1
               
                WHERE t1.school_year_term = :school_year_term
                AND t1.active = :active
                -- AND t1.first_period_room_id IS NOT NULL
                -- AND t1.second_period_room_id IS NOT NULL
                ");
                
            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindValue(":active", "yes");
            $sql->execute();

            if($sql->rowCount() > 0){
                $activeSection = $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            
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

        public function GetCurrentSectionWithEnrolledStudent(
            $current_school_year_id){

            $activeSection = [];
            $enrollment_status = "enrolled";
            $active = "yes";

            $sql = $this->con->prepare("SELECT t1.school_year_id, t2.* FROM enrollment AS t1

                INNER JOIN course AS t2 ON t2.course_id = t1.course_id
                
                WHERE t1.enrollment_status=:enrollment_status
                AND t1.school_year_id=:school_year_id

                GROUP BY t1.course_id
            ");

            $sql->bindParam(":enrollment_status", $enrollment_status);
            $sql->bindParam(":school_year_id", $current_school_year_id);
            
            $sql->execute();
        
            if($sql->rowCount() > 0){
                $activeSection = $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            // print_r($course_ids);
            return $activeSection;

        }

        public function GetSectionSubjectCodes(
            $program_id, $current_semester, $course_level, $type){

            $sql = $this->con->prepare("SELECT 
            
                t1.*
                
                FROM subject_program AS t1

                -- INNER JOIN course AS t2 ON t2.course_id = t1.course_id
                
                WHERE t1.program_id=:program_id
                AND t1.semester=:semester
                AND t1.course_level=:course_level
                AND t1.department_type=:department_type

            ");

            $sql->bindParam(":program_id", $program_id);
            $sql->bindParam(":semester", $current_semester);
            $sql->bindParam(":course_level", $course_level);
            $sql->bindParam(":department_type", $type);
            
            $sql->execute();
        
            if($sql->rowCount() > 0){
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];

        }

        public function GetStudentsEnrolledInSection(
            $course_id,
            $school_year_id){

            $studentEnrolledList = [];
            $enrollment_status = "enrolled";

            $sql = $this->con->prepare("SELECT 
                t1.school_year_id,
                t1.enrollment_approve,
                t2.*,

                t3.firstname,
                t3.username,
                t3.student_unique_id,
                t3.lastname,
                t3.course_level,
                t3.email,
                t3.student_unique_id,

                t3.admission_status,
                t3.student_statusv2,
                t3.course_id,
                t3.student_id,
                t3.course_level,
                t3.student_status,
                t3.is_tertiary,
                t3.new_enrollee
                
                FROM enrollment AS t1

                INNER JOIN course AS t2 ON t2.course_id = t1.course_id
                AND t2.course_id=:course_id

                INNER JOIN student AS t3 ON t3.student_id = t1.student_id


                WHERE t1.enrollment_status=:enrollment_status
                AND t1.school_year_id=:school_year_id

            ");

            $sql->bindParam(":enrollment_status", $enrollment_status);
            $sql->bindParam(":school_year_id", $school_year_id);
            $sql->bindParam(":course_id", $course_id);
            
            $sql->execute();
        
            if($sql->rowCount() > 0){
                $studentEnrolledList = $sql->fetchAll(PDO::FETCH_ASSOC);
            }
            // print_r($course_ids);
            return $studentEnrolledList;

        }

        public function RemoveUnEnrolledCreatedSectionWithinSemester(
            $current_school_year_term,
            $school_year_id){

            $successRemove = false;

            $enrollment = new Enrollment($this->con);

            $getEnrolledSection = $enrollment
                ->GetAllEnrolledEnrollmentCourseIDWithinSemester(
                    $school_year_id);
            
            $semesterSectionID = [];
            $sections = $this->GetAllActiveSection($current_school_year_term);

            foreach ($sections as $key => $value) {
                array_push($semesterSectionID, $value['course_id']);
            }

            $excludedSectionSemester = array_diff($semesterSectionID, $getEnrolledSection);

            // print_r($excludedSectionSemester);
            
            $update = $this->con->prepare("UPDATE course
                SET is_remove=:is_remove,
                    active=:active
                WHERE course_id=:course_id");
            
            foreach ($excludedSectionSemester as $key => $courseIds) {
                 
                // Update this to be removed = 1.
                
                $update->bindValue(":is_remove", 1);
                $update->bindValue(":active", "no");
                $update->bindParam(":course_id", $courseIds);

                if($update->execute()){
                    $successRemove = true;
                }
            }

            return $successRemove;
        }
         
        public function RemoveUnEnrolledSectionInFirstSemester(
            $current_school_year_term,
            $school_year_id){

            $semester = "First";
            $successRemove = false;

            $enrollment = new Enrollment($this->con);

            $getEnrolledSection = $enrollment
                ->GetAllCourseIDsWithRoomInSemester(
                    $school_year_id, $semester);

            // print_r($getEnrolledSection);
            
            $semesterSectionID = [];
            $sections = $this->GetAllActiveSection($current_school_year_term);

            foreach ($sections as $key => $value) {
                array_push($semesterSectionID, $value['course_id']);
            }

            $excludedSectionSemester = [];
            if(count($semesterSectionID) > 0 && 
                count($getEnrolledSection) > 0){

                $excludedSectionSemester = array_diff($semesterSectionID,
                    $getEnrolledSection);
            }


            // print_r($excludedSectionSemester);

            $removeSection = $this->con->prepare("DELETE FROM course
                WHERE course_id=:course_id
            ");
            
            foreach ($excludedSectionSemester as $key => $courseIds) {
                 
                $removeSection->bindParam(":course_id", $courseIds);
                $removeSection->execute();

                if($removeSection->rowCount() > 0){
                    $successRemove = true;
                }
            }

            return $successRemove;
        }

        public function CheckSectionIDHasEnrolledForm($course_id, 
            $school_year_id = null){

            $sql = $this->con->prepare("SELECT t1.course_id 
            
                FROM course AS t1
                INNER JOIN enrollment as t2 ON t2.course_id = t1.course_id
                AND t2.enrollment_status = 'enrolled'
                AND t2.school_year_id = :school_year_id
                AND t2.course_id = :course_id
                LIMIT 1
                ");
        
            $sql->bindParam(":school_year_id", $school_year_id);
            $sql->bindParam(":course_id", $course_id);
            $sql->execute();

            if($sql->rowCount() > 0){
                // echo "Course Id: $course_id has enrolled form in it.";
                // echo "<br>";
                return true;
            }
            return false;
        }

        public function GetAllNoRoomCreatedSectionWithoutEnrolledFormWithinSemester(
            $current_school_year_term, $semester, $school_year_id = null){

            $semester = ucfirst($semester);

            $arr = [];

            if($semester == "First"){

                $sql = $this->con->prepare("SELECT t1.course_id 
                
                    FROM course AS t1
                    INNER JOIN enrollment as t2 ON t2.course_id = t1.course_id
                    AND t2.enrollment_status = 'tentative'
                    AND t2.school_year_id = :school_year_id

                    WHERE t1.school_year_term=:school_year_term
                    AND t1.first_period_room_id IS NULL
                    
                    GROUP BY t2.course_id
                    ");
            
                $sql->bindParam(":school_year_term", $current_school_year_term);
                $sql->bindParam(":school_year_id", $school_year_id);
                $sql->execute();

                if($sql->rowCount() > 0){
                    
                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                        $row_course_id = $row['course_id'];

                        # Check if Course Id has enrolled
                        # If it has, continue/exclude from the array
                        if($this->CheckSectionIDHasEnrolledForm(
                            $row_course_id, $school_year_id) === true){

                            continue;
                        }else if($this->CheckSectionIDHasEnrolledForm(
                            $row_course_id, $school_year_id) == false){

                            array_push($arr, $row_course_id);
                        }
                    }
                }
            }

            if($semester == "Second"){

                $sql = $this->con->prepare("SELECT t1.course_id 
                
                    FROM course AS t1
                    INNER JOIN enrollment as t2 ON t2.course_id = t1.course_id
                    AND t2.school_year_id = :school_year_id
                    AND t2.enrollment_status = 'tentative'

                    WHERE t1.school_year_term=:school_year_term
                    AND t1.first_period_room_id IS NULL
                    AND t1.second_period_room_id IS NULL

                    GROUP BY t2.course_id
                ");
            
                $sql->bindParam(":school_year_term", $current_school_year_term);
                $sql->bindParam(":school_year_id", $school_year_id);
                $sql->execute();

                if($sql->rowCount() > 0){

                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                        $row_course_id = $row['course_id'];

                        # Check if Course Id has enrolled
                        # If it has, continue/exclude from the array
                        if($this->CheckSectionIDHasEnrolledForm(
                            $row_course_id, $school_year_id) === true){
                            continue;
                        }else if($this->CheckSectionIDHasEnrolledForm(
                            $row_course_id, $school_year_id) == false){
                            array_push($arr, $row_course_id);
                        }
                    }
                    // return $sql->fetchAll(PDO::FETCH_COLUMN);
                }
            }

            return $arr;
        }

        public function RemoveUnEnrolledSectionWithinSemester(
            $current_school_year_term, $semester, $school_year_id = null){

            $semester = ucfirst($semester);

            $successRemove = false;

            $enrollment = new Enrollment($this->con);
 
            $createdSectionHasNoEnrolledForm = $this->GetAllNoRoomCreatedSectionWithoutEnrolledFormWithinSemester
                ($current_school_year_term, $semester, $school_year_id);
            
                            
            $removeSection = $this->con->prepare("DELETE FROM course
                WHERE course_id=:course_id");
            
            foreach ($createdSectionHasNoEnrolledForm as $key => $courseId) {
                 
                // $courseId = $value['course_id'];
                $removeSection->bindParam(":course_id", $courseId);
                $removeSection->execute();

                if($removeSection->rowCount() > 0){
                    $successRemove = true;
                }
            }

            return $successRemove;
        }

        public function RemoveUnEnrolledSectionInSecondSemester(
            $current_school_year_term, $semester, $school_year_id = null){

            $semester = ucfirst($semester);

            $successRemove = false;

            $enrollment = new Enrollment($this->con);
 
            $sectionToExcluded = $enrollment->GetAllSectionToExclude($current_school_year_term,
                $semester);

            $hasRoomFrom1stTo2nd = $enrollment->GetAllSectionsFromFirstToSecondWithRoom($current_school_year_term,
                $semester);

            $excludedSectionSemester = [];

            // print_r($sectionToExcluded);

            if(count($sectionToExcluded) > 0 && 
                count($hasRoomFrom1stTo2nd) > 0){

                $excludedSectionSemester = array_diff($sectionToExcluded,
                    $hasRoomFrom1stTo2nd);
            }
            
            $removeSection = $this->con->prepare("DELETE FROM course
                WHERE course_id=:course_id
            ");
            
            foreach ($excludedSectionSemester as $key => $courseIds) {
                 
                $removeSection->bindParam(":course_id", $courseIds);
                $removeSection->execute();

                if($removeSection->rowCount() > 0){
                    $successRemove = true;
                }
            }

            return $successRemove;
        }
         
        public function CheckRoomIsTakenCurrentSemester(
            $first_or_second_period_room_id,
            $column_name,
            $current_school_year_term) {

            // if($first_or_second_period_room_id == 0) return;

            $sql = $this->con->prepare("SELECT * FROM course
                WHERE $column_name = :first_or_second_period_room_id
                AND school_year_term = :school_year_term
                AND active = 'yes'
            ");

            $sql->bindParam(":first_or_second_period_room_id", $first_or_second_period_room_id);
            $sql->bindParam(":school_year_term", $current_school_year_term);

            $sql->execute();

            return $sql->rowCount() > 0;
        }


        public function GetAvailableFindSection($program_id,
            $current_school_year_term,
            $pending_course_level){

                // echo $program_id;
                // echo "<br>";
                // echo $current_school_year_term;
                // echo "<br>";
                // echo $pending_course_level;

            $sql = $this->con->prepare("SELECT * FROM course

                WHERE program_id=:program_id
                AND active=:active
                AND school_year_term =:school_year_term
                AND course_level =:course_level
                AND is_full ='no'
                AND is_remove != 1
                
                ");

            $sql->bindParam(":program_id", $program_id);
            $sql->bindValue(":active", "yes");
            $sql->bindParam(":school_year_term", $current_school_year_term);
            $sql->bindParam(":course_level", $pending_course_level);

            $sql->execute();
        
            if($sql->rowCount() > 0){
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];
        }

        public function GetSectionIdHasRoomSemester($period, $school_year_term){

            $period_column = strtolower($period) . "_period_room_id";

            if($period == "First"){

                $sql = $this->con->prepare("SELECT first_period_room_id FROM course

                    WHERE school_year_term =:school_year_term
                    AND first_period_room_id IS NOT NULL
                    AND active ='yes'");

                // $sql->bindParam(":period_column", $period_column);
                $sql->bindParam(":school_year_term", $school_year_term);

                $sql->execute();
            
                if($sql->rowCount() > 0){
                    return $sql->fetchAll(PDO::FETCH_COLUMN);
                }            
            }
            if($period == "Second"){

                $sql = $this->con->prepare("SELECT second_period_room_id FROM course

                    WHERE school_year_term =:school_year_term
                    AND second_period_room_id IS NOT NULL
                    AND active ='yes'");

                // $sql->bindParam(":period_column", $period_column);
                $sql->bindParam(":school_year_term", $school_year_term);

                $sql->execute();
            
                if($sql->rowCount() > 0){
                    return $sql->fetchAll(PDO::FETCH_COLUMN);
                }            
            }

            return [];
        }

        public function CheckSectionIsBelowMinStudent($students_enrolled,
            $course_id,
            $school_year_term){


                // echo $students_enrolled;
            $sql = $this->con->prepare("SELECT * FROM course

                WHERE school_year_term =:school_year_term
                AND course_id = :course_id
                AND min_student > :min_student
                AND active ='yes'");

            $sql->bindParam(":school_year_term", $school_year_term);
            $sql->bindParam(":course_id", $course_id);
            $sql->bindParam(":min_student", $students_enrolled);

            $sql->execute();
        
            if($sql->rowCount() > 0){
                return true;
            }   

            return false;

        }

     

    }


?>