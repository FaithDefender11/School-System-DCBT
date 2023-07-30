<?php
class Helper {

   public static $errorArray = array();



    // public static function ValidateFirstname($text) {

    //     $trimmed = trim($text);

    //     if (empty($text)) {
    //         array_push(self::$errorArray, Constants::$firstNameRequired);
    //         return;
    //     } 
    //     else if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
    //         array_push(self::$errorArray, Constants::$invalidFirstNameCharacters);
    //         return;
    //     }
    //     else if (( strlen($trimmed) > 0 && strlen($trimmed) <= 2)) {
    //         array_push(self::$errorArray, Constants::$firstNameIsTooShort);
    //         return;
    //     } 
    //     else if (strlen($trimmed) > 25) {
    //         array_push(self::$errorArray, Constants::$firstNameIsTooLong);
    //         return;
    //     } 

    //     if(empty(self::$errorArray)) {
    //         $output = self::sanitizeFormString($text);
    //         return $output;
    //     }

    // }

    // public static function ValidateLastname($text) {

    //     $trimmed = trim($text);

    //     if (empty($text)) {
    //         array_push(self::$errorArray, Constants::$lastNameRequired);
    //         return;
    //     } 
    //     else if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
    //         array_push(self::$errorArray, Constants::$invalidLastNameCharacters);
    //         return;
    //     }
    //     else if (( strlen($trimmed) > 0 && strlen($trimmed) <= 2)) {
    //         array_push(self::$errorArray, Constants::$lastNameIsTooShort);
    //         return;
    //     } 
    //     else if (strlen($trimmed) > 25) {
    //         array_push(self::$errorArray, Constants::$lastNameIsTooLong);
    //         return;
    //     } 

    //     if(empty(self::$errorArray)) {
    //         $output = self::sanitizeFormString($text);
    //         return $output;
    //     }

    // }

    public static function ValidateLastname($text) {

        return self::FormNameValidation($text,
            Constants::$lastNameRequired,
            Constants::$invalidLastNameCharacters,
            Constants::$lastNameIsTooShort,
            Constants::$lastNameIsTooLong);
    }

    public static function ValidateMotherLastname($text) {

        return self::FormNameValidation($text,
            Constants::$motherLastNameRequired,
            Constants::$invalidMotherLastNameCharacters,
            Constants::$motherLastNameIsTooShort,
            Constants::$motherLastNameIsTooLong);
    }

    public static function ValidateMotherFirstname($text) {

        return self::FormNameValidation($text,
            Constants::$motherFirstNameRequired,
            Constants::$invalidMotherFirstNameCharacters,
            Constants::$motherFirstNameIsTooShort,
            Constants::$motherFirstNameIsTooLong);
    }

    public static function ValidateMotherMiddlename($text) {
        return self::FormNameValidation($text,
            Constants::$motherMiddleNameRequired,
            Constants::$invalidMotherMiddleNameCharacters,
            Constants::$motherMiddleNameIsTooShort,
            Constants::$motherMiddleNameIsTooLong);
    }

    

    public static function ValidateFirstname($text) {

        return self::FormNameValidation($text,
            Constants::$firstNameRequired,
            Constants::$invalidFirstNameCharacters,
            Constants::$firstNameIsTooShort,
            Constants::$firstNameIsTooLong);
        
    }

    public static function ValidateMiddlename($text) {
        return self::FormNameValidation($text,
            Constants::$middleNameRequired,
            Constants::$invalidMiddleNameCharacters,
            Constants::$middleNameIsTooShort,
            Constants::$middleNameIsTooLong);
        
    }

    public static function ValidateBirthPlace($text) {
        return self::FormNameValidation($text,
            Constants::$birthPlaceRequired,
            Constants::$invalidBirthPlaceCharacters,
            Constants::$birthPlaceIsTooShort,
            Constants::$birthPlaceIsTooLong);
    }

    public static function ValidateAddressv2($text) {
        return self::FormNameValidation($text,
            Constants::$addressRequired,
            Constants::$invalidAddressCharacters,
            Constants::$addressIsTooShort,
            Constants::$addressIsTooLong);
    }

