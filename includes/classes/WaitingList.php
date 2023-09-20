<?php

    class WaitingList{

        private $con, $waiting_list_id, $sqlData;


        public function __construct($con, $waiting_list_id = null){
            $this->con = $con;
            $this->waiting_list_id = $waiting_list_id;

            $query = $this->con->prepare("SELECT * FROM waiting_list
                WHERE waiting_list_id=:waiting_list_id");

            $query->bindValue(":waiting_list_id", $waiting_list_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetProgramId() {
            return isset($this->sqlData['program_id']) ? $this->sqlData["program_id"] : 0; 
        }

        public function AddStudentToWaitingList($student_id, $school_year_id,
            $program_id, $course_level) {

            $now = date("Y-m-d H:i:s");

            $insert = $this->con->prepare("INSERT INTO waiting_list
                (student_id, school_year_id, program_id, course_level,
                status, date_creation)

                VALUES(:student_id, :school_year_id, :program_id, :course_level,
                :status, :date_creation)");
            
            $insert->bindValue(":student_id", $student_id);
            $insert->bindValue(":school_year_id", $school_year_id);
            $insert->bindValue(":program_id", $program_id);
            $insert->bindValue(":course_level", $course_level);
            $insert->bindValue(":status", "Waiting");
            $insert->bindValue(":date_creation", $now);
            $insert->execute();

            if($insert->rowCount() > 0){
                return true;
            }

            return false;
        }

        public function AddPendingEnrolleeToWaitingList(
            $pending_enrollees_id, $school_year_id,
            $program_id, $course_level) {

            $now = date("Y-m-d H:i:s");

            $insert = $this->con->prepare("INSERT INTO waiting_list
                (pending_enrollees_id, school_year_id, program_id, course_level,
                status, date_creation)

                VALUES(:pending_enrollees_id, :school_year_id, :program_id, :course_level,
                :status, :date_creation)");
            
            $insert->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $insert->bindValue(":school_year_id", $school_year_id);
            $insert->bindValue(":program_id", $program_id);
            $insert->bindValue(":course_level", $course_level);
            $insert->bindValue(":status", "Waiting");
            $insert->bindValue(":date_creation", $now);
            $insert->execute();

            if($insert->rowCount() > 0){
                return true;
            }

            return false;
        }

        public function RegistrarWaitingListUpdate($student_id, $school_year_id) {

            $now = date("Y-m-d H:i:s");

            $update = $this->con->prepare("UPDATE waiting_list

                SET registrar_evaluated=:registrar_evaluated,
                    registrar_evaluated_date=:registrar_evaluated_date

                WHERE student_id=:student_id
                AND school_year_id=:school_year_id
                ");
            
            $update->bindValue(":registrar_evaluated", "yes");
            $update->bindValue(":registrar_evaluated_date", $now);
            $update->bindValue(":student_id", $student_id);
            $update->bindValue(":school_year_id", $school_year_id);
            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }

            return false;
        }

        public function CashierWaitingListUpdate($student_id, $school_year_id) {

            $now = date("Y-m-d H:i:s");

            $update = $this->con->prepare("UPDATE waiting_list

                SET cashier_evaluated=:cashier_evaluated,
                    cashier_evaluated_date=:cashier_evaluated_date

                WHERE student_id=:student_id
                AND school_year_id=:school_year_id
                ");
            
            $update->bindValue(":cashier_evaluated", "yes");
            $update->bindValue(":cashier_evaluated_date", $now);
            $update->bindValue(":student_id", $student_id);
            $update->bindValue(":school_year_id", $school_year_id);
            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }

            return false;
        }
    }
?>