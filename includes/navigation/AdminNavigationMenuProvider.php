<?php

    class AdminNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){


        $base_url = '/school-system-dcbt/admin';

        $dashboard_url = $base_url .  "/dashboard/index.php";
        $school_year_url = $base_url .  "/school_year/index.php";
        $teacher_url = $base_url .  "/teacher/index.php";
        $course_url = $base_url .  "/course/index.php";
        $subject_url = $base_url .  "/subject/index.php";
        $section_url = $base_url .  "/section/index.php";
        $schedule_url = $base_url .  "/schedule/index.php";
        $account_url = $base_url .  "/account/index.php";
        $logout_url = '/school-system-dcbt/logout.php';

        $class = "navigationItem ";


        $active_nav_dashboard = $page == "dashboard" ? "active" : "";
        $active_nav_sy = $page == "school_year" ? "active" : "";

        // $sideBarNavigationItem = $this->createNavByIcon("Dashboard", 
        //     "bi bi-clipboard-data icon ", $dashboard_url, $class . $active_nav_dashboard);
        
        // $sideBarNavigationItem .= $this->createNavByIcon("School Year", 
        //     "bi bi-calendar icon", $school_year_url, $class . $active_nav_sy);

        $sideBarNavigationItem = $this->createNavByIcon("Dashboard", 
            "bi bi-clipboard-data icon ", $dashboard_url, $class . $this->GetActiveClass($page, "dashboard"));
        
        $sideBarNavigationItem .= $this->createNavByIcon("School Year", 
            "bi bi-calendar icon ", $school_year_url, $class . $this->GetActiveClass($page, "school_year"));

        $sideBarNavigationItem .= $this->createNavByIcon("Teacher", 
            "bi bi-person icon ", $teacher_url, $class . $this->GetActiveClass($page, "teacher"));

        $sideBarNavigationItem .= $this->createNavByIcon("Courses", 
            "bi bi-book icon ", $course_url, $class . $this->GetActiveClass($page, "course"));

        $sideBarNavigationItem .= $this->createNavByIcon("Subject", 
            "bi bi-file icon ", $subject_url, $class . $this->GetActiveClass($page, "subject"));

        $sideBarNavigationItem .= $this->createNavByIcon("Section", 
            "bi bi-person-plus-fill icon", $section_url, $class . $this->GetActiveClass($page, "section"));

        $sideBarNavigationItem .= $this->createNavByIcon("Schedule", 
            "bi bi-clock icon", $schedule_url, $class . $this->GetActiveClass($page, "schedule"));

        $sideBarNavigationItem .= $this->createNavByIcon("Account", 
            "bi bi-person-circle", $account_url, $class . $this->GetActiveClass($page, "account"));
        

        // $sideBarNavigationItem .= $this->createNavByIcon("Teacher", 
        //     "bi bi-person icon", $teacher_url, $class);

        // $sideBarNavigationItem .= $this->createNavByIcon("Courses", 
        //     "bi bi-book icon", $course_url, $class);

        // $sideBarNavigationItem .= $this->createNavByIcon("Subject", 
        //     "bi bi-file icon", $subject_url, $class);

        // $sideBarNavigationItem .= $this->createNavByIcon("Section", 
        //     "bi bi-person-plus-fill icon", $section_url, $class);


        // $sideBarNavigationItem .= $this->createNavByIcon("Schedule", 
        //     "bi bi-clock icon", $schedule_url, $class);

        // $sideBarNavigationItem .= $this->createNavByIcon("Account", 
        //     "bi bi-person-circle icon", $account_url, $class);

        if(User::isAdminLoggedIn()) {
            $sideBarNavigationItem .= $this->createNavByIcon("Log Out", 
                "bi bi-box-arrow-right icon", $logout_url, $class);
        }

        return "
            <div class='navigationContainer'>
                $sideBarNavigationItem
            </div>
        ";
    }

    private function GetActiveClass($currentPage, $activePage) : string {
        return $currentPage == $activePage ? "active" : "";
    }

    public function createNavByIcon($text, $icon, $link, $active_class){

        return "
            <div class='$active_class'>
                <a href='$link'>
                    <i style='color: white;' class='$icon'></i>
                    <span>$text</span>
                </a>
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



}