    public static function ValidateOccupation($text) {

        $trimmed = trim($text);

        if (empty($text)) {
            array_push(self::$errorArray, Constants::$fatherOccupationRequired);
            return;
        } 

        if (!preg_match("/^[a-zA-Z ]+$/", $trimmed)) {
            array_push(self::$errorArray, Constants::$invalidFatherOccupationCharacters);
            return;
        }

        if(empty(self::$errorArray)) {

            echo $trimmed;
            $output = self::sanitizeFormString($trimmed);
            return $output;
        }

    }


    public static function ValidateAddress($text) {

        $trimmed = trim($text);
 

        if (empty($text)) {
            array_push(self::$errorArray, Constants::$addressRequired);
            return;
        } 
        else if (!preg_match("/^[a-zA-Z0-9 ]+$/", $text)) {
            array_push(self::$errorArray, Constants::$invalidAddressCharacters);
            return;
        }
        else if (( strlen($trimmed) > 0 && strlen($trimmed) <= 2)) {
            array_push(self::$errorArray, Constants::$addressIsTooShort);
            return;
        } 
        else if (strlen($trimmed) > 25) {
            array_push(self::$errorArray, Constants::$addressIsTooLong);
            return;
        } 

        if(empty(self::$errorArray) && preg_match("/^[a-zA-Z0-9 ]+$/", $trimmed)) {
            $output = self::sanitizeFormString($trimmed);
            return $output;
        }

    }

    public static function ValidateContactNumber($text) {

        $trimmed = trim($text);

        if (empty($text)) {
            array_push(self::$errorArray, Constants::$ContactNumberRequired);
            return;
        } 
        // "123!";, "Hello 1234" Not valid
        // "456"; Valid
        // Regular expression pattern to check for valid string

        $integerOfStringPattern = '/^[0-9]+$/';

        // Check if the input string is not exactly 11 characters long or does not contain only integers
        if (!(strlen($trimmed) === 11 || preg_match($integerOfStringPattern, $trimmed) === 1)) {
            array_push(self::$errorArray, Constants::$invalidContactNumberCharacters);
            return;
        }

        if (!(strlen($trimmed) === 11)){
            array_push(self::$errorArray, Constants::$invalidContactNumber2Characters);
            return;
        }

        //  09123456789"; // Valid phone number
        // "12345678901"; // Invalid phone number (doesn't start with '0')
        $numberWith11DigitPattern = '/^0[0-9]{10}$/';

        if (preg_match($numberWith11DigitPattern, $trimmed) === 1
            && preg_match($numberWith11DigitPattern, $trimmed) === 1) {

            return $trimmed;
            // return self::sanitizeContactNumber($trimmed);
        }else{
            array_push(self::$errorArray, Constants::$invalidContactNumberCharacters);
            return;
        }
    }

    public static function ValidateEmail($userEmail, $optional = false) {

        // If optional the email verfication should working
        // Only the required email will be disabled.

        if($optional === false){

            if (empty($userEmail)) {
                array_push(self::$errorArray, Constants::$EmailRequired);
                return;
            }  

            if (!empty($userEmail) 
                    && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {

                // Email is in a valid format
                $domain = substr(strrchr($userEmail, "@"), 1);
                if (strtolower($domain) === 'gmail.com') {
                    // Email has the correct domain (Gmail)
                    $userEmail = strtolower($userEmail); // Convert the email to lowercase
                    return trim($userEmail);
                } else {
                    array_push(self::$errorArray, Constants::$invalidEmailCharacters);
                    return;
                    // Uncomment the lines below if you want to handle invalid domain separately
                    // array_push(self::$errorArray, Constants::$invalidEmailDomain);
                    // return;
                }
            } else {

                // Email is not in a valid format
                array_push(self::$errorArray, Constants::$invalidEmailCharacters);
                return;
                // array_push(self::$errorArray, Constants::$invalidEmailFormat);
                // return;
            }

        }
            
        if($optional === true && !empty($userEmail)){

            if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                // Email is in a valid format
                $domain = substr(strrchr($userEmail, "@"), 1);
                if (strtolower($domain) === 'gmail.com') {
                    // Email has the correct domain (Gmail)
                    $userEmail = strtolower($userEmail); // Convert the email to lowercase
                    return trim($userEmail);
                } else {
                    array_push(self::$errorArray, Constants::$invalidEmailCharacters);
                    return;
                    // Uncomment the lines below if you want to handle invalid domain separately
                    // array_push(self::$errorArray, Constants::$invalidEmailDomain);
                    // return;
                }
            } else {

                // Email is not in a valid format
                array_push(self::$errorArray, Constants::$invalidEmailCharacters);
                return;
                // array_push(self::$errorArray, Constants::$invalidEmailFormat);
                // return;
            }

        }
        


    }

