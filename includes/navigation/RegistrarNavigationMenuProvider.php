<?php

    class RegistrarNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create(){

        $base_url = 'http://localhost/school-system-dcbt';
        $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        $dashboard_url = $base_url .  "/registrar_dashboard.php";
        $admission_url = $base_url .  "/registrar_admission.php";
        $students_url = $base_url .  "/registrar_student_list.php";
        $section_url = $base_url .  "/registrar_section_list.php";
        $enrollment_history_url = $base_url .  "/registrar_enrollment_history.php";


        $sideBarNavigationItem = $this->createNavByIcon("Dashboard", 
            "bi bi-clipboard-data icon", $dashboard_url);

        $sideBarNavigationItem .= $this->createNavByIcon("Admission", 
            "bi bi-calendar icon", $admission_url);

        $sideBarNavigationItem .= $this->createNavByIcon("Students", 
            "bi bi-person icon", $students_url);

        $sideBarNavigationItem .= $this->createNavByIcon("Sections", 
            "bi bi-book icon", $section_url);

        $sideBarNavigationItem .= $this->createNavByIcon("Enrollment History", 
            "bi bi-clock-history icon", $enrollment_history_url);

        if(User::isRegistrarLoggedIn()) {
            // echo "qwe";
            // $sideBarNavigationItem .= $this->createNavItem("Settings", "assets/images/icons/settings.png", "settings.php");
            $sideBarNavigationItem .= $this->createNavByIcon("Log Out", 
                "bi bi-box-arrow-right icon", $logout_url);
        }

        return "
            <div class='navigationContainer'>
                $sideBarNavigationItem
            </div>
        ";

    }

    public function createNavItem($text, $icon, $link){
        return "
            <div class='navigationItem'>
                <a href='$link'>
                    <img src='$icon' />
                    <span>$text</span>
                </a>
            </div>
        ";
    }

    public function createNavByIcon($text, $icon, $link){
        return "
            <div class='navigationItem'>
                <a href='$link'>
                    <i style='color: white;' class='$icon'></i>
                    <span>$text</span>
                </a>
            </div>
        ";
    }

}

?>