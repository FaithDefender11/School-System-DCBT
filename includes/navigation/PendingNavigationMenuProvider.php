<?php 

    class PendingStudentNavigationMenu{
        
        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create($page){
      
            // HARD-CODED
            // $base_url = 'http://localhost/school-system-dcbt/student/';

            $base_url = "";

            // $logout_url = 'http://localhost/school-system-dcbt/logout.php';

            if ($_SERVER['SERVER_NAME'] === 'localhost') {
                // Running on localhost
                // $base_url = 'http://localhost/school-system-dcbt/registrar/';
                $base_url = 'http://localhost/school-system-dcbt/student/';

            } else {
                // Running on web hosting
                $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';
            }

            $logout_url = 'http://localhost/school-system-dcbt/enrollee_logout.php';
            // $logout_url = "http://localhost/school-system-dcbt/logout.php";

            if ($_SERVER['SERVER_NAME'] !== 'localhost') {
                $new_url = str_replace("/student/", "", $base_url);
                $logout_url = "$new_url/enrollee_logout.php";

            }

            // Set the dynamic part of the URL using a global variable
            $student_profile = $base_url . 'tentative/process.php?new_student=true&step=1';

            $sql = $this->con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
                AND is_finished=:is_finished
                ");

            $sql->bindValue(":pending_enrollees_id", $this->userLoggedInObj);
            $sql->bindValue(":is_finished", 1);
            $sql->execute();

            if($sql->rowCount() > 0){
                $student_profile = $base_url . 'tentative/profile.php?fill_up_state=finished';
            }

            $result = "";

            if(User::IsStudentPendingAuthenticated()){
               
                $result .= Helper::createNavByIconARC("Registration", 
                    "bi bi-clipboard-data icon", $student_profile, Constants::$navigationClass . Helper::GetActiveClass($page, "registration"));

                $result .= Helper::createNavByIconARC("Log Out", 
                    "bi bi-box-arrow-right icon", $logout_url, Constants::$navigationClass);
            }

            return "
                <div class='navigationContainer'>
                    $result
                </div>
            ";
        }
        
    }

?>