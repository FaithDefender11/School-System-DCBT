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

                    // echo $course_id;

                    $students_enrolled = $enrollment->GetStudentEnrolled($course_id);

                    $output .= "
                    
                        <tr>
                            <td>$course_id</td>
                            <td>$program_section</td>
                            <td>$students_enrolled</td>
                        </tr>
                    ";
                }
            }

            return $output;
        }
        

    }
?>