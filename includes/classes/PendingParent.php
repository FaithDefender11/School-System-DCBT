<?php

    class PendingParent{

    private $con, $sqlData, $parent_id;
    
    public $errorArray = array();

    public function __construct($con, $parent_id = null){

        $this->con = $con;
        $this->parent_id = $parent_id;

        $query = $this->con->prepare("SELECT * FROM parent
                WHERE parent_id=:parent_id");

        $query->bindValue(":parent_id", $parent_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

        if($this->sqlData == null){

            $pending_enrollees_id = $parent_id;

            $query = $this->con->prepare("SELECT * FROM parent
                WHERE pending_enrollees_id=:pending_enrollees_id");

            $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetParentID() {
        return isset($this->sqlData['parent_id']) ? ucfirst($this->sqlData["parent_id"]) : ""; 
    }
    public function GetFirstName() {
        return isset($this->sqlData['firstname']) ? ucfirst($this->sqlData["firstname"]) : ""; 
    }

    public function GetGuardianRelationship() {
        return isset($this->sqlData['relationship']) ? ucfirst($this->sqlData["relationship"]) : ""; 
    }

    public function GetLastName() {
        return isset($this->sqlData['lastname']) ? ucfirst($this->sqlData["lastname"]) : ""; 
    }
    public function GetMiddleName() {
        return isset($this->sqlData['middle_name']) ? ucfirst($this->sqlData["middle_name"]) : ""; 
    }

    public function GetContactNumber() {
        return isset($this->sqlData['contact_number']) ? $this->sqlData["contact_number"] : ""; 
    }
    public function GetSuffix() {
        return isset($this->sqlData['suffix']) ? ucfirst($this->sqlData["suffix"]) : ""; 
    }

    public function GetOccupation() {
        return isset($this->sqlData['occupation']) ? ucfirst($this->sqlData["occupation"]) : ""; 
    }

    public function GetEmail() {
        return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
    }

    public function GetFatherFirstName() {
        return isset($this->sqlData['father_firstname']) ? $this->sqlData["father_firstname"] : ""; 
    }
    public function GetFatherLastName() {
        return isset($this->sqlData['father_lastname']) ? $this->sqlData["father_lastname"] : ""; 
    }

    public function GetFatherMiddleName() {
        return isset($this->sqlData['father_middle']) ? $this->sqlData["father_middle"] : ""; 
    }

    public function GetFatherSuffix() {
        return isset($this->sqlData['father_suffix']) ? $this->sqlData["father_suffix"] : ""; 
    }

    public function GetFatherOccupation() {
        return isset($this->sqlData['father_occupation']) ? $this->sqlData["father_occupation"] : ""; 
    }

    public function GetFatherContactNumber() {
        return isset($this->sqlData['father_contact_number']) ? $this->sqlData["father_contact_number"] : ""; 
    }
    public function GetFatherEmail() {
        return isset($this->sqlData['father_email']) ? $this->sqlData["father_email"] : ""; 
    }


    public function GetMotherFirstName() {
        return isset($this->sqlData['mother_firstname']) ? $this->sqlData["mother_firstname"] : ""; 
    }
    public function GetMotherLastName() {
        return isset($this->sqlData['mother_lastname']) ? $this->sqlData["mother_lastname"] : ""; 
    }

    public function GetMotherMiddleName() {
        return isset($this->sqlData['mother_middle']) ? $this->sqlData["mother_middle"] : ""; 
    }

    public function GetMotherSuffix() {
        return isset($this->sqlData['mother_suffix']) ? $this->sqlData["mother_suffix"] : ""; 
    }

    public function GetMotherOccupation() {
        return isset($this->sqlData['mother_occupation']) ? $this->sqlData["mother_occupation"] : ""; 
    }

    public function GetMotherContactNumber() {
        return isset($this->sqlData['mother_contact_number']) ? $this->sqlData["mother_contact_number"] : ""; 
    }
    public function GetMotherEmail() {
        return isset($this->sqlData['mother_email']) ? $this->sqlData["mother_email"] : ""; 
    }

    public function UpdatePendingParent($pending_enrollees_id, $parent_id, $firstname, $lastname,
        $middle_name, $suffix, $contact_number,
        $email, $occupation, $relationship,

        $father_firstname,
        $father_lastname,
        $father_middle_name,
        $father_suffix,
        $father_contact_number,
        $father_email,
        $father_occupation,
        $mother_firstname,
        $mother_lastname,
        $mother_middle_name,
        $mother_suffix,
        $mother_contact_number,
        $mother_email,
        $mother_occupation)
         {
 

        $query = $this->con->prepare("UPDATE parent 
            SET 
                firstname=:firstname,
                lastname=:lastname,
                middle_name=:middle_name,
                suffix=:suffix,

                contact_number=:contact_number,
                email=:email,
                occupation=:occupation,
                relationship=:relationship,


                father_firstname=:father_firstname,
                father_lastname=:father_lastname,
                father_middle=:father_middle,
                father_suffix=:father_suffix,
                father_contact_number=:father_contact_number,
                father_email=:father_email,
                father_occupation=:father_occupation,

                mother_firstname=:mother_firstname,
                mother_lastname=:mother_lastname,
                mother_middle=:mother_middle,
                mother_suffix=:mother_suffix,
                mother_contact_number=:mother_contact_number,
                mother_email=:mother_email,
                mother_occupation=:mother_occupation

                
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND parent_id=:parent_id

            -- AND active=
        ");

        $query->bindParam(":firstname", $firstname);
        $query->bindParam(":lastname", $lastname);
        $query->bindParam(":middle_name", $middle_name);
        $query->bindParam(":suffix", $suffix);

        $query->bindParam(":contact_number", $contact_number);
        $query->bindParam(":email", $email);
        $query->bindParam(":occupation", $occupation);
        $query->bindParam(":relationship", $relationship);


        $query->bindParam(":father_firstname", $father_firstname);
        $query->bindParam(":father_lastname", $father_lastname);
        $query->bindParam(":father_middle", $father_middle_name);
        $query->bindParam(":father_suffix", $father_suffix);
        $query->bindParam(":father_contact_number", $father_contact_number);
        $query->bindParam(":father_email", $father_email);
        $query->bindParam(":father_occupation", $father_occupation);


        $query->bindParam(":mother_firstname", $mother_firstname);
        $query->bindParam(":mother_lastname", $mother_lastname);
        $query->bindParam(":mother_middle", $mother_middle_name);
        $query->bindParam(":mother_suffix", $mother_suffix);
        $query->bindParam(":mother_contact_number", $mother_contact_number);
        $query->bindParam(":mother_email", $mother_email);
        $query->bindParam(":mother_occupation", $mother_occupation);

 
        $query->bindParam(":parent_id", $parent_id);
        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;

    }

    public function InsertParentInformation(
        $pending_enrollees_id = null,
        $firstname,
        $lastname,
        $middle_name,
        $suffix,
        $contact_number,
        $email,
        $occupation,
        $relationship,
        $father_firstname,
        $father_lastname,
        $father_middle_name,
        $father_suffix,
        $father_contact_number,
        $father_email,
        $father_occupation,
        $mother_firstname,
        $mother_lastname,
        $mother_middle_name,
        $mother_suffix,
        $mother_contact_number,
        $mother_email,
        $mother_occupation,
        $student_id = null) {

        $query = $this->con->prepare("INSERT INTO parent (
            pending_enrollees_id,
            firstname,
            lastname,
            middle_name,
            suffix,
            contact_number,
            email,
            occupation,
            relationship,
            father_firstname,
            father_lastname,
            father_middle,
            father_suffix,
            father_contact_number,
            father_email,
            father_occupation,
            mother_firstname,
            mother_lastname,
            mother_middle,
            mother_suffix,
            mother_contact_number,
            mother_email,
            mother_occupation,

            student_id
        ) VALUES (
            :pending_enrollees_id,
            :firstname,
            :lastname,
            :middle_name,
            :suffix,
            :contact_number,
            :email,
            :occupation,
            :relationship,
            :father_firstname,
            :father_lastname,
            :father_middle,
            :father_suffix,
            :father_contact_number,
            :father_email,
            :father_occupation,
            :mother_firstname,
            :mother_lastname,
            :mother_middle,
            :mother_suffix,
            :mother_contact_number,
            :mother_email,
            :mother_occupation,
            :student_id
        )");

        $query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $query->bindValue(":firstname", $firstname  ?? "");
        $query->bindValue(":lastname", $lastname ?? "");
        $query->bindValue(":middle_name", $middle_name ?? "");
        $query->bindValue(":suffix", $suffix ?? "");
        $query->bindValue(":contact_number", $contact_number ?? "");
        $query->bindValue(":email", $email ?? "");
        $query->bindValue(":occupation", $occupation ?? "");
        $query->bindValue(":relationship", $relationship ?? "");
        $query->bindValue(":father_firstname", $father_firstname ?? "");
        $query->bindValue(":father_lastname", $father_lastname ?? "");
        $query->bindValue(":father_middle", $father_middle_name ?? "");
        $query->bindValue(":father_suffix", $father_suffix ?? "");
        $query->bindValue(":father_contact_number", $father_contact_number ?? "");
        $query->bindValue(":father_email", $father_email ?? "");
        $query->bindValue(":father_occupation", $father_occupation ?? "");
        $query->bindValue(":mother_firstname", $mother_firstname ?? "");
        $query->bindValue(":mother_lastname", $mother_lastname ?? "");
        $query->bindValue(":mother_middle", $mother_middle_name ?? "");
        $query->bindValue(":mother_suffix", $mother_suffix ?? "");
        $query->bindValue(":mother_contact_number", $mother_contact_number ?? "");
        $query->bindValue(":mother_email", $mother_email ?? "");
        $query->bindValue(":mother_occupation", $mother_occupation ?? "");
        $query->bindValue(":student_id", $student_id ?? null);

        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }
        return false;

    }

    public function CheckEnrolleeHasParent($pending_enrollees_id){


        $query= $this->con->prepare("SELECT pending_enrollees_id FROM parent
            WHERE pending_enrollees_id=:pending_enrollees_id");

        $query->bindParam(":pending_enrollees_id", $pending_enrollees_id);
        $query->execute();

        return $query->rowCount() > 0;
    }
    

    public function getInputValue($name) {
        if(isset($_POST[$name])) {
            echo $_POST[$name];
        }
    }

    public function DisplayText($post_name, $db_text){

        $output = "";
        
        if(count($this->errorArray) > 0){
            $output = $this->getInputValue($post_name);
        }else{
            $output = $db_text;
        }
        return $output;
    }

    // public function FormNameValidation($text, $required,
    //     $invalidChar, $textShort, $textLong) {

    //     $trimmed = trim($text);


    //     if (empty($text)) {
    //         array_push($this->errorArray, $required);
    //         // echo $text;
    //         return;
    //     } 
    //     // John doe -> John Doe
    //     // Exclamation Marks and others are not valid here. (!@#$%^&*()<>)
    //     else if (!preg_match("/^[a-zA-Z ]+$/", $text)) {
    //         array_push($this->errorArray, $invalidChar);
    //         // echo $text;
    //         return;
    //     }
    //     else if ((strlen($trimmed) > 0 && strlen($trimmed) <= 1)) {
    //         array_push($this->errorArray, $textShort);
    //         // echo $text;
    //         return;
    //     } 
    //     else if (strlen($trimmed) > 25) {
    //         array_push($this->errorArray, $textLong);
    //         // echo $text;
    //         return;
    //     } 

    //     if(empty($this->errorArray)) {

    //         $output = $this->sanitizeFormString($text);
    //         return $output;
    //     }

    // }

    public function sanitizeFormString($inputText) {
    
        $inputText = Helper::removeControlCharacters($inputText);
        // Remove null bytes
        $inputText = Helper::removeNullBytes($inputText);

        // Remove common HTML entities from input
        $inputText = Helper::removeHtmlEntities($inputText);

        // $inputText = htmlspecialchars($inputText);
        $inputText = strip_tags($inputText);
        // $inputText = str_replace(" ", "", $inputText);
        $inputText = trim($inputText);

        $inputText = strtolower($inputText);
        // $inputText = ucfirst($inputText);
        $inputText = ucwords($inputText); // Capitalize the first letter of each word

        return $inputText;
    }


    // public function ValidateMotherLastname($text) {

    //     return Helper::FormNameValidation($text,
    //         Constants::$motherLastNameRequired,
    //         Constants::$invalidMotherLastNameCharacters,
    //         Constants::$motherLastNameIsTooShort,
    //         Constants::$motherLastNameIsTooLong);
        
    // }

    public function ValidateMotherLastName($text, $isRequired = false) {

        $trimmed = trim($text);

        $pattern = '/^[A-Za-z]+$/';

        // echo $text;
        if($isRequired){

            if (empty($text)) {
                array_push(Helper::$errorArray, Constants::$motherLastNameRequired);
                // echo $text;
            } 
        }

        if(!empty($text)){
        
            if (!preg_match($pattern, $trimmed)) {
                array_push(Helper::$errorArray, Constants::$invalidMotherLastNameCharacters);
                return;
            }
            // John doe -> John Doe
            // Exclamation Marks and others are not valid here. (!@#$%^&*()<>)
            else if (!preg_match("/^[a-zA-Z ]+$/", $trimmed)) {
                array_push(Helper::$errorArray, Constants::$invalidMotherLastNameCharacters);
                // echo $text;
                return;
            }
            else if ((strlen($trimmed) > 0 && strlen($trimmed) <= 1)) {
                array_push(Helper::$errorArray, Constants::$motherLastNameIsTooShort);
                // echo $text;
                return;
            } 
            else if (strlen($trimmed) > 25) {
                array_push(Helper::$errorArray, Constants::$motherLastNameIsTooLong);
                // echo $text;
                return;
            } 

            // if(empty(Helper::$errorArray)) {
                $output = Helper::sanitizeFormString($text);
                return $output;
            // }

        }

        return "";
        // if(empty(Helper::$errorArray)) {

        //     $output = Helper::sanitizeFormString($text);
        //     return $output;
        // }


    }



    public function MakingRequiredField($parent, $text) {

        $textField = "";

        $textField = $parent->ValidateMotherFirstName(
                $_POST[$text], true);
            
        return $textField;
    }

    // public function FatherRequiredFieldMandatory($fieldTriggered, $text) {

    //     if($fieldTriggered !== "" && $father_contact_number === NULL){

    //         $father_contact_number = $this->ValidateFatherContactNumber(
    //             $_POST['father_contact_number'], true);
    //     }

    //     if($fieldTriggered !== "" && $father_firstname === ""){

    //         $father_firstname = $this->ValidateFatherFirstname(
    //             $_POST['father_firstname'], true);
    //     }

    //     if($fieldTriggered !== "" && $father_lastname === ""){

    //         $father_lastname = $this->ValidateFatherLastname(
    //             $_POST['father_lastname'], true);
    //     }
    // }

    public function ValidateMotherFirstname($text, $isRequired = false) {
        return Helper::FormNameValidation($text,
            Constants::$motherFirstNameRequired,
            Constants::$invalidMotherFirstNameCharacters,
            Constants::$motherFirstNameIsTooShort,
            Constants::$motherFirstNameIsTooLong, $isRequired);
    }

    public function ValidateMotherMiddlename($text, $isRequired = false) {
        return Helper::FormNameValidation($text,
            Constants::$motherMiddleNameRequired,
            Constants::$invalidMotherMiddleNameCharacters,
            Constants::$motherMiddleNameIsTooShort,
            Constants::$motherMiddleNameIsTooLong, $isRequired);
    }

    public function ValidateMotherContactNumber($text, $isRequired = false) {

        $trimmed = trim($text);

        if($isRequired){
            if (empty($text)) {
                array_push(Helper::$errorArray, Constants::$motherContactNumberRequired);
                return;
            } 
        }

        // "123!";, "Hello 1234" Not valid
        // "456"; Valid
        // Regular expression pattern to check for valid string

        $integerOfStringPattern = '/^[0-9]+$/';

        if(!empty($trimmed)){

            // Check if the input string is not exactly 11 characters long or does not contain only integers
            if (!(strlen($trimmed) === 11 || preg_match($integerOfStringPattern, $trimmed) === 1)) {
                array_push(Helper::$errorArray, Constants::$invalidMotherContactNumberCharacters);
                return;
            }

            if (!(strlen($trimmed) === 11)){
                array_push(Helper::$errorArray, Constants::$invalidMotherContactNumber2Characters);
                return;
            }

            //  09123456789"; // Valid phone number
            // "12345678901"; // Invalid phone number (doesn't start with '0')
            $numberWith11DigitPattern = '/^0[0-9]{10}$/';

            if (preg_match($numberWith11DigitPattern, $trimmed) === 1
                && preg_match($numberWith11DigitPattern, $trimmed) === 1) {

                return $trimmed;
                // return Helper::sanitizeContactNumber($trimmed);
            }else{
                array_push(Helper::$errorArray, Constants::$invalidMotherContactNumberCharacters);
                return;
            }
        }
    }

    public  function ValidateMotherOccupation($text, $isRequired = false) {

        $trimmed = trim($text);

        if($isRequired){
            if (empty($text)) {
                array_push(Helper::$errorArray, Constants::$motherOccupationRequired);
                return;
            } 
        }

        if(!(empty($text))){

            if (!preg_match("/^[a-zA-Z ]+$/", $trimmed)) {
                array_push(Helper::$errorArray, Constants::$invalidMotherOccupationCharacters);
                return;
            }

            if (strlen($trimmed) > 25) {
                array_push(Helper::$errorArray, Constants::$motherOccupationIsTooLong);
                return;
            }

            if ((strlen($trimmed) > 0 && strlen($trimmed) <= 3)) {
                array_push(Helper::$errorArray, Constants::$motherOccupationIsTooShort);
                return;
            }

            $output = Helper::sanitizeFormString($trimmed);
            return $output;

        }
        return "";
        // if(empty(Helper::$errorArray)) {

        //     // echo $trimmed;
        //     $output = Helper::sanitizeFormString($trimmed);
        //     return $output;
        // }

    }

    public function ValidateMotherEmail($userEmail, $optional = false) {

        # Two and six are the only invalid ones. 
        # Gmail (or the related G Suite for Education), will often reject examples four and five as well.
        
        // 1. john.doe@example.com
        // 2. john..doe@example.com
        // 3. x@example.com
        // 4. user@[2001:DB8::1]
        // 5. "()<>[]:,;@\\\"!#$%&'-/=?^_`{}| ~.a"@example.org
        // 6. a"b(c)d,e:f;g<h>i[j\k]l@example.com
        // 7. example@s.example
        
        if($optional === true && !empty($userEmail)){

            if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                // Regular expression pattern for email validation
                $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

                // Check if the email matches the pattern
                if (preg_match($pattern, $userEmail)) {

                    // Separate the local part and domain part of the email
                    list($localPart, $domain) = explode('@', $userEmail, 2);

                    // Check if the domain part is a valid domain (you can add more domains as needed)
                    $validDomains = array('gmail.com', 'example.com', 'yourdomain.com'); // Add more domains here

                    if (in_array(strtolower($domain), $validDomains)) {

                        // Email has the correct domain, convert the email to lowercase and return
                        $userEmail = strtolower($userEmail);
                        return trim($userEmail);
                    } else {
                        // Invalid domain, add an error message to the error array
                        array_push(Helper::$errorArray, Constants::$invalidMotherEmailCharacters);
                        return;
                    }
                } else {
                    // Invalid email format, add an error message to the error array
                    array_push(Helper::$errorArray, Constants::$invalidMotherEmailCharacters);
                    return;
                }
            } else {
                // Email is not in a valid format, add an error message to the error array
                array_push(Helper::$errorArray, Constants::$invalidMotherEmailCharacters);
                return;
            }

        }
        
        if($optional === false){

            if (empty($userEmail)) {
                array_push(Helper::$errorArray, Constants::$motherEmailRequired);
                return;
            }  

            if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                // Regular expression pattern for email validation
                $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

                // Check if the email matches the pattern
                if (preg_match($pattern, $userEmail)) {

                    // Separate the local part and domain part of the email
                    list($localPart, $domain) = explode('@', $userEmail, 2);

                    // Check if the domain part is a valid domain (you can add more domains as needed)
                    $validDomains = array('gmail.com', 'example.com', 'yourdomain.com'); // Add more domains here

                    if (in_array(strtolower($domain), $validDomains)) {

                        // Email has the correct domain, convert the email to lowercase and return
                        $userEmail = strtolower($userEmail);
                        return trim($userEmail);
                    } else {
                        // Invalid domain, add an error message to the error array
                        array_push(Helper::$errorArray, Constants::$invalidMotherEmailCharacters);
                        return;
                    }
                } else {
                    // Invalid email format, add an error message to the error array
                    array_push(Helper::$errorArray, Constants::$invalidMotherEmailCharacters);
                    return;
                }
            } else {
                // Email is not in a valid format, add an error message to the error array
                array_push(Helper::$errorArray, Constants::$invalidMotherEmailCharacters);
                return;
            }

        }
    }

    public function ValidateFatherLastName($text, $isRequired = false) {

        $trimmed = trim($text);

        $pattern = '/^[A-Za-z]+$/';

        if($isRequired == true){
            if (empty($text)) {
                array_push(Helper::$errorArray, Constants::$fatherLastNameRequired);
                return;
            } 
        }

        if(!empty($text)){

            // echo $trimmed;
            // echo "<br>";

            // if (!preg_match($pattern, $trimmed)) {
            //     array_push(Helper::$errorArray, Constants::$invalidFatherLastNameCharacters);
            //     return;
            // }
            // John doe -> John Doe
            // Exclamation Marks and others are not valid here. (!@#$%^&*()<>)
            if (!preg_match("/^[a-zA-Z ]+$/", $trimmed)) {
                array_push(Helper::$errorArray, Constants::$invalidFatherLastNameCharacters);
                // echo $text;
                return;
            }
            if ((strlen($trimmed) > 0 && strlen($trimmed) <= 1)) {
                array_push(Helper::$errorArray, Constants::$fatherLastNameIsTooShort);
                // echo $text;
                return;
            } 
            if (strlen($trimmed) > 25) {
                array_push(Helper::$errorArray, Constants::$fatherLastNameIsTooLong);
                // echo $text;
                return;
            } 

            // if(empty(Helper::$errorArray)) {
                $output = Helper::sanitizeFormString($trimmed);
                return $output;
            // }
        }
        
        # If no value returned.
        return "";

    }

    public function ValidateFatherFirstname($text, $isRequired = false) {
        return Helper::FormNameValidation($text,
            Constants::$fatherFirstNameRequired,
            Constants::$invalidFatherFirstNameCharacters,
            Constants::$fatherFirstNameIsTooShort,
            Constants::$fatherFirstNameIsTooLong, $isRequired);
    }

    public function ValidateFatherMiddlename($text, $isRequired = false) {
        return Helper::FormNameValidation($text,
            Constants::$fatherMiddleNameRequired,
            Constants::$invalidFatherMiddleNameCharacters,
            Constants::$fatherMiddleNameIsTooShort,
            Constants::$fatherMiddleNameIsTooLong, $isRequired);
    }


    public function ValidateFatherSuffix($suffix, $isRequired = false) {

        $trimmed = trim($suffix);

        $trimmed = strtoupper($trimmed);
        
        $validSuffixes = array("JR", "SR", "II", "III", "IV", "V");

        if ($isRequired && empty($trimmed)) {
            array_push(Helper::$errorArray, Constants::$fatheSuffixRequired);
            return;
        }

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
            array_push(Helper::$errorArray, Constants::$invalidFatherSuffixNameCharacters);
            return;
        }

        return "";
    }

    public function ValidateFatherContactNumber($text, $isRequired = false) {

        $trimmed = trim($text);

        if($isRequired == true){
            if (empty($text)) {
                array_push(Helper::$errorArray, Constants::$fatherContactNumberRequired);
                return;
            } 
        }

        if(!empty($trimmed)){

            // "123!";, "Hello 1234" Not valid
            // "456"; Valid
            // Regular expression pattern to check for valid string
            $integerOfStringPattern = '/^[0-9]+$/';

            // Check if the input string is not exactly 11 characters long or does not contain only integers
            if (!(strlen($trimmed) === 11 || preg_match($integerOfStringPattern, $trimmed) === 1)) {
                array_push(Helper::$errorArray, Constants::$invalidFatherContactNumberCharacters);
                return;
            }

            if (!(strlen($trimmed) === 11)){
                array_push(Helper::$errorArray, Constants::$invalidFatherContactNumber2Characters);
                return;
            }

            //  09123456789"; // Valid phone number
            // "12345678901"; // Invalid phone number (doesn't start with '0')
            $numberWith11DigitPattern = '/^0[0-9]{10}$/';

            if (preg_match($numberWith11DigitPattern, $trimmed) === 1
                && preg_match($numberWith11DigitPattern, $trimmed) === 1) {

                return $trimmed;
                // return Helper::sanitizeContactNumber($trimmed);
            }else{
                array_push(Helper::$errorArray, Constants::$invalidFatherContactNumberCharacters);
                return;
            }
        }

    }
 
    public function ValidateFatherOccupation($text, $isRequired = false) {

        $trimmed = trim($text);

        if($isRequired == true){
            if (empty($text)) {
                array_push(Helper::$errorArray, Constants::$fatherOccupationRequired);
                return;
            } 
        }
       
        if(!(empty($text))){

            if (!preg_match("/^[a-zA-Z ]+$/", $trimmed)) {
                array_push(Helper::$errorArray, Constants::$invalidFatherOccupationCharacters);
                return;
            }

            if (strlen($trimmed) > 25) {
                array_push(Helper::$errorArray, Constants::$fatherOccupationIsTooLong);
                return;
            }

            if ((strlen($trimmed) > 0 && strlen($trimmed) <= 3)) {
                array_push(Helper::$errorArray, Constants::$fatherOccupationIsTooShort);
                return;
            }

            $output = Helper::sanitizeFormString($trimmed);
            return $output;
        }
        return "";
        // if(empty(Helper::$errorArray)) {
        //     // echo $trimmed;
        //     $output = Helper::sanitizeFormString($trimmed);
        //     return $output;
        // }

    }

    public function ValidateFatherEmail($userEmail, $optional = false) {

        # Two and six are the only invalid ones. 
        # Gmail (or the related G Suite for Education), will often reject examples four and five as well.
        
        // 1. john.doe@example.com
        // 2. john..doe@example.com
        // 3. x@example.com
        // 4. user@[2001:DB8::1]
        // 5. "()<>[]:,;@\\\"!#$%&'-/=?^_`{}| ~.a"@example.org
        // 6. a"b(c)d,e:f;g<h>i[j\k]l@example.com
        // 7. example@s.example
        
        if($optional === true && !empty($userEmail)){

            if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                // Regular expression pattern for email validation
                $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

                // Check if the email matches the pattern
                if (preg_match($pattern, $userEmail)) {

                    // Separate the local part and domain part of the email
                    list($localPart, $domain) = explode('@', $userEmail, 2);

                    // Check if the domain part is a valid domain (you can add more domains as needed)
                    $validDomains = array('gmail.com', 'example.com', 'yourdomain.com'); // Add more domains here

                    if (in_array(strtolower($domain), $validDomains)) {

                        // Email has the correct domain, convert the email to lowercase and return
                        $userEmail = strtolower($userEmail);
                        return trim($userEmail);
                    } else {
                        // Invalid domain, add an error message to the error array
                        array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
                        return;
                    }
                } else {
                    // Invalid email format, add an error message to the error array
                    array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
                    return;
                }
            } else {
                // Email is not in a valid format, add an error message to the error array
                array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
                return;
            }

        }
        
        if($optional === false){

            if (empty($userEmail)) {
                array_push(Helper::$errorArray, Constants::$fatherEmailRequired);
                return;
            }  

            if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                // Regular expression pattern for email validation
                $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

                // Check if the email matches the pattern
                if (preg_match($pattern, $userEmail)) {

                    // Separate the local part and domain part of the email
                    list($localPart, $domain) = explode('@', $userEmail, 2);

                    // Check if the domain part is a valid domain (you can add more domains as needed)
                    $validDomains = array('gmail.com', 'example.com', 'yourdomain.com'); // Add more domains here

                    if (in_array(strtolower($domain), $validDomains)) {

                        // Email has the correct domain, convert the email to lowercase and return
                        $userEmail = strtolower($userEmail);
                        return trim($userEmail);
                    } else {
                        // Invalid domain, add an error message to the error array
                        array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
                        return;
                    }
                } else {
                    // Invalid email format, add an error message to the error array
                    array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
                    return;
                }
            } else {
                // Email is not in a valid format, add an error message to the error array
                array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
                return;
            }

        }
    }

    public function ValidateGuardianLastName($text, $isRequired = false) {

        $trimmed = trim($text);
        $minLength = 2;
        $maxLength = 25;

        // If the field is required and the text is empty, return an error message
        if ($isRequired && empty($trimmed)) {
            array_push(Helper::$errorArray, Constants::$guardianLastNameRequired);
            return;
        }

        if(!empty($text)){

            $pattern = '/^[A-Za-z]+$/';
            // Surname Extra name -> Doe Doe
            // if (!preg_match($pattern, $trimmed)) {
            //         array_push(Helper::$errorArray, Constants::$invalidGuardianLastNameCharacters);
            //         return;
            //     }
            // Check if the text contains only letters and spaces
            if (!preg_match("/^[a-zA-Z ]+$/", $trimmed)) {
                array_push(Helper::$errorArray, Constants::$invalidGuardianLastNameCharacters);
                return;
            }

            // Check the text length
            $length = strlen($trimmed);
            if ($length < $minLength) {
                array_push(Helper::$errorArray, Constants::$guardianLastNameIsTooShort);
                return;
            } elseif ($length > $maxLength) {
                array_push(Helper::$errorArray, Constants::$guardianLastNameIsTooLong);
                return;
            }

            $output = Helper::sanitizeFormString($trimmed);
            return $output;
        }

        // If there are no errors, sanitize the input and return the output
        // if (empty(Helper::$errorArray)) {
        //     $output = Helper::sanitizeFormString($text);
        //     return $output;
        // }
        
        return "";
    }

    public function DoesGuardianNonRequiredFieldsValid($inputFields,
        $field1, $field2, $field3, $field4) {

        if($inputFields !== ""
                && ($field1 === "" || $field2 === ""
                    || $field3 === "" || $field4 === "")){
            return false;
        }
        return true;
    }


    public function ValidateGuardianFirstname($text, $isRequired = false) {

        return Helper::FormNameValidation($text,
            Constants::$guardianFirstNameRequired,
            Constants::$invalidGuardianFirstNameCharacters,
            Constants::$guardianFirstNameIsTooShort,
            Constants::$guardianFirstNameIsTooLong,
            $isRequired);

    }

    public function ValidateGuardianMiddlename($text) {

        return Helper::FormNameValidation($text,
            Constants::$guardianMiddleNameRequired,
            Constants::$invalidGuardianMiddleNameCharacters,
            Constants::$guardianMiddleNameIsTooShort,
            Constants::$guardianMiddleNameIsTooLong, false);

    }

    public function ValidateGuardianSuffix($suffix) {

        $trimmed = trim($suffix);

        $trimmed = strtoupper($trimmed);
        
        $validSuffixes = array("JR", "SR", "II", "III", "IV", "V");

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
            array_push(Helper::$errorArray, Constants::$invalidGuardianSuffixNameCharacters);
            return $trimmed;
        }
    }


    public function ValidateGuardianContactNumber($text, $isRequired = false) {

        $trimmed = trim($text);

        if($isRequired && empty($text)){
            array_push(Helper::$errorArray, Constants::$guardianContactNumberRequired);
            return;
        }
       
        // "123!";, "Hello 1234" Not valid
        // "456"; Valid
        // Regular expression pattern to check for valid string

        if(!empty($text)){

            $integerOfStringPattern = '/^[0-9]+$/';

            // Check if the input string is not exactly 11 characters long or does not contain only integers
            if (!(strlen($trimmed) === 11 || preg_match($integerOfStringPattern, $trimmed) === 1)) {
                array_push(Helper::$errorArray, Constants::$invalidGuardianContactNumberCharacters);
                return;
            }

            if (!(strlen($trimmed) === 11)){
                array_push(Helper::$errorArray, Constants::$invalidGuardianContactNumber2Characters);
                return;
            }

            //  09123456789"; // Valid phone number
            // "12345678901"; // Invalid phone number (doesn't start with '0')
            $numberWith11DigitPattern = '/^0[0-9]{10}$/';

            if (preg_match($numberWith11DigitPattern, $trimmed) === 1
                && preg_match($numberWith11DigitPattern, $trimmed) === 1) {

                return $trimmed;
                // return Helper::sanitizeContactNumber($trimmed);
            }else{
                array_push(Helper::$errorArray, Constants::$invalidGuardianContactNumberCharacters);
                return;
            }
        }

    }
 
    public function ValidateGuardianOccupation($text, $isRequired = false) {
        

        $trimmed = trim($text);

        if ($isRequired && empty($text)) {
            array_push(Helper::$errorArray, Constants::$guardianOccupationRequired);
            return;
        } 

        if(!(empty($text))){

            if (!preg_match("/^[a-zA-Z ]+$/", $trimmed)) {
                array_push(Helper::$errorArray, Constants::$invalidGuardianOccupationCharacters);
                return;
            }

            if (strlen($trimmed) > 25) {
                array_push(Helper::$errorArray, Constants::$guardianOccupationIsTooLong);
                return;
            }

            if ((strlen($trimmed) > 0 && strlen($trimmed) <= 3)) {
                array_push(Helper::$errorArray, Constants::$guardianOccupationIsTooShort);
                return;
            }

            $output = Helper::sanitizeFormString($trimmed);
            return $output;

        }
       
        // if(empty(Helper::$errorArray)) {

        //     // echo $trimmed;
        //     $output = Helper::sanitizeFormString($trimmed);
        //     return $output;
        // }

        return "";

    }

    public function ValidateGuardianEmail($userEmail, $isRequired = false) {

        # Two and six are the only invalid ones. 
        # Gmail (or the related G Suite for Education), will often reject examples four and five as well.
        
        // 1. john.doe@example.com
        // 2. john..doe@example.com
        // 3. x@example.com
        // 4. user@[2001:DB8::1]
        // 5. "()<>[]:,;@\\\"!#$%&'-/=?^_`{}| ~.a"@example.org
        // 6. a"b(c)d,e:f;g<h>i[j\k]l@example.com
        // 7. example@s.example
        
        // if($isRequired === true && !empty($userEmail)){

        //     if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        //         // Regular expression pattern for email validation
        //         $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        //         // Check if the email matches the pattern
        //         if (preg_match($pattern, $userEmail)) {

        //             // Separate the local part and domain part of the email
        //             list($localPart, $domain) = explode('@', $userEmail, 2);

        //             // Check if the domain part is a valid domain (you can add more domains as needed)
        //             $validDomains = array('gmail.com', 'example.com', 'yourdomain.com'); // Add more domains here

        //             if (in_array(strtolower($domain), $validDomains)) {

        //                 // Email has the correct domain, convert the email to lowercase and return
        //                 $userEmail = strtolower($userEmail);
        //                 return trim($userEmail);
        //             } else {
        //                 // Invalid domain, add an error message to the error array
        //                 array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
        //                 return;
        //             }
        //         } else {
        //             // Invalid email format, add an error message to the error array
        //             array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
        //             return;
        //         }
        //     } else {
        //         // Email is not in a valid format, add an error message to the error array
        //         array_push(Helper::$errorArray, Constants::$invalidFatherEmailCharacters);
        //         return;
        //     }

        // }


        if ($isRequired && empty($userEmail)) {
            array_push(Helper::$errorArray, Constants::$guardianEmailRequired);
            return;
        }  

        if(!empty($userEmail)){
            if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                
                // Regular expression pattern for email validation
                $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

                // Check if the email matches the pattern
                if (preg_match($pattern, $userEmail)) {

                    // Separate the local part and domain part of the email
                    list($localPart, $domain) = explode('@', $userEmail, 2);

                    // Check if the domain part is a valid domain (you can add more domains as needed)
                    $validDomains = array('gmail.com', 'example.com', 'yourdomain.com'); // Add more domains here

                    if (in_array(strtolower($domain), $validDomains)) {

                        // Email has the correct domain, convert the email to lowercase and return
                        $userEmail = strtolower($userEmail);
                        return trim($userEmail);
                    } else {
                        // Invalid domain, add an error message to the error array
                        array_push(Helper::$errorArray, Constants::$invalidGuardianEmailCharacters);
                        return;
                    }
                } else {
                    // Invalid email format, add an error message to the error array
                    array_push(Helper::$errorArray, Constants::$invalidGuardianEmailCharacters);
                    return;
                }
            } else {
                // Email is not in a valid format, add an error message to the error array
                array_push(Helper::$errorArray, Constants::$invalidGuardianEmailCharacters);
                return;
            }
        }


    }

 
    public function ValidateGuardianRelationship($text, $isRequired = false) {

        return Helper::FormNameValidation($text,
            Constants::$guardianRelationshipRequired,
            Constants::$invalidGuardianRelationshipCharacters,
            Constants::$guardianRelationshipIsTooShort,
            Constants::$guardianRelationshipIsTooLong,
            $isRequired);

    }

    public function RemovingParentOfNewStudent($student_id){

        $delete = $this->con->prepare("DELETE FROM parent 
            WHERE student_id = :student_id
            ");

        $delete->bindParam(":student_id", $student_id);
        $delete->execute();

        if($delete->rowCount() > 0){
           return true;
        }
        return false;
    }

}

?>