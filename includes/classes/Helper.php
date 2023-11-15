<?php
class Helper {

    public static $errorArray = array();

    public static function ValidateDepartment($department) {

        $trimmed = trim($department);


        $trimmed = strtoupper($trimmed);
        
        $validDepartment = array("SENIOR HIGH SCHOOL", "TERTIARY");

        // Valid
        if (!empty($trimmed) 
            && in_array($trimmed, $validDepartment)
            ) {

            $trimmed = ucwords(strtolower($trimmed));

            // echo $trimmed;

            return $trimmed;
        }
        // Rest are Invalid
        else if(empty($trimmed)){

            array_push(self::$errorArray, Constants::$requiredDepartment);
            return;
        }
        else if ((!empty($trimmed) 
            && !in_array($trimmed, $validDepartment))
            || !preg_match("/^[a-zA-Z ]+$/", $trimmed)) 
            {

            // /: Delimiter to mark the start and end of the regular expression pattern.
            // ^: Asserts the start of the string.
            // [a-zA-Z ]+: Matches one or more occurrences of alphabetic characters (both lowercase and uppercase) and spaces.
            // $: Asserts the end of the string.

            //  echo $trimmed;

            array_push(self::$errorArray, Constants::$invalidDepartment);
            return $trimmed;
        }

        // echo $trimmed;
    }

    public static function ValidateAdmissionType($text) {

        $trimmed = trim($text);

        $trimmed = strtoupper($trimmed);
        
        $validAdmissionType = array("NEW", "TRANSFEREE");

        // Valid
        if (!empty($trimmed) 
            && in_array($trimmed, $validAdmissionType)
            ) {

            $trimmed = ucwords(strtolower($trimmed));

            return $trimmed;
        }
        // Rest are Invalid
        else if(empty($trimmed)){

            array_push(self::$errorArray, Constants::$requiredAdmissionType);
            return;
        }
        else if ((!empty($trimmed) 
            && !in_array($trimmed, $validAdmissionType))
            || !preg_match("/^[a-zA-Z ]+$/", $trimmed)) 
            {

            // /: Delimiter to mark the start and end of the regular expression pattern.
            // ^: Asserts the start of the string.
            // [a-zA-Z ]+: Matches one or more occurrences of alphabetic characters (both lowercase and uppercase) and spaces.
            // $: Asserts the end of the string.

            //  echo $trimmed;

            array_push(self::$errorArray, Constants::$invalidAdmissionType);
            return $trimmed;
        }

        // echo $trimmed;
    }

    public static function ValidateCourseLevel($level) {
        
        $validCourseLevel = array(
            // Tertiary
            1, 2, 3, 4,
            // SHS
            11, 12
        );

        // $level = "5";
        $level = intval($level);

        if(empty($level)){
            echo "empty $level";
            array_push(self::$errorArray, Constants::$requiredGradeLevel);
            return;
        }
        if (!is_int($level)) {
            // echo $level;
            echo "is not int";
            array_push(self::$errorArray, Constants::$invalidGradeLevel);
            return;
        }
        // Valid

        if (!empty($level) 
            && is_int($level)
            && in_array($level, $validCourseLevel)
            ) {
            // echo "in array";

            // $level = ucwords(strtolower($level));
            return $level;
        }
        
        if ((!empty($level) 
            && !in_array($level, $validCourseLevel))) 
            {
                // echo "not in array";
            array_push(self::$errorArray, Constants::$invalidGradeLevel);
            return $level;
        }

        // echo $trimmed;
    }

    public static function ValidateLastName($text) {

        $trimmed = trim($text);

        // $pattern = '/^[A-Za-z]+$/';

        // echo $trimmed;
        // echo "<br>";

        if (empty($text)) {
            array_push(self::$errorArray, Constants::$lastNameRequired);
            return;
        } 
        // else if (!preg_match($pattern, $trimmed)) {
        //     array_push(self::$errorArray, Constants::$invalidLastNameCharacters);
        //     return;
        // }
        // John doe -> John Doe
        // Exclamation Marks and others are not valid here. (!@#$%^&*()<>)
        else if (!preg_match("/^[a-zA-Z\s]+$/", $trimmed)) {
            array_push(self::$errorArray, Constants::$invalidLastNameCharacters);
            return;
        }
        else if ((strlen($trimmed) > 0 && strlen($trimmed) <= 1)) {
            array_push(self::$errorArray, Constants::$lastNameIsTooShort);
            return;
        } 
        else if (strlen($trimmed) > 25) {
            array_push(self::$errorArray, Constants::$lastNameIsTooLong);
            return;
        } 

        if(empty(self::$errorArray)) {

            $output = self::sanitizeFormString($text);
            return $output;
        }
    }

    public static function validateSurnameTest($trimmed) {
        if (!preg_match("/^[a-zA-Z\s]+$/", $trimmed)) {
            array_push(self::$errorArray, "Invalid last name characters");
            return self::$errorArray;
        }

        // Surname is valid
        return true;
    }

    public static function ValidateMotherLastname($text) {

        return self::FormNameValidation($text,
            Constants::$motherLastNameRequired,
            Constants::$invalidMotherLastNameCharacters,
            Constants::$motherLastNameIsTooShort,
            Constants::$motherLastNameIsTooLong, true);
    }

    public static function ValidateMotherFirstname($text) {

        return self::FormNameValidation($text,
            Constants::$motherFirstNameRequired,
            Constants::$invalidMotherFirstNameCharacters,
            Constants::$motherFirstNameIsTooShort,
            Constants::$motherFirstNameIsTooLong, true);
    }

    public static function ValidateMotherMiddlename($text) {
        return self::FormNameValidation($text,
            Constants::$motherMiddleNameRequired,
            Constants::$invalidMotherMiddleNameCharacters,
            Constants::$motherMiddleNameIsTooShort,
            Constants::$motherMiddleNameIsTooLong, true);
    }

    

    public static function ValidateFirstname($text) {

        return self::FormNameValidation($text,
            Constants::$firstNameRequired,
            Constants::$invalidFirstNameCharacters,
            Constants::$firstNameIsTooShort,
            Constants::$firstNameIsTooLong, true);
        
    }

