<?php

    class Teacher{

        private $con, $teacher_id, $sqlData;

        public function __construct($con, $teacher_id = null){
            $this->con = $con;
            $this->teacher_id = $teacher_id;

            $query = $this->con->prepare("SELECT * FROM teacher
                 WHERE teacher_id=:teacher_id");

            $query->bindValue(":teacher_id", $teacher_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

            if($this->sqlData == null){

                $query = $this->con->prepare("SELECT * FROM teacher
                 WHERE username=:username");

                $query->bindValue(":username", $teacher_id);
                $query->execute();

                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }

        public function GetTeacherId() {
            return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : "qwe"; 
        }

        public function GetTeacherFirstName() {
            return isset($this->sqlData['firstname']) ? ucfirst($this->sqlData["firstname"]) : ""; 
        }

        public function GetTeacherMiddleName() {
            return isset($this->sqlData['middle_name']) ? ucfirst($this->sqlData["middle_name"]) : ""; 
        }

        public function GetTeacherLastName() {
            return isset($this->sqlData['lastname']) ? ucfirst($this->sqlData["lastname"]) : ""; 
        }
        public function GetTeacherSuffix() {
            return isset($this->sqlData['suffix']) ? ucfirst($this->sqlData["suffix"]) : ""; 
        }
        public function GetStatus() {
            return isset($this->sqlData['teacher_status']) ? ucfirst($this->sqlData["teacher_status"]) : ""; 
        }     

        public function GetDepartmentId() {
            return isset($this->sqlData['department_id']) ?  $this->sqlData["department_id"] : ""; 
        }  

        public function GetTeacherProfile() {
            return isset($this->sqlData['profilePic']) ? $this->sqlData["profilePic"] : ""; 
        }   

        public function GetCreation() {
            return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : ""; 
        }  
                
        public function GetDepartmentName() {

            $department_id = $this->GetDepartmentId();
            $sql = $this->con->prepare("SELECT department_name FROM department
                WHERE department_id=:department_id");
            
            $sql->bindValue(":department_id", $department_id);
            $sql->execute();
            if($sql->rowCount() > 0){

                $name = $sql->fetchColumn();
                return ucfirst($name);
            }
            return "N/A";

        } 

        public function GetTeacherFullName() {
           $firstname = $this->GetTeacherFirstName();
           $lastname = $this->GetTeacherLastName();
           return $firstname . " " . $lastname;
        }

        public function GetTeacherReligion() {
            return isset($this->sqlData['religion']) ? ucfirst($this->sqlData["religion"]) : ""; 
        }

        public function GetTeacherAddress() {
            return isset($this->sqlData['address']) ? ucfirst($this->sqlData["address"]) : ""; 
        }

        public function GetTeacherEmail() {
            return isset($this->sqlData['email']) ? $this->sqlData["email"] : ""; 
        }


        public function GetTeacherCitizenship() {
            return isset($this->sqlData['citizenship']) ? ucfirst($this->sqlData["citizenship"]) : ""; 
        }


        public function GetTeacherContactNumber() {
            return isset($this->sqlData['contact_number']) ? ucfirst($this->sqlData["contact_number"]) : ""; 
        }

        public function GetTeacherBirthplace() {
            return isset($this->sqlData['birthplace']) ? ucfirst($this->sqlData["birthplace"]) : ""; 
        }

        public function GetTeacherBirthday() {
            return isset($this->sqlData['birthday']) ? ucfirst($this->sqlData["birthday"]) : ""; 
        }

        public function GetTeacherGender() {
            return isset($this->sqlData['gender']) ? ucfirst($this->sqlData["gender"]) : ""; 
        }

        public function createTeacherForm(){

            $department_selection = $this->CreateTeacherDepartmentSelection();

            if(isset($_POST['create_teacher_btn'])){

                $firstname = $_POST['firstname'];
                $middle_name = $_POST['middle_name'];
                $lastname = $_POST['lastname'];
                $suffix = $_POST['suffix'];
                $department_id = $_POST['department_id'];
                // $profilePic = $_POST['profilePic'];
                $profilePic = "";
                $gender = $_POST['gender'];
                $email = $_POST['email'];
                $contact_number = $_POST['contact_number'];
                $address = $_POST['address'];
                $citizenship = $_POST['citizenship'];
                $birthplace = $_POST['birthplace'];
                $birthday = $_POST['birthday'];
                $religion = $_POST['religion'];
                $status = "active";

                $image = $_FILES['profilePic'] ?? null;
                $imagePath = '';

                if (!is_dir('../../assets')) {
                    mkdir('../../assets');
                }

                if (!is_dir('../../assets/images')) {
                    mkdir('../../assets/images');
                }

                if (!is_dir('../../assets/images/teacher_profiles')) {
                    mkdir('../../assets/images/teacher_profiles');
                }

                if ($image && $image['tmp_name']) {

                    $uploadDirectory = '../../assets/images/teacher_profiles/';
                    $originalFilename = $image['name'];

                    $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                    $targetPath = $uploadDirectory . $uniqueFilename;

                    move_uploaded_file($image['tmp_name'], $targetPath);

                    $imagePath = $targetPath;

                    // Remove Directory Path in the Database.
                    $imagePath = str_replace('../../', '', $imagePath);

                }

                // Prepare and execute the statement

                $query = "INSERT INTO teacher (firstname, middle_name, lastname, suffix, department_id, profilePic, gender, email, contact_number,
                        address, citizenship, birthplace, birthday, religion, teacher_status) 

                    VALUES (:firstname, :middle_name, :lastname, :suffix, :department_id, :profilePic, :gender, :email, :contact_number,
                        :address, :citizenship, :birthplace, :birthday, :religion, :teacher_status)";

                $statement = $this->con->prepare($query);

                $statement->bindParam(':firstname', $firstname);
                $statement->bindParam(':middle_name', $middle_name);
                $statement->bindParam(':lastname', $lastname);
                $statement->bindParam(':suffix', $suffix);
                $statement->bindParam(':department_id', $department_id);
                $statement->bindParam(':profilePic', $imagePath);
                $statement->bindParam(':gender', $gender);
                $statement->bindParam(':email', $email);
                $statement->bindParam(':contact_number', $contact_number);
                $statement->bindParam(':address', $address);
                $statement->bindParam(':citizenship', $citizenship);
                $statement->bindParam(':birthplace', $birthplace);
                $statement->bindParam(':birthday', $birthday);
                $statement->bindParam(':religion', $religion);
                $statement->bindParam(':teacher_status', $status);

                // Execute the statement
                if ($statement->execute()) {

                    Alert::success("Successfully Created", "index.php");
                    exit();
                } else {

                    Alert::error("Successfully Created", "index.php");
                    exit();
                }
            }

            return "
                <div class='card'>
                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Create Teacher</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>

                            <div class='form-group mb-2'>
                                <label for=''>First Name</label>
                                <input class='form-control' type='text' placeholder='' name='firstname'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Middle Name</label>
                                <input class='form-control' placeholder='' name='middle_name'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Last Name</label>
                                <input class='form-control' type='text' placeholder='' name='lastname'>
                            </div>
                    
                            <div class='form-group mb-2'>
                                <label for=''>Suffix</label>
                                <input class='form-control' type='text' placeholder='' name='suffix'>
                            </div>

                            <div class='form-group mb-2'>
                                $department_selection
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Profile Pic</label>
                                <input class='form-control' type='file' placeholder='' name='profilePic'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Gender</label>
                                <select class='form-control' required name='gender' id='gender'>
                                    <option value='Male'>Male</option>
                                    <option value='Female'>Female</option>
                                 </select>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Email</label>
                                <input class='form-control' type='text' placeholder='' name='email'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Contact No.</label>
                                <input class='form-control' type='text' placeholder='' name='contact_number'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Address</label>
                                <input class='form-control' type='text' placeholder='' name='address'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Citizenship</label>
                                <input class='form-control' type='text' placeholder='' name='citizenship'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Birth Place</label>
                                <input class='form-control' type='text' placeholder='' name='birthplace'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Birth Date</label>
                                <input class='form-control' type='date' placeholder='' name='birthday'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Religion</label>
                                <input class='form-control' type='text' placeholder='' name='religion'>
                            </div>
        
                            <button type='submit' class='btn btn-success'
                                name='create_teacher_btn'>Save</button>

                        </form>
                    </div>
                </div>

            ";
            

        }
        public function createTeacherFormFunction(){

            $firstname = $_POST['firstname'];
            $middle_name = $_POST['middle_name'];
            $lastname = $_POST['lastname'];
            $suffix = $_POST['suffix'];
            $department_id = $_POST['department_id'];
            // $profilePic = $_POST['profilePic'];
            $profilePic = "";
            $gender = $_POST['gender'];
            $email = $_POST['email'];
            $contact_number = $_POST['contact_number'];
            $address = $_POST['address'];
            $citizenship = $_POST['citizenship'];
            $birthplace = $_POST['birthplace'];
            $birthday = $_POST['birthday'];
            $religion = $_POST['religion'];
            $status = "active";

            $image = $_FILES['profilePic'] ?? null;
            $imagePath = '';

            if (!is_dir('../../assets')) {
                mkdir('../../assets');
            }

            if (!is_dir('../../assets/images')) {
                mkdir('../../assets/images');
            }

            if (!is_dir('../../assets/images/teacher_profiles')) {
                mkdir('../../assets/images/teacher_profiles');
            }

            if ($image && $image['tmp_name']) {

                $uploadDirectory = '../../assets/images/teacher_profiles/';
                $originalFilename = $image['name'];

                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                move_uploaded_file($image['tmp_name'], $targetPath);

                $imagePath = $targetPath;

                // Remove Directory Path in the Database.
                $imagePath = str_replace('../../', '', $imagePath);

            }

            // Prepare and execute the statement

            $query = "INSERT INTO teacher (firstname, middle_name, lastname, suffix, department_id, profilePic, gender, email, contact_number,
                    address, citizenship, birthplace, birthday, religion, teacher_status) 

                VALUES (:firstname, :middle_name, :lastname, :suffix, :department_id, :profilePic, :gender, :email, :contact_number,
                    :address, :citizenship, :birthplace, :birthday, :religion, :teacher_status)";

            $statement = $this->con->prepare($query);

            $statement->bindParam(':firstname', $firstname);
            $statement->bindParam(':middle_name', $middle_name);
            $statement->bindParam(':lastname', $lastname);
            $statement->bindParam(':suffix', $suffix);
            $statement->bindParam(':department_id', $department_id);
            $statement->bindParam(':profilePic', $imagePath);
            $statement->bindParam(':gender', $gender);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':contact_number', $contact_number);
            $statement->bindParam(':address', $address);
            $statement->bindParam(':citizenship', $citizenship);
            $statement->bindParam(':birthplace', $birthplace);
            $statement->bindParam(':birthday', $birthday);
            $statement->bindParam(':religion', $religion);
            $statement->bindParam(':teacher_status', $status);

            // Execute the statement
            if ($statement->execute()) {

                Alert::success("Successfully Created", "index.php");
                exit();
            } else {

                Alert::error("Successfully Created", "index.php");
                exit();
            }
        }
        public function editTeacherForm(
            $firstname,
            $middle_name,
            $lastname, $suffix,$department_id,$profilePic,
            $gender,$email, $contact_number, $address,$citizenship,
            $birthplace,$birthday,$religion,$status, $teacher_id){

            $department_selection = $this->CreateTeacherDepartmentSelection($department_id);

            if(isset($_POST['edit_teacher_btn'])){

                $firstname = $_POST['firstname'];
                $middle_name = $_POST['middle_name'];
                $lastname = $_POST['lastname'];
                $suffix = $_POST['suffix'];
                $department_id = $_POST['department_id'];
                // $profilePic = $_POST['profilePic'];
                $profilePic = "";
                $gender = $_POST['gender'];
                $email = $_POST['email'];
                $contact_number = $_POST['contact_number'];
                $address = $_POST['address'];
                $citizenship = $_POST['citizenship'];
                $birthplace = $_POST['birthplace'];
                $birthday = $_POST['birthday'];
                $religion = $_POST['religion'];

                // echo $status;

                $image = $_FILES['profilePic'] ?? null;
                
                $imagePath = '';

                // if (!is_dir('../../assets')) {
                //     mkdir('../../assets');
                // }

                // if (!is_dir('../../assets/images')) {
                //     mkdir('../../assets/images');
                // }

                // if (!is_dir('../../assets/images/teacher_profiles')) {
                //     mkdir('../../assets/images/teacher_profiles');
                // }

                $query = "UPDATE teacher SET 
                    firstname = :firstname,
                    middle_name = :middle_name,
                    lastname = :lastname,
                    suffix = :suffix,
                    department_id = :department_id,
                    profilePic = :profilePic,
                    gender = :gender,
                    email = :email,
                    contact_number = :contact_number,
                    address = :address,
                    citizenship = :citizenship,
                    birthplace = :birthplace,
                    birthday = :birthday,
                    religion = :religion,
                    teacher_status = :teacher_status

                WHERE teacher_id = :teacher_id";

                $update = $this->con->prepare($query);

                $update->bindParam(':firstname', $firstname);
                $update->bindParam(':middle_name', $middle_name);
                $update->bindParam(':lastname', $lastname);
                $update->bindParam(':suffix', $suffix);
                $update->bindParam(':department_id', $department_id);
                $update->bindParam(':profilePic', $imagePath);
                $update->bindParam(':gender', $gender);
                $update->bindParam(':email', $email);
                $update->bindParam(':contact_number', $contact_number);
                $update->bindParam(':address', $address);
                $update->bindParam(':citizenship', $citizenship);
                $update->bindParam(':birthplace', $birthplace);
                $update->bindParam(':birthday', $birthday);
                $update->bindParam(':religion', $religion);
                $update->bindParam(':teacher_status', $status);
                $update->bindParam(':teacher_id', $teacher_id);

                if ($update->execute()) {
                    Alert::success("Successfully Edited", "index.php");
                }

            }

            return "
                <div class='card'>

                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Edit Teacher</h4>
                    </div>

                    <div class='card-body'>
                        
                        <form method='POST' enctype='multipart/form-data'>


                            <div class='form-group mb-2'>
                                <label for=''>First Name</label>
                                <input class='form-control' type='text' value='$firstname' name='firstname'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Middle Name</label>
                                <input class='form-control' value='$middle_name' name='middle_name'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Last Name</label>
                                <input class='form-control' type='text' value='$lastname' name='lastname'>
                            </div>
                    
                            <div class='form-group mb-2'>
                                <label for=''>Suffix</label>
                                <input class='form-control' type='text' value='$suffix' name='suffix'>
                            </div>

                            <div class='form-group mb-2'>
                                $department_selection
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Profile Pic</label>
                                <input class='form-control' type='file' value='' name='profilePic'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Gender</label>
                                <select class='form-control' required name='gender' id='gender'>
                                    <option value='Male'" . ($gender === 'Male' ? ' selected' : '') . ">Male</option>
                                    <option value='Female'" . ($gender === 'Female' ? ' selected' : '') . ">Female</option>
                                 </select>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Email</label>
                                <input class='form-control' type='text' value='$email' name='email'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Contact No.</label>
                                <input class='form-control' type='text' value='$contact_number' name='contact_number'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Address</label>
                                <input class='form-control' type='text' value='$address' name='address'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Citizenship</label>
                                <input class='form-control' type='text' value='$citizenship' name='citizenship'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Birth Place</label>
                                <input class='form-control' type='text' value='$birthplace' name='birthplace'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Birth Date</label>
                                <input class='form-control' type='date' value='$birthday' name='birthday'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Religion</label>
                                <input class='form-control' type='text' value='$religion' name='religion'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Status</label>
                                <div>
                                    <label>
                                        <input type='radio' name='status' value='active' " . ($status === 'Active' ? 'checked' : '') . "> Active
                                    </label>
                                    <label>
                                        <input type='radio' name='status' value='non-active' " . ($status === 'Inactive' ? 'checked' : '') . "> Inctive
                                    </label>
                                </div>
                            </div>
                            <button type='submit' class='btn btn-success'
                                name='edit_teacher_btn'>Save</button>

                        </form>
                    </div>
                </div>

            ";
            

        }

        public function CreateTeacherDepartmentSelection($department_id = null){

            $SHS = "Senior High School";
            $TERTIARY = "Tertiary";

            $query = $this->con->prepare("SELECT * FROM department
                WHERE department_name =:shs
                OR department_name =:tertiary
                ");

            $query->bindParam(":shs", $SHS);
            $query->bindParam(":tertiary", $TERTIARY);
            $query->execute();

            $html = "<div class='form-group'>
                        <label for=''>Department</label>
                        <select class='form-control' name='department_id' required>
                            <option value=''>Choose Department</option>";

            if ($query->rowCount() > 0) {

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                
                    $row_department_id = $row['department_id'];

                    $selected = ($row_department_id == $department_id) ? 'selected' : '';

                    $html .= "
                        <option $selected value='" . $row['department_id'] . "'>" . $row['department_name'] . "</option>
                    ";
                }
            }
            $html .= "</select>
                    </div>";

            return $html;
        }

        public function GetTeacherSubjectLoad($teacher_id){

            $query = $this->con->prepare("SELECT * FROM teacher as t1

                INNER JOIN subject_schedule as t2 ON t2.teacher_id = t1.teacher_id

                WHERE t2.teacher_id = :teacher_id
                ");

            $query->bindParam(":teacher_id", $teacher_id);
            $query->execute();

            return $query->rowCount();

        }

    }
?>