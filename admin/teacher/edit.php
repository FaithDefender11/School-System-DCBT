<?php 

  include_once('../../includes/admin_header.php');
  include_once('../../includes/classes/Teacher.php');
      include_once('../../includes/classes/SchoolYear.php');
 
  if(isset($_GET['id'])){

    $teacher_id = $_GET['id'];

    $teacher = new Teacher($con, $teacher_id);

    $firstname = $teacher->GetTeacherFirstName();
    $middle_name = $teacher->GetTeacherMiddleName();
    $lastname = $teacher->GetTeacherLastName();
    $suffix = $teacher->GetTeacherSuffix();
    $department_id = $teacher->GetDepartmentId();
    $profilePic = $teacher->GetTeacherProfile();
    $gender = $teacher->GetTeacherGender();
    $email = $teacher->GetTeacherEmail();
    $contact_number = $teacher->GetTeacherContactNumber();
    $address = $teacher->GetTeacherAddress();
    $citizenship = $teacher->GetTeacherCitizenship();
    $birthplace = $teacher->GetTeacherBirthplace();
    $birthday = $teacher->GetTeacherBirthday();
    $religion = $teacher->GetTeacherReligion();
    $status = $teacher->GetStatus();

     
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];


    $form = $teacher->editTeacherForm(
        $firstname,
        $middle_name,
        $lastname,
        $suffix,
        $department_id,
        $profilePic,
        $gender,
        $email,
        $contact_number,
        $address,
        $citizenship,
        $birthplace,
        $birthday,
        $religion,
        $status
    );

    echo "
        <div class='col-md-10 row offset-md-1'>
            $form
        </div>
    ";
}
?>