    public static function sanitizeContactNumber($phoneNumber) {

        $phoneNumber = self::removeControlCharacters($phoneNumber);
        // Remove null bytes
        $phoneNumber = self::removeNullBytes($phoneNumber);

        // Remove common HTML entities from input
        $phoneNumber = self::removeHtmlEntities($phoneNumber);

        // $phoneNumber = htmlspecialchars($phoneNumber);
        $phoneNumber = strip_tags($phoneNumber);


    }
    public static function sanitizeContactNumberv2($input) {

        // Remove any non-numeric characters except for '+'
        $sanitized_number = preg_replace('/[^0-9+]/', '', $input);

        // Check if the number starts with a '+'
        if (substr($sanitized_number, 0, 1) === '+') {
            // If it starts with '+', keep only the first '+'
            $sanitized_number = '+' . ltrim(substr($sanitized_number, 1), '+');
        } else {
            // If it does not start with '+', ensure it starts with '63' (Philippines country code)
            $sanitized_number = '63' . ltrim($sanitized_number, '0');
        }

        // Ensure that the number starts with '09' (Philippines mobile prefix)
        if (substr($sanitized_number, 0, 2) !== '09') {
            $sanitized_number = '09' . substr($sanitized_number, 2);
        }

        // Limit the number to 11 digits (Philippines mobile numbers)
        $sanitized_number = substr($sanitized_number, 0, 11);

        return $sanitized_number;
    }

    public static function ValidateSuffix($suffix) {

        $trimmed = trim($suffix);

        $trimmed = strtoupper($trimmed);
        
        $validSuffixes = array("JR", "SR", "II", "III", "IV");

        if (!empty($trimmed) 
            && in_array($trimmed, $validSuffixes)
            ) {
            return $trimmed;
        }
        else if(empty($trimmed)){
            return "N/A";
        }
        else if (!empty($trimmed) 
            && !in_array($trimmed, $validSuffixes)
            ) {
            array_push(self::$errorArray, Constants::$invalidSuffixNameCharacters);
            return $trimmed;
        }
    }

    public static function ValidateCivilStatus($suffix) {

        $trimmed = trim($suffix);

        $trimmed = strtoupper($trimmed);
        
        $validCivilStatus = array("SINGLE", "MARRIED");

        // Valid
        if (!empty($trimmed) 
            && in_array($trimmed, $validCivilStatus)
            ) {

            $trimmed = ucwords(strtolower($trimmed));
            return $trimmed;
        }
        // Rest are Invalid
        else if(empty($trimmed)){

            array_push(self::$errorArray, Constants::$civilStatusRequired);
            return;
        }
        else if ((!empty($trimmed) 
            && !in_array($trimmed, $validCivilStatus))
            || !preg_match("/^[a-zA-Z ]+$/", $trimmed)

            ) {
            array_push(self::$errorArray, Constants::$invalidCivilStatusCharacters);
            return $trimmed;
        }
    }

    public static function ValidateGender($suffix) {

        $trimmed = trim($suffix);

        $trimmed = strtoupper($trimmed);
        
        $validGender = array("MALE", "FEMALE");

        // Valid
        if (!empty($trimmed) 
            && in_array($trimmed, $validGender)
            ) {

            $trimmed = ucwords(strtolower($trimmed));
            return $trimmed;
        }
        // Rest are Invalid
        else if(empty($trimmed)){

            array_push(self::$errorArray, Constants::$genderRequired);
            return;
        }
        else if ((!empty($trimmed) 
            && !in_array($trimmed, $validGender))
            || !preg_match("/^[a-zA-Z ]+$/", $trimmed)

            ) {
            array_push(self::$errorArray, Constants::$invalidGenderCharacters);
            return $trimmed;
        }
    }

