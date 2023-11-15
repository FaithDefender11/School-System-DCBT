<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Announcement.php');


    echo Helper::RemoveSidebar();


    if(isset($_GET['id'])){

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_announcement_id = $_GET['id'];

        $teacherAnnouncement = new Announcement($con, $teacher_announcement_id);

        $title = $teacherAnnouncement->GetTitle();
        $content = $teacherAnnouncement->GetContent();
        $subject_code = $teacherAnnouncement->GetSubjectCode();
        $creation = $teacherAnnouncement->GetDateCreation();
        $creation = date("F d, Y h:i a", strtotime($creation));


        $back_url = "subject_announcement.php?id=$teacher_announcement_id";

        ?>
        <div class="content">
            <!-- <nav>
                <a href="<?php echo $back_url;?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav> -->
            <main>
                <div class="floating" id="shs-sy">
                    <header>
                        <div class="title">
                            <h3>Whos viewed</h3>
                        </div>
                    </header>
                    
                    <main>

                        <table id="department_table" class="a" style="margin: 0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    $stud = $con->prepare("SELECT 
                                        t3.firstname
                                        ,t3.lastname
                                        ,t3.student_unique_id
                                        ,t3.student_id

                                        FROM student_subject as t1
                                        INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
                                        AND t2.enrollment_status = 'enrolled'

                                        INNER JOIN student as t3 ON t3.student_id = t2.student_id


                                        WHERE t1.subject_code=:subject_code
                                        AND t1.school_year_id=:school_year_id

                                        GROUP BY t3.student_id
                                        ORDER BY t3.lastname 
                                        
                                    ");

                                    $stud->bindParam(":subject_code", $subject_code);
                                    $stud->bindParam(":school_year_id", $current_school_year_id);
                                    $stud->execute();

                                    if($stud->rowCount() > 0){

                                        while($row_stud = $stud->fetch(PDO::FETCH_ASSOC)){
                                            
                                            $student_id = $row_stud['student_id'];
                                            $firstname = $row_stud['firstname'];
                                            $lastname = $row_stud['lastname'];

                                            // $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                            $fullname = ucwords($lastname) . ", " . ucwords($firstname);

                                            $student_view_obj = $teacherAnnouncement->GetStudentViewedAnnouncementId($teacher_announcement_id);
                                            
                                            $status = "
                                                <i style='color: orange' class='fas fa-times'></i>
                                            ";


                                           
                                            if($student_view_obj !== NULL){

                                                $studentViewedAnnouncementIds = $student_view_obj['announcement_id'];
                                                $student_date_viewed = $student_view_obj['date_viewed'];
                                                $student_date_viewed = date("M d, Y h:i a", strtotime($student_date_viewed));
                                                $student_viewed_id = $student_view_obj['student_id'];


                                                if($student_id == $student_viewed_id){
                                                    $status =  $student_date_viewed;

                                                }


                                            }

                                            echo "
                                                <tr>
                                                    <td style='font-size: 15px'>
                                                        $fullname
                                                    </td>
                                                    <td>$status</td>
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
        <?php
    }
?>