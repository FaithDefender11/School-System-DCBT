<?php
class Helper {

    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        
        return $inputText;
    }

    public static function GetActiveClass($currentPage, $activePage) {
        // echo $activePage;
        return $currentPage == $activePage ? "active" : null;
    }

    public static function createNavByIcon($text, $icon, $link, $active_class){

        // <span style='display:none;' class='notification_count'>1</span>

        return "
            <div class='$active_class'>
                <a href='$link'>
                    <span class='notification_count'>5</span>
                    <i style='color: white;' class='$icon'></i>
                    <span class='span_text'>$text</span>
                </a>
            </div>
        ";
    }

    public static function createNavItem($text, $icon, $link){
        return "
            <div class='navigationItem'>
                <a href='$link'>
                    <img src='$icon' />
                    <span>$text</span>
                </a>
            </div>
        ";
    }

    public static function GetUrlPath() : string{

        $directoryURI = $_SERVER['REQUEST_URI'];
        $path = parse_url($directoryURI, PHP_URL_PATH);
        $components = explode('/', $path);
        // var_dump($components);
        $page = $components[3];
        return $page;
    }
    
    public static function DocumentTitlePage($page) : string{

        $parts = explode('_', $page);
        $transformedString = null;

        foreach ($parts as $part) {
            $transformedString .= ucfirst($part) . ' ';
        }

        return $transformedString;

    }

    public static function CreateTopDepartmentTab($isTertiary,
        $shs_url = null, $tertiary_url = null){

        $buttonTop = "
            <div id='btn' style='left: 0px'></div>
        ";
        if($isTertiary == true){
            $buttonTop = "
                <div id='btn' style='left: 129px'></div>
            ";
        }

        $shs_department_url = "";
        $tertiary_department_url = "";

        if($shs_url != null){
            $shs_department_url = $shs_url;
        }else{
            $shs_department_url = "shs_index.php";
        }

        $button_default_style = " style='border:none; outline:0;'";
        return "
            <nav>
                <h3>Department</h3>
                <div class='form-box'>
                    <div class='button-box'>
                        $buttonTop
                        <a style='color: white;' href='$shs_department_url'>
                            <button $button_default_style type='button' class='toggle-btn'>
                                SHS
                            </button>
                        </a>
                        <a style='color: white;' href='tertiary_index.php'>
                            <button $button_default_style type='button' class='toggle-btn'>
                                Tertiary
                            </button>
                        </a>
                    </div>
                </div>
            </nav>
        ";
    }

    public static function CreateTwoTabs($first_url,$first_tab_name,
        $second_url, $second_tab_name){



        return "
            <div class='tabs'>
                <a style='background-color: #d6cdcd;' class='tab' href='$first_url'>
                    <button style='background-color: #d6cdcd; font-weight: bold;' id='teachers-list'>
                        <i class='bi bi-clipboard-check icon'></i>
                        $first_tab_name
                    </button>
                </a>
                <a class='tab' href='$second_url'>
                    <button id='teachers-list'>
                        <i class='bi bi-collection icon'></i>
                        $second_tab_name
                    </button>
                </a>
            </div>
        ";
    }
}
?>