    public static function ValidateNationality($suffix) {

        $trimmed = trim($suffix);

        $trimmed = strtoupper($trimmed);

        $validNationality = array(
            "JAPANESE",
            "TAIWANESE",
            "FILIPINO"
        );

        // Valid
        if (!empty($trimmed) 
            && in_array($trimmed, $validNationality)
            ) {
            $trimmed = ucwords(strtolower($trimmed));

            return $trimmed;
        }
        // Rest are Invalid
        else if(empty($trimmed)){
            array_push(self::$errorArray, Constants::$nationalityRequired);
            return;
        }
        else if ((!empty($trimmed) 
            && !in_array($trimmed, $validNationality))
            || !preg_match("/^[a-zA-Z ]+$/", $trimmed)

            ) {
            array_push(self::$errorArray, Constants::$invalidNationalityCharacters);
            return $trimmed;
        }
    }

    public static function ValidateReligion($suffix) {

        $trimmed = trim($suffix);

        $trimmed = strtoupper($trimmed);

        $religionList = array(
            "CATHOLIC",
            "CHRISTIAN",
            "OTHER"
        );

        // Valid
        if (!empty($trimmed) 
            && in_array($trimmed, $religionList)
            ) {
            $trimmed = ucwords(strtolower($trimmed));

            return $trimmed;
        }
        // Rest are Invalid
        else if(empty($trimmed)){
            array_push(self::$errorArray, Constants::$religionRequired);
            return;
        }
        else if ((!empty($trimmed) 
            && !in_array($trimmed, $religionList))
            || !preg_match("/^[a-zA-Z ]+$/", $trimmed)

            ) {
            array_push(self::$errorArray, Constants::$invalidReligionCharacters);
            return $trimmed;
        }
    }

    // public static function FormSuffixValidation($suffix) {

    //     $trimmedSuffix = trim($suffix);
    //     // echo $trimmedSuffix;

    //     $validSuffixes = array("JR", "SR", "II", "III", "IV");

    //     $trimmedSuffix = strtoupper(trim($trimmedSuffix));

    //     if (in_array($trimmedSuffix, $validSuffixes)) {
    //         // echo "on";
    //         return $trimmedSuffix;
    //     } else {
    //         // No Suffix
    //         return "N/A";
    //     }
    // }

 
    public static function FormNameValidation($text, $required,
        $invalidChar, $textShort, $textLong) {
        $trimmed = trim($text);

        // if (empty($text)) {
        //     array_push(self::$errorArray, Constants::$lastNameRequired);
        //     return;
        // } 
        // else if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
        //     array_push(self::$errorArray, Constants::$invalidLastNameCharacters);
        //     return;
        // }
        // else if (( strlen($trimmed) > 0 && strlen($trimmed) <= 2)) {
        //     array_push(self::$errorArray, Constants::$lastNameIsTooShort);
        //     return;
        // } 
        // else if (strlen($trimmed) > 25) {
        //     array_push(self::$errorArray, Constants::$lastNameIsTooLong);
        //     return;
        // } 

        //   var_dump(self::$errorArray);

        if (empty($text)) {
            array_push(self::$errorArray, $required);
            // echo $text;
            return;
        } 
        // John doe -> John Doe
        // Exclamation Marks and others are not valid here. (!@#$%^&*()<>)
        else if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
            array_push(self::$errorArray, $invalidChar);
            // echo $text;
            return;
        }
        else if ((strlen($trimmed) > 0 && strlen($trimmed) <= 1)) {
            array_push(self::$errorArray, $textShort);
            // echo $text;
            return;
        } 
        else if (strlen($trimmed) > 25) {
            array_push(self::$errorArray, $textLong);
            // echo $text;
            return;
        } 

        // print_r(self::$errorArray);