    public static function ValidateMiddlename($text) {
        return self::FormNameValidation($text,
            Constants::$middleNameRequired,
            Constants::$invalidMiddleNameCharacters,
            Constants::$middleNameIsTooShort,
            Constants::$middleNameIsTooLong, true);
        
    }

    public static function ValidateBirthPlace($text) {
        return self::FormNameValidation($text,
            Constants::$birthPlaceRequired,
            Constants::$invalidBirthPlaceCharacters,
            Constants::$birthPlaceIsTooShort,
            Constants::$birthPlaceIsTooLong, true);
    }

    public static function ValidateAddressv2($text) {
        return self::FormNameValidation($text,
            Constants::$addressRequired,
            Constants::$invalidAddressCharacters,
            Constants::$addressIsTooShort,
            Constants::$addressIsTooLong, true);
    }

    public static function ValidateFatherOccupation($text) {

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

            // echo $trimmed;
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
        else if (!preg_match("/^[a-zA-Z0-9, ]+$/", $text)) {
            array_push(self::$errorArray, Constants::$invalidAddressCharacters);
            return;
        }
        else if ((strlen($trimmed) > 0 && strlen($trimmed) <= 2)) {
            array_push(self::$errorArray, Constants::$addressIsTooShort);
            return;
        } 
        else if (strlen($trimmed) > 50) {
            array_push(self::$errorArray, Constants::$addressIsTooLong);
            return;
        } 

        if(empty(self::$errorArray) && preg_match("/^[a-zA-Z0-9 ]+$/", $trimmed)) {
            $output = self::sanitizeFormString($trimmed);
            return $output;
        }

    }

