<?php 

  include_once('../../includes/admin_header.php');
  include_once('../../includes/classes/Teacher.php');
  include_once('../../includes/classes/Department.php');
 
  $teacher = new Teacher($con, null);

?>
    

<div class="content">
  <div class="tabs">
    <?php
        echo "
            <button class='tab' 
                style='background-color: var(--mainContentBG); color: black'
                onclick=\"window.location.href = 'index.php';\">
                <i class='bi bi-clipboard-check'></i>
                Teacher List
            </button>
        ";

        echo "
            <button class='tab' 
                id='shsPayment'
                style='background-color: var(--theme); color: white'
                onclick=\"window.location.href = 'subject_schedule.php';\">
                <i class='bi bi-book'></i>
                Teacher Schedule
            </button>
        ";
    ?>
  </div>


  

  <!--SHS-TEACHERS-->
  <main>
    <div class="floating">
      <header>
        <div class="title">
          <h3>Teachers</h3>
        </div>
        <div class="action">
          <a href="create.php">
            <button type="button" class="default large">+ Add new</button>
          </a>
        </div>
      </header>
      <main>
        <table
          class="a"
          style="margin: 0"
        >
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Teaching Subjects</th>
              <th>Department</th>
              <th>Status</th>
              <th>Date added</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $query = $con->prepare("SELECT * FROM teacher as t1
                
                ");

              $query->execute();
              if($query->rowCount() > 0){

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                  $teacher_id = $row['teacher_id'];
                  $school_teacher_id = $row['school_teacher_id'];

                  $department_id = $row['department_id'];

                  $department = new Department($con, $department_id);

                  $departmentName = $department->GetDepartmentName();

                  if($departmentName == "Senior High School"){
                    $departmentName = "SHS";
                  }

                  $teacher_status = $row['teacher_status'];
                  if($teacher_status == "inactive"){
                    $teacher_status = "In-active";
                  }
                  $date_creation = $row['date_creation'];

                  $date_creation = $date_creation !== NULL ? date('Y-m-d', strtotime($date_creation)) : 'Not Set';

                  $fullname = $row['firstname'] . " " . $row['middle_name'] . " " . $row['lastname'];

                  $subject_load_count = $teacher->GetTeacherSubjectLoad($teacher_id);

                  echo "
                    <tr>
                      <td>$school_teacher_id</td>
                      <td>
                        <a style='color: black;' href='edit.php?id=$teacher_id'>
                          $fullname
                        </a>
                      </td>
                      <td>$subject_load_count</td>
                      <td>$departmentName</td>
                      <td>$teacher_status</td>
                      <td>$date_creation</td>
                      <td>
                          <button type='button' 
                            onclick=\"window.location.href='info.php?details=show&id=$teacher_id'\"
                            class='information'>View</button>
                      </td>
                    </tr>
                  ";

                }
              }

            ?>
          </tbody>
        </table>
      </main>
    </div>
  </main>

</div>

<?php include_once('../../includes/footer.php')?>


