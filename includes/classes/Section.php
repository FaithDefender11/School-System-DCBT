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

        public function GetSectionGradeLevel() {
            return isset($this->sqlData['course_level']) ? $this->sqlData["course_level"] : ""; 
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

        public function CreateSectionLevelContent($program_id, $term,
            $course_level, $enrollment){

            $output = "";
            $query = $this->con->prepare("SELECT t1.* 

                FROM course as t1 

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
                    $active = $row['active'];
                    $is_full = $row['active'];
                    $capacity = $row['capacity'];

                    $active_status = ($active != "no") 
                        ? "<i style='color: green;' class='fas fa-check'></i>" 
                        : "<i style='color: orange;' class='fas fa-times'></i>";

                    
                    // echo $course_id;
                    $students_enrolled = $enrollment->GetStudentEnrolled($course_id);

                    $output .= "
                        <tr>
                            <td>$course_id</td>
                            <td>
                                <a style='color:white;' href='subject_list.php?id=$course_id'>
                                    $program_section
                                </a>
                            </td>
                            <td>$students_enrolled / $capacity</td>
                            <td>$active_status</td>
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

            }else if($department_name == "Tertiary"){
                $html = "<div class='form-group mb-2'>
                    <label class='mb-2'>Course Level</label>

                <select id='course_level' class='form-control' name='course_level'>";

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

    }
?>