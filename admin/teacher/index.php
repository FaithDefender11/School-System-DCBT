<?php 

  include_once('../../includes/admin_header.php');
  include_once('../../includes/classes/Teacher.php');
 
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
              <th>Subject load</th>
              <th>Hours per week</th>
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
                  $teacher_status = $row['teacher_status'];
                  $date_creation = $row['date_creation'];

                  $date_creation = $date_creation !== NULL ? date('Y-m-d', strtotime($date_creation)) : 'Not Set';

                  $fullname = $row['firstname'] . " " . $row['lastname'];

                  $subject_load_count = $teacher->GetTeacherSubjectLoad($teacher_id);

                  echo "
                    <tr>
                      <td>$teacher_id</td>
                      <td>
                        <a style:'color: black;' href='edit.php?id=$teacher_id'>
                          $fullname
                        </a>

                      </td>
                      <td>$subject_load_count</td>
                      <td></td>
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


