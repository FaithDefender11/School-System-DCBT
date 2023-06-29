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
}
?>