    public static function ValidateSchoolName($text) {

        $trimmed = trim($text);
 

        if (empty($text)) {
            array_push(self::$errorArray, Constants::$schoolRequired);
            return;
        } 
        
        else if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
            array_push(self::$errorArray, Constants::$invalidSchoolCharacters);
            return;
        }
        else if ((strlen($trimmed) > 0 && strlen($trimmed) <= 2)) {
            array_push(self::$errorArray, Constants::$schoolIsTooShort);
            return;
        } 
        else if (strlen($trimmed) > 50) {
            array_push(self::$errorArray, Constants::$schoolIsTooLong);
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


    public static function ValidateEmailNewEnrollee(
        $pending_enrollees_id, 
        $userEmail, 
        $optional = false, $con = null) {

        // If optional the email verfication should working
        // Only the required email will be disabled.

        // echo $userEmail;
        $pending = new Pending($con);
            
        $pending_unique_email = $pending->CheckUniqueEnrolleeEmail(
            $userEmail, $pending_enrollees_id);
        
        // var_dump($pending_unique_email);

        // echo "qwe";
        // echo "<br>";

        if($pending_unique_email == false){
            array_push(self::$errorArray, Constants::$EmailUnique);
            return;
        }

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
                }
            } else {
                // Email is not in a valid format
                array_push(self::$errorArray, Constants::$invalidEmailCharacters);
                return;
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
 
    public static function ValidateEmail($userEmail, 
        $optional = false, $con = null) {

        // If optional the email verfication should working
        // Only the required email will be disabled.

        // echo $userEmail;
        $student = new Student($con);
            
        $student_unique_email = $student->CheckUniqueStudentEmail($userEmail);
        // $student_unique_email = true;
        
        // var_dump($student_unique_email);

        // echo "qwe";
        // echo "<br>";

        if($student_unique_email == false){
            array_push(self::$errorArray, Constants::$EmailUnique);
            return;
        }

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
                }
            } else {
                // Email is not in a valid format
                array_push(self::$errorArray, Constants::$invalidEmailCharacters);
                return;
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

    public static function ValidateEnrolleeEmail($userEmail, 
        $optional = false, $con = null) {

        // If optional the email verfication should working
        // Only the required email will be disabled.

        // echo $userEmail;
        $enrollee = new Pending($con);
            
        $enrollee_unique_email = $enrollee->CheckUniqueEnrolleesEmail($userEmail);
        
        // var_dump($student_unique_email);

        // echo "qwe";
        // echo "<br>";

        if($enrollee_unique_email == false){
            array_push(self::$errorArray, Constants::$EmailUnique);
            return;
        }

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
                }
            } else {
                // Email is not in a valid format
                array_push(self::$errorArray, Constants::$invalidEmailCharacters);
                return;
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

    public static function ValidateLRN($lrn, $optional = false, $con = null) {
        
        $lrn = trim($lrn);

        $student = new Student($con);

        $student_unique_lrn = $student->CheckUniqueStudentLRN($lrn);
        
        if($student_unique_lrn == false){
            array_push(self::$errorArray, Constants::$LRNUnique);
            return;
        }else{

            return $lrn;
        }

        if($optional === false){

            // if (empty($lrn)) {
            //     array_push(self::$errorArray, Constants::$LRNRequired);
            //     return;
            // }  

            // LRN is not in a valid format

        }

        // if($optional === true && !empty($userEmail)){

        //     // Email is not in a valid format
        //     array_push(self::$errorArray, Constants::$invalidEmailCharacters);
        //     return;
        //     // array_push(self::$errorArray, Constants::$invalidEmailFormat);
        //     // return;
        // }

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
            $trimmed = ucwords(strtolower($trimmed));
            return $trimmed;
        }
        else if(empty($trimmed)){
            return "";
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

 
    public static function FormNameValidationReal($text, $required,
        $invalidChar, $textShort, $textLong, $isRequired = null) {

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

        // If required = true -> Field is required
        // Else field doesnt required, but will validate if user has entered data

        if($isRequired === true){

            if (empty($text)) {
                array_push(self::$errorArray, $required);
                // echo $text;
                return;
            } 

            // John doe -> John Doe
            // Exclamation Marks and others are not valid here. (!@#$%^&*()<>)
            if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
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
        }

        if($isRequired === false){
            // echo "false";
            // John doe -> John Doe
            // Exclamation Marks and others are not valid here. (!@#$%^&*()<>)
            if(!empty($text)){

                if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
                    echo $text . " is false";
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
            }

        }

        

        // print_r(self::$errorArray);

        if(empty(self::$errorArray)) {

            $output = self::sanitizeFormString($text);
            return $output;
        }

    }

    public static function FormNameValidation($text, $required, $invalidChar,
        $textShort, $textLong, $isRequired = false) {

        $trimmed = trim($text);
        $length = strlen($trimmed);

        // Check if the field is required and the text is empty
        if ($isRequired && empty($trimmed)) {
            array_push(self::$errorArray, $required);
            return;
        }

        // Check if the text contains only letters and spaces
        if(!(empty($text))){

            if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
                array_push(self::$errorArray, $invalidChar);
                return;
            }

            // Check the text length
            if ($length > 0 && $length <= 1) {
                array_push(self::$errorArray, $textShort);
                return;
            } elseif ($length > 25) {
                array_push(self::$errorArray, $textLong);
                return;
            }

            // if (empty(self::$errorArray)) {
                $output = self::sanitizeFormString($trimmed);
                return $output;
            // }

        }
        return "";
        
        // If there are no errors, sanitize the input and return the output
        // if (empty(self::$errorArray)) {
        //     $output = self::sanitizeFormString($text);
        //     return $output;
        // }

        // if(empty(Helper::$errorArray)) {
        //     // echo $trimmed;
        //     $output = Helper::sanitizeFormString($trimmed);
        //     return $output;
        // }
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

    public static function getError2($error) {
        
        if(in_array($error, self::$errorArray)) {
            return $error;
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
    public static function removeControlCharacters($input) {
        return preg_replace('/[\x00-\x1F\x7F]/u', '', $input);
    }

    // Helper function to remove null bytes
    public static function removeNullBytes($input) {
        return str_replace("\0", '', $input);
    }
    // Helper function to remove common HTML entities
    public static function removeHtmlEntities($input) {
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


    public static function createNavByIcon($text, $icon, $link, $active_class, $readonly = false){

        // <span style='display:none;' class='notification_count'>1</span>

        $textLink = "";

        if($readonly == true){
            $textLink = "style='pointer-events: none;'";
        }
        

        return "
            <div class='$active_class'>
                <a $textLink href='$link'>
                    <span class='badge'>5</span>
                    <i style='color: white;' class='$icon'>
                        <span class='span_text'>$text</span>
                    </i>
                </a>
            </div>
        ";
    }


    public static function createNavByIconARC($text, $icon, $link, $active_class) {
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

    // public static function createNavByIcon($text, $icon, $link, $active_class){

    //     // <span style='display:none;' class='notification_count'>1</span>

    //     return "
    //         <div class='$active_class'>
    //             <a href='$link'>
    //                 <span class='notification_count'>5</span>
    //                 <i style='color: white;' class='$icon'></i>
    //                 <span class='span_text'>$text</span>
    //             </a>
    //         </div>
    //     ";
    // }

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

        $page = NULL;

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
        
            $page = $components[3];
        } else {
            $page = $components[2];

        }
        // $page = $components[2];
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
            <div id='btn' style='left: 0px; width: 114px'></div>
        ";

        if($isTertiary == true){
            $buttonTop = "
                <div id='btn' style='left: 110px; width: 140px'></div>
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


    public static function RegistrarDepartmentSection($isTertiary,
        $shs_url = null, $tertiary_url = null){

        $buttonTop = "
            <div id='btn' style='left: 0px; width: 114px'></div>
        ";

        if($isTertiary == true){
            $buttonTop = "
                <div id='btn' style='left: 110px; width: 140px'></div>
            ";
        }

        $button_default_style = " style='border:none; outline:0;'";
        
        return "
            <nav>
                <h3>Department</h3>
                <div class='form-box'>
                    <div class='button-box'>
                        $buttonTop
                        <a style='color: white;' href='$shs_url.php'>
                            <button $button_default_style type='button' class='toggle-btn'>
                                SHS
                            </button>
                        </a>
                        <a style='color: white;' href='$tertiary_url.php'>
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
    
    public static function RevealStudentTypePending(
        $type,
        //  $doesGraduate = null,
        $enrolle_enrollment_status = null,
        $admission_status = null,
        $student_status = null
        ){

        $output = "";

        // $text = $doesGraduate == true ? "Graduate" : "";
        $text = "";

        if($type == 'SHS' || $type == 'Senior High School'){
            $output = "Senior High School";
        }
        else if($type == 'Tertiary'){
            $output = "Tertiary";
        }

        
        return "
            <div class='title'>
                <small><em>$output &nbsp</em></small>
                <small><em>$enrolle_enrollment_status &nbsp </em></small>
                <small><em>$admission_status &nbsp </em></small>
             
            </div>
             
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
        $student_level, $type, $section_acronym, $student_status, $enrollment_date){

        $formattedDate = "";

        $student_status = $student_status == 1 ? "Active" 
            : ($student_status == 0 ? "Inactive" : "");

        $date = new DateTime($enrollment_date);
        $formattedDate = $date->format('m/d/Y');
        // echo $formattedDate;

        $type == 'Tertiary' ? 'Course' : ($type == 'Senior High School' ? 'Strand' : '');

        return "
            <div class='cards'>
                <div class='card'>
                    <sup>Student No.</sup>
                    <sub>$student_unique_id</sub>
                </div>
                <div class='card'>
                    <sup>Level</sup>
                    <sub>$student_level</sub>
                </div>
                <div class='card'>
                    <sup>$type</sup>
                    <sub>$section_acronym</sub>
                </div>
                <div class='card'>
                    <sup>Status</sup>
                    <sub>$student_status</sub>
                </div>
                <div class='card'>
                    <sup>Added on</sup>
                    <sub>$formattedDate</sub>
                </div>
            </div>
        ";
    }

    public static function CreateTeacherTabs($school_teacher_id,
        $department_type, $status, $creation_date){

        $formattedDate = "";

        $date = new DateTime($creation_date);
        $formattedDate = $date->format('m/d/Y');
        // echo $formattedDate;

        return "
            <div class='cards'>
                <div class='card'>
                    <sup>Teacher ID.</sup>
                    <sub>$school_teacher_id</sub>
                </div>
                <div class='card'>
                    <sup>Department</sup>
                    <sub>$department_type</sub>
                </div>
                <div class='card'>
                    <sup>Status</sup>
                    <sub>$status</sub>
                </div>
                <div class='card'>
                    <sup>Added on</sup>
                    <sub>$formattedDate</sub>
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
                    <sup>Section ID</sup>
                    <sub>$section_id</sub>
                </div>
                <div class='card'>
                    <sup>School Year</sup>
                    <sub>$school_year_term</sub>
                </div>
                <div class='card'>
                    <sup>Semester</sup>
                    <sub>$school_year_period</sub>
                </div>
                <div class='card'>
                    <sup>Strand</sup>
                    <sub>$acronym</sub>
                </div>
                <div class='card'>
                    <sup>Level</sup>
                    <sub>$level</sub>
                </div>
                <div class='card'>
                    <sup>Students</sup>
                    <sub>$totalStudents</sub>
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
                    <sup>Form ID</sup>
                    <sub>$enrollment_form_id</sub>
                </div>
                <div class='card'>
                    <sup>Admission type</sup>
                    <sub>$admission_status</sub>
                </div>
                <div class='card'>
                    <sup>Student no.</sup>
                    <sub>N/A</sub>
                </div>
                <div class='card'>
                    <sup>Status</sup>
                    <sub>Evaluation</sub>
                </div>
                <div class='card'>
                    <sup>Submitted on</sup>
                    <sub>$formattedDate</sub>
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
                    <sup>Form ID</sup>
                    <sub>$enrollment_form_id</sub>
                </div>
                <div class='card'>
                    <sup>Admission type</sup>
                    <sub>$updated_type</sub>
                </div>
                <div class='card'>
                    <sup>Student no.</sup>
                    <sub><a style='color: #333' target='_blank' href='$link'>$student_unique_id</a></sub>
                </div>
                <div class='card'>
                    <sup>Status</sup>
                    <sub>Evaluation</sub>
                </div>
                <div class='card'>
                    <sup>Submitted on</sup>
                    <sub>$formattedDate</sub>
                </div>
            </div>
        ";
    }

    public static function PendingEnrollmentDetailsTop($steps = null,
        $pending_enrollees_id = null,
        $enrollee_enrollment_status = null,
        $admission_status = null) {

            $result = "";

            $extra = "";

            if($steps == "step1"){

                //  <a href='contact_enrollee.php?id=$pending_enrollees_id' class='dropdown-item' style='color: black'>
                //         <i class='fas fa-phone'></i>
                //         Contact
                //     </a>

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
            
            $enrollee_enroll_status = "";
            $enrollee_admission_status = "";

            if($admission_status === "Standard"){

                $enrolleeAdmissionStatusChanger = "enrolleeAdmissionStatusChanger(\"Transferee\", $pending_enrollees_id)";
                
                $enrollee_admission_status = "
                    <a onclick='$enrolleeAdmissionStatusChanger' href='#' class='dropdown-item' style='color: teal'>
                        <i class='bi bi-file-earmark-x'></i>
                        Mark as Transferee
                    </a>
                ";

            }else if($admission_status === "Transferee"){

                $enrolleeAdmissionStatusChanger = "enrolleeAdmissionStatusChanger(\"Standard\", $pending_enrollees_id)";
                
                $enrollee_admission_status = "
                    <a onclick='$enrolleeAdmissionStatusChanger' href='#' class='dropdown-item' style='color: teal'>
                        <i class='bi bi-file-earmark-x'></i>
                        Mark as Standard
                    </a>
                ";
            }

            #
            if($enrollee_enrollment_status === "Regular"){

                $enrolleeEnrollmentStatusChanger = "enrolleeEnrollmentStatusChanger(\"Irregular\", $pending_enrollees_id)";
                
                $enrollee_enroll_status = "
                    <a onclick='$enrolleeEnrollmentStatusChanger' href='#' class='dropdown-item' style='color: green'>
                        <i class='bi bi-file-earmark-x'></i>
                        Mark as Irregular
                    </a>
                ";
            }else if($enrollee_enrollment_status === "Irregular"){

                $enrolleeEnrollmentStatusChanger = "enrolleeEnrollmentStatusChanger(\"Regular\", $pending_enrollees_id)";
                $enrollee_enroll_status = "
                    <a onclick='$enrolleeEnrollmentStatusChanger' href='#' class='dropdown-item' style='color: green'>
                        <i class='bi bi-file-earmark-x'></i>
                        Mark as Regular
                    </a>
                ";
            }

            // <a onclick='$reject' href='#' class='dropdown-item' style='color: yellow'>
            //                         <i class='bi bi-file-earmark-x'></i>
            //                         Reject form
            //                     </a>

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
                                

                                $enrollee_enroll_status
                                $enrollee_admission_status
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

    public static function renderGradeRecordHeader(
        $enrollment_school_year, $default_text)
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

    # TEACHER NOTIFICATION
    public static function lmsTeacherNotificationHeader($con,
        $teacherLoggedInId,
        $school_year_id,
        $teachingSubjects,
        $announcementPath = "",
        $studentAssignmentPath = "",
        $showAllPath = "",
        $showCalendar = ""

        ){
        
        if($showAllPath == "first"){

            # You`re in the notification folder.
            $showAllPath = "";
        }
        else if($showAllPath == "second"){

            # You`re 1 level outside of notification folder.
            $showAllPath = "../notification/";

        }
        

        if($showCalendar == "first"){

            $showCalendar = "";
        }
        else if($showCalendar == "second"){

            $showCalendar = "../dashboard/";
        }

        if($announcementPath == "first"){

            # You`re in the notification folder.
            $announcementPath = "";
        }
        else if($announcementPath == "second"){

            # You`re 1 level outside of notification folder.
            $announcementPath = "../notification/";
        }

        if($studentAssignmentPath == "first"){

            # You`re in the class folder.
            $studentAssignmentPath = "";
        }
        else if($studentAssignmentPath == "second"){

            # You`re 1 level outside of class folder.
            $studentAssignmentPath = "../class/";
        }

        
        $announcement = new Announcement($con);
        $notification = new Notification($con);
        $teacher = new Teacher($con, $teacherLoggedInId);


        $firstname = ucwords($teacher->GetTeacherFirstName());
        $lastname = ucwords($teacher->GetTeacherLastName());

        $adminAnnouncement = $announcement->CheckTeacherIdBelongsToAdminAnnouncement(
            $school_year_id,
            $teacherLoggedInId);

        $studentListSubmittedNotification = $notification->GetStudentSubmittedAssignmentNotification(
            $teachingSubjects, $school_year_id);

        // print_r($studentListSubmittedNotification);

        $adminAnnouncement = $announcement->CheckTeacherIdBelongsToAdminAnnouncement($school_year_id,
            $teacherLoggedInId);

        // var_dump($adminAnnouncement);

        $studentSubmittedAndAdminAnnouncement = array_merge($studentListSubmittedNotification,
            $adminAnnouncement);

        # Sorting the two array ORDER BY DESC alike.
        usort($studentSubmittedAndAdminAnnouncement, function($a, $b) {
            $dateA = strtotime($a['date_creation']);
            $dateB = strtotime($b['date_creation']);

            if ($dateA == $dateB) {
                return 0;
            }
            
            return ($dateA > $dateB) ? -1 : 1; // Change from 1 to -1 for descending order
        });


        $notificationCount = count($studentSubmittedAndAdminAnnouncement);


        $totalAdminNotifCount = count($adminAnnouncement);

        $totalViewedAdminNotificationCount = $notification->GetTeacherViewedNotificationFromAdminCount(
            $adminAnnouncement, $teacherLoggedInId);

        $totalTeacherViewedCount = $notification->GetTeacherViewedNotificationCount(
            $studentListSubmittedNotification, $teacherLoggedInId);

        $totalAdminAndTeacherNotifCount = $totalViewedAdminNotificationCount + $totalTeacherViewedCount;

        $totalNotifCount = count($studentSubmittedAndAdminAnnouncement);


        $totalUnviewed = ($notificationCount - $totalAdminAndTeacherNotifCount);

        // var_dump($notificationCount);

        ?>
            <div class="icons">

                <button class="sidebar" id="sidebar-btn">
                    <i class="bi bi-list"></i>
                </button>

                <div class="notif">
                    
                    <button
                        class="icon"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Notification">

                        <i class="bi bi-bell-fill"></i>
                        <!-- <span class="<?= $notificationCount > 0 ? "badge-1" : "" ?>"><?= $notificationCount > 0 ? $notificationCount : "" ?></span> -->
                        <span class="<?= $totalUnviewed > 0 ? "badge-1" : "" ?>"><?= $totalUnviewed > 0 ? $totalUnviewed : "" ?></span>

                    </button>
                    
                    <div  class="notif-menu">
                        <?php 

                            $announcement_url = "";
                        
                            foreach ($studentSubmittedAndAdminAnnouncement as $key => $notification) {

                                # code...

                                $notification_id = isset($notification['notification_id']) ? $notification['notification_id'] : "";

                                $notif_exec = new Notification($con, $notification_id);

                                // $sender_role = $notification['sender_role'];
                                $sender_role = isset($notification['sender_role']) ? $notification['sender_role'] : '';
                                
                                // $date_creation = $notification['date_creation'];

                                $date_creation = isset($notification['date_creation']) ? $notification['date_creation'] : '';
                                

                                $date_creation = date("M d, Y h:i a", strtotime($date_creation));

                               
                                // $subject_code = $notification['subject_code'];
                                $subject_code = isset($notification['subject_code']) ? $notification['subject_code'] : '';

                                // $announcement_id = $notification['announcement_id'];
                                $announcement_id = isset($notification['announcement_id']) ? $notification['announcement_id'] : '';
                                
                                // $subject_assignment_submission_id = $notification['subject_assignment_submission_id'];
                                $subject_assignment_submission_id = isset($notification['subject_assignment_submission_id']) ? $notification['subject_assignment_submission_id'] : '';


                                $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $subject_assignment_submission_id);
                                $subject_code_assignment_id =  $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();
                                $student_id =  $subjectAssignmentSubmission->GetStudentId();


                                $sender_name = "";
                                $type = "";
                                $title = "";
                                $button_url = "";

                                $assignment_notification_url = "";

                                $status = "
                                    <i style='color: orange' class='fas fa-times'></i>
                                ";


                                $admin_announcement_id = isset($notification['announcement_id']) ? $notification['announcement_id'] : '';
                                
                                $admin_title = isset($notification['title']) ? $notification['title'] : '';
                                $admin_content = isset($notification['content']) ? $notification['content'] : '';
                                
                                $admin_users_id = isset($notification['users_id']) ? $notification['users_id'] : '';
                                $admin_date_creation_db = isset($notification['date_creation']) ? $notification['date_creation'] : '';

                                $date_creation = date("M d, Y h:i a", strtotime($admin_date_creation_db));

                                $admin_role = isset($notification['role']) ? $notification['role'] : '';

                                if($admin_announcement_id != NULL && 
                                    $subject_code == NULL &&
                                    $admin_users_id != NULL &&
                                    $admin_role == "admin" ){

                                   
                                        $user = new User($con, $admin_users_id);

                                        $sender_name = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());


                                    
                                        // var_dump($assigment_name);

                                        $type = "Announcement";
                                        $title = "<span style='font-weight: bold;'>$admin_title</span>";

                                        // $announcement_url = "../notification/announcement_view.php?id=$admin_announcement_id&notification=true";
                                        $announcement_url = $announcementPath . "announcement_view.php?id=$admin_announcement_id&notification=true";

                                        $button_url = "
                                            <button onclick='window.location.href=\"$announcement_url\"' class='btn btn-info btn-sm'>
                                                <i class='fas fa-eye'></i>
                                            </button>
                                        ";

                                        $announcement = new Announcement($con, $admin_announcement_id);



                                        $teacherAnnouncementViewed = $announcement->CheckTeacherViewedAnnouncement(
                                            $admin_announcement_id, $teacherLoggedInId);

                                        if($teacherAnnouncementViewed){
                                            $status = "
                                                <i style='color: green' class='fas fa-check'></i>
                                        ";

                                    }
                                }

                                if($subject_assignment_submission_id != NULL && 
                                    $subject_code != NULL &&
                                    $sender_role == "student" ){

                                   
                                    $student = new Student($con, $student_id);

                                    $sender_name = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());


                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    // var_dump($assigment_name);

                                    $type = "Assignment";
                                    $title = "Submitted $type: <span style='font-weight: bold;'>$assigment_name</span>";

                                    // $assignment_notification_url
                                    $announcement_url = "$studentAssignmentPath" . "student_submission_view.php?id=$subject_assignment_submission_id&n_id=$notification_id&notification=true";
                                    
                                    // $announcement_url = "student_submission_view.php?id=$subject_assignment_submission_id&n_id=$notification_id&notification=true";

                                    $button_url = "
                                        <button onclick='window.location.href=\"$assignment_notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";
                                }
                           
                                
                                $notif_exec = new Notification($con, $notification_id);

                                $studentViewed = $notif_exec->CheckTeacherViewedNotification($notification_id,
                                    $teacherLoggedInId);

                             

                                if($studentViewed){
                                    $status = "
                                        <i style='color: green' class='fas fa-check'></i>
                                    ";
                                }

                                ?>

                                
                                    <a href="<?= $announcement_url; ?>" class="notif-item">
                                        <div class="col">
                                            <header>
                                                <div class="title">
                                                    <h5><?= $sender_name;?></h6>
                                                    <span><?="$type: ";?><?= $title;?></span>
                                                    <small><?= $date_creation;?></small>
                                                    <span><?= $status;?></span>
                                                </div>
                                            </header>
                                        </div>
                                    </a>
                                <?php
                            }
                        ?>

                        <?php if($notificationCount > 0):?>

                            <div class="action">
                                <button onclick="window.location.href = '<?php echo $showAllPath . "index.php"; ?>'" class="default">See all</button>
                                <button class="clean">Mark all read</button>
                            </div>
                            <?php else:?>
                                <div class="action">
                                    <button onclick="window.location.href = '<?php echo $showAllPath . "index.php"; ?>'" class="default">Show all</button>
                                    <button class="clean">Mark all read</button>
                                    <!-- <h5 class="text-center">No notification.</h5> -->
                                </div>
                        <?php endif;?>

                    </div>  


                </div>

                <?php 
                
                    $assignment_calendar_url = $showCalendar . "calendar.php";
                
                ?>

                <button  class="calendar-btn" title="Assignment calendar"
                    onclick="window.location.href = '<?= $assignment_calendar_url; ?>'">
                    <i class="bi bi-calendar-event"></i>

                </button>
                
                <div class="username">
                    <button title="Profile" onclick="window.location.href='#'"><?= "$lastname, $firstname"?></button>
                </div>

            </div>
        <?php
    }

    # STUDENT NOTIFICATION
    public static function lmsStudentNotificationHeader($con,
        $studentLoggedInId,
        $school_year_id,
        $enrolledSubjectList,
        $enrollment_id,
        $notificationPath = "",
        $coursesPath = "",
        $showAllPath = "",
        $logout_url = null,
        $showCalendar = ""){
        
        if($showAllPath == "first"){

            # You`re in the notification folder.
            $showAllPath = "";
        }
        else if($showAllPath == "second"){

            # You`re 1 level outside of notification folder.
            $showAllPath = "../notification/";
        }

        if($showCalendar == "first"){

            # You`re in the notification folder.
            $showCalendar = "";
        }
        else if($showCalendar == "second"){

            # You`re 1 level outside of notification folder.
            $showCalendar = "../lms/";
        }

        if($notificationPath == "first"){

            # You`re in the notification folder.
            $notificationPath = "";
        }
        else if($notificationPath == "second"){

            # You`re 1 level outside of notification folder.
            $notificationPath = "../notification/";
        }

        if($coursesPath == "first"){

            # You`re in the class folder.
            $coursesPath = "";
        }
        else if($coursesPath == "second"){

            # You`re 1 level outside of class folder.
            $coursesPath = "../courses/";
        }

        $notif = new Notification($con);

        $student = new Student($con, $studentLoggedInId);


        $firstname = ucwords($student->GetFirstName());
        $lastname = ucwords($student->GetLastName());

        $studentEnrolledSubjectAssignmentNotif = $notif->GetStudentAssignmentNotificationv2(
            $enrolledSubjectList, $school_year_id);


        $gradedAssignments = $notif->GetStudentGradedAssignmentNotification(
            $enrolledSubjectList, $school_year_id, $studentLoggedInId);

  
        $allAdminNotification = $notif->GetAdminAnnouncement($school_year_id);

        // var_dump($school_year_id);
        // print_r($allAdminNotification);

        $studentsDueDateNotif = $notif->GetStudentDueDateNotifications(
            $enrolledSubjectList, $school_year_id, $studentLoggedInId);

        $mergedArray = array_merge($studentEnrolledSubjectAssignmentNotif,
            $allAdminNotification, $gradedAssignments, $studentsDueDateNotif);

        // var_dump($mergedArray);

        $notificationCount = count($mergedArray);


        // usort($mergedArray, ['Notification', 'SortByDateCreation']);
        
        usort($mergedArray, function($a, $b) {
            $dateA = strtotime($a['date_creation']);
            $dateB = strtotime($b['date_creation']);

            if ($dateA == $dateB) {
                return 0;
            }
            
            return ($dateA > $dateB) ? -1 : 1; // Change from 1 to -1 for descending order
        });

        $totalViewed = 0;
    
        foreach ($mergedArray as $key => $value) {
            # code...

            $notification_id = $value['notification_id'];
            // echo "notification_id: $notification_id";

            $count = $notif->GetStudentNotificationsViewedCount(
                $school_year_id, $studentLoggedInId, $notification_id);

            if($count != 0){
                $totalViewed += $count;

            }
        }
        
        $unViewedCount = ($notificationCount - $totalViewed);

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            $base_url = 'http://localhost/school-system-dcbt/student/';
        } else {
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';
        }

        $studentLoggedInId = isset($_SESSION["studentLoggedInId"]) 
                ? $_SESSION["studentLoggedInId"] : "";
        $studentLoggedInObj = new Student($con, $studentLoggedInId);
        $student_id = $studentLoggedInObj->GetStudentId();
        $profile_url = $base_url . "profile/my_profile.php?id=" . $student_id;


        // var_dump($notificationCount);

        ?>

            <div class="icons">

                <button class="sidebar" id="sidebar-btn">
                    <i class="bi bi-list"></i>
                </button>

                <div class="notif">
                    <button
                        class="icon"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Notification">

                        <i class="bi bi-bell-fill"></i>
                        <!-- <span class="<?= $notificationCount > 0 ? "badge-1" : "" ?>"><?= $notificationCount > 0 ? $notificationCount : "" ?></span> -->
                        <span class="<?= $unViewedCount > 0 ? "badge-1" : "" ?>"><?= $unViewedCount > 0 ? $unViewedCount : "" ?></span>
                        
                    </button>
                    
                    <div class="notif-menu">
            
                        <?php 

                            $notification_url = "";

                            foreach ($mergedArray as $key => $notification) {

                                // $department_id = $row['department_id'];
                                
                                $notification_id = $notification['notification_id'];

                                $notif_exec = new Notification($con, $notification_id);

                                $sender_role = $notification['sender_role'];
                                $date_creation = $notification['date_creation'];
                                $date_creation = date("M d, Y h:i a", strtotime($date_creation));

                                $sender_role = $notification['sender_role'];

                                $subject_code = $notification['subject_code'];
 

                               $subject_code_assignment_id = $notification['subject_code_assignment_id'];

                                $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

                                $subjectperiodcodetopicId = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
                                
                                $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subjectperiodcodetopicId);

                                $subjectProgramId = $subjectPeriodCodeTopic->GetSubjectProgramId();

                                $subjectProgram = new SubjectProgram($con, $subjectProgramId);

                                $subject_title = $subjectProgram->GetTitle();




                                $subject_code_assignment_id = $notification['subject_code_assignment_id'];
                                $announcement_id = $notification['announcement_id'];
                                $subject_assignment_submission_id = $notification['subject_assignment_submission_id'];

                                $sender_name = "";

                                $type = "";
                                $title = "";
                                $button_url = "";

                                $assignment_notification_url = "";


                                // var_dump($sender_role);
                                // echo "<br>";

                                if($sender_role === "admin" && 
                                    $announcement_id != NULL){

                                    $announcement = new Announcement($con, $announcement_id);
                                    $users_id = $announcement->GetUserId();

                                    $users = new User($con, $users_id);

                                    $sender_name = ucwords($users->getFirstName()) . " " . ucwords($users->getLastName());
                                    
                                    $type = "Announcement";

                                    $title = "Admin add announcement: ";

                                    $announcementTitle = $announcement->GetTitle();

                                    $title = "Admin add announcement: <span style='font-weight: bold;'>$announcementTitle</span>";

                                    $notification_url = $notificationPath . "admin_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    // $notification_url = "../notification/admin_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    
                                    // $notification_url = "admin_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    
                                    $button_url = "
                                        <button title='View notification' onclick='window.location.href=\"$notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";
                                    
                                }

                                if($subject_code_assignment_id != NULL && 
                                    $subject_code != NULL &&
                                    $subject_assignment_submission_id == NULL &&
                                    $announcement_id == NULL){

                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    $subjectPeriodCodeTopicId = $assigment->GetSubjectPeriodCodeTopicId();

                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con,
                                        $subjectPeriodCodeTopicId);

                                    
                                    $teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

                                    // var_dump($teacher_id);
                                    $teacher = new Teacher($con, $teacher_id);

                                    $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    $sender_name = trim($sender_name);

                                    $type = "Assignment";
                                    $title = "Add $type: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_title</span>";

                                    $get_student_subject_id = NULL;

                                    if($subject_code != NULL){

                                        $studentSubject = new StudentSubject($con);

                                        $get_student_subject_id = $studentSubject->GetStudentSubjectIdBySectionSubjectCode(
                                            $subject_code, $studentLoggedInId, $enrollment_id);

                                    }

                                    $notification_url = $coursesPath. "task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification=true";
                                    // $notification_url = "task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification=true";
                                    // $assignment_notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification=true";

                                    $button_url = "
                                        <button onclick='window.location.href=\"$notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";
                                }

                                if($subject_code_assignment_id != NULL && 
                                    $subject_code != NULL &&
                                    $subject_assignment_submission_id != NULL &&
                                    $announcement_id == NULL
                                
                                    ){

                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    $subjectPeriodCodeTopicId = $assigment->GetSubjectPeriodCodeTopicId();

                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con,
                                        $subjectPeriodCodeTopicId);

                                    
                                    $teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

                                    // var_dump($teacher_id);
                                    $teacher = new Teacher($con, $teacher_id);

                                    $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    $sender_name = trim($sender_name);

                                    $type = "Graded";
                                    $title = "Assignment: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_title</span>";

                                    $get_student_subject_id = NULL;

                                    if($subject_code != NULL){

                                        $studentSubject = new StudentSubject($con);

                                        $get_student_subject_id = $studentSubject->GetStudentSubjectIdBySectionSubjectCode(
                                            $subject_code, $studentLoggedInId, $enrollment_id);

                                    }

                                    $notification_url = $coursesPath. "task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification=true";

                                    $button_url = "
                                        <button onclick='window.location.href=\"$notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";

                                }

                                if($announcement_id != NULL 
                                    && $subject_code != NULL
                                    && $sender_role = "teacher"
                                    ){


                                    $announcement = new Announcement($con, $announcement_id);
                                    $announcementTitle = $announcement->GetTitle();


                                    $announcementTeacherId = $announcement->GetTeacherId();

                                    $teacher = new Teacher($con, $announcementTeacherId);

                                    $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    $sender_name = trim($sender_name);

                                    $type = "Announcement";

                                    $title = "Add $type: <span style='font-weight: bold;'>$announcementTitle</span> on <span style='font-weight: bold;'>$subject_code</span>";

                                    $notification_url = $coursesPath . "student_subject_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    // $announcement_url = "../courses/student_subject_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true";
                                    
                                    $button_url = "
                                        <button onclick='window.location.href=\"$notification_url\"' class='btn btn-primary btn-sm'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                    ";

                                }

                                $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $subject_assignment_submission_id);
                                $subject_assignment_submission_student_id = $subjectAssignmentSubmission->GetStudentId();

                                if($subject_code_assignment_id != NULL && 
                                    $subject_code != NULL &&
                                    $subject_assignment_submission_id != NULL &&
                                    $subject_assignment_submission_student_id == $studentLoggedInId &&
                                    $announcement_id == NULL &&
                                    $sender_role != "auto"

                                    ){

                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    $subjectPeriodCodeTopicId = $assigment->GetSubjectPeriodCodeTopicId();

                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con,
                                        $subjectPeriodCodeTopicId);

                                    
                                    $teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

                                    // var_dump($teacher_id);
                                    $teacher = new Teacher($con, $teacher_id);

                                    $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    $sender_name = trim($sender_name);

                                    $type = "Graded";
                                    $title = "Assignment: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_title</span>";

                                    $get_student_subject_id = NULL;

                                    if($subject_code != NULL){

                                        $studentSubject = new StudentSubject($con);

                                        $get_student_subject_id = $studentSubject->GetStudentSubjectIdBySectionSubjectCode(
                                            $subject_code, $studentLoggedInId, $enrollment_id);

                                    }

                                    $notification_url = $coursesPath. "task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification=true";
                                    // $assignment_notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification=true";

                                    // $notification_url = "../courses/task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification_due=true";

                                }

                                if($subject_code_assignment_id != NULL && 
                                    $subject_code != NULL &&
                                    $subject_assignment_submission_id == NULL &&
                                    $announcement_id == NULL &&
                                    $sender_role == "auto"
                                    ){

                                    $assigment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
                                    $assigment_name = $assigment->GetAssignmentName();

                                    // $subjectPeriodCodeTopicId = $assigment->GetSubjectPeriodCodeTopicId();

                                    // $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con,
                                    //     $subjectPeriodCodeTopicId);

                                    // $teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

                                    // var_dump($teacher_id);
                                    // $teacher = new Teacher($con, $teacher_id);

                                    // $sender_name = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                    // $sender_name = trim($sender_name);

                                    $sender_name = trim("System");

                                    $type = "Due soon";
                                    $title = "Assignment $type: <span style='font-weight: bold;'>$assigment_name</span> on <span style='font-weight: bold;'>$subject_title</span>";

                                    $get_student_subject_id = NULL;

                                    if($subject_code != NULL){

                                        $studentSubject = new StudentSubject($con);

                                        $get_student_subject_id = $studentSubject->GetStudentSubjectIdBySectionSubjectCode(
                                            $subject_code, $studentLoggedInId, $enrollment_id);

                                    }
                                    
                                    $notification_url = $coursesPath. "task_submission.php?sc_id=$subject_code_assignment_id&ss_id=$get_student_subject_id&n_id=$notification_id&notification_due=true";

                                }



                                $status = "
                                        <i style='color: orange' class='fas fa-times'></i>
                                    ";
 
                                #
                                $notif_exec = new Notification($con, $notification_id);
                                $studentViewed = $notif_exec->CheckStudentViewedNotification($notification_id, $studentLoggedInId);

                                $studentViewedDue = $notif_exec->CheckStudentViewedDueDateNotification(
                                    $notification_id, $studentLoggedInId);

                                if($studentViewed){
                                    $status = "
                                        <i style='color: green' class='fas fa-check'></i>
                                    ";
                                }

                                if($studentViewedDue){
                                    $status = "
                                        <i style='color: green' class='fas fa-check'></i>
                                    ";
                                }

                                ?>


                                
                                    <a href="<?= $notification_url; ?>" class="notif-item">
                                        <div class="col">

                                            <header>
                                                <div class="title">
                                                    <h5><?= $sender_name;?></h6>
                                                    <span><?="$type: ";?><?= $title;?></span>
                                                    <small><?= $date_creation;?></small>
                                                    <span><?= $status;?></span>
                                                </div>
                                            </header>

                                        </div>
                                    </a>

                                <?php
                            }
                        
                        ?>

                        <?php if($notificationCount > 0):?>

                            <div class="action">
                                <button onclick="window.location.href = '<?php echo $showAllPath . "index.php"; ?>'" class="default">Show All</button>
                                <button class="clean">Mark all read</button>
                            </div>

                            <?php else:?>
                                <div class="action">
                                    <button onclick="window.location.href = '<?php echo $showAllPath . "index.php"; ?>'" class="default">Show all</button>
                                    <button class="clean">Mark all read</button>
                                    <!-- <h5 class="text-center">No notification.</h5> -->
                                </div>
                        <?php endif;?>

                    </div>
 
                </div>


                <?php 
                
                    $task_calendar_url = $showCalendar . "student_calendar.php";

                
                ?>

                <!-- $notificationPath . "admin_announcement.php?id=$announcement_id&n_id=$notification_id&notification=true -->
                <button  class="calendar-btn" title="Task calendar"
                    onclick="window.location.href = '<?= $task_calendar_url; ?>'">
                    <i class="bi bi-calendar-event"></i>
                </button>
            
                <div class="username">
                    <button title="Profile" onclick="window.location.href='<?= $profile_url; ?>'"><?= "$lastname, $firstname"?></button>
                </div>

            </div>
        <?php
    }

