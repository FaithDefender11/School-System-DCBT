

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');

    require_once __DIR__ . '../../../vendor/autoload.php';

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');
  
?>
  

<div class="content">
    <main>

        <div class="floating" id="shs-sy">

            <header>
                <div class="title">
                    <h3 style="font-weight: bold;">Users Logs
                         
                    </h3>
                </div>
            </header>

            <main>

                <table id="" class="a" style="margin: 0">

                    <thead>
                        <tr class="text-center"> 
                            <th>Role</th>  
                            <th>Description</th>  
                        </tr>	
                    </thead>

                    <tbody>
                        <?php 

                            $query = $con->prepare("SELECT t1.* 
                            
                                FROM users_log AS t1
                            ");

                            $query->execute();

                            if($query->rowCount() > 0){
                                
                                $enrollment = new Enrollment($con);

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $role = $row['role'];
                                    $users_log_id = $row['users_log_id'];
                                    $description = $row['description'];

                                  

                                    echo "
                                        <tr>
                                            <td>$role</td>
                                            <td>$description</td>
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