        if(empty(self::$errorArray)) {

            $output = self::sanitizeFormString($text);
            return $output;
        }

    }

    public static function getInputValue($name) {
        if(isset($_POST[$name])) {
            echo $_POST[$name];
        }
    }

    public static function EchoErrorField($nameRequired, $invalidChars,
        $textShort, $textLong){

        echo Helper::getError($nameRequired);
        echo Helper::getError($invalidChars);
        echo Helper::getError($textShort);
        echo Helper::getError($textLong);
    }

    public static function validateField($text, $fieldName,
        $required = true, $minLength = 0, $maxLength = 25) {

        $trimmed = trim($text);


        if ($required && empty($text)) {
            array_push(self::$errorArray, "$fieldName is required.");
            return null;
        }

        if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
            array_push(self::$errorArray, "Invalid characters in $fieldName.");
            return null;
        }

        $length = strlen($trimmed);

        if ($length > 0 && $length <= $minLength) {
            array_push(self::$errorArray, "$fieldName is too short.");
            return null;
        }

        if ($length > $maxLength) {
            array_push(self::$errorArray, "$fieldName is too long.");
            return null;
        }

        return $trimmed;
    }


    public static function sanitizeFormString($inputText) {
    
        $inputText = self::removeControlCharacters($inputText);
        // Remove null bytes
        $inputText = self::removeNullBytes($inputText);

        // Remove common HTML entities from input
        $inputText = self::removeHtmlEntities($inputText);

        // $inputText = htmlspecialchars($inputText);
        $inputText = strip_tags($inputText);
        // $inputText = str_replace(" ", "", $inputText);
        $inputText = trim($inputText);

        $inputText = strtolower($inputText);
        // $inputText = ucfirst($inputText);
        $inputText = ucwords($inputText); // Capitalize the first letter of each word

        
        return $inputText;
    }
 

    // public static function validateFirstNamxe($firstname) {

    //     $trimmedFirstname = trim($firstname);

    //     // Check for empty input
    //     if (empty($trimmedFirstname)) {
    //         array_push(self::$errorArray, Constants::$fieldRequired);
    //     } 
    //     // Length validation (example: between 2 and 50 characters)
    //     elseif (strlen($trimmedFirstname) < 2 || strlen($trimmedFirstname) > 50) {
    //         array_push(self::$errorArray, Constants::$invalidLength);
    //     } 
    //     // Format consistency (example: capitalize the first letter)
    //     else {
    //         $formattedFirstname = ucfirst($trimmedFirstname);
    //         // Check if the formatted name is different from the original to catch inconsistent formats
    //         if ($formattedFirstname !== $trimmedFirstname) {
    //             array_push(self::$errorArray, Constants::$formatError);
    //         }
    //     }
    // }

    public static function DisplayText($post_name, $db_text){
        $output = "";
        
        if(count(self::$errorArray) > 0){
            $output = self::getInputValue($post_name);
            
        }else{
            $output = $db_text;
        }
        return $output;

    }

    public static function getError($error) {
        
        if(in_array($error, self::$errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }

    public static function getErrorWithTimeout($error) {

        if (in_array($error, self::$errorArray)) {

            $errorMessageId = 'error_' . uniqid(); // Generate a unique ID for the error message element
            echo "<span id='$errorMessageId' class='errorMessage'>$error</span>";

            echo "<script>
                setTimeout(function() {
                    var errorMessage = document.getElementById('$errorMessageId');
                    if (errorMessage) {
                        errorMessage.style.display = 'none';
                    }
                }, 1500); // 5000 milliseconds (5 seconds)
            </script>";
        }
    }


    // Helper function to remove control characters and newlines
    private static function removeControlCharacters($input) {
        return preg_replace('/[\x00-\x1F\x7F]/u', '', $input);
    }

    // Helper function to remove null bytes
    private static function removeNullBytes($input) {
        return str_replace("\0", '', $input);
    }
    // Helper function to remove common HTML entities
    private static function removeHtmlEntities($input) {
        // Customize this list based on your requirements
        $html_entities = [
            '&amp;'  => '&',
            '&lt;'   => '<',
            '&gt;'   => '>',
            '&#039;' => "'",
            '&#x27;' => "'",
            '&quot;' => '"',
            '&#x22;' => '"',
        ];
        return str_replace(array_keys($html_entities), array_values($html_entities), $input);
    }

    public static function sanitizeString($input, $encoding = 'UTF-8') {

        $sanitized_input = htmlspecialchars($input, ENT_QUOTES, $encoding);

        // Strip HTML tags
        $sanitized_input = strip_tags($sanitized_input);

        // Sanitize using filter_var with FILTER_SANITIZE_STRING
        $sanitized_input = filter_var($sanitized_input, FILTER_SANITIZE_STRING);

        // Remove control characters and newlines
        $sanitized_input = self::removeControlCharacters($sanitized_input);

        // Remove null bytes
        $sanitized_input = self::removeNullBytes($sanitized_input);

        // Remove common HTML entities from input
        $sanitized_input = self::removeHtmlEntities($sanitized_input);

        return $sanitized_input;
    }


    public static function sanitizeEmail($email) {
        
        // Basic sanitization using filter_var
        $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Check if the email has the correct domain (gmail.com or its subdomains)
        $domain = substr(strrchr($sanitized_email, "@"), 1);
        $allowed_domains = ['gmail.com'];
        if (!in_array(strtolower($domain), $allowed_domains)) {
            // Invalid domain, return false
            return false;
        }

        // Convert the email to lowercase
        $sanitized_email = strtolower($sanitized_email);

        return $sanitized_email;
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

    public static function RevealStudentTypePending($type, $doesGraduate = null){

        $output = "";

        $text = $doesGraduate == true ? "<span style='font-weight: bold;' class='text-primary'>Graduate</span>" : "";

        if($type == 'SHS' || $type == 'Senior High School'){
            $output = "Senior High School";
        }
        else if($type == 'Tertiary'){
            $output = "Tertiary";
        }

        return "
            <span class='text-muted' style='font-size: 15px;'>
                <em>$output &nbsp &nbsp $text</em> 
            </span>
        ";
    }

    public static function RevealStudentTypeStudent($is_tertiary){

        $output = "";

        if($is_tertiary == 0){
            $output = "Senior High School";
        }
        else if($is_tertiary == 1){
            $output = "Tertiary";
        }

        return "
            <span class='text-muted' style='font-size: 15px;'>
               <em>$output</em> 
            </span>
        ";
    }

    public static function CreateStudentTabs($student_unique_id,
        $student_level, $type, $section_acronym, $payment_status, $enrollment_date){

        $formattedDate = "";

        $date = new DateTime($enrollment_date);
        $formattedDate = $date->format('m/d/Y');
        // echo $formattedDate;

        $type == 'Tertiary' ? 'Course' : ($type == 'Senior High School' ? 'Strand' : '');

        return "
            <div class='cards'>
                <div class='card'>
                    <p class='text-center mb-0'>Student No.</p>
                    <p class='text-center'>$student_unique_id</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Level</p>
                    <p class='text-center'>$student_level</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>$type</p>
                    <p class='text-center'>$section_acronym</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Status</p>
                    <p class='text-center'>$payment_status</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Added on</p>
                    <p class='text-center'>
                        $formattedDate
                    </p>
                </div>
            </div>
        ";
    }
    public static function SectionHeaderCards($section_id,
        $school_year_term, $school_year_period,
        $acronym, $level, $totalStudents){
       
        return "
            <div class='cards'>
                <div class='card'>
                    <p class='text-center mb-0'>Section ID</p>
                    <p class='text-center'>$section_id</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>School Year</p>
                    <p class='text-center'>$school_year_term</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Semester</p>
                    <p class='text-center'>$school_year_period</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Strand</p>
                    <p class='text-center'>$acronym</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Level</p>
                    <p class='text-center'>$level</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Students</p>
                    <p class='text-center'>
                        $totalStudents
                    </p>
                </div>
            </div>
        ";
    }

    public static function ProcessPendingCards($enrollment_form_id,
        $date_creation, $admission_status){

        $date = new DateTime($date_creation);
        $formattedDate = $date->format('m/d/Y H:i');

        $admission_status = $admission_status == "Standard" ? "New" : ($admission_status == "Transferee" ? "Transferee" : "");

        return "
            <div class='cards'>
                <div class='card'>
                    <p class='text-center mb-0'>Form ID</p>
                    <p class='text-center'>$enrollment_form_id</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Admission type</p>
                    <p class='text-center'>$admission_status</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Student no.</p>
                    <p class='text-center'>N/A</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Status</p>
                    <p class='text-center'>Evaluation</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Submitted on</p>
                    <p class='text-center'>
                        $formattedDate
                    </p>
                </div>
            </div>
        ";
    }

    public static function ProcessStudentCards($student_id, $enrollment_form_id, $student_unique_id,
        $date_creation, $new_enrollee,
        $enrollment_is_new_enrollee, $enrollment_is_transferee,
            $student_enrollment_student_status){

        // $student = new Student($con);

        $link = "../student/record_details.php?id=$student_id&grade_records=show";


        $date = new DateTime($date_creation);
        $formattedDate = $date->format('m/d/Y');

        // $admission_status = $new_enrollee == 1 ? "New" : ($new_enrollee == 0 ? "Old" : "");

        $updated_type = "";

        // echo $new_enrollee;
        // echo $enrollment_is_new_enrollee;
        // echo $enrollment_is_transferee;
        
        if($new_enrollee == 1 
            && $enrollment_is_new_enrollee == 1 
            && $enrollment_is_transferee == 1
 
            ){

            $updated_type = "New Transferee";

        }
        else if($new_enrollee == 1 
            && $enrollment_is_new_enrollee == 1 
            && $enrollment_is_transferee == 0){

            $updated_type = "New";

        }
        else if($new_enrollee == 0 
            && $enrollment_is_new_enrollee == 0 
            && $enrollment_is_transferee == 0
            && $student_enrollment_student_status == "Irregular"
            ){

            $updated_type = "Old Irregular";

        }
        else if($new_enrollee == 0 
            && $enrollment_is_new_enrollee == 0 
            && $enrollment_is_transferee == 0
            && $student_enrollment_student_status == "Regular"
            ){

            $updated_type = "Old Regular";

        }
        else{
            $updated_type = "SD";
        }


        return "
            <div class='cards'>
                <div class='card'>
                    <p class='text-center mb-0'>Form ID</p>
                    <p class='text-center'>$enrollment_form_id</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Admission type</p>
                    <p class='text-center'>$updated_type</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Student no.</p>
                    <a style='color: #333' href='$link'class='text-center'>$student_unique_id</a>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Status</p>
                    <p class='text-center'>Evaluation</p>
                </div>
                <div class='card'>
                    <p class='text-center mb-0'>Submitted on</p>
                    <p class='text-center'>
                        $formattedDate
                    </p>
                </div>
            </div>
        ";
    }

    public static function PendingEnrollmentDetailsTop($steps = null,
        $pending_enrollees_id = null) {

            $result = "";

            $extra = "";

            if($steps == "step1"){

                $extra .= "
                    <a href='pending_enrollee_edit.php?id=$pending_enrollees_id' class='dropdown-item text-primary''>
                            <i class='bi bi-file-earmark-x'></i>
                        Edit
                    </a>
                ";
            }

            $additional = "
                <a href='form_alignment.php?id=' class='text-primary dropdown-item'>
                <i class='bi bi-pencil'></i>Edit form
                </a>
            ";

            $reject = "rejectForm($pending_enrollees_id)";

            $headerHtml = "
                <header>
                    <div class='title'>
                        <h1>Enrollment form</h1>
                    </div>
                    <div class='action'>
                        <div class='dropdown'>
                            <button class='icon'>
                                <i class='bi bi-three-dots-vertical'></i>
                            </button>
                            <div class='dropdown-menu'>
                                <a onclick='$reject' href='#' class='dropdown-item' style='color: yellow'>
                                    <i class='bi bi-file-earmark-x'></i>
                                    Reject form
                                </a>
                                $extra
                            </div>
                        </div>
                    </div>
                </header>
            ";

        $result .= $headerHtml;

        return $result;
    }


    public static function RemoveSidebar(){

        $output = '
            <head>
                <style>
                    .sidebar-nav {
                        display: none;
                    }
                    .mainSectionContainer {
                        padding: 0;
                    }
                </style>
            </head>
        ';
        return $output;

    }   
    //  OR USE &$content
    public static function renderCard($title, $content)
    {
     

        // $content = $content ?? "-";

        echo "
            <div class='card'>
                <p>$title</p>
                <p>$content</p>
            </div>
        ";
    }

    public static function renderGradeRecordHeader($enrollment_school_year, $default_text)
    {
        if ($enrollment_school_year !== null) {
            $term = $enrollment_school_year['term'];
            // $period = $enrollment_school_year['period'];
            // $school_year_id = $enrollment_school_year['school_year_id'];
            // $enrollment_course_id = $enrollment_school_year['course_id'];
            // $enrollment_form_id = $enrollment_school_year['enrollment_form_id'];

            echo "
                <header>
                    <div class='title'>
                        <h4 class='text-info'>
                            SY $term
                        </h4>
                    </div>
                </header>
            ";
        } else {
            echo "
                <header>
                    <div class='col-md-12' class='title'>
                        <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'></p>
                        <h4 class='text-muted'>
                            $default_text
                        </h4>
                    </div>
                </header>
            ";
        }
    }
}
?>