    public static function DoesEnrollmentPrinted($printed = null) {
        return $printed;
    }

    public static function enrollmentStudentHeader($con, $studentLoggedInId) {
        $student = new Student($con, $studentLoggedInId);

        $firstname = ucwords($student->GetFirstName());
        $lastname = ucwords($student->GetLastName());

        ?>
        <div class="icons">
            <button class="sidebar">
            <i class="bi bi-list"></i>
            </button>
            <div class="username" style="width: 100%; justify-content: flex-end">
                <button title="Username"><?= "$lastname, $firstname" ?></button>
            </div>
        </div>
    <?php
    }

    public static function pendingStudentHeader($con, $enrolleeLoggedInObj) {
        $enrolleeLoggedIn = isset($_SESSION["enrollee_id"]) 
        ? $_SESSION["enrollee_id"] : "";

        $enrolleeLoggedInObj = new Pending($con, $enrolleeLoggedIn);
        if (!isset($_SESSION['enrollee_id']) 
        || $_SESSION['enrollee_id'] == '') {

        header("Location: /school-system-dcbt/index.php");
        exit();
        }
        ?>
        <div class="icons">
            <button class="sidebar">
            <i class="bi bi-list"></i>
            </button>
            <div class="username" style="width: 100%; justify-content: flex-end">
                <button 
                    title="Username"
            >
                <?php echo $enrolleeLoggedInObj->GetPendingLastName(); ?>, 
                <?php echo $enrolleeLoggedInObj->GetPendingFirstName(); ?>
            </button>
            </div>
        </div>
    <?php
    }
}
?>