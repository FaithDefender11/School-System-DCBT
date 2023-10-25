
<script src="./preferred_course.js"></script>

<?php 

    
    // if(isset($_SESSION['modal_gatepass'])){
    //     echo $_SESSION['modal_gatepass'];
    // }

    // echo $pending_enrollees_id;

    
    $studentRequirement = new StudentRequirement($con);
    
    $student_requirement_id = $studentRequirement->GetStudentRequirement(
        $pending_enrollees_id,
        $school_year_id);

    if($student_requirement_id == NULL){

        # Create.
        $initNewEnrolleeStudentRequirement = $studentRequirement
            ->InitializedPendingEnrolleeRequirement($pending_enrollees_id,
            $school_year_id);
        
    }
?>

<style>
    <?php include "../../assets/css/student-form-responsive.css";  ?>
</style>
<div class="content">

    <!-- <div class="container mt-5">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            Open Modal
        </button>
    </div> -->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Terms and Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>DATA PRIVACY NOTICE</h5>
                    <div class="form-check">
                        <input type="checkbox"  class="form-check-input" id="agreeCheckbox">
                        <label class="form-check-label" for="agreeCheckbox">I agree to the Data Privacy Notice</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    <button type="button" disabled class="btn btn-primary" id="nextButton">Next</button>
                </div>

            </div>
        </div>
    </div>

    <nav>
        <a href="<?php echo $logout_url;?>">
            <i class="fas fa-sign-out-alt"></i>
            <h3>Logout</h3>
        </a>
    </nav>

    <main>
        <div class="floating noBorder">
            <header>
                <div class="title">
                    <h2 style="color: var(--titleTheme)">New Student Form</h2>
                    <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester;?> Semester</small>
                </div>
            </header>
            <div class="progress">
                <span class="dot active"><p>Preferred Course/Strand</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Personal Information</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Validate Details</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Finished</p></span>
            </div>
            <main>
                <form action="">
                <header>
                    <div class="title">
                        <h3>Admission Type
                            &nbsp;<span class="errorMessage admission_error"></span>
                        </h3>

                    </div>
                </header>
                <div class="row">
                    <span>
                        <div class="form-element">
                            <label for="new">New Student</label>
                            <div>
                            <input
                                type="radio"
                                name="admission_type"
                                id="new"
                                value="New"
                                <?php echo $admission_status === "Standard" ? "checked" : ""; ?>
                            />
                            </div>
                        </div>
                        <div class="form-element">
                            <label for="transferee">Transferee</label>
                            <div>
                                <input
                                    type="radio"
                                    name="admission_type"
                                    id="transferee"
                                    value="Transferee"
                                    <?php echo $admission_status === "Transferee" ? "checked" : ""; ?>
                                />
                            </div>
                        </div>
                    </span>
                </div>
                
                <header>
                    <div class="title">
                        <h3>Department
                            &nbsp;<span class="errorMessage department_error"></span>
                        </h3>

                    </div>
                </header>
                <div class="row">
                    <span>


                        <div class="form-element">
                            <label for="shs">Senior High</label>
                            <div>
                            <input
                                type="radio"
                                name="department_type"
                                id="shs"
                                value="Senior High School"

                                <?php 
                                    echo $pending_type == "SHS" ? "checked" : "";
                                ?>


                            />
                            </div>
                        </div>

                        <div class="form-element">
                            <label for="tertiary">College</label>
                            <div>
                            <input
                                type="radio"
                                name="department_type"
                                id="tertiary"
                                value="Tertiary"
                                <?php 
                                    echo $pending_type == "Tertiary" ? "checked" : "";
                                ?>
                            />
                            </div>
                        </div>
                    </span>
                </div>

            
                
                    <!-- <header>
                        <div class="title">
                        <h3>Course/Strand</h3>
                        </div>
                    </header> -->
                    <div class="row">

                        <span>

                            <div class="form-element">
                                <label for="">Course/Strand</label>
                                <select style="width: 450px" class="form-control" name="program_id" id="program_id">
                                    <?php 

                                        $type = $pending_type == "SHS" ? "Senior High School" 
                                            : ($pending_type == "Tertiary" ? "Tertiary" : "");

                                            // echo $type;
                                        
                                        $department_id = $department->GetDepartmentIdByName($type);

                                        // echo $deparment_id;
                                    
                                        $query = $con->prepare("SELECT * FROM program
                                            WHERE department_id=:department_id");

                                        $query->bindParam(":department_id", $department_id);

                                        $query->execute();

                                        if($query->rowCount() > 0){

                                            $output = "";
                                            $output .= "
                                                <option value='' selected >Select Program</option>
                                            ";

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $db_program_id = $row['program_id'];
                                                $acronym = $row['acronym'];
                                                $program_name = $row['program_name'];

                                                $selected = "";

                                                if($db_program_id == $program_id){
                                                    $selected = "selected";
                                                }

                                                $output .= "
                                                    <option value='$db_program_id' $selected>$program_name</option>
                                                ";
                                            }
                                            echo $output;
                                        }
                                    
                                    ?>
                                </select>
                            </div>

                            <div>
                                <label for="">Level &nbsp;<span class="errorMessage course_level_error" 
                                    ></span>
                                </label>
                                <?php 
                                    echo $pending->PendingCourseLevelDropdown($pending_type, 
                                        $course_level);
                                ?>
                            </div>
                        </span>

                    </div>
                </form>
            </main>

            <div class="action">
                <?php if($does_enrollee_finished_input === true):?>
                    <button name="preferred_btn"
                        class="information large"
                        onclick="window.location.href = 'profile.php?fill_up_state=finished'">
                    Back
                </button>
                <?php endif;?>
                
                <button name="preferred_btn"
                    class="default large"
                    onclick="<?php echo "PreferredBtn($pending_enrollees_id)"; ?>"
                >
                Proceed
                </button>
            </div>
        </div>
    </main>

</div>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script> -->


<script>

    var acceptance_condition = `
        <?php echo $acceptance_condition; ?>
    `;

    if(acceptance_condition == 0){

        setTimeout(function() {
            ModalInitialized();
        }, 500); // Delay for 2 seconds (2000 milliseconds)
    }
    
    function ModalInitialized(){

        $(document).ready(function() {

            $('#myModal').modal({ backdrop: 'static', keyboard: false }); // Show the modal with static backdrop
            $('#myModal').modal('show'); // Show the modal when the page loads
        
            // Handle checkbox change event
            $('#agreeCheckbox').change(function() {
                if ($(this).is(':checked')) {
                    $('#nextButton').prop('disabled', false); // Enable the "Next" button
                } else {
                    $('#nextButton').prop('disabled', true); // Disable the "Next" button
                }
            });

            // Handle "Next" button click event
                // console.log("qwer");

            $('#nextButton').click(function() {

                var pending_enrollees_id = `
                    <?php echo $pending_enrollees_id; ?>
                `;

                $.ajax({
                    // url: '../../ajax/requirement/session_init.php',
                    url: '../../ajax/requirements/condition_acceptance.php',
                    type: 'POST',
                    data: { 
                        accepted_term: "yes",
                        pending_enrollees_id
                    }, // You can pass data here
                    success: function(response) {
                        // Handle the server response if needed

                        response = response.trim();
                        if(response === "success_accepted" ){
                            $('#myModal').modal('hide'); // Close the modal
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Status:', status);
                        console.log('Response Text:', xhr.responseText);
                        console.log('Response Code:', xhr.status);
                    }
                });

            });
        
        });
    }

</